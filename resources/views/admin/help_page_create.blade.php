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
                <form method="POST" action="">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-12 mt-3">
                            <input id="title" type="text" class="form-control @error('title') is-invalid @enderror h-100" name="title" placeholder="title" value="{{ $page->title ?? old('title') }}" autofocus>
                        </div>
                        <div class="col-md-12 mt-3">
                            <select name="belongs_to_menu">
                                @foreach($menus as $menu)
                                    <option value="{{ $menu->id }}">{{ $menu->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mt-3">
                            <select name="lang">
                                    <option value="en">English</option>
                                    <option value="sq">Shqip</option>
                            </select>
                        </div>
                        <div class="col-md-12 mt-3">
                            <input id="slug" type="text" class="form-control @error('slug') is-invalid @enderror h-100" name="slug" placeholder="slug" value="{{ $page->slug ?? old('slug') }}" autofocus>
                        </div>
                        <div class="col-md-12 mt-3">
                            <textarea rows="10" cols="50" id="page_content" type="text" class="form-control @error('page_content') is-invalid @enderror h-100" name="content" value="{{ $page->content ?? old('content') }}" autofocus>{{ $page->content ?? old('content') }}</textarea>
                        </div>
                    </div>
                        <button class="btn btn-primary" type="submit">{{ trans('general.save') }}</button>
                </form>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>
<script>
  CKEDITOR.replace('page_content');
</script>
@endsection
