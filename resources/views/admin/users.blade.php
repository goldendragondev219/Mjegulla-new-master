@extends('layouts.app')

<title>Users | {{ config('app.name') }} </title>

@section('content')


<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Users ( {{ $total_users }} ) - ONLINE - {{ $online_users }}</h1>
</div>
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">Users</div>

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

                    @if ($users->isNotEmpty())
                    <div class="form-group row">
                        <div class="col-6 col-md-9">
                            <input id="search" type="text" class="form-control @error('search') is-invalid @enderror h-100" name="search" placeholder="Search..." value="{{ old('search', request('search')) }}" autofocus>
                        </div>
                        <div class="col-6 col-md-3">
                            <button class="btn btn-primary w-100" id="search_user_btn">Search</button>
                        </div>
                    </div>
                    <div class="table-responsive-sm table-responsive-md table-responsive-xl">
                    <table class="table table-striped">
                    <thead>
                        <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Credit Card</th>
                        <th>Stores</th>
                        <th>Language</th>
                        <th>Action</th>
                        </tr>
                    </thead>
                        @foreach($users as $user)
                            <tbody>
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if ($user->default_payment_method)
                                            <span class="badge badge-success">Yes</span>
                                        @else
                                            <span class="badge badge-primary">No</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->shops->isNotEmpty())
                                            <a href="{{ route('admin_stores', $user->id) }}"><span class="badge badge-success">{{ $user->shops->count() }} Stores View</span></a>
                                        @else
                                            <span class="badge badge-primary">No</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->default_language }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('login_to_user', $user->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-success">Login</button>
                                        </form>
                                    </td>
                                </tr>
                            </tbody>
                        @endforeach
                    </table>
                    <div class="pagination">
                        {{ $users->links('vendor.pagination.default') }}
                    </div>
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
                            <p style="margin-top: 1rem;">There are no users!</p>
                        </div>
                        @endif
                </div>
            </div>
        </div>
    </div>

@endsection
