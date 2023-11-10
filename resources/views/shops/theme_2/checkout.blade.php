<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $shop->shop_name }} | {{ trans('general.checkout') }}</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="Customer login">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    @include('shops.theme_2.includes.header_assets')

    @include('shops.theme_2.includes.header')

       <!-- BEGIN: Checkout Page Section -->
        <section class="checkoutPage" style="padding: 80px 0 117px;">
            <div class="container">


            @if (isset($error) && $error)
                <div class="alert alert-danger">
                    {{ trans('general.payment_problem') }}
                </div>
            @endif

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


                <div class="row">
                    <div class="col-lg-6">
                        <!-- <div class="loginLinks">
                            <p>Already have an account? <a href="javascript:void(0);">Click Here to Login</a></p>
                        </div> -->
                        <form id="order-form" method="POST" action="/order/checkout">
                        @csrf
                        <div class="checkoutForm">
                            <h3>Your Billing Address</h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" value="{{ old('first_name', $customer_billing->first_name) }}" name="first_name" placeholder="{{ trans('general.first_name') }} *"/>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" value="{{ old('last_name', $customer_billing->last_name) }}" name="last_name" placeholder="{{ trans('general.last_name') }} *"/>
                                </div>
                                <div class="col-lg-12">
                                    <input type="text" value="{{ old('company', $customer_billing->company) }}" name="company" placeholder="{{ trans('general.company_name') }}"/>
                                </div>
                                <div class="col-lg-12 py-3">
                                    <select id="shipping_country" name="country" style="width: 100%; height: 40px; border-radius: 10px;">
                                        @foreach($shipping_rates as $ship_countries)
                                            <option value="{{ old('country', $ship_countries->id) }}" shipping-price="{{ $ship_countries->price }}"
                                                @if($ship_countries->id == $customer_billing->country) selected @endif>
                                                {{ $ship_countries->country }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                @if($shop->store_type == 'dropshipping')
                                <div class="col-lg-12 mb-3">
                                    <div class="billing-select">
                                        <label>{{ trans('general.shipping') }} <abbr class="required" title="required">*</abbr></label>
                                        <select id="shipping_company" name="shipping_company" style="width: 100%; height: 40px; border-radius: 10px;">
                                            @if(isset($cj_shipping_fees['data']))
                                                @foreach($cj_shipping_fees['data'] as $shipping_company)
                                                    <option value="{{ old('shipping_company', $shipping_company['logisticName']) }}" shipping-company="{{ $shipping_company['logisticName'] }}">{{ $shipping_company['logisticName'] }} [ {{ trans('general.cj_checkout_deliver_details', ['days' => $shipping_company['logisticAging'], 'price' => $shipping_company['logisticPrice']]) }} ]</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                               @endif

                                <div class="col-lg-12">
                                    <input type="text" value="{{ old('address', $customer_billing->address) }}" name="address" placeholder="{{ trans('general.street_address') }} *"/>
                                </div>
                                <div class="col-lg-6">
                                    <input type="text" value="{{ old('city', $customer_billing->city) }}" name="city" placeholder="{{ trans('general.city') }} *"/>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" value="{{ old('zip', $customer_billing->zip) }}" name="zip" placeholder="{{ trans('general.zip_code') }} *"/>
                                </div>
                                <div class="col-md-12">
                                    <input type="text" value="{{ old('phone', $customer_billing->phone) }}" name="phone" placeholder="{{ trans('general.phone') }} *"/>
                                </div>
                                <div class="col-lg-12">
                                    <textarea name="order_note" placeholder="Order Note">{{ old('order_note', $customer_billing->order_note) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <!-- <div class="shippingCoupons">
                            <h3>Coupon Code</h3>
                            <div class="couponFormWrap clearfix">
                                <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="Write your Coupon Code">
                                <button type="submit" class="ulinaBTN" name="apply_coupon" value="Apply Code"><span>Apply Code</span></button>
                            </div>
                        </div> -->
                        <div class="orderReviewWrap">
                            <h3>Your Order</h3>
                            <div class="orderReview">
                            <table>
                                <thead>
                                    <tr>
                                        <th>{{ trans('general.product') }}</th>
                                        <th>{{ trans('general.price') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <div class="your-order-middle">
                                        <ul>
                                            @php
                                                $total_to_pay = 0;
                                            @endphp
                                            @foreach($cart as $cart)
                                            <li>
                                                {{ $cart->product->product_name }}
                                                <span>
                                                    €
                                                    @if($cart->variant)
                                                        {{ number_format($cart->variant->price, 2) }}
                                                        @php $total_to_pay += $cart->variant->price; @endphp
                                                    @else
                                                        @if($cart->product->base_price_discount)
                                                            {{ number_format($cart->product->base_price - ($cart->product->base_price * $cart->product->base_price_discount / 100),2) }}
                                                            @php $total_to_pay += $cart->product->base_price - ($cart->product->base_price * $cart->product->base_price_discount / 100); @endphp
                                                        @else
                                                            {{ number_format($cart->product->base_price, 2) }}
                                                            @php
                                                                $total_to_pay += $cart->product->base_price;
                                                            @endphp
                                                        @endif
                                                    @endif
                                                </span>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>{{ trans('general.subtotal') }} </th>
                                        <td>
                                            <div class="pi01Price">
                                                <ins id="checkout_sub_total">€{{ number_format($total_to_pay,2) }}</ins>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="shippingRow">
                                        <th>{{ trans('general.shipping') }}</th>
                                        <td>
                                            <div class="pi01Price">
                                                <ins id="checkout_shipping_amount">€0</ins>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('general.total') }}</th>
                                        <td>
                                            <div class="pi01Price">
                                                <ins id="checkout_total_amount">€{{ number_format($total_to_pay,2) }}</ins>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>

                                @php
                                    $payment_methods = json_decode($shop->payment_methods);
                                @endphp
                                @if($shop->active == 'yes')
                                <ul class="wc_payment_methods">
                                    @if($payment_methods->credit_card)
                                        <li class="active">
                                            <input type="radio" checked="" value="credit_card" name="payment_method" id="payment_method" @if($customer_billing->payment_method == 'credit_card') checked="checked" @endif required>
                                            <label for="payment_method">{{ trans('general.credit_card') }}</label>
                                            <div class="paymentDesc shows">
                                                {{ trans('general.pay_via_credit_card') }}
                                                <img src="https://paymentsplugin.com/assets/blog-images/stripe-badge-transparent.png" style="width: 300px; height:auto;">
                                            </div>
                                        </li>
                                    @endif
                                    @if($payment_methods->cash)
                                    <li>
                                        <input type="radio" value="cash" name="payment_method" id="payment_method" @if($customer_billing->payment_method == 'cash') checked="checked" @endif required>
                                        <label for="payment_method">{{ trans('general.cash') }}</label>
                                        <div class="paymentDesc">
                                            {{ trans('general.pay_via_cash') }}
                                        </div>
                                    </li>
                                    @endif
                                </ul>
                                <a id="order-form-btn" class="placeOrderBTN ulinaBTN" style="cursor: pointer;"><span id="processing_text">{{ trans('general.place_order') }}</span></a>
                                </form>
                                @else
                                    {{ trans('general.shop_deactive') }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @include('shops.theme_2.includes.related_products')
            </div>
        </section>
        <!-- END: Checkout Page Section -->

        @include('shops.theme_2.includes.footer')



@if($customer_billing->country)
    <script>
        //select first shipping option on page load, if customer billing not set.
        $(document).ready(function(){
            $('#shipping_company').prop('selectedIndex', {{ $customer_billing->country - 1 }}).change();
        });
    </script>
@else
    <script>
        //select first shipping option on page load.
        $(document).ready(function(){
            $('#shipping_company').prop('selectedIndex', 0).change();
        });
    </script>
@endif


@if(isset($cj_shipping_fees['data']))
    <script>
        var shipping_companies = @json($cj_shipping_fees['data']);
    </script>
@endif


<script>

$('#shipping_country').on('change', function(){
    var selectedCountry = $(this).val();
    var shop_type = "{{ $shop->store_type }}";
    if(shop_type == 'local_store'){
        var shipping_price = parseFloat($(this).find('option:selected').attr('shipping-price'));
        var checkout_sub_total = parseFloat($('#checkout_sub_total').text().replace('€', ''));
        $('#checkout_shipping_amount').html('€' + shipping_price.toFixed(2));
        var checkout_total_amount = checkout_sub_total+shipping_price;
        $('#checkout_total_amount').html('€' + checkout_total_amount.toFixed(2));
    }else{
        $.ajax({
            type: "POST",
            url: '/shipping/country',
            headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            data: {
                country_id: selectedCountry,
                shop_id: "{{ $shop->id }}"
            },
            success: function(response) {
                shipping_companies = response;
                if (response.length === 0) {
                    alert('There is no shipping method for this country');
                }
                // Clear existing options
                $('#shipping_company').empty();
                    
                // Add new options based on the response
                $.each(response, function(index, shipping_company) {
                    var remove_cj_packet_name = shipping_company.logisticName.replace('CJPacket', '');

                    var optionText = remove_cj_packet_name + ' [ ' + '@lang('general.cj_checkout_deliver_details', ['days' => "' + shipping_company.logisticAging + '", 'price' => "' + shipping_company.logisticPrice + '"])' + ' ]';


                    $('#shipping_company').append($('<option>', {
                        value: shipping_company.logisticName,
                        'shipping-company': shipping_company.logisticName,
                        text: optionText
                    }));
                });
                //set first as default
                $('#shipping_company').prop('selectedIndex', 0).change();
            },
            error: function(xhr, status, error) {
                // Handle error response
                alert(xhr.responseText);
            }
        });
    }

});


$('#shipping_company').on('change', function(){
    var selected_company = $(this).val();
    
    // Iterate through the shipping_companies array
    for (var i = 0; i < shipping_companies.length; i++) {
        var company = shipping_companies[i];
        
        // Check if the logisticName matches the selected_company
        if (company.logisticName === selected_company) {
            var logisticPrice = company.logisticPrice;
            var checkout_sub_total = parseFloat($('#checkout_sub_total').text().replace('€', ''));
            $('#checkout_shipping_amount').html('€' + logisticPrice.toFixed(2));
            var checkout_total_amount = checkout_sub_total+logisticPrice;
            $('#checkout_total_amount').html('€' + checkout_total_amount.toFixed(2));
            
            break; // Exit the loop once a match is found
        }
    }
});



</script>

<script>
    var submitButton = document.getElementById('order-form-btn');
    var form = document.getElementById('order-form');
    

    submitButton.addEventListener('click', function() {
        submitButton.disabled = true;
        var processing_btn = document.getElementById('processing_text');
        processing_btn.textContent = "{{ trans('general.processing') }}";
        //submitButton.textContent = "{{ trans('general.processing') }}";
        if(facebook_pixel){
            fbq('track', 'CheckOutProceed', {
                content_type: 'product',
                value: checkout_total_amount,
                currency: 'EUR'
            });
        }
        if(tiktok_pixel){
            ttq.push('track', 'CheckOutProceed', {
                content_type: 'product',
                value: checkout_total_amount,
                currency: 'EUR'
            });
        }
        form.submit();
    });
</script>
