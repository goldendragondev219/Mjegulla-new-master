<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $shop->shop_name }} | {{ $category_name }}</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="{{ $shop->shop_description }}">
    <meta name="keywords" content="{{ $shop->shop_seo_keywords }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    @include('shops.theme_1.includes.header_assets')




    <div class="main-wrapper">
        @include('shops.theme_1.includes.header')


        <div class="product-area section-padding-1 pt-150 pb-75">
            <div class="container-fluid">
                <h1 class="pt-20 pb-20">{{ trans('general.category') }}: {{ $category_name }}</h1>
                <div class="tab-content jump">
                    <div id="product-1" class="tab-pane active">
                        <div class="row">
                            @foreach($products->items() as $product)

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
                {{ $products->links() }}
            </div>
        </div>

        <!-- COLLECTIONS -->

        <!-- <div class="banner-area pb-85">
            <div class="container">
                <div class="section-title mb-45">
                    <h2>Our Collections</h2>
                </div>
                <div class="row">
                    <div class="col-lg-7 col-md-7">
                        <div class="banner-wrap banner-mr-1 mb-30">
                            <div class="banner-img banner-img-zoom">
                                <a href="product-details.html"><img src="{{ asset('theme_1/assets/images/banner/banner-1.jpg') }}" alt=""></a>
                            </div>
                            <div class="banner-content-1">
                                <h2>Zara Pattern Boxed <br>Underwear</h2>
                                <p>Stretch, fresh-cool help you alway comfortable</p>
                                <div class="btn-style-1">
                                    <a class="animated btn-1-padding-2" href="product-details.html">Shop Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-5">
                        <div class="banner-wrap  banner-ml-1 mb-30">
                            <div class="banner-img banner-img-zoom">
                                <a href="product-details.html"><img src="{{ asset('theme_1/assets/images/banner/banner-2.jpg') }}" alt=""></a>
                            </div>
                            <div class="banner-content-2">
                                <h2>Basic Color Caps</h2>
                                <p>Minimalist never cool, choose and make the simple great again!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

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


    @include('shops.theme_1.includes.footer')