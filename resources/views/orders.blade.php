@extends('layouts.app')

<title>{{trans('general.orders')}} | {{ config('app.name') }} </title>

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
<!-- Button trigger modal -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ trans('general.orders') }}</h1>
    <!-- <a type="button" class="btn btn-primary" data-toggle="modal" data-target="#createOrder">
        {{ trans('general.create_order') }}
    </a> -->
</div>
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">{{ trans('general.orders') }}</div>

                <div class="card-body table-responsive-sm table-responsive-md table-responsive-xl">
                    @if ($orders)
                    <table class="table table-striped">
                    <thead>
                        <tr>
                        <th>{{ trans('general.order') }} ID</th>
                        <th>{{ trans('general.product') }}</th>
                        <th>{{ trans('general.order_amount') }}</th>
                        <th>{{ trans('general.payment_method') }}</th>
                        <th>{{ trans('general.status') }}</th>

                        @if(auth()->user()->isDropshipping())
                            <th>{{ trans('general.shipping') }}</th>
                        @endif

                        @if(auth()->user()->isDropshipping())
                            <th>{{ trans('general.cj_order_paid') }}</th>
                        @endif

                        @if(auth()->user()->isDropshipping())
                            <th>{{ trans('general.tracking') }}</th>
                        @endif

                        <th>{{ trans('general.date') }}</th>
                        <th>{{ trans('general.action') }}</th>
                        </tr>
                    </thead>
                        <tbody>
                        @foreach($orders as $order)
                                <tr>
                                <td style="font-size: 12px;">{{ $order['order_id'] }}</td>
                                <td style="font-size: 12px;">
                                @php
                                    $price = 0;
                                    $product_counter = 1;
                                @endphp
                                @foreach($order['products'] as $product)
                                    {{ $product_counter }} - {{ $product['product_name'] }}<br>
                                    @php
                                        $price += $product['price'];
                                        $product_counter++;
                                    @endphp
                                @endforeach
                                </td>
                                <td style="font-size: 12px;">€{{ number_format($price,2) }} + ( €{{number_format($order['shipping_price'],2)}} {{ trans('general.shipping') }} ) = €{{ number_format($price + $order['shipping_price'],2) }}</td>
                                <td>
                                    @if($product['payment_method'] == 'cash')
                                        <span class="badge badge-info" style="font-size: 12px;">{{ trans('general.cash') }}</span>
                                    @else
                                        <span class="badge badge-success" style="font-size: 12px;">{{ trans('general.credit_card') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-info" style="font-size: 12px;">{{ $product['cj_status'] ?? '-' }}</span>
                                </td>


                                @if(auth()->user()->isDropshipping())
                                    <td>
                                        <span class="badge badge-info" style="font-size: 12px;">{{ $product['cj_postal'] ?? '-' }}</span>
                                    </td>
                                @endif

                                @if(auth()->user()->isDropshipping())
                                    <td>
                                        @if($product['cj_paid'] == 'yes')
                                            <span class="badge badge-success" style="font-size: 12px;">{{ trans('general.yes') }}</span>
                                        @else
                                            <span class="badge badge-danger" style="font-size: 12px;">{{ trans('general.no') }}</span>
                                        @endif
                                    </td>
                                @endif

                                @if(auth()->user()->isDropshipping())
                                    <td>
                                        <span class="badge badge-info" style="font-size: 12px;">{{ $product['cj_tracking'] ?? '-' }}</span>
                                    </td>
                                @endif

                                <td style="font-size: 12px;">{{ $product['created_at'] }}</td>
                                <td class="d-flex">
                                    <a href="{{ route('single_order',$order['order_id']) }}" class="btn btn-secondary mr-2" style="font-size: 12px;">{{ trans('general.view') }}</a>
                                    <a href="{{ route('single_order_invoice', $order['order_id']) }}" class="btn btn-info mr-2" target="_blank" style="font-size: 12px;">{{ trans('general.invoice') }}</a>
                                    @if($product['cj_paid'] == 'no' && auth()->user()->isDropshipping())
                                    <form method="POST" action="{{ route('pay_order_balance_cj', $order['order_id']) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-success mr-2" style="font-size: 12px;">
                                            {{ trans('general.cj_pay_order') }}
                                        </button>
                                    </form>
                                    @endif
                                </td>
                                </tr>
                        @endforeach
                        </tbody>
                    </table>
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
                        <i class="fas fa-shipping-fast" style="font-size: 5rem;"></i>
                        <p style="margin-top: 1rem;">{{ trans('general.no_orders') }}</p>
                    </div>
                    @endif
                </div>
                <div class="pagination" style="margin-left: 20px;">
                    {!! $pagination !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
<div class="modal fade" id="createOrder" tabindex="-1" role="dialog" aria-labelledby="createOrderLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createOrderLabel">{{ trans('general.create_order') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="#">
            @csrf
            <span>{{ trans('general.create_order_modal_message') }}</span>                      
        </form>
      </div>
    </div>
  </div>
</div>


@include('sweetalert::alert')



@include('layouts.scripts')


@endsection
