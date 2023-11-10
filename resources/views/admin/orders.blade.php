@extends('layouts.app')

<title>Orders | {{ config('app.name') }} </title>

@section('content')


<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Orders ( {{ $total_orders }} )</h1>
</div>
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">Orders</div>

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

                    @if ($orders->isNotEmpty())
                    <div class="table-responsive-sm table-responsive-md table-responsive-xl">
                    <table class="table table-striped">
                    <thead>
                        <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Store</th>
                        <th>Owner</th>
                        <th>Shipping</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Tracking</th>
                        <th>CJ PAID</th>
                        <th>Created</th>
                        <th>Updated</th>
                        </tr>
                    </thead>
                        @foreach($orders as $order)
                            <tbody>
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->user_id }}</td>
                                    <td><a href="{{ route('admin_orders', $order->store->id) }}">{{ $order->store->shop_name }}</a></td>
                                    <td><a href="/admin/users?search={{ $order->store->user->email }}">{{ $order->store->user->name }}</a></td>
                                    <td>€{{ $order->shipping_price }}</td>
                                    <td>€{{ $order->amount }}</td>
                                    <td>{{ $order->cj_status }}</td>
                                    <td>
                                        @if($order->cj_tracking !== null)
                                            {{ $order->cj_tracking }}
                                        @else
                                            <span class="badge badge-primary">No</span>
                                        @endif
                                    </td>
                                    <td>{{ $order->cj_paid }}</td>
                                    <td>{{ $order->created_at }}</td>
                                    <td>{{ $order->updated_at }}</td>
                                </tr>
                            </tbody>
                        @endforeach
                    </table>
                    <div class="pagination">
                        {{ $orders->links('vendor.pagination.default') }}
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
                            <p style="margin-top: 1rem;">There are no orders!</p>
                        </div>
                        @endif
                </div>
            </div>
        </div>
    </div>

@endsection
