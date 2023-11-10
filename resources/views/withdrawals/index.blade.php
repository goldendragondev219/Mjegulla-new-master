@extends('layouts.app')

<title>{{trans('general.withdrawals')}} | {{ config('app.name') }} </title>

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
    <h1 class="h3 mb-0 text-gray-800">{{ trans('general.withdrawals') }}</h1>
</div>

    <div class="row">

        <!-- Withdrawals pending -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                {{ trans('general.withdrawals_pending') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">€{{ number_format($pending,2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-euro-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Withdrawals this week -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                {{ trans('general.withdrawals_this_week') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">€{{ number_format($this_week,2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-euro-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Withdrawals this month -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                {{ trans('general.withdrawals_this_month') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">€{{ number_format($this_month,2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-euro-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Withdrawals this year -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                {{ trans('general.withdrawals_this_year') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">€{{ number_format($this_year,2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-euro-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <div class="row pb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">{{ trans('general.withdraw') }}</div>

                <div class="card-body">
                    @if($default_payout_method)
                        <span>{{ trans('general.withdrawal_default_account', ['account' => $default_payout_method]) }} - <a href="{{ route('payout_methods_view') }}">{{ trans('general.edit') }}</a></span>
                        <p>{{ trans('general.available_balance') }} €{{ number_format(auth()->user()->balanceAvailable(),2) }}</p>
                        <form method="POST" action="{{ route('withdraw') }}">
                            @csrf
                            <div class="form-group row mt-3">
                                <div class="col-12">
                                    <input id="amount" type="text" class="form-control @error('amount') is-invalid @enderror" name="amount" value="" required autofocus placeholder="{{ trans('general.withdrawal_amount') }}">
                                </div>
                            </div>
                            <button class="btn btn-primary" @if($pending || !$default_payout_method) disabled @endif>{{ trans('general.withdraw') }}</button>
                        </form>
                    @else
                        <span><a href="{{ route('payout_methods_view') }}">{{ trans('general.add_withdrawal_method') }}</a></span>
                    @endif

                    @if(!$default_payout_method)
                        <p>{{ trans('general.available_balance') }} €{{ number_format(auth()->user()->balanceAvailable(),2) }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-12">
            <div class="card shadow withdrawals">
                <div class="card-header withdrawals-header">{{ trans('general.withdrawals') }}</div>

                <div class="card-body">
                    @if($withdrawals->isNotEmpty())
                    <div class="table-responsive-sm table-responsive-md table-responsive-xl">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ trans('general.withdrawal_account') }}</th>
                                    <th>{{ trans('general.withdrawal_amount') }}</th>
                                    <th>{{ trans('general.status') }}</th>
                                    <th>{{ trans('general.date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($withdrawals as $withdrawal)
                                <tr>
                                    <td>{{ json_decode($withdrawal->transfer_details)->method }}</td>
                                    <td>€{{ $withdrawal->amount }}</td>
                                    <td>
                                        @if($withdrawal->status == 'pending')
                                            <span class="badge badge-warning">{{ trans('general.withdrawals_pending') }}</span>
                                        @else
                                            <span class="badge badge-success">{{ trans('general.withdrawal_completed') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $withdrawal->created_at }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                        <style>
                        .withdrawals{
                            background: transparent;
                            border-style: none; 
                        }
                        .withdrawals-header{
                            display: none;
                        }
                        </style>
                        <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; height: 50vh;">
                            <i class="far fa-euro-sign" style="font-size: 5rem;"></i>
                            <p style="margin-top: 1rem;">{{ trans('general.no_withdrawals') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
