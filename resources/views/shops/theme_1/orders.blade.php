<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $shop->shop_name }} | {{ trans('general.orders') }}</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="Customer login">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    @include('shops.theme_1.includes.header_assets')

    @include('shops.theme_1.includes.header')
    <div class="cart-main-area pt-150 pb-120">
            <div class="container">
                <h3 class="cart-page-title">{{ trans('general.your_orders') }}</h3>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <form action="#">
                            <div class="table-content table-responsive cart-table-content">
                                
                                @if($orders)
                                <table style="width: 100%;">
    <thead>
        <tr>
            <th>{{ trans('general.image') }}</th>
            <th>{{ trans('general.product_name') }}</th>
            <th>{{ trans('general.unit_price') }}</th>
            <th>{{ trans('general.action') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr data-bs-toggle="collapse" data-bs-target="#collapse-{{ $order['order_id'] }}" aria-expanded="false" aria-controls="collapse-{{ $order['order_id'] }}">
            <td style="color: blue; cursor: pointer;">{{ trans('general.order_id') }}: {{ $order['order_id'] }}</td>
        </tr>
        <tr class="collapse" id="collapse-{{ $order['order_id'] }}" aria-labelledby="heading-{{ $order['order_id'] }}" data-bs-parent="#accordion">
            <td colspan="5">
                <table class="table">
                    <tbody>
                        @php
                        $total = 0;
                        @endphp
                        @foreach($order['products'] as $product)
                        <tr>
                            <td class="product-thumbnail">
                                <a href="#"><img src="{{ $product['product_single_image'] }}" style="width: 50px; height: 50px; object-fit: contain; border-radius: 5px; margin-left: 10px;"></a>
                            </td>
                            <td class="product-name"><a href="/product/{{$product['product_url']}}">{{$product['product_name']}}</a></td>
                            <td class="product-subtotal">
                                €{{ number_format($product['price'],2) }}
                            </td>
                            <td class="product-wishlist-cart">
                                <a href="/product/{{$product['product_url']}}">{{ trans('general.view_product') }}</a>
                            </td>
                        </tr>
                        @php
                        $total += $product['price'];
                        @endphp
                        @endforeach
                        <tr>
                            <td colspan="2"></td>
                            <td><strong>Shipping: €{{ number_format($order['shipping_price'], 2) }}</strong></td>
                            <td><strong>Total: €{{ number_format(($total + $order['shipping_price']), 2) }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

                                @else
                                    {{ trans('general.no_orders') }}
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="cart-shiping-update-wrapper">
                                        <div class="cart-shiping-update">
                                            <a href="/">{{ trans('general.continue_shopping') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @include('shops.theme_1.includes.related_products')


@include('shops.theme_1.includes.footer')


<script>
$(document).ready(function() {
  var total_products_count = 0;
  var total_products_value = 0;

  $('.product-subtotal').each(function() {
    total_products_count++;

    var value = parseFloat($(this).text().replace('€', ''));
    total_products_value += value;
  });

  $('#total-products-count').html(total_products_count);
  $('#total-products-value').html('€' + total_products_value.toFixed(2));
});

</script>