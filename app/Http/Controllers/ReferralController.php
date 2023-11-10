<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Referral;
use App\Models\User;
use App\Models\PayoutMethods;
use App\Models\ReferralWithdrawal;

class ReferralController extends Controller
{
    public function index()
    {
        $total_referrals = Referral::where('refer_id', auth()->user()->id)->count();
        $payout_methods = PayoutMethods::where('user_id', auth()->user()->id)
        ->get();
        $withdrawals = ReferralWithdrawal::where('user_id', auth()->user()->id)
        ->orderBy('id', 'DESC')
        ->get();
        return view('referral',[
            'total_referrals' => $total_referrals,
            'available_balance' => auth()->user()->referral_balance,
            'payout_methods' => $payout_methods,
            'withdrawals' => $withdrawals,
            'default_payout_method' => $this->defaultPayoutMethod(),
            'pending' => $this->pendingWithdrawalsAmount(),
        ]);
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


    public function pendingWithdrawalsAmount()
    {
        $pending_withdrawals = ReferralWithdrawal::where('user_id', auth()->user()->id)
        ->where('status', 'pending')
        ->sum('amount');

        return $pending_withdrawals;
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
            if(auth()->user()->referral_balance >= $request->amount){
                $withdraw = new ReferralWithdrawal();
                $withdraw->user_id = auth()->user()->id;
                $withdraw->transfer_details = $this->defaultPayoutMethodJSON();
                $withdraw->amount = $request->amount;
                $withdraw->save();
                auth()->user()->decrement('referral_balance', $withdraw->amount);
                return redirect()->back()->with('success', trans('general.withdrawal_created_success'));
            }else{
                return back()->withErrors(trans('general.withdrawal_low_balance'));
            }
        }else{
            return back()->withErrors(trans('general.withdrawal_no_payout_method'));
        }
    }


}
