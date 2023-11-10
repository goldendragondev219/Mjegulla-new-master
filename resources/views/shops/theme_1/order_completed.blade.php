<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $shop->shop_name }} | {{ trans('general.order') }} @if (isset($success) && $success) {{ trans('general.completed') }} @else {{ trans('general.error') }} @endif</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="Customer login">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    @include('shops.theme_1.includes.header_assets')

    @include('shops.theme_1.includes.header')

    <div class="cart-main-area pt-150 pb-120">
        <div class="container text-center">


            @if (isset($success) && $success)
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100">
                <circle cx="50" cy="50" r="45" stroke="#008000" stroke-width="10" fill="#ffffff" />
                <path d="M30 50 L45 65 L70 30" stroke="#008000" stroke-width="10" fill="none" />
            </svg>
            <h3>{{ trans('general.order_completed') }}</h3> 
            <p>{{ trans('general.order_completed_desc') }}</p>
            @endif

            @if (isset($error) && $error)
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100">
                <circle cx="50" cy="50" r="45" stroke="#ff0000" stroke-width="10" fill="#ffffff" />
                <path d="M30 30 L70 70 M30 70 L70 30" stroke="#ff0000" stroke-width="10" />
            </svg>
            <h3>{{ trans('general.order_error') }}</h3> 
            <p>{{ trans('general.order_error_desc') }}</p>
            @endif

        </div>
    </div>
    @include('shops.theme_1.includes.related_products')

<script>
    if(facebook_pixel){
        fbq('track', 'OrderCompleted');
    }

    if(tiktok_pixel){
        ttq.push('track', 'OrderCompleted');
    }

</script>

    @include('shops.theme_1.includes.footer')