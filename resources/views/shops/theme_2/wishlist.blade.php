<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $shop->shop_name }} | {{ trans('general.wishlist') }}</title>
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
                            <h3>{{ trans('general.wishlist_items') }}</h3>
                        </div>
                    </div>
                    <div class="col-lg-12">
                    @if($wishlist_count >= 1)
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
                                @foreach($wishlist as $wish)
                                    <tr>
                                        <td class="product-thumbnail">
                                            <a href="#"><img src="{{ $wish->product->product_single_image }}"  style="width: 50px; height: 50px; object-fit: contain; border-radius: 5px; margin-left: 10px;"/></a>
                                        </td>
                                        <td class="product-name">
                                            <a href="/product/{{$wish->product->product_url}}">{{$wish->product->product_name}}</a>
                                        </td>
                                        <td class="product-price">
                                            <div class="pi01Price">
                                                <ins class="product-subtotal">€{{number_format($wish->product->base_price - ($wish->product->base_price * $wish->product->base_price_discount / 100), 2)}}</ins>
                                            </div>
                                        </td>
                                        <td class="product-addtocart">
                                            <a href="/product/{{$wish->product->product_url}}" class="ulinaBTN"><span>{{ trans('general.view_product') }}</span></a>
                                        </td>
                                        <!-- <td class="product-remove">
                                            <a href="javascript:void(0);" class="remove"><span></span></a>
                                        </td> -->
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                            {{ trans('general.no_products_on_wishlist') }}
                        @endif
                    </div>
                    @include('shops.theme_2.includes.related_products')
                </div>
            </div>
        </section>
        <!-- END: Cart Page Section -->

@include('shops.theme_2.includes.footer')