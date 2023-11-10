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

<div class="card shadow mb-4">
    <div class="card-header">{{ trans('general.custom_design') }}</div>
    <div class="card-body">
        <div class="card-body">
                <form method="POST" action="{{ route('admin_help_page_parent_menu_update', $menu->id) }}">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-12 mt-3">
                            <input id="title" type="text" class="form-control @error('title') is-invalid @enderror h-100" name="title" placeholder="title" value="{{ $menu->title ?? old('title') }}" autofocus>
                        </div>
                        <div class="col-md-12 mt-3">
                            <select name="lang">
                                    <option value="en" @if($menu->lang === 'en') selected @endif>English</option>
                                    <option value="sq" @if($menu->lang === 'sq') selected @endif>Shqip</option>
                            </select>
                        </div>
                    </div>
                        <button class="btn btn-primary" type="submit">{{ trans('general.update') }}</button>
                </form>
        </div>
    </div>
</div>
@endsection
