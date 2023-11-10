<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Shop;
use App\Models\PayoutMethods;
use App\Models\Withdrawals;

class WithdrawalController extends Controller
{
    public function index()
    {
        $payout_methods = PayoutMethods::where('user_id', auth()->user()->id)
        ->get();

        $withdrawals = Withdrawals::where('user_id', auth()->user()->id)
        ->where('shop_id', auth()->user()->managing_shop)
        ->orderBy('id', 'DESC')
        ->get();

        $pending_withdrawals = Withdrawals::where('user_id', auth()->user()->id)
        ->where('shop_id', auth()->user()->managing_shop)
        ->where('status', 'pending')
        ->sum('amount');



        return view('withdrawals.index',[
            'payout_methods' => $payout_methods,
            'default_payout_method' => $this->defaultPayoutMethod(),
            'withdrawals' => $withdrawals,
            'pending' => $this->pendingWithdrawalsAmount(),
            'this_week' => $this->withdrawalsThisWeek(),
            'this_month' => $this->withdrawalsThisMonth(),
            'this_year' => $this->withdrawalsThisYear(),
        ]);
    }


    public function pendingWithdrawalsAmount()
    {
        $pending_withdrawals = Withdrawals::where('user_id', auth()->user()->id)
        ->where('shop_id', auth()->user()->managing_shop)
        ->where('status', 'pending')
        ->sum('amount');

        return $pending_withdrawals;
    }


    public function defaultPayoutMethod()
    {
        $default_payout_method = PayoutMethods::where('user_id', auth()->user()->id)
        ->where('active', 'yes')
        ->first();

        return ($default_payout_method) ? json_decode($default_payout_method->details)->method : null;
    }

    public function defaultPayoutMethodJSON()
    {
        $default_payout_method = PayoutMethods::where('user_id', auth()->user()->id)
        ->where('active', 'yes')
        ->first();

        return $default_payout_method->details;
    }


    public function withdrawalsThisWeek() {
        $userId = auth()->user()->id;
        $shopId = auth()->user()->managing_shop;
    
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
    
        $withdrawals = Withdrawals::where('user_id', $userId)
            ->where('shop_id', $shopId)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->sum('amount');
    
        return $withdrawals;
    }

    public function withdrawalsThisMonth() {
        $userId = auth()->user()->id;
        $shopId = auth()->user()->managing_shop;
    
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
    
        $withdrawals = Withdrawals::where('user_id', $userId)
            ->where('shop_id', $shopId)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('amount');
    
        return $withdrawals;
    }

    public function withdrawalsThisYear() {
        $userId = auth()->user()->id;
        $shopId = auth()->user()->managing_shop;
    
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();
    
        $withdrawals = Withdrawals::where('user_id', $userId)
            ->where('shop_id', $shopId)
            ->whereBetween('created_at', [$startOfYear, $endOfYear])
            ->sum('amount');
    
        return $withdrawals;
    }


    public function withdraw(Request $request)
    {
        $validator = $request->validate([
            'amount' => 'required|numeric|min:50',
        ]);

        if($this->pendingWithdrawalsAmount() != null){
            return back()->withErrors(trans('general.withdrawal_limit_one_withdrawal'));
        }

        if($this->defaultPayoutMethod()){
            if(auth()->user()->balanceAvailable() >= $request->amount){
                $withdraw = new Withdrawals();
                $withdraw->user_id = auth()->user()->id;
                $withdraw->shop_id = auth()->user()->managing_shop;
                $withdraw->transfer_details = $this->defaultPayoutMethodJSON();
                $withdraw->amount = $request->amount;
                $withdraw->save();
                Shop::where('id', auth()->user()->managing_shop)->decrement('balance_available', $withdraw->amount);
                return redirect()->back()->with('success', trans('general.withdrawal_created_success'));
            }else{
                return back()->withErrors(trans('general.withdrawal_low_balance'));
            }
        }else{
            return back()->withErrors(trans('general.withdrawal_no_payout_method'));
        }
    }   


    public function payoutMethodsView()
    {
        $user = auth()->user();
        $paypal = PayoutMethods::where('user_id', $user->id)
            ->where('details->method', 'PayPal')
            ->first();

        $paysera = PayoutMethods::where('user_id', $user->id)
            ->where('details->method', 'Paysera')
            ->first();

        $bank = PayoutMethods::where('user_id', $user->id)
            ->where('details->method', 'Bank Transfer')
            ->first();
    

    
        return view('withdrawals.payout_method', [
            'primary' => $this->defaultPayoutMethod(),
            'paypal' => ($paypal) ? json_decode($paypal->details)->account : '',
            'bank' => ($bank) ? json_decode($bank->details) : '',
            'paysera' => ($paysera) ? json_decode($paysera->details)->account : '',
        ]);
    }
    


    public function updatePaymentMethod(Request $request, $method)
    {
        $validator = null;
        $details = [];
        $active = 'no';
    
        switch ($method) {
            case 'PayPal':
                $validator = $request->validate([
                    'paypal' => 'required|email|max:50',
                ]);
                $details = [
                    'method' => 'PayPal',
                    'account' => $request->paypal,
                ];
                $active = $request->has('paypal_primary') ? 'yes' : 'no';
                break;
    
            case 'Bank Transfer':
                $validator = $request->validate([
                    'bank_holder_name' => 'required|string|max:100',
                    'bank_holder_address' => 'required|string|max:200',
                    'bank_country' => 'required|string|max:100',
                    'bank_city' => 'required|string|max:100',
                    'bank_name' => 'required|string|max:200',
                    'bank_iban' => 'required|string|max:100',
                    'bank_swift' => 'required|string|max:100',
                ]);
                $details = [
                    'method' => 'Bank Transfer',
                    'bank_holder_name' => $request->bank_holder_name,
                    'bank_holder_address' => $request->bank_holder_address,
                    'bank_country' => $request->bank_country,
                    'bank_city' => $request->bank_city,
                    'bank_name' => $request->bank_name,
                    'bank_iban' => $request->bank_iban,
                    'bank_swift' => $request->bank_swift,
                ];
                $active = $request->has('bank_primary') ? 'yes' : 'no';
                break;
    
            case 'Paysera':
                $validator = $request->validate([
                    'paysera' => 'required|email|max:50',
                ]);
                $details = [
                    'method' => 'Paysera',
                    'account' => $request->paysera,
                ];
                $active = $request->has('paysera_primary') ? 'yes' : 'no';
                break;
    
            default:
                return back()->withErrors(trans('general.withdrawal_no_payout_method'));
        }
    
        // Deactivate other active methods
        PayoutMethods::where('user_id', auth()->user()->id)
            ->where('active', 'yes')
            ->update(['active' => 'no']);
    
        $payout_method = PayoutMethods::updateOrCreate(
            ['user_id' => auth()->user()->id, 'details->method' => $method],
            [
                'details' => json_encode($details),
                'active' => $active,
            ]
        );
    
        return redirect()->back()->with('success', trans('general.payout_method_changed_success'));
    }
    





}
