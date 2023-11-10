@extends('layouts.app')

<title>Parent Menus | {{ config('app.name') }} </title>

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Parent Menus</h1>
</div>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <a type="button" href="{{ route('admin_help_page_parent_menu_create_view') }}" class="btn btn-primary">
        Create Parent
    </a>
</div>
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">Parent Menus</div>

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

                    @if ($menus->isNotEmpty())
                    <div class="table-responsive-sm table-responsive-md table-responsive-xl">
                    <table class="table table-striped">
                    <thead>
                        <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Language</th>
                        <th>Action</th>
                        </tr>
                    </thead>
                        @foreach($menus as $menu)
                            <tbody>
                                <tr>
                                    <td>{{ $menu->id }}</td>
                                    <td>{{ $menu->title }}</td>
                                    <td>{{ $menu->lang }}</td>
                                    <td class="d-flex">
                                        <a class="btn btn-primary" href="{{ route('admin_help_pages_parent_menu_edit_view', $menu->id) }}">Edit</a>
                                        <form method="POST" action="{{ route('admin_help_page_parent_menu_delete', $menu->id) }}">
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
                            <p style="margin-top: 1rem;">There are no Parent menus!</p>
                        </div>
                        @endif
                </div>
            </div>
        </div>
    </div>

@endsection
