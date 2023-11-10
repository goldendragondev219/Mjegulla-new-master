<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $shop->shop_name }} | {{ trans('general.checkout') }}</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="Customer login">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    @include('shops.theme_1.includes.header_assets')

    @include('shops.theme_1.includes.header')

    <div class="cart-main-area pt-150 pb-120">
            <div class="container">
            <div class="checkout-wrap pt-30">


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
                        <div class="col-lg-7">
                            <div class="billing-info-wrap mr-50">
                                <h3>{{ trans('general.billing_details') }}</h3>
                                <form id="order-form" action="/order/checkout" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <div class="billing-info mb-20">
                                            <label>{{ trans('general.first_name') }} <abbr class="required" title="required">*</abbr></label>
                                            <input type="text" value="{{ old('first_name', $customer_billing->first_name) }}" name="first_name" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="billing-info mb-20">
                                            <label>{{ trans('general.last_name') }} <abbr class="required" title="required">*</abbr></label>
                                            <input type="text" value="{{ old('last_name', $customer_billing->last_name) }}" name="last_name" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="billing-info mb-20">
                                            <label>{{ trans('general.company_name') }}</label>
                                            <input type="text" value="{{ old('company', $customer_billing->company) }}" name="company">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="billing-select mb-20">
                                            <label>{{ trans('general.country') }} <abbr class="required" title="required">*</abbr></label>
                                            <select id="shipping_country" name="country">
                                                @foreach($shipping_rates as $ship_countries)
                                                    <option value="{{ old('country', $ship_countries->id) }}" shipping-price="{{ $ship_countries->price }}"
                                                        @if($ship_countries->id == $customer_billing->country) selected @endif>
                                                        {{ $ship_countries->country }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    @if($shop->store_type == 'dropshipping')
                                    <div class="col-lg-12">
                                        <div class="billing-select mb-20">
                                            <label>{{ trans('general.shipping') }} <abbr class="required" title="required">*</abbr></label>
                                            <select id="shipping_company" name="shipping_company">
                                                @if(isset($cj_shipping_fees['data']))
                                                    @foreach($cj_shipping_fees['data'] as $shipping_company)
                                                        <option value="{{ old('shipping_company', $shipping_company['logisticName']) }}" shipping-company="{{ $shipping_company['logisticName'] }}">{{ str_replace('CJPacket', '', $shipping_company['logisticName']) }} [ {{ trans('general.cj_checkout_deliver_details', ['days' => $shipping_company['logisticAging'], 'price' => $shipping_company['logisticPrice']]) }} ]</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    @endif

                                    <div class="col-lg-12">
                                        <div class="billing-info mb-20">
                                            <label>{{ trans('general.street_address') }} <abbr class="required" title="required">*</abbr></label>
                                            <input class="billing-address" value="{{ old('address', $customer_billing->address) }}" name="address" placeholder="{{ trans('general.address_placeholder') }}" type="text" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="billing-info mb-20">
                                            <label>{{ trans('general.city') }} <abbr class="required" title="required">*</abbr></label>
                                            <input type="text" value="{{ old('city', $customer_billing->city) }}" name="city" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="billing-info mb-20">
                                            <label>{{ trans('general.zip_code') }} <abbr class="required" title="required">*</abbr></label>
                                            <input type="text" value="{{ old('zip', $customer_billing->zip) }}" name="zip" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="billing-info mb-20">
                                            <label>{{ trans('general.phone') }} <abbr class="required" title="required">*</abbr></label>
                                            <input type="text" value="{{ old('phone', $customer_billing->phone) }}" name="phone" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="additional-info-wrap">
                                    <label>{{ trans('general.order_note') }}</label>
                                    <textarea placeholder="Notes about your order, e.g. special notes for delivery. " name="order_note">{{ old('order_note', $customer_billing->order_note) }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="your-order-area">
                                <h3>{{ trans('general.your_order') }}</h3>
                                <div class="your-order-wrap gray-bg-4">
                                    <div class="your-order-info-wrap">
                                        <div class="your-order-info">
                                            <ul>
                                                <li>{{ trans('general.product') }} <span>{{ trans('general.price') }}</span></li>
                                            </ul>
                                        </div>
                                        <div class="your-order-middle">
                                            <ul>
                                                @php
                                                    $total_to_pay = 0;
                                                @endphp
                                                @foreach($cart as $cart)
                                                <li>{{ $cart->product->product_name }} <span>€
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
                                                    </span></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="your-order-info order-subtotal">
                                            <ul>
                                                <li>{{ trans('general.subtotal') }} <span id="checkout_sub_total">€{{ number_format($total_to_pay,2) }} </span></li>
                                            </ul>
                                        </div>
                                        <div class="your-order-info order-shipping">
                                            <ul>
                                                <li>{{ trans('general.shipping') }} <span id="checkout_shipping_amount">€0 </span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="your-order-info order-total">
                                            <ul>
                                                <li>{{ trans('general.total') }} <span id="checkout_total_amount">€0 </span></li>
                                            </ul>
                                        </div>
                                    </div>
                                    @php
                                        $payment_methods = json_decode($shop->payment_methods);
                                    @endphp
                                    <div class="payment-method">
                                        @if($shop->active == 'yes')
                                            @if($payment_methods->credit_card)
                                            <div class="pay-top sin-payment">
                                                <input id="payment-method-2" class="input-radio" type="radio" value="credit_card" name="payment_method" @if($customer_billing->payment_method == 'credit_card') checked="checked" @endif required>
                                                <label for="payment-method-2">{{ trans('general.credit_card') }}</label>
                                                <div class="payment-box payment_method_bacs">
                                                    <p>{{ trans('general.pay_via_credit_card') }}</p>
                                                    <img src="https://paymentsplugin.com/assets/blog-images/stripe-badge-transparent.png" style="width: 300px; height:auto;">
                                                </div>
                                            </div>
                                            @endif
                                            @if($payment_methods->cash)
                                            <div class="pay-top sin-payment">
                                                <input id="payment-method-3" class="input-radio" type="radio" value="cash" name="payment_method" @if($customer_billing->payment_method == 'cash') checked="checked" @endif required>
                                                <label for="payment-method-3">{{ trans('general.cash') }} </label>
                                                <div class="payment-box payment_method_bacs">
                                                    <p>{{ trans('general.pay_via_cash') }}</p>
                                                </div>
                                            </div>
                                            @endif
                                    </div>
                                </div>
                                <div class="Place-order">
                                    <a id="order-form-btn">{{ trans('general.place_order') }}</a>
                                </div>
                                </form>
                                @else
                                    {{ trans('general.shop_deactive') }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('shops.theme_1.includes.related_products')


@include('shops.theme_1.includes.footer')



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
        submitButton.textContent = "{{ trans('general.processing') }}";
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
