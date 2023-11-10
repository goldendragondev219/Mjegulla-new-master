@extends('layouts.app')
<title>{{trans('general.payment_method')}} | {{ config('app.name') }} </title>
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
                {{ $error }}<br>
            @endforeach
        </ul>
    </div>
@endif


<div class="row">
        @include('includes.shop_settings_sidebar')

        <div class="col-12 col-sm-12 col-md-12 col-lg-8 mb-4 main-content">

            <div class="card shadow mb-4">
                <div class="card-header">{{ trans('general.payment_method') }}</div>
                <div class="card-body">
                    <div class="card-body">
                        <p>{{ trans('general.shop_payment_method_desc') }}</p>
                    <div class="row">

                            @if($shop->store_type == 'dropshipping')
                                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mb-4">
                                    <div class="card plugin-card" style="height: 250px; overflow: hidden; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                                        <i class="fas fa-credit-card" style="padding-top: 60px; font-size: 50px; height: 150px;"></i>
                                        <div class="card-body plugin-info" style="font-size: 14px;">
                                            <h6 class="card-title plugin-title" style="font-size: 16px;">{{ trans('general.credit_cards') }}</h6>
                                            @if($payment_methods->credit_card)
                                            <form method="POST" action="{{ route('update_shop_payment_method', ['id' => auth()->user()->managing_shop, 'method' => 'credit_card']) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger" style="font-size: 12px;">{{ trans('general.deactivate') }}</button>
                                            </form>
                                            @else
                                            <form method="POST" action="{{ route('update_shop_payment_method', ['id' => auth()->user()->managing_shop, 'method' => 'credit_card']) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary" style="font-size: 12px;">{{ trans('general.activate') }}</button>
                                            </form>                                        
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($shop->store_type == 'local_store')
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mb-4">
                                <div class="card plugin-card" style="height: 250px; overflow: hidden; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                                    <i class="fas fa-money-bill-wave" style="padding-top: 60px; font-size: 50px; height: 150px;"></i>
                                    <div class="card-body plugin-info" style="font-size: 14px;">
                                        <h6 class="card-title plugin-title" style="font-size: 16px;">{{ trans('general.cash') }}</h6>
                                        @if($payment_methods->cash)
                                        <form method="POST" action="{{ route('update_shop_payment_method', ['id' => auth()->user()->managing_shop, 'method' => 'cash']) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" style="font-size: 12px;">{{ trans('general.deactivate') }}</button>
                                        </form>
                                        @else
                                        <form method="POST" action="{{ route('update_shop_payment_method', ['id' => auth()->user()->managing_shop, 'method' => 'cash']) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary" style="font-size: 12px;">{{ trans('general.activate') }}</button>
                                        </form>    
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
