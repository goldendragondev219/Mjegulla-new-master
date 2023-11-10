@extends('layouts.app')
<title>{{trans('general.cj_dropshipping_menu_edit')}} | {{ config('app.name') }} </title>
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
                <div class="card-header">{{ trans('general.cj_dropshipping_menu_edit') }}</div>
                <div class="card-body">
                    <div class="card-body">
                        @if(!isset($cj_data))
                        <form method="post" action="{{ route('update_cj_d') }}">
                            @csrf
                            <span>{{ trans('general.cj_d_edit_description') }}</span>
                            <div class="form-group row">
                                <div class="col-md-12 mt-3">
                                    <input id="cj_email" type="email" class="form-control @error('cj_email') is-invalid @enderror" name="cj_email" value="{{ old('cj_email') }}" required autocomplete="email" placeholder="{{ trans('general.email') }}" autofocus>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input id="cj_api_key" type="text" class="form-control" name="cj_api_key" value="" required placeholder="{{ trans('general.cj_d_account_api_key') }}" autofocus>
                                </div>
                            </div>
                            <button class="btn btn-primary" type="submit">Connect</button>
                        </form>
                        <span>{{ trans('general.cj_d_find_api_key') }} <a href="https://cjdropshipping.com/myCJ.html#/apikey" target="_blank">https://cjdropshipping.com/myCJ.html#/apikey</a></span>
                        @else
                            <label>{{ trans('general.cj_account_balance') }}: ${{ number_format($cj_balance, 2) }} USD <a href="https://cjdropshipping.com/newmycj/wallet" target="_blank">[{{ trans('general.cj_wallet_top_up') }}]</a></label><br>
                            <label>{{ trans('general.email') }}: {{ $cj_data->email }}</label><br>
                            <label>API Key: {{ str_repeat('*', strlen($cj_data->api_key) - 4) . substr($cj_data->api_key, -4) }}</label><br>

                            <form method="POST" action="{{ route('delete_cj_dropshipping_api', $cj_data->id) }}">
                                @csrf
                                <button class="btn btn-danger" type="submit">{{ trans('general.delete') }}</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
</div>

@endsection
