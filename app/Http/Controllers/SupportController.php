<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\SupportTickets;
use App\Models\SupportTicketMessages;


class SupportController extends Controller
{
    public function SupportView()
    {
        $tickets = SupportTickets::where('user_id', auth()->user()->id)->orderBy('updated_at', 'DESC')->paginate(20);
        return view('support',[
            'tickets' => $tickets,
        ]);
    }

    public function CreateTicket(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|min:1|max:100',
            'message' => 'required|string|min:1|max:500',
        ]);

        $create_ticket = SupportTickets::create([
            'ticket_id' => Str::uuid(),
            'title' => $request->title,
            'user_id' => auth()->user()->id,
            'admin_seen' => 0,
            'user_seen' => 1,
        ]);

        SupportTicketMessages::create([
            'ticket_id' => $create_ticket->ticket_id,
            'user_id' => auth()->user()->id,
            'admin_id' => 0,
            'message' => $request->message,
        ]);

        return back()->with('success', trans('general.ticket_created'));

    }

    public function ViewTicket($ticket_id)
    {
        $ticket = SupportTickets::where('ticket_id', $ticket_id)->firstOrFail();
        
        if ($ticket->user_id == auth()->user()->id) {
            $ticket_messages = SupportTicketMessages::where('ticket_id', $ticket->ticket_id)
                ->where('user_id', auth()->user()->id)
                ->get();
    
            $ticket->update([
                'user_seen' => 1
            ]);
    
            return view('support_ticket', [
                'messages' => $ticket_messages,
                'title' => $ticket->title,
                'ticket_id' => $ticket->ticket_id, 
            ]);
        } else {
            abort(403, 'Unauthorized');
        }
    }
    


    public function ReplyTicket(Request $request, $ticket_id)
    {
        $ticket = SupportTickets::where('ticket_id', $ticket_id)->firstOrFail();
        if($ticket->user_id == auth()->user()->id){
            $data = $request->validate([
                'message' => 'required|string|min:1|max:500',
            ]);
            SupportTicketMessages::create([
                'ticket_id' => $ticket->ticket_id,
                'user_id' => auth()->user()->id,
                'admin_id' => 0,
                'message' => $request->message,
            ]);
            $ticket->update([
                'user_seen' => 1,
                'admin_seen' => 0,
                'updated_at' => now(),
            ]);
            return back()->with('success', trans('general.ticket_reply'));
        }
    }


}
