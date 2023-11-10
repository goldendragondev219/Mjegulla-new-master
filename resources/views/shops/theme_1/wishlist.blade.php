<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $shop->shop_name }} | {{ trans('general.wishlist') }}</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="Customer login">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    @include('shops.theme_1.includes.header_assets')

    @include('shops.theme_1.includes.header')

    <div class="cart-main-area pt-150 pb-120">
            <div class="container">
                <h3 class="cart-page-title">{{ trans('general.wishlist_items') }}</h3>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <form action="wishlist.html#">
                            <div class="table-content table-responsive cart-table-content">
                                @if($wishlist_count >= 1)
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
                                        @foreach($wishlist as $wish)
                                        <tr>
                                            <td class="product-thumbnail">
                                                <a href="#"><img src="{{ $wish->product->product_single_image }}" style="width: 150px; height: 150px; object-fit: contain; border-radius: 5px; margin-left: 10px;"></a>
                                            </td>
                                            <td class="product-name"><a href="/product/{{$wish->product->product_url}}">{{$wish->product->product_name}}</a></td>
                                            <td class="product-subtotal">€{{number_format($wish->product->base_price - ($wish->product->base_price * $wish->product->base_price_discount / 100), 2)}}</td>
                                            <td class="product-wishlist-cart">
                                                <a href="/product/{{$wish->product->product_url}}">{{ trans('general.view_product') }}</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @else
                                    {{ trans('general.no_products_on_wishlist') }}
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @include('shops.theme_1.includes.related_products')


    @include('shops.theme_1.includes.footer')