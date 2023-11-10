@extends('layouts.app')

<title>{{trans('general.my_shops')}} | {{ config('app.name') }} </title>

@section('content')
<style>
    .my-shadow-text {
        color: #333; /* set the text color */
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3); /* add a shadow effect */
    }
</style>

@if (isset($success) && $success)
    <div class="alert alert-success">
        {{ trans('general.shop_create_msg') }}
    </div>
@endif

@if (isset($error) && $error)
    <div class="alert alert-danger">
        {{ trans('general.shop_create_fail') }}
    </div>
@endif

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


<div class="container">
    <div class="row">


    <div class="card col-md-4 mt-4 mb-4 h-150" onclick="window.location.href='{{ route('new') }}'" style="border-style: dashed; border-width: 2px; display: flex; align-items: center; justify-content: center; cursor: pointer; background-color:#f8f9fc;">
        <div onclick="window.location.href='{{ route('new') }}'" class="panel-body text-center">
            <h5 class="mt-3" onclick="window.location.href='{{ route('new') }}'">{{ trans('general.create_new_shop') }}</h5>
            <p class="text-primary">{{ trans('general.here') }}</p>
        </div>
    </div>

    @include('includes.shops_home')

    </div>
</div>



@endsection
