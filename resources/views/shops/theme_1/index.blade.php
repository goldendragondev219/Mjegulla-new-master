<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $shop->shop_name }}</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="{{ $shop->shop_description }}">
    <meta name="keywords" content="{{ $shop->shop_seo_keywords }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    @include('shops.theme_1.includes.header_assets')




    <div class="main-wrapper">
        @include('shops.theme_1.includes.header')
        @if(isset($featured_switch) && $featured_switch != 'off')
            <div class="slider-area bg-gray">
                <div class="hero-slider-active-1 hero-slider-pt-1 nav-style-1 dot-style-1">
                    <div class="single-hero-slider single-animation-wrap">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="hero-slider-content-1 hero-slider-content-1-pt-1 slider-animated-1">
                                        <h1 class="animated">{{ $featured_details['title'] }}</h1>
                                        <p class="animated">{{ $featured_details['description'] }}</p>
                                        @if(isset($featured_details['button_name']) && isset($featured_details['button_url']))
                                            <div class="btn-style-1">
                                                <a class="animated btn-1-padding-1" href="{{ $featured_details['button_url'] }}">{{ $featured_details['button_name'] }}</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="hero-slider-img-1 slider-animated-1">
                                        <img class="animated" src="{{ $featured_details['featured_image'] }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif



        <!-- COLLECTIONS -->
        @foreach($collections as $collection)
        <div class="banner-area py-4 pb-75" @if(!isset($featured_switch) || $featured_switch == 'off') style="margin-top: 150px;" @endif>
            <div class="container">
                <div class="section-title mb-45">
                    <h2>{{ $collection->name }}</h2>
                </div>
                    <div id="product-1" class="tab-pane active">
                        <div class="row">
                        @php
                            $collection_products = \App\Models\CollectionProducts::where('shop_id', $shop->id)
                            ->where('collection_id', $collection->id)
                            ->limit(6)
                            ->orderBy('id', 'DESC')
                            ->get();
                        @endphp
                        @foreach($collection_products as $c_p)
                            @foreach($c_p->product as $product)
                                @if(!$product->base_price_discount)
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-6">
                                        <div class="single-product-wrap mb-35">
                                            <div class="product-img product-img-zoom mb-20">
                                                <a href="/product/{{ $product->product_url }}">
                                                    <img src="{{ $product->product_single_image }}" alt="">
                                                </a>
                                            </div>
                                            <div class="product-content-wrap">
                                                <div class="product-content-left">
                                                    <h4><a href="/product/{{ $product->product_url }}">{{ $product->product_name }}</a></h4>
                                                    <div class="product-price">
                                                        <span>€{{ $product->base_price }}</span>
                                                    </div>
                                                </div>
                                                <div class="product-content-right tooltip-style">
                                                    <button class="font-inc" onclick="addToWishlist({{$product->shop_id}},{{$product->id}})"><i class="icon-heart" product-id="{{ $product->id }}"></i><span>{{ trans('general.wishlist') }}</span></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <!-- For discount -->
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-6">
                                        <div class="single-product-wrap mb-35">
                                            <div class="product-img product-img-zoom mb-20">
                                                <a href="/product/{{ $product->product_url }}">
                                                    <img src="{{ $product->product_single_image }}" alt="">
                                                </a>
                                                <span class="pro-badge left bg-red">-{{ $product->base_price_discount }}%</span>
                                            </div>
                                            <div class="product-content-wrap">
                                                <div class="product-content-left">
                                                    <h4><a href="/product/{{ $product->product_url }}">{{ $product->product_name }}</a></h4>
                                                    <div class="product-price">
                                                        <span class="new-price">€{{ number_format($product->base_price - ($product->base_price * $product->base_price_discount / 100), 2) }}</span>
                                                        <span class="old-price">€{{ $product->base_price }}</span>
                                                    </div>
                                                </div>
                                                <div class="product-content-right tooltip-style">
                                                    <button class="font-inc" onclick="addToWishlist({{$product->shop_id}},{{$product->id}})"><i class="icon-heart" product-id="{{ $product->id }}"></i><span>{{ trans('general.wishlist') }}</span></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endforeach
                            <div class="product-content-wrap">
                                <div class="product-content-left">
                                    <h4><a href="/collection/{{ $collection->slug }}">{{ trans('general.view_more') }}</a></h4>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
        @endforeach



        <div class="product-area section-padding-1 @if(!isset($featured_switch) || $featured_switch == 'off') pt-150 @else pt-115 @endif pb-75">
            <div class="container-fluid">
                <div class="tab-content jump">
                    <div id="product-1" class="tab-pane active">
                        <div class="row">

                            @foreach($products as $product)

                            @if(!$product->base_price_discount)
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-6">
                                <div class="single-product-wrap mb-35">
                                    <div class="product-img product-img-zoom mb-20">
                                        <a href="/product/{{ $product->product_url }}">
                                            <img src="{{ $product->product_single_image }}" alt="">
                                        </a>
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="product-content-left">
                                            <h4><a href="/product/{{ $product->product_url }}">{{ $product->product_name }}</a></h4>
                                            <div class="product-price">
                                                <span>€{{ $product->base_price }}</span>
                                            </div>
                                        </div>
                                        <div class="product-content-right tooltip-style">
                                            <button class="font-inc" onclick="addToWishlist({{$product->shop_id}},{{$product->id}})"><i class="icon-heart" product-id="{{ $product->id }}"></i><span>{{ trans('general.wishlist') }}</span></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else

                            <!-- For discount -->
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-6">
                                <div class="single-product-wrap mb-35">
                                    <div class="product-img product-img-zoom mb-20">
                                        <a href="/product/{{ $product->product_url }}">
                                            <img src="{{ $product->product_single_image }}" alt="">
                                        </a>
                                        <span class="pro-badge left bg-red">-{{ $product->base_price_discount }}%</span>
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="product-content-left">
                                            <h4><a href="/product/{{ $product->product_url }}">{{ $product->product_name }}</a></h4>
                                            <div class="product-price">
                                                <span class="new-price">€{{ number_format($product->base_price - ($product->base_price * $product->base_price_discount / 100), 2) }}</span>
                                                <span class="old-price">€{{ $product->base_price }}</span>
                                            </div>
                                        </div>
                                        <div class="product-content-right tooltip-style">
                                            <button class="font-inc" onclick="addToWishlist({{$product->shop_id}},{{$product->id}})"><i class="icon-heart" product-id="{{ $product->id }}"></i><span>{{ trans('general.wishlist') }}</span></button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            @endif

                            @endforeach



                        </div>
                    </div>
                </div>
            </div>
        </div>

        

        <!-- BRANDS -->
        
        <!-- <div class="brand-logo-area pt-100 pb-100">
            <div class="container">
                <div class="brand-logo-wrap brand-logo-mrg">
                    <div class="single-brand-logo mb-10">
                        <img src="{{ asset('theme_1/assets/images/brand-logo/brand-logo-1.png') }}" alt="brand-logo">
                    </div>
                    <div class="single-brand-logo mb-10">
                        <img src="{{ asset('theme_1/assets/images/brand-logo/brand-logo-2.png') }}" alt="brand-logo">
                    </div>
                    <div class="single-brand-logo mb-10">
                        <img src="{{ asset('theme_1/assets/images/brand-logo/brand-logo-3.png') }}" alt="brand-logo">
                    </div>
                    <div class="single-brand-logo mb-10">
                        <img src="{{ asset('theme_1/assets/images/brand-logo/brand-logo-4.png') }}" alt="brand-logo">
                    </div>
                    <div class="single-brand-logo mb-10">
                        <img src="{{ asset('theme_1/assets/images/brand-logo/brand-logo-5.png') }}" alt="brand-logo">
                    </div>
                </div>
            </div>
        </div> -->

        <div class="pagination py-3 d-flex justify-content-center">
            {{ $products->links('vendor.pagination.default') }}
        </div>


    @include('shops.theme_1.includes.footer')