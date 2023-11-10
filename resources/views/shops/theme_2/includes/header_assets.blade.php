<!DOCTYPE html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">

        <!-- BEGIN: CSS -->
        <link rel="stylesheet" href="{{ asset('theme_2/css/bootstrap.css') }}">
        <link rel="stylesheet" href="{{ asset('theme_2/css/animate.css') }}">
        <link rel="stylesheet" href="{{ asset('theme_2/css/fontawesome-all.css') }}">
        <link rel="stylesheet" href="{{ asset('theme_2/css/owl.theme.default.min.css') }}">
        <link rel="stylesheet" href="{{ asset('theme_2/css/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ asset('theme_2/css/lightcase.css') }}">
        <link rel="stylesheet" href="{{ asset('theme_2/css/slick.css') }}">
        <link rel="stylesheet" href="{{ asset('theme_2/css/nice-select.css') }}">
        <link rel="stylesheet" href="{{ asset('theme_2/css/settings.css') }}">
        <link rel="stylesheet" href="{{ asset('theme_2/css/ulina-icons.css') }}">
        <link rel="stylesheet" href="{{ asset('theme_2/css/ignore_for_wp.css') }}">
        <link rel="stylesheet" href="{{ asset('theme_2/css/preset.css') }}">
        <link rel="stylesheet" href="{{ asset('theme_2/css/theme.css') }}">
        <link rel="stylesheet" href="{{ asset('theme_2/css/responsive.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css">
        <!-- END: CSS -->

        <!-- BEGIN: Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="{{ $shop->shop_image_url }}">
        <!-- END: Favicon -->
    </head>


    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-9FH4XS2DLL"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-9FH4XS2DLL');
    </script>

    @if(isset($shop->google_analytics))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $shop->google_analytics }}"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '{{ $shop->google_analytics }}');
        </script>
    @endif

    @if(isset($shop->facebook_pixel))
        <script>
            var facebook_pixel = true;
        </script>
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{{ $shop->facebook_pixel }}');
            fbq('track', 'PageView');
        </script>
        <noscript>
            <img height="1" width="1" style="display:none" 
            src="https://www.facebook.com/tr?id={{ $shop->facebook_pixel }}&ev=PageView&noscript=1"/>
        </noscript>
    @else
        <script>
            var facebook_pixel = false;
        </script>
    @endif



    @if(isset($shop->tiktok_pixel))
        <!-- TikTok Pixel Code -->
        <script>
            var tiktok_pixel = true;
        </script>
        <script>
        !function(w,d,t){
            var ttq = w.ttq || (w.ttq = []);
            ttq.push('init', '{{ $shop->tiktok_pixel }}');
            var s = d.createElement(t);
            s.src = 'https://analytics.tiktok.com/i18n/pixel/sdk.js';
            s.async = true;
            s.onload = function() {
                if (w.ttq) {
                    ttq.push('track', 'PageView');
                }
            };
            var firstScript = d.getElementsByTagName(t)[0];
            firstScript.parentNode.insertBefore(s, firstScript);
        }(window, document, 'script');
        </script>
        <!-- End of TikTok Pixel Code -->
    @else
        <script>
            var tiktok_pixel = false;
        </script>
    @endif


    <style>
        {{ $shop->custom_css }}
    </style>


@php

use \App\Models\StoreImpressions;
$impression = new StoreImpressions();
$impression->store_id = $shop->id;
$impression->user_location = $_SERVER['HTTP_CF_IPCOUNTRY'] ?? null;
$impression->url = '/' . ltrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$impression->user_ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR'];
$impression->referred = $_SERVER['HTTP_REFERER'] ?? null;
$impression->user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'no user agent';
$impression->save();

@endphp