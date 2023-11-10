@extends('layouts.app')

<title>Stores | {{ config('app.name') }} </title>

@section('content')


<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Stores ( {{ $total_stores }} )</h1>
</div>
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">Stores</div>

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

                    @if ($stores->isNotEmpty())
                    <div class="form-group row">
                        <div class="col-6 col-md-9">
                            <input id="search" type="text" class="form-control @error('search') is-invalid @enderror h-100" name="search" placeholder="Search..." value="{{ old('search', request('search')) }}" autofocus>
                        </div>
                        <div class="col-6 col-md-3">
                            <button class="btn btn-primary w-100" id="search_store_btn">Search</button>
                        </div>
                    </div>
                    <div class="table-responsive-sm table-responsive-md table-responsive-xl">
                    <table class="table table-striped">
                    <thead>
                        <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>URL</th>
                        <th>Owner Name</th>
                        <th>Owner Email</th>
                        <th>Store Type</th>
                        <th>Available Products</th>
                        <th>Orders</th>
                        <th>Active</th>
                        </tr>
                    </thead>
                        @foreach($stores as $store)
                            <tbody>
                                <tr>
                                    <td>{{ $store->id }}</td>
                                    <td>{{ $store->shop_name }}</td>
                                    <td>
                                        <a href="{{ $store->custom_domain ? "https://{$store->custom_domain}" : "https://{$store->my_shop_url}." . parse_url(config('app.url'), PHP_URL_HOST) . '/' }}" target="_blank">{{ $store->custom_domain ? "https://{$store->custom_domain}" : "https://{$store->my_shop_url}." . parse_url(config('app.url'), PHP_URL_HOST) . '/' }}</a>
                                    </td>
                                    <td><a href="{{ route('admin_stores', $store->user->id) }}">{{ $store->user->name }}</a></td>
                                    <td>{{ $store->user->email }}</td>
                                    <td>{{ $store->store_type }}</td>
                                    <td>{{ $store->products_available }}</td>
                                    <td><a href="{{ route('admin_orders', $store->id) }}">{{ $store->orders->count() }}</a></td>
                                    <td>
                                        @if ($store->active == 'yes')
                                            <span class="badge badge-success">Yes</span>
                                        @else
                                            <span class="badge badge-danger">No</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        @endforeach
                    </table>
                    <div class="pagination">
                        {{ $stores->links('vendor.pagination.default') }}
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
                            <p style="margin-top: 1rem;">There are no stores!</p>
                        </div>
                        @endif
                </div>
            </div>
        </div>
    </div>

@endsection
