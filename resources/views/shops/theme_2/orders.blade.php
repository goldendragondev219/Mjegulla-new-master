<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $shop->shop_name }} | {{ trans('general.your_orders') }}</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="Customer login">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    @include('shops.theme_2.includes.header_assets')

    @include('shops.theme_2.includes.header')
        
        <!-- END: Cart Page Section -->
        <section class="cartPageSection woocommerce" style="padding: 80px 0 117px;">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="cartHeader">
                            <h3>{{ trans('general.your_orders') }}</h3>
                        </div>
                    </div>
                    <div class="col-lg-12">
                    @if($orders)
                        <table class="shop_table cart_table wisthlist_table">
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
                    @include('shops.theme_2.includes.related_products')
                </div>
            </div>
        </section>
        <!-- END: Cart Page Section -->

@include('shops.theme_2.includes.footer')