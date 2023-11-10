@extends('layouts.app')
<title>{{trans('general.billing')}} | {{ config('app.name') }} </title>
@section('content')
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    <div class="row">
        <!-- sidebar -->
        <div class="col-12 col-sm-12 col-md-12 col-lg-4 mb-4">
            <div class="card shadow">
                <ul class="list-group">
                    <a href="{{ route('edit') }}" style="text-decoration: none;"><li class="list-group-item {{ Request::is('account/settings') ? ' active' : '' }}" aria-current="true"><i class="fas fa-cog"></i> {{ trans('general.account') }} {{ trans('general.settings') }}</li></a>
                    <a href="{{ route('change_password_view') }}" style="text-decoration: none;"><li class="list-group-item {{ Request::is('account/password') ? ' active' : '' }}"><i class="fas fa-lock"></i> {{ trans('general.change_password') }}</li></a>
                    <a href="{{ route('billing_view') }}" style="text-decoration: none;"><li class="list-group-item {{ Request::is('account/billing') ? ' active' : ' ' }}"><i class="fas fa-money-bill"></i> {{ trans('general.billing') }}</li></a>
                </ul>
            </div>
        </div>

        <div class="col-12 col-sm-12 col-md-12 col-lg-8 mb-4 main-content">
            <div class="card shadow mb-4">
                <div class="card-header">{{ trans('general.credit_cards') }}</div>
                <div class="card-body">
                @if (count($cards) === 0)
                    <p>{{ trans('general.no_linked_cards') }}</p>
                    @else
                        @foreach($cards as $card)
                            <div class="card p-2 mb-4">
                                
                                <div class="row">
                                    <div class="col-6">
                                        @if(!empty($card->name))
                                            <span>{{ $card->name }}</span>
                                        @else
                                            <span>XXXX XXXX XXXX {{ $card->card->last4 }}</span>
                                        @endif
                                    </div>
                                    <div class="col-6">
                                    <i class="fab fa-cc-{{ strtolower($card->card->brand) }} fa-2x float-right" data-toggle="tooltip" data-placement="top" title="{{ $card->card->brand }}"></i>
                                    </div> 
                                </div>
                                    @if(!empty($card->name))
                                        <span>XXXX XXXX XXXX {{ $card->card->last4 }}</span>
                                    @endif
                                <div class="row">
                                    <div class="col-6">
                                        <span>{{ $card->card->exp_month }} / {{ $card->card->exp_year }}</span>
                                    </div>
                                    <div class="col-6" style="text-align: right;">
                                        @if($card->id === auth()->user()->default_payment_method)
                                            <span class="badge badge-success" data-toggle="tooltip" data-placement="top" title="{{ trans('general.future_payments_card') }}">{{ trans('general.primary') }}</span>
                                        @else
                                            <div class="credit-cards d-flex float-right">
                                                <form method="POST" action="{{ route('make_card_primary', $card->id) }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success mr-2" data-toggle="tooltip" data-placement="top" title="{{ trans('general.make_primary_info') }}" style="font-size: 12px;">{{ trans('general.primary') }}</button>
                                                </form>
                                                <form method="POST" action="{{ route('delete_card', $card->id) }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="{{ trans('general.delete_card_info') }}" style="font-size: 12px;">{{ trans('general.delete') }}</button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                @endif
                </div>
            </div>
            <div class="card shadow mb-4">
                <div class="card-header">{{ trans('general.subscriptions') }}</div>
                    <div class="card-body">
                        @if (is_array($subscriptions) && count($subscriptions) === 0)
                            <p>{{ trans('general.no_active_subscriptions') }}</p>
                        @else
                            @foreach ($subscriptions as $subscription) 
                            <div class="card p-2 mb-4">
                                <div class="row">
                                    <div class="col-6">
                                        <h4>{{ $subscription['product_name'] }}</h4>
                                    </div>
                                    <div class="col-6" style="text-align: right; right: 5px;">
                                        @if($subscription['status'] == 'active')
                                            <span class="badge badge-success" data-toggle="tooltip" data-placement="top" title="{{ trans('general.shop_active_info') }}">{{ $subscription['status'] }} </span>
                                        @else
                                            <span class="badge badge-danger" data-toggle="tooltip" data-placement="top" title="{{ trans('general.shop_no_longer_active') }}">{{ $subscription['status'] }}</span>
                                        @endif
                                    </div>

                                
                                    <div class="col-6">
                                        <span>€{{ $subscription['amount']/100 }} / {{ trans('general.month') }}<br></span>
                                        @if($subscription['status'] != 'active')
                                            <span class="badge badge-danger"  data-toggle="tooltip" data-placement="top" title="{{ trans('general.subscription_ended_info') }}">{{ $subscription['ends_at'] }}</span>
                                        @else
                                            @if(!$subscription['will_be_cancelled'])
                                                <span class="badge badge-success"  data-toggle="tooltip" data-placement="top" title="{{ trans('general.subscription_renewal_info', ['date' => $subscription['ends_at'] ]) }}">{{ $subscription['ends_at'] }}</span>
                                            @else
                                                <span class="badge badge-danger"  data-toggle="tooltip" data-placement="top" title="{{ trans('general.subscription_cancelation_info', ['date' => $subscription['ends_at'] ]) }}">{{ $subscription['ends_at'] }}</span>
                                            @endif
                                        @endif
                                    </div>

                                    @if(!$subscription['will_be_cancelled'])
                                        @if($subscription['status'] == 'active')
                                            <div class="col-6" style="text-align: right; right: 5px;">
                                                <form method="POST" action="{{ route('cancel_subscription', $subscription['plan_id']) }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="{{ trans('general.shop_no_longer_accessible_info', ['date' => $subscription['ends_at'] ]) }}" style="font-size: 12px;">{{ trans('general.cancel') }}</button>
                                                </form>
                                            </div>
                                        @endif
                                    @else
                                        @if($subscription['status'] == 'active')
                                            <div class="col-6" style="text-align: right; right: 5px;">
                                                <form method="POST" action="{{ route('subscription_reactivate', $subscription['sub_id']) }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="{{ trans('general.re_subscribe_info', ['date' => $subscription['ends_at'] ]) }}">{{ trans('general.re_subscribe') }}</button>
                                                </form>
                                            </div>
                                        @endif
                                    @endif

                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>  
        </div>


<script type="application/javascript" src="https://code.jquery.com/jquery-3.6.3.min.js" crossorigin="anonymous"></script>

<script>
$(document).ready(function() {
  $('[data-toggle="tooltip"]').tooltip();
});
</script>

@endsection
