@extends('layouts.app')
<title>{{trans('general.upgrade_package')}} | {{ config('app.name') }} </title>
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
                <div class="card-header">{{ trans('general.upgrade_package') }}</div>
                <div class="card-body">
                    {{ trans('general.your_active_package_is') }}  @if($has_active) {{ $package->package_type }} @else FREE @endif 
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-xs-12 col-l-6 col-xl-6">
                            <div class="card shadow mb-4 shadow-sm">
                                <div class="card-header bg-primary text-white">
                                <h4 class="my-0 font-weight-normal">{{ trans('general.basic') }}</h4>
                                </div>
                                <div class="card-body">
                                <h1 class="card-title pricing-card-title">€19.99<small class="text-muted">/ {{ trans('general.month') }}</small></h1>
                                <ul class="list-unstyled mt-3 mb-4">
                                    <li><i class="fas fa-store"></i> 1 {{ trans('general.sub_shop') }}</li>
                                    <li><i class="fas fa-shopping-bag"></i> 50 {{ trans('general.sub_products') }}</li>
                                    <li><i class="fas fa-chart-line"></i> 1000 {{ trans('general.sub_sales') }} / {{ trans('general.month') }}</li>
                                    <li><i class="fas fa-globe"></i> {{ trans('general.custom_domain') }}</li>
                                    <li><i class="fas fa-code"></i> {{ trans('general.custom_design') }}</li>
                                    <li><i class="fas fa-percentage"></i> 8% + 0.50cent {{ trans('general.per_transaction_fee') }}</li>
                                    <li><i class="fas fa-headset"></i> 24/7 {{ trans('general.support') }}</li>
                                </ul>

                                @if(!$has_active)
                                    @if($has_active && $package->package_type === 'Basic')
                                        <button type="submit" class="btn btn-sm btn-primary" style="font-size: 12px;" disabled>{{ trans('general.activated') }}</button>
                                    @else
                                        <form method="POST" action="{{ route('shop_package_upgrade_post_req', ['id' => auth()->user()->managing_shop, 'package' => 'Basic']) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary" style="font-size: 12px;">{{ trans('general.activate') }}</button>
                                        </form>   
                                    @endif
                                @endif
                                </div>
                            </div>
                            </div>
                            <div class="col-md-6 col-xs-12 col-l-6 col-xl-6">
                            <div class="card shadow mb-4 shadow-sm">
                                <div class="card-header bg-primary text-white">
                                <h4 class="my-0 font-weight-normal">{{ trans('general.premium') }}</h4>
                                </div>
                                <div class="card-body">
                                <h1 class="card-title pricing-card-title">€29.99<small class="text-muted">/ {{ trans('general.month') }}</small></h1>
                                <ul class="list-unstyled mt-3 mb-4">
                                    <li><i class="fas fa-store"></i> 1 {{ trans('general.sub_shop') }}</li>
                                    <li><i class="fas fa-shopping-bag"></i> ∞ {{ trans('general.sub_products') }}</li>
                                    <li><i class="fas fa-chart-line"></i> 5000 {{ trans('general.sub_sales') }} / {{ trans('general.month') }}</li>
                                    <li><i class="fas fa-globe"></i> {{ trans('general.custom_domain') }}</li>
                                    <li><i class="fas fa-code"></i> {{ trans('general.custom_design') }}</li>
                                    <li><i class="fas fa-percentage"></i> 5% + 0.50cent {{ trans('general.per_transaction_fee') }}</li>
                                    <li><i class="fas fa-headset"></i> 24/7 {{ trans('general.support') }}</li>
                                </ul>
                                    @if($has_active && $package->package_type === 'Premium')
                                        <button type="submit" class="btn btn-sm btn-primary" style="font-size: 12px;" disabled>{{ trans('general.activated') }}</button>
                                    @else
                                    <form method="POST" action="{{ route('shop_package_upgrade_post_req', ['id' => auth()->user()->managing_shop, 'package' => 'Premium']) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary" style="font-size: 12px;">{{ trans('general.activate') }}</button>
                                    </form>                                      
                                    @endif                                
                                </div>
                            </div>
                            </div>
                            <div class="alert alert-info subscription_cancelation_info">
                                {{ trans('general.can_stop_subscription_at_any_time') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
