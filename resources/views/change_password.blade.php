@extends('layouts.app')

<title>{{trans('general.change_password')}} | {{ config('app.name') }} </title>

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
            <div class="card shadow">
                <div class="card-header">{{ trans('general.change_password') }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('changePassword') }}">
                            @csrf

                            <div class="form-group row">
                                <div class="col-12">
                                    <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" value="" required autocomplete="current_password" autofocus placeholder="{{ trans('general.current_password') }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-12">
                                    <input id="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" value="" required autocomplete="new_password" autofocus placeholder="{{ trans('general.new_password') }}">
                                </div>
                            </div>


                            <div class="form-group row">
                                <div class="col-12">
                                    <input id="new_password_confirmation" type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror" name="new_password_confirmation" value="" required autocomplete="new_password_confirmation" autofocus placeholder="{{ trans('general.retype_password') }}">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary float-right">
                                    {{ trans('general.update') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
@endsection
