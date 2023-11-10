@extends('layouts.app')
<title>{{trans('general.custom_design')}} | {{ config('app.name') }} </title>
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
                <div class="card-header">{{ trans('general.custom_design') }}</div>
                <div class="card-body">
                    <div class="card-body">
                        @if($has_active_package)
                            <form method="POST" action="{{ route('update_custom_css') }}">
                                @csrf
                                <span>{{ trans('general.custom_css_desc') }}</span>
                                <div class="form-group row">
                                    <div class="col-md-12 mt-3">
                                        <textarea rows="10" cols="50" id="custom-css" type="text" class="form-control @error('custom_css') is-invalid @enderror h-100" name="custom_css" value="{{ $custom_css ?? old('custom_css') }}" placeholder="{{ trans('general.css_code') }}" autofocus>{{ $custom_css ?? old('custom_css') }}</textarea>
                                    </div>
                                </div>
                                    <button class="btn btn-primary" type="submit">{{ trans('general.update') }}</button>
                            </form>
                        @else
                            <span>{{ trans('general.no_active_package_css') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
</div>

@endsection
