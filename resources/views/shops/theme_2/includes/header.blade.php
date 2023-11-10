    <body>
        <!-- BEGIN: Section -->
        <section class="preloader" id="preloader">
            <div class="spinner-eff spinner-eff-1">
                <div class="bar bar-top"></div>
                <div class="bar bar-right"></div>
                <div class="bar bar-bottom"></div>
                <div class="bar bar-left"></div>
            </div>
        </section>
        <!-- END: Section -->

        <!-- BEGIN: TopBar Section -->
        <section class="topbarSection">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8 col-md-6">
                        <div class="tbInfo">
                            @if($header_message)
                                <i class="fa-solid fa-bolt-lightning"></i> {{ $header_message }}
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-6">
                        <div class="tbAccessNav">
                            @if(isset($social_switch) && $social_switch != 'off')
                                <div class="anSocial">
                                    @foreach($social_networks as $social => $url)
                                        @if($social != 'phone' && $social != 'address')
                                            <a class="{{ $social }}" href="{{ $url }}"><i class="fa-brands fa-{{ $social }}"></i></a>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row"><div class="col-lg-12"><div class="tbBar"></div></div></div>
            </div>
        </section>
        <!-- END: TopBar Section -->
        
        @csrf
        <!-- BEGIN: Section -->
        <header class="header01 h01Mode2 isSticky">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="headerInner02">
                            <div class="logo">
                                <a href="/">
                                    <a href="/"><img src="{{ $shop->shop_image_url }}" alt="logo" style="height: 50px; width: auto; object-fit: contain;"></a>
                                </a>
                            </div>
                            <div class="mainMenu">
                                <ul>
                                    <li><a href="/">{{ trans('general.home') }} </a></li>
                                    @foreach ($categories as $cat)
                                    @if ($cat->depth === 0)
                                        @if (count($cat->subCategories) > 0)
                                            <li class="menu-item-has-children">
                                                <a href="/category/{{ $cat->link }}">{{ $cat->label }}</a>
                                                <ul>
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
                            </div>
                            <div class="accessNav">
                                <a href="javascript:void(0);" class="menuToggler"><i class="fa-solid fa-bars"></i> <span>Menu</span></a>
                                <div class="anItems">
                                    
                                    @if(auth()->guard('customers')->check())
                                    <div class="anCart">
                                        <a href="javascript:void(0);"><i class="fa-solid fa-user"></i></a>
                                        <div class="cartWidgetArea">
                                            <div class="cartWidgetProduct">
                                                <a href="/my/orders"><i class="fa-solid fa-user"></i> {{ trans('general.my_orders') }}</a>
                                                <a href="/customer/logout"><i class="fa-solid fa-sign-out"></i> {{ trans('general.logout') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                        <div class="anUser"><a href="#" onclick="$('#loginModal').modal('show');"><i class="fa-solid fa-user"></i></a></div>
                                    @endif
                                    @if(auth()->guard('customers')->check())
                                    <div class="anCart">
                                        <a href="/my/cart/"><i class="fa-solid fa-shopping-cart"></i><span class="cart-count">{{ $cart_count }}</span></a>
                                    </div>
                                    <div class="anCart">
                                        <a href="/my/wishlist/"><i class="fa-solid fa-heart"></i><span class="wishlist-count">{{ $wishlist_count }}</span></a>    
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="blankHeader"></div>
        <!-- END: Section -->