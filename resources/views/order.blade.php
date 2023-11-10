@extends('layouts.app')

<title>{{trans('general.order')}} | {{ config('app.name') }} </title>

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">{{ trans('general.order') }} - {{ $order['order_id'] }}</div>

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

                    @if ($order)
                    <span style="font-size: 25px; font-weight: 600;">{{ trans('general.order_products') }}</span>
                    <div class="table-responsive-sm table-responsive-md table-responsive-xl">
                    <table class="table table-striped">
                    <thead>
                        <tr>
                        <th>#</th>
                        <th>{{ trans('general.image') }}</th>
                        <th>{{ trans('general.name') }}</th>
                        <th>{{ trans('general.variant') }}</th>
                        <th>{{ trans('general.sku') }}</th>
                        <th>{{ trans('general.variant') }} ID</th>
                        <th>{{ trans('general.action') }}</th>
                        </tr>
                    </thead>
                        @php
                            $count_rows = 1;
                        @endphp
                            <tbody>
                            @foreach($order['products'] as $product)
                                <tr>
                                    <td>{{ $count_rows }}</td>
                                    <td><img class="card-img-top" src="{{ $product['product_single_image'] ?? '' }}" style="height: 100px; width:auto;"></td>
                                    <td>{{ $product['product_name'] }}</td>
                                    <td>
                                        @if(isset($product['variant']))
                                            {{ $product['variant']['size'] }}<br>
                                            {{ $product['variant']['color'] }}<br>
                                            {{ $product['variant']['material'] }}<br>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>@if (isset($product['product_sku'])) {{ $product['product_sku'] }} @else - @endif</td>
                                    <td>
                                        @if(isset($product['variant'])) {{ $product['variant']['id'] }} @else - @endif
                                    </td>
                                    <td class="d-flex">
                                        <a href="/edit/product/{{ $product['product_id'] }}" class="btn btn-secondary mr-2">{{ trans('general.view_product') }}</a>
                                    </td>
                                </tr>
                            
                        @php 
                            $count_rows++ 
                        @endphp
                        @endforeach
                        </tbody>
                    </table>
                    </div>

                    <span style="font-size: 25px; font-weight: 600;">{{ trans('general.customer_billing') }}</span>
                    <div class="table-responsive-sm table-responsive-md table-responsive-xl">
                    <table class="table table-striped">
                    <thead>
                        <tr>
                        <th>{{ trans('general.full_name') }}</th>
                        <th>{{ trans('general.company') }}</th>
                        <th>{{ trans('general.country') }}</th>
                        <th>{{ trans('general.address') }}</th>
                        <th>{{ trans('general.city') }}</th>
                        <th>{{ trans('general.zip_code') }}</th>
                        <th>{{ trans('general.phone') }}</th>
                        <th>{{ trans('general.order_note') }}</th>
                        </tr>
                    </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $order['billing']['first_name'] }} {{ $order['billing']['last_name'] }}</td>
                                    <td>@if (isset($order['billing']['company'])) {{ $order['billing']['company'] }} @else - @endif</td>
                                    <td>{{ $order['billing']['country'] }}</td>
                                    <td>{{ $order['billing']['address'] }}</td>
                                    <td>{{ $order['billing']['city'] }}</td>
                                    <td>{{ $order['billing']['zip'] }}</td>
                                    <td>{{ $order['billing']['phone'] }}</td>
                                    <td>{{ $order['billing']['order_note'] }}</td>
                                </tr>
                            </tbody>
                    </table>
                    </div>
                    <a href="{{ route('single_order_invoice', $order['order_id']) }}" class="btn btn-info mr-2 float-right" target="_blank">{{ trans('general.invoice') }}</a>
                    <!-- <a href="#" class="btn btn-primary mr-2 float-right">Mark as shipped</a> -->

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
                            <p style="margin-top: 1rem;">{{ trans('general.order_does_not_exist') }}</p>
                        </div>
                        @endif
                </div>
            </div>
        </div>
    </div>




@include('sweetalert::alert')



@include('layouts.scripts')
    <script type="application/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.3/sweetalert2.min.js" integrity="sha512-eN8dd/MGUx/RgM4HS5vCfebsBxvQB2yI0OS5rfmqfTo8NIseU+FenpNoa64REdgFftTY4tm0w8VMj5oJ8t+ncQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


@endsection
