<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="icon" href="{{ asset('home_assets/logo.png') }}" type="image/x-icon">
      <link rel="shortcut icon" href="{{ asset('home_assets/logo.png') }}" type="image/x-icon">

      <!-- PWA  -->
      <link rel="apple-touch-icon" href="{{ asset('home_assets/pwa_logo.png') }}">
      <link rel="manifest" href="{{ asset('/manifest.json') }}">

      <title>@if(request()->path() !== '/') @yield('title') - @endif {{ config('app.name') }} | Fuqizo biznesin tënd në internet me lehtësi.</title>
      <link href="{{ asset('home_assets/assets/css/style-collaboration.css%3Fv1.0.0.css') }}" rel="stylesheet" type="text/css">
      <meta property="og:image" content="{{ asset('home_assets/thumb.png') }}" />
      <meta name="description" content="Filloni të shisni produktet dhe shërbimet tuaja në internet, në dyqan ose në lëvizje me Mjegulla - platformën përfundimtare për t'ju ndihmuar të rritni biznesin tuaj." />
      <meta name="keywords" content="shit online, shit produkte online, si te shes produkte online, dyqan online, krijo dyqan online, si te krijoj dyqan online" />
      <!-- <link rel="stylesheet" href="assets/css/style-collaboration.css%3Fv1.0.0.css"> -->
      <!-- Google tag (gtag.js) -->
      <script async src="https://www.googletagmanager.com/gtag/js?id=G-9FH4XS2DLL"></script>
      <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-9FH4XS2DLL');
      </script>
   </head>

<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/653ec02af2439e1631e9b076/1hduhtabf';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->

   <body class="nk-body" data-navbar-collapse="xl">
      <div class="nk-app-root home-collaboration-tool">
         <header class="nk-header">
            <div class="nk-header-main  bg-purple-200">
               <div class="container-fluid container-xl">
                  <div class="nk-header-wrap">
                     <div class="nk-header-logo">
                        <a href="/" class="logo-link">  
                        <div class="logo-wrap" style="line-height: 3em;"><img class="logo-img logo-dark" src="{{ asset('home_assets/logo.png') }}" srcset="{{ asset('home_assets/logo.png') }} 2x" alt="brand-logo" style="height: 50px;"></div>
                        </a>
                     </div>
                     <nav class="nk-header-menu nk-navbar">
                        <div>
                           <ul class="nk-nav">
                                <li class="nk-nav-item"><a href="/" class="nk-nav-link"><span class="nk-nav-text">{{ trans('general.home') }}</span></a></li>
                                <li class="nk-nav-item has-sub">
                                    <a href="index-collaboration-tool.html#" class="nk-nav-link nk-nav-toggle"><span class="nk-nav-text">{{ trans('general.help') }}</span></a>
                                    <ul class="nk-nav-sub nk-nav-mega">
                                        <li class="nk-nav-item">
                                        <ul>
                                            <li>
                                                <a href="/help" class="nk-nav-link">
                                                    <div class="media-group">
                                                    <div class="text-primary me-3"><em class="icon ni ni-chat-circle-fill"></em></div>
                                                    <div class="media-text sm"><span class="lead-text">{{ trans('general.q_a') }}</span><span class="sub-text m-0">{{ trans('general.q_a_desc') }}</span></div>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                        </li>
                                    </ul>
                                </li>
                           </ul>
                           <div class="nk-navbar-btn d-lg-none">
                              <ul class="nk-btn-group sm justify-content-center">
                                @if(auth()->guest())
                                    <li class="w-100"><a href="/login" class="btn btn-primary w-100"><em class="icon ni  ni-user"></em><span>{{ trans('general.login') }}</span></a></li>
                                @else
                                    <li class="w-100"><a href="{{ route('home') }}" class="btn btn-primary w-100"><em class="icon ni ni-layout-fill"></em><span>{{ trans('general.my_shops') }}</span></a></li>
                                @endif
                              </ul>
                           </div>
                        </div>
                     </nav>
                     <div class="nk-header-action">
                        <ul class="nk-btn-group sm justify-content-center">
                            @if(auth()->guest())
                                <li class="d-none d-md-block"><a href="/login" class="btn btn-primary"><em class="icon ni ni-user"></em><span>{{ trans('general.login') }}</span></a></li>
                            @else
                            <li class="d-none d-md-block"><a href="{{ route('home') }}" class="btn btn-primary"><em class="icon ni ni-layout-fill"></em><span>{{ trans('general.my_shops') }}</span></a></li>
                            @endif
                           <li class="nk-navbar-toggle"><button class="btn  btn-outline-dark navbar-toggle rounded-1 h-100 p-2"><em class="icon ni ni-menu"></em></button></li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
         </header>