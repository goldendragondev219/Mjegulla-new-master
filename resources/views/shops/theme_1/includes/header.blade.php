@csrf
<header class="header-area transparent-bar section-padding-1">
            <div class="container-fluid">
                <div class="header-large-device">
                    <div class="header-top header-top-ptb-1 border-bottom-1">
                        <div class="row">
                            <div class="col-xl-4 col-lg-5">
                                <div class="header-offer-wrap">
                                    @if($header_message)
                                        <p><i class="icon-paper-plane"></i> {{ $header_message }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xl-8 col-lg-7">
                                <div class="header-top-right">
                                    @if(isset($social_switch) && $social_switch != 'off')
                                        <div class="social-style-1 social-style-1-mrg">
                                            @foreach($social_networks as $social => $url)
                                                @if($social != 'phone')
                                                    @if($social == 'tiktok')
                                                        <a class="{{ $social }}" href="{{ $url }}" target="_blank" style="margin-top: -2px;"><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M448,209.91a210.06,210.06,0,0,1-122.77-39.25V349.38A162.55,162.55,0,1,1,185,188.31V278.2a74.62,74.62,0,1,0,52.23,71.18V0l88,0a121.18,121.18,0,0,0,1.86,22.17h0A122.18,122.18,0,0,0,381,102.39a121.43,121.43,0,0,0,67,20.14Z"/></svg></a>
                                                    @else
                                                        <a class="{{ $social }}" href="{{ $url }}" target="_blank"><i class="icon-social-{{ $social }}"></i></a>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="header-bottom">
                        <div class="row align-items-center">
                            <div class="col-xl-2 col-lg-2">
                                <div class="logo">
                                    <a href="/"><img src="{{ $shop->shop_image_url }}" alt="logo" style="height: 80px; width: auto; object-fit: contain;"></a>
                                </div>
                            </div>
                            <div class="col-xl-8 col-lg-7">
                                <div class="main-menu main-menu-padding-1 main-menu-lh-1">
                                    <nav>
                                        <ul>
                                            <li>
                                                <a href="/">{{ trans('general.home') }}</a>
                                            </li>
                                            @foreach ($categories as $cat)
                                                @if ($cat->depth === 0)
                                                    <li>
                                                        <a href="/category/{{ $cat->link }}">{{ $cat->label }}</a>
                                                        @if (count($cat->subCategories) > 0)
                                                            <ul class="sub-menu-style">
                                                                @foreach ($cat->subCategories as $sub)
                                                                    <li><a href="/sub-category/{{ $sub->link }}">{{ $sub->label }}</a></li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-3">
                                <div class="header-action header-action-flex header-action-mrg-right">
                                    @if(auth()->guard('customers')->check())
                                        <div class="same-style-2">
                                            <a href="/my/wishlist"><i class="icon-heart"></i><span class="pro-count red wishlist-count">{{ $wishlist_count }}</span></a>
                                        </div>

                                        <div class="same-style-2">
                                            <a class="cart-active" href="/my/cart/">
                                                <a href="/my/cart/"><i class="icon-basket-loaded"></i><span class="pro-count red cart-count">{{ $cart_count }}</span></a>
                                            </a>
                                        </div>
                                    @endif

                                    @if(auth()->guard('customers')->check())
                                        <div class="same-style-2" onclick="$('.dropdown-toggle').dropdown('toggle');">
                                            <div class="dropdown" onclick="$('.dropdown-toggle').dropdown('toggle');">
                                                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top: -10px;">
                                                    <i class="icon-user" onclick="$('.dropdown-toggle').dropdown('toggle');" style="font-size: 20px;"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="/my/orders">{{ trans('general.my_orders') }}</a>
                                                    <a class="dropdown-item" href="/customer/logout">{{ trans('general.logout') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="same-style-2">
                                            <a href="#" onclick="$('#loginModal').modal('show');"><i class="icon-user"></i></a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="header-small-device small-device-ptb-1">
                    <div class="row align-items-center">
                        <div class="col-5">
                            <div class="mobile-logo">
                                <a href="/">
                                <a href="/"><img src="{{  $shop->shop_image_url }}" alt="logo" style="height: 50px; width: auto;"></a>
                                </a>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="header-action header-action-flex">

                                    @if(auth()->guard('customers')->check())
                                        <div class="same-style-2">
                                            <a href="/my/wishlist"><i class="icon-heart"></i><span class="pro-count red wishlist-count">{{ $wishlist_count }}</span></a>
                                        </div>
                                        
                                        <div class="same-style-2">
                                            <a href="/my/cart/"><i class="icon-basket-loaded"></i><span class="pro-count red cart-count">{{ $cart_count }}</span></a>
                                        </div>
                                    @endif


                                    @if(auth()->guard('customers')->check())
                                        <div class="same-style-2" onclick="$('.dropdown-toggle').dropdown('toggle');">
                                            <div class="dropdown" onclick="$('.dropdown-toggle').dropdown('toggle');">
                                                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top: -10px;">
                                                    <i class="icon-user" onclick="$('.dropdown-toggle').dropdown('toggle');" style="font-size: 20px;"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="/my/orders">{{ trans('general.my_orders') }}</a>
                                                    <a class="dropdown-item" href="/customer/logout">{{ trans('general.logout') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="same-style-2">
                                            <a href="#" onclick="$('#loginModal').modal('show');"><i class="icon-user"></i></a>
                                        </div>
                                    @endif

                                <div class="same-style-2 main-menu-icon">
                                    <a class="mobile-header-button-active" href="index.html#"><i class="icon-menu"></i> </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Mobile menu start -->
        <div class="mobile-header-active mobile-header-wrapper-style">
            <div class="clickalbe-sidebar-wrap">
                <a class="sidebar-close"><i class="icon_close"></i></a>
                <div class="mobile-header-content-area">
                    @if($header_message)
                    <div class="header-offer-wrap mobile-header-padding-border-4">
                        <p><i class="icon-paper-plane"></i> {{ $header_message }}</p>
                    </div>
                    @endif
                    <div class="mobile-menu-wrap mobile-header-padding-border-2">
                        <!-- mobile menu start -->
                        <nav>
                            <ul class="mobile-menu">
                                <li><a href="/">{{ trans('general.home') }}</a></li>
                                @foreach ($categories as $cat)
                                    @if ($cat->depth === 0)
                                        @if (count($cat->subCategories) > 0)
                                            <li class="menu-item-has-children">
                                                <span class="menu-expand"><i></i></span>
                                                <a href="/category/{{ $cat->link }}">{{ $cat->label }}</a>
                                                <ul class="dropdown" style="display: none;">
                                                    @foreach ($cat->subCategories as $sub)
                                                        <li><a href="/sub-category/{{ $sub->link }}">{{ $sub->label }}</a></li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @else
                                            <li>
                                                <a href="/category/{{ $cat->link }}">{{ $cat->label }}</a>
                                            </li>
                                        @endif
                                    @endif
                                @endforeach
                            </ul>
                        </nav>
                        <!-- mobile menu end -->
                    </div>
                    @if(isset($social_networks['phone']) || isset($social_networks['address']))
                    <div class="mobile-contact-info mobile-header-padding-border-4">
                        <ul>
                            @if(isset($social_networks['phone']))<li><i class="icon-phone "></i>{{ $social_networks['phone'] }}</li>@endif
                            @if(isset($social_networks['address']))<li><i class="icon-home "></i>{{ $social_networks['address'] }}</li>@endif
                        </ul>
                    </div>
                    @endif
                    @if(isset($social_switch) && $social_switch != 'off')
                        <div class="mobile-social-icon">
                            @foreach($social_networks as $social => $url)
                                @if($social != 'phone' && $social != 'address')
                                    @if($social == 'tiktok')
                                        <a class="{{ $social }}" href="{{ $url }}" target="_blank" style="margin-top: -2px; background-color: #00ffea;"><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M448,209.91a210.06,210.06,0,0,1-122.77-39.25V349.38A162.55,162.55,0,1,1,185,188.31V278.2a74.62,74.62,0,1,0,52.23,71.18V0l88,0a121.18,121.18,0,0,0,1.86,22.17h0A122.18,122.18,0,0,0,381,102.39a121.43,121.43,0,0,0,67,20.14Z"/></svg></a>
                                    @else
                                        <a class="{{ $social }}" href="{{ $url }}" target="_blank"><i class="icon-social-{{ $social }}"></i></a>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    @endif
                    <br>
                </div>
            </div>
        </div>
        </head>
<body>