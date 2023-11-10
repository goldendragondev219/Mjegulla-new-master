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

    @include('shops.theme_2.includes.header_assets')

    @include('shops.theme_2.includes.header')

        <!-- BEGIN: Popular Products Section -->
        <section class="popularProductsSection2 mt-3">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="productTabs">
                            <div class="tab-content" id="productTabContent">
                                <div class="tab-pane fade show active" id="lates-tab-pane" role="tabpanel" aria-labelledby="lates-tab" tabindex="0">
                                    <div class="row">
                                        @foreach($products as $product)

                                            @if(!$product->base_price_discount)
                                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-6">
                                                    <div class="productItem01">
                                                        <div class="pi01Thumb">
                                                            <img src="{{ $product->product_single_image }}" alt=""/>
                                                            <img src="{{ $product->product_single_image }}" alt=""/>
                                                            <div class="pi01Actions">
                                                                <a href="javascript:void(0);" onclick="addToWishlist({{$product->shop_id}},{{$product->id}})" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="pi01Details">
                                                            <h3><a href="/product/{{ $product->product_url }}">{{ $product->product_name }}</a></h3>
                                                            <div class="pi01Price">
                                                                <ins>€{{ $product->base_price }}</ins>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-6">
                                                    <div class="productItem01 pi01NoRating">
                                                        <div class="pi01Thumb">
                                                            <img src="{{ $product->product_single_image }}" alt=""/>
                                                            <img src="{{ $product->product_single_image }}" alt=""/>
                                                            <div class="pi01Actions">
                                                                <a href="javascript:void(0);" onclick="addToWishlist({{$product->shop_id}},{{$product->id}})" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                            </div>
                                                            <div class="productLabels clearfix">
                                                                <span class="plDis">-{{ $product->base_price_discount }}%</span>
                                                            </div>
                                                        </div>
                                                        <div class="pi01Details">
                                                            <h3><a href="/product/{{ $product->product_url }}">{{ $product->product_name }}</a></h3>
                                                            <div class="pi01Price">
                                                                <ins>€{{ number_format($product->base_price - ($product->base_price * $product->base_price_discount / 100), 2) }}</ins>
                                                                <del>€{{ $product->base_price }}</del>
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
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </section>
        <!-- END: Popular Products Section -->



@include('shops.theme_2.includes.footer')

