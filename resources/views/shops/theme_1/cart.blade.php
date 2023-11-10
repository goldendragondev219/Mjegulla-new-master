<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $shop->shop_name }} | {{ trans('general.cart') }}</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="Customer login">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    @include('shops.theme_1.includes.header_assets')

    @include('shops.theme_1.includes.header')

    <div class="cart-main-area pt-150 pb-120">
            <div class="container">
                <h3 class="cart-page-title">{{ trans('general.your_cart_items') }}</h3>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <form action="#">
                            <div class="table-content table-responsive cart-table-content">
                                @if($cart_count >= 1)
                                <table style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>{{ trans('general.image') }}</th>
                                            <th>{{ trans('general.product_name') }}</th>
                                            <th>{{ trans('general.variant') }}</th>
                                            <th>{{ trans('general.unit_price') }}</th>
                                            <th>{{ trans('general.action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($cart as $cart)
                                        <tr class="products_in_cart" data-product="{{ $cart->product_id }}">
                                            <td class="product-thumbnail">
                                                <a href="#"><img src="{{ $cart->product->product_single_image }}" style="width: 150px; height: 150px; object-fit: contain; border-radius: 5px; margin-left: 10px;"></a>
                                            </td>
                                            <td class="product-name"><a href="/product/{{$cart->product->product_url}}">{{$cart->product->product_name}}</a></td>
                                            <td class="product-variant">
                                                @if($cart->variant)
                                                    {{ $cart->variant->size }}<br>{{ $cart->variant->color }}<br>{{ $cart->variant->material }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="product-subtotal">
                                                €@if($cart->variant) 
                                                    {{ number_format($cart->variant->price, 2) }} 
                                                @else 
                                                    @if($cart->product->base_price_discount)
                                                        {{ number_format($cart->product->base_price - ($cart->product->base_price * $cart->product->base_price_discount / 100),2) }}
                                                    @else
                                                        {{ number_format($cart->product->base_price,2) }} 
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="product-wishlist-cart">
                                                <a href="/product/{{$cart->product->product_url}}">{{ trans('general.view_product') }}</a>
                                                <a href="#" onclick="deleteProductFromCart({{ $cart->product_id }})" style="background-color: red;">{{ trans('general.remove') }}</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @else
                                    {{ trans('general.no_products_in_cart') }}
                                @endif
                            </div>
                            @if($cart_count >= 1)
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="cart-shiping-update-wrapper">
                                        <div class="cart-shiping-update">
                                            <a href="/">{{ trans('general.continue_shopping') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </form>
                        @if($cart_count >= 1)
                        <div class="row">
                            <div class="col-lg-4 col-md-12">
                                <div class="grand-totall">
                                    <div class="title-wrap">
                                        <h4 class="cart-bottom-title section-bg-gary-cart">{{ trans('general.cart_total') }}</h4>
                                    </div>
                                    <h5>{{ trans('general.total_products') }} <span id="total-products-count">{{ $cart_count }}</span></h5>
                                    <h4 class="grand-totall-title">{{ trans('general.total') }} <span id="total-products-value">€0</span></h4>
                                    <a href="/my/checkout">{{ trans('general.proceed_to_checkout') }}</a>
                                </div>
                            </div>
                        </div>
                        @endif
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




function deleteProductFromCart(product_id) {

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
    })

    $.ajax({
        method: 'POST',
        url: "/cart/delete-product/"+product_id,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        success: function(response) {
            if(response.status){
                Toast.fire({
                    icon: 'success',
                    title: response.message
                })
                //$('.products_in_cart[data-product='+product_id+']').hide();
                //if(response.last){
                    location.reload();
                //}
            }else{
                Toast.fire({
                    icon: 'error',
                    title: response.message
                })
            }

        },
        error: function(error) {
            Toast.fire({
                icon: 'error',
                title: error
            })
        }
    });
}



</script>