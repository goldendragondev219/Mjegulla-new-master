<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $shop->shop_name }} | {{ trans('general.cart') }}</title>
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
                            <h3>{{ trans('general.your_cart_items') }}</h3>
                        </div>
                    </div>
                    <div class="col-lg-12">
                    @if($cart_count >= 1)
                        <table class="shop_table cart_table wisthlist_table">
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
                                    <tr>
                                        <td class="product-thumbnail">
                                            <a href="#"><img src="{{ $cart->product->product_single_image }}" style="width: 50px; height: 50px; object-fit: contain; border-radius: 5px; margin-left: 10px;"/></a>
                                        </td>
                                        <td class="product-name">
                                            <a href="/product/{{$cart->product->product_url}}">{{$cart->product->product_name}}</a>
                                        </td>
                                        <td class="product-variant">
                                            @if($cart->variant)
                                                {{ $cart->variant->size }}<br>{{ $cart->variant->color }}<br>{{ $cart->variant->material }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="product-price product-subtotal">
                                            <div class="pi01Price">
                                                €@if($cart->variant) 
                                                    {{ number_format($cart->variant->price, 2) }} 
                                                @else 
                                                    @if($cart->product->base_price_discount)
                                                        {{ number_format($cart->product->base_price - ($cart->product->base_price * $cart->product->base_price_discount / 100),2) }}
                                                    @else
                                                        {{ number_format($cart->product->base_price,2) }} 
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                        <td class="product-addtocart">
                                            <a href="/product/{{$cart->product->product_url}}" class="ulinaBTN"><span>{{ trans('general.view_product') }}</span></a>
                                            <a href="/product/{{$cart->product->product_url}}" class="ulinaBTN" onclick="deleteProductFromCart({{ $cart->product_id }})"><span>{{ trans('general.remove') }}</span></a>
                                        </td>
                                        <!-- <td class="product-remove">
                                            <a href="javascript:void(0);" class="remove"><span></span></a>
                                        </td> -->
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="actions">
                                    <td colspan="2" class="text-start">
                                        <a href="/" class="ulinaBTN"><span>{{ trans('general.continue_shopping') }}</span></a>  
                                    </td>
                                    <!-- <td colspan="4" class="text-end">
                                        <a href="shop_full_width.html" class="ulinaBTN2">Update Cart</a>  
                                        <a href="shop_full_width.html" class="ulinaBTN2">Clear All</a>  
                                    </td> -->
                                </tr>
                            </tfoot>
                        </table>
                        @else
                            {{ trans('general.no_products_in_cart') }}
                        @endif
                    </div>
                    @if($cart_count >= 1)
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="col-sm-12 cart_totals" style="margin: 0px;">
                                <table class="shop_table shop_table_responsive">
                                    <tr class="cart-subtotal">
                                        <th>{{ trans('general.total_products') }}</th>
                                        <td data-title="Subtotal">
                                            <div class="pi01Price">
                                                <ins id="total-products-count">{{ $cart_count }}</ins>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="order-total">
                                        <th>{{ trans('general.cart_total') }}</th>
                                        <td data-title="Subtotal">
                                            <div class="pi01Price">
                                                <ins id="total-products-value">€0</ins>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <a href="/my/checkout" class="checkout-button ulinaBTN">
                                    <span>{{ trans('general.proceed_to_checkout') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @include('shops.theme_2.includes.related_products')
            </div>
        </section>
        <!-- END: Cart Page Section -->




@include('shops.theme_2.includes.footer')


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