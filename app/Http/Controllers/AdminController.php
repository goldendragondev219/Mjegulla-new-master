<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketReply;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Help;
use App\Models\Shop;
use App\Models\Orders;
use App\Models\SupportTickets;
use App\Models\SupportTicketMessages;

class AdminController extends Controller
{

    public function usersView(Request $request)
    {
        if($request->search){
            $users = User::where('name', 'LIKE', '%'.$request->search.'%')
            ->orWhere('email', 'LIKE', '%'.$request->search.'%')
            ->orderBy('id', 'DESC')->paginate(10);    
        }else{
            $users = User::orderBy('id', 'DESC')->paginate(10);
        }
        $total_users = User::count();
        $online_users = User::whereBetween('last_seen', [now()->subMinutes(2), now()])->count();
        return view('admin.users',[
            'users' => $users,
            'total_users' => $total_users,
            'online_users' => $online_users,
        ]);
    }

    public function loginToUser($id)
    {
        $user = User::find($id);

        if ($user) {
            // Authenticate the user
            Auth::login($user);
    
            // Redirect to a protected area or perform any necessary actions
            return redirect('/home');
        } else {
            // Handle the case where the user does not exist
            return redirect()->back()->with('error', 'User not found');
        }
    }

    public function StoresView(Request $request, $user_id = null)
    {
        $search = $request->search;
    
        $stores = Shop::when($user_id, function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })
        ->when($search, function ($query) use ($search) {
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('shop_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('shop_description', 'LIKE', '%' . $search . '%')
                    ->orWhere('shop_seo_keywords', 'LIKE', '%' . $search . '%')
                    ->orWhere('my_shop_url', 'LIKE', '%' . $search . '%')
                    ->orWhere('custom_domain', 'LIKE', '%' . $search . '%');
            });
        })
        ->orderBy('id', 'DESC')
        ->paginate(10);
    
        $total_stores = $stores->total();
    
        return view('admin.stores', [
            'stores' => $stores,
            'total_stores' => $total_stores,
        ]);
    }
    


    public function ordersView($store_id = null)
    {
        if($store_id){
            $orders = Orders::where('shop_id', $store_id)->where('finished', 'yes')->orderBy('id', 'DESC')->paginate(10);
            $total_orders = Orders::where('shop_id', $store_id)->where('finished', 'yes')->count();
        }else{
            $orders = Orders::where('finished', 'yes')->orderBy('id', 'DESC')->paginate(10);
            $total_orders = Orders::count();
        }

        return view('admin.orders',[
            'orders' => $orders,
            'total_orders' => $total_orders,
        ]);
    }


    public function helpPages()
    {
        $help_pages = Help::where('menu', 'no')->get();;
        return view('admin.help_pages',[
            'help_pages' => $help_pages,
        ]);
    }



    public function helpPageCreateView()
    {
        $menus = Help::where('menu', 'yes')->get();
        return view('admin.help_page_create',[
            'menus' => $menus,
        ]);
    }


    public function helpPageCreate(Request $request)
    {
        // Generate a URL-friendly slug from the title
        $slug = Str::slug($request->slug);
     
        $request->validate([
            'belongs_to_menu' => 'required',
            'title' => 'required',
            'content' => 'required',
            'slug' => 'required|unique:help,slug',
            'lang' => 'required',
        ]);
    
        $help = Help::create([
            'belongs_to_menu' => $request->belongs_to_menu,
            'title' => $request->title,
            'content' => $request->content,
            'slug' => $slug,
            'lang' => $request->lang,
        ]);
    
        return redirect()->route('admin_help_page_edit', $help->id)->with('success', 'Updated Successfully');
    }


    public function helpPageEdit($id)
    {
        $page = Help::find($id);
        $menus = Help::where('menu', 'yes')->get();
        return view('admin.help_page_edit',[
            'page' => $page,
            'menus' => $menus,
        ]);
    }

    public function helpPageUpdate(Request $request, $id)
    {
        // Generate a URL-friendly slug from the title
        $slug = Str::slug($request->slug);
     
        $request->validate([
            'belongs_to_menu' => 'required',
            'title' => 'required',
            'content' => 'required',
            'slug' => 'required|unique:help,slug,' . $id, // Ignore the current record with ID $id
            'lang' => 'required',
        ]);
    
        // Update the Help model with the new data
        $help = Help::find($id);
        $help->update([
            'belongs_to_menu' => $request->belongs_to_menu,
            'title' => $request->title,
            'content' => $request->content,
            'slug' => $slug,
            'lang' => $request->lang,
        ]);
    
        return redirect()->back()->with('success', 'Updated Successfully');
    }
    

    public function helpPageDelete($id)
    {
        Help::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Deleted Successfully');
    }


    public function helpPagesParentMenus()
    {
        $menus = Help::where('menu', 'yes')->get();
        return view('admin.help_page_parent_menus',[
            'menus' => $menus,
        ]);
    }

    public function helpPagesParentMenuEditView($id)
    {
        $menu = Help::where('id', $id)->where('menu', 'yes')->first();
        return view('admin.help_page_parent_menu_edit',[
            'menu' => $menu,
        ]);
    }

    public function helpPagesParentMenuCreateView(Request $request)
    {
        return view('admin.help_page_parent_menu_create');
    }

    public function helpPagesParentMenuCreate(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'lang' => 'required',
        ]);

        $help = Help::create([
            'title' => $request->title,
            'menu' => 'yes',
            'lang' => $request->lang,
        ]);
        return redirect()->route('admin_help_pages_parent_menu_edit_view', $help->id)->with('success', 'Created Successfully');
    }

    public function helpPageParentUpdate(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'lang' => 'required',
        ]);

        $help = Help::find($id);
        $help->update([
            'title' => $request->title,
            'lang' => $request->lang,
        ]);
        return redirect()->back()->with('success', 'Updated Successfully');
    }

    public function helpPageParentDelete($id)
    {
        Help::where('id', $id)->delete();
        Help::where('belongs_to_menu', $id)->delete();
        return redirect()->back()->with('success', 'Deleted Successfully');   
    }



    public function SupportView()
    {
        $tickets = SupportTickets::orderBy('updated_at', 'DESC')->get();
        return view('admin.support',[
            'tickets' => $tickets,
        ]);
    }


    public function SupportTicket($ticket_id)
    {
        $ticket = SupportTickets::where('ticket_id', $ticket_id)->firstOrFail();
    
        $ticket_messages = SupportTicketMessages::where('ticket_id', $ticket->ticket_id)
                ->get();

        $ticket->update([
            'admin_seen' => 1,
        ]);
    
        return view('admin.support_ticket', [
            'messages' => $ticket_messages,
            'title' => $ticket->title,
            'ticket_id' => $ticket->ticket_id, 
        ]);
    }


    public function ReplyTicket(Request $request, $ticket_id)
    {
        $ticket = SupportTickets::where('ticket_id', $ticket_id)->firstOrFail();
        $data = $request->validate([
            'message' => 'required|string|min:1|max:500',
        ]);
        $message = SupportTicketMessages::create([
            'ticket_id' => $ticket->ticket_id,
            'user_id' => $ticket->user_id,
            'admin_id' => auth()->user()->id,
            'message' => $request->message,
        ]);
        $ticket->update([
            'user_seen' => 0,
            'admin_seen' => 1,
            'updated_at' => now(),
        ]);
        $user = User::find($ticket->user_id);
        $mail_data = [
            'name' => $user->name,
            'lang' => $user->default_language,
            'title' => $ticket->title,
            'message' => $message->message,
        ];

        Mail::to($user->email)->queue(new TicketReply($mail_data));
        return back()->with('success', 'Message has been sent.');
    }


}
