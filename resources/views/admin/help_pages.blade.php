@extends('layouts.app')

<title>Help Pages | {{ config('app.name') }} </title>

@section('content')

<div class="float-right ml-3">
    <a type="button" href="{{ route('admin_help_pages_parent_menus') }}" class="btn btn-primary">
        Parent Menus
    </a>
</div>

<div class="float-right">
    <a type="button" href="{{ route('admin_help_pages_create_view') }}" class="btn btn-primary">
        Create Page
    </a>
</div>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Help Pages</h1>
</div>
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">Help Pages</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
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

                    @if ($help_pages->isNotEmpty())
                    <div class="table-responsive-sm table-responsive-md table-responsive-xl">
                    <table class="table table-striped">
                    <thead>
                        <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Belong Menu</th>
                        <th>Slug</th>
                        <th>Language</th>
                        <th>Action</th>
                        </tr>
                    </thead>
                        @foreach($help_pages as $page)
                            <tbody>
                                <tr>
                                    <td>{{ $page->id }}</td>
                                    <td>{{ $page->title }}</td>
                                    <td>{{ $page->belongsToMenu->title }}</td>
                                    <td>{{ $page->slug }}</td>
                                    <td>{{ $page->lang }}</td>
                                    <td class="d-flex">
                                        <a class="btn btn-primary" href="{{ route('admin_help_page_edit', $page->id) }}">Edit</a>
                                        <a class="btn btn-secondary" href="/help/{{ $page->slug }}" target="_blank">View</a>
                                        <form method="POST" action="{{ route('admin_help_page_delete', $page->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>

                                    </td>
                                </tr>
                            </tbody>
                        @endforeach
                    </table>
                    </div>
                        @else
                        <style>
                            .card{
                            background: transparent;
                            border-style: none; 
                            }
                            .card-header{
                            display: none;
                            }
                        </style>
                        <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; height: 50vh;">
                            <i class="fas fa-dna" style="font-size: 5rem;"></i>
                            <p style="margin-top: 1rem;">There are no Help Pages!</p>
                        </div>
                        @endif
                </div>
            </div>
        </div>
    </div>

@endsection
