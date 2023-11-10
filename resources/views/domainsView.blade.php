@extends('layouts.app')
<title>{{trans('general.custom_domain')}} | {{ config('app.name') }} </title>
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
                <div class="card-header">{{ trans('general.custom_domain') }}</div>
                <div class="card-body">
                    <div class="card-body">
                            @if($can_add_custom_domain == 'yes')
                                @if($active_custom_domain == 1)
                                    <span>{!! trans('general.custom_domain_waiting', ['domain' => $custom_domain]) !!}</span>
                                    <p>{!! trans('general.custom_domain_config_info') !!}</p>
                                    <div class="table-responsive-sm table-responsive-md table-responsive-xl mt-3 mb-3">
                                        <table class="table table-striped">
                                            <tr>
                                                <td>jeff.ns.cloudflare.com</td>
                                            </tr>
                                            <tr>
                                                <td>rita.ns.cloudflare.com</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <p>{{ trans('general.custom_domain_footer_desc') }}</p>
                                    <form method="POST" action="{{ route('delete_custom_domain', $custom_domain) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">
                                            {{ trans('general.delete') }} {{ $custom_domain }}
                                        </button>
                                    </form>

                                @else
                                    <span>{{ trans('general.add_your_custom_domain') }}</span>
                                    <form method="POST" action="{{ route('add_custom_domain') }}">
                                        @csrf
                                        <div class="input-group mb-3 mt-3">
                                            <input type="text" class="form-control" id="custom_domain" name="custom_domain" placeholder="{{ trans('general.add_your_custom_domain') }}" value="">
                                        </div>
                                        <button class="btn btn-primary">{{ trans('general.save') }}</button>
                                    </form>
                                @endif
                            @else
                                <p>{{ trans('general.only_paid_package_custom_domain') }}</p>
                                <a class="btn btn-primary" href="{{ route('shop_package_upgrade', auth()->user()->managing_shop) }}">{{ trans('general.upgrade_package') }}</a>
                            @endif
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header">{{ trans('general.url_of_subdomain') }}</div>
                <div class="card-body">
                    <div class="card-body">
                        <span>{{ trans('general.change_subdomain_info') }}</span>
                            <form method="POST" action="{{ route('update_subdomain') }}">
                                @csrf
                                <div class="input-group mb-3 mt-3">
                                    <input type="text" class="form-control" id="my_shop_url" name="my_shop_url" placeholder="{{ trans('general.shop_url') }}" aria-describedby="basic-addon3" value="{{ $subdomain }}">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3">.{{ str_replace(['http://', 'https://', 'www.', ':8000'], '', url('/')) }}</span>
                                    </div>
                                </div>
                                <button class="btn btn-primary">{{ trans('general.update') }}</button>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
