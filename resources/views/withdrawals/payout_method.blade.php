@extends('layouts.app')

<title>{{trans('general.payout_method')}} | {{ config('app.name') }} </title>

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

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ trans('general.payout_method') }}</h1>
</div>


    <div class="row pb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">{{ trans('general.payout_method') }}</div>

                <div class="card-body">
                    @if($primary)
                        <p>{{ trans('general.withdrawal_default_account', ['account' => $primary]) }}</p>
                    @endif
                    <ul class="nav nav-tabs" id="paymentTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link @if($primary == 'PayPal' || !$primary) active @endif" id="paypal-tab" data-toggle="tab" href="#paypal" role="tab" aria-controls="paypal" aria-selected="true">PayPal</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if($primary == 'Bank Transfer') active @endif" id="bank-transfer-tab" data-toggle="tab" href="#bank-transfer" role="tab" aria-controls="bank-transfer" aria-selected="false">Bank Transfer</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if($primary == 'Paysera') active @endif" id="paysera-tab" data-toggle="tab" href="#paysera" role="tab" aria-controls="paysera" aria-selected="false">Paysera</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="paymentTabsContent">
                        <div class="tab-pane fade @if($primary == 'PayPal' || !$primary) show active @endif" id="paypal" role="tabpanel" aria-labelledby="paypal-tab">
                            <!-- Content for PayPal tab -->
                            <form method="POST" action="{{ route('update_payment_method', 'PayPal') }}">
                                @csrf
                                <div class="form-group row mt-3">
                                    <div class="col-12">
                                    <input id="paypal" type="email" class="form-control @error('paypal') is-invalid @enderror" name="paypal" value="{{ $paypal ? $paypal : old('paypal') }}" required autofocus placeholder="{{ trans('general.paypal_email') }}">
                                    </div>
                                </div>
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" name="paypal_primary" id="paypal_primary" @if($primary == 'PayPal') checked @endif>
                                    <label class="form-check-label" for="paypal_primary">
                                        {{ trans('general.make_method_primary') }}
                                    </label>
                                </div>
                                <button class="btn btn-primary mt-3">{{ trans('general.save') }}</button>
                            </form>
                        </div>
                        <div class="tab-pane fade @if($primary == 'Bank Transfer') show active @endif" id="bank-transfer" role="tabpanel" aria-labelledby="bank-transfer-tab">
                            <!-- Content for Bank Transfer tab -->
                            <form method="POST" action="{{ route('update_payment_method', 'Bank Transfer') }}">
                                @csrf
                                <div class="form-group row mt-3">
                                    <div class="col-12">
                                        <input id="bank_holder_name" type="text" class="form-control @error('bank_holder_name') is-invalid @enderror" name="bank_holder_name" value="{{ $bank ? $bank->bank_holder_name : old('bank_holder_name') }}" required autofocus placeholder="{{ trans('general.bank_holder_name') }}">
                                    </div>
                                </div>
                                <div class="form-group row mt-3">
                                    <div class="col-12">
                                        <input id="bank_holder_address" type="text" class="form-control @error('bank_holder_address') is-invalid @enderror" name="bank_holder_address" value="{{ $bank ? $bank->bank_holder_address : old('bank_holder_address') }}" required autofocus placeholder="{{ trans('general.bank_holder_address') }}">
                                    </div>
                                </div>
                                <div class="form-group row mt-3">
                                    <div class="col-12">
                                        <input id="bank_country" type="text" class="form-control @error('bank_country') is-invalid @enderror" name="bank_country" value="{{ $bank ? $bank->bank_country : old('bank_country') }}" required autofocus placeholder="{{ trans('general.bank_country') }}">
                                    </div>
                                </div>
                                <div class="form-group row mt-3">
                                    <div class="col-12">
                                        <input id="bank_city" type="text" class="form-control @error('bank_city') is-invalid @enderror" name="bank_city" value="{{ $bank ? $bank->bank_city : old('bank_city') }}" required autofocus placeholder="{{ trans('general.bank_city') }}">
                                    </div>
                                </div>
                                <div class="form-group row mt-3">
                                    <div class="col-12">
                                        <input id="bank_name" type="text" class="form-control @error('bank_name') is-invalid @enderror" name="bank_name" value="{{ $bank ? $bank->bank_name : old('bank_name') }}" required autofocus placeholder="{{ trans('general.bank_name') }}">
                                    </div>
                                </div>
                                <div class="form-group row mt-3">
                                    <div class="col-12">
                                        <input id="bank_iban" type="text" class="form-control @error('bank_iban') is-invalid @enderror" name="bank_iban" value="{{ $bank ? $bank->bank_iban : old('bank_iban') }}" required autofocus placeholder="{{ trans('general.bank_iban') }}">
                                    </div>
                                </div>
                                <div class="form-group row mt-3">
                                    <div class="col-12">
                                        <input id="bank_swift" type="text" class="form-control @error('bank_swift') is-invalid @enderror" name="bank_swift" value="{{ $bank ? $bank->bank_swift : old('bank_swift') }}" required autofocus placeholder="{{ trans('general.bank_swift') }}">
                                    </div>
                                </div>
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" name="bank_primary" id="bank_primary" @if($primary == 'Bank Transfer') checked @endif>
                                    <label class="form-check-label" for="bank_primary">
                                        {{ trans('general.make_method_primary') }}
                                    </label>
                                </div>
                                <button class="btn btn-primary mt-3">{{ trans('general.save') }}</button>
                            </form>
                        </div>
                        <div class="tab-pane fade @if($primary == 'Paysera') show active @endif" id="paysera" role="tabpanel" aria-labelledby="paysera-tab">
                            <!-- Content for Paysera tab -->
                            <form method="POST" action="{{ route('update_payment_method', 'Paysera') }}">
                                @csrf
                                <div class="form-group row mt-3">
                                    <div class="col-12">
                                        <input id="paysera" type="email" class="form-control @error('paysera') is-invalid @enderror" name="paysera" value="{{ $paysera ? $paysera : old('paysera') }}" required autofocus placeholder="{{ trans('general.paysera_email') }}">
                                    </div>
                                </div>
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" name="paysera_primary" id="paysera_primary" @if($primary == 'Paysera') checked @endif>
                                    <label class="form-check-label" for="paysera_primary">
                                        {{ trans('general.make_method_primary') }}
                                    </label>
                                </div>
                                <button class="btn btn-primary mt-3">{{ trans('general.save') }}</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


@endsection
