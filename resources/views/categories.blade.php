@extends('layouts.app')

<title>{{trans('general.categories')}} | {{ config('app.name') }} </title>

@section('content')

{!! \App\Facades\CustomMenu::render() !!}

@endsection
