@if ($related_products->isNotEmpty())
<div class="related-product pb-115">
            <div class="container">
                <div class="section-title mb-45 text-center">
                    <h2>{{ trans('general.related_products') }}</h2>
                </div>
                <div class="">
                <div class="row">
                @foreach($related_products as $product)

                    @if(!$product->base_price_discount)
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-6">
                    <div class="product-plr-1">
                        <div class="single-product-wrap">
                            <div class="product-img product-img-zoom mb-15">
                                <a href="/product/{{ $product->product_url }}">
                                    <img src="{{ $product->product_single_image }}" alt="">
                                </a>
                                <div class="product-action-2 tooltip-style-2">
                                    <button title="Wishlist"><i class="icon-heart font-inc" onclick="addToWishlist({{$product->shop_id}},{{$product->id}})"></i></button>
                                </div>
                            </div>
                            <div class="product-content-wrap-2 text-center">
                                <div class="product-rating-wrap">
                                    <div class="product-rating">
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                        <i class="icon_star gray"></i>
                                    </div>
                                    <span>(2)</span>
                                </div>
                                <h3><a href="/product/{{ $product->product_url }}">{{ $product->product_name }}</a></h3>
                                <div class="product-price-2">
                                    <span>€{{ $product->base_price }}</span>
                                </div>
                            </div>
                            <div class="product-content-wrap-2 product-content-position text-center">
                                <div class="product-rating-wrap">
                                    <div class="product-rating">
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                        <i class="icon_star gray"></i>
                                    </div>
                                    <span>(2)</span>
                                </div>
                                <h3><a href="/product/{{ $product->product_url }}">{{ $product->product_name }}</a></h3>
                                <div class="product-price-2">
                                    <span>€{{ $product->base_price }}</span>
                                </div>
                                <div class="pro-add-to-cart">
                                    <button onclick="window.location.href='/product/{{ $product->product_url }}'">{{ trans('general.view') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                    @else

                    <!-- produkt me zbritje -->
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-6">
                    <div class="product-plr-1">
                        <div class="single-product-wrap">
                            <div class="product-img product-img-zoom mb-15">
                                <a href="/product/{{ $product->product_url }}">
                                    <img src="{{ $product->product_single_image }}" alt="">
                                </a>
                                <span class="pro-badge left bg-red">-{{ $product->base_price_discount }}%</span>
                                <div class="product-action-2 tooltip-style-2">
                                    <button title="Wishlist" onclick="addToWishlist({{$product->shop_id}},{{$product->id}})"><i class="icon-heart"></i></button>
                                </div>
                            </div>
                            <div class="product-content-wrap-2 text-center">
                                <div class="product-rating-wrap">
                                    <div class="product-rating">
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                        <i class="icon_star gray"></i>
                                    </div>
                                    <span>(2)</span>
                                </div>
                                <h3><a href="/product/{{ $product->product_url }}">{{ $product->product_name }}</a></h3>
                                <div class="product-price-2">
                                    <span class="new-price">€{{ number_format($product->base_price - ($product->base_price * $product->base_price_discount / 100), 2) }}</span>
                                    <span class="old-price">€{{ $product->base_price }}</span>
                                </div>
                            </div>
                            <div class="product-content-wrap-2 product-content-position text-center">
                                <div class="product-rating-wrap">
                                    <div class="product-rating">
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                        <i class="icon_star gray"></i>
                                    </div>
                                    <span>(2)</span>
                                </div>
                                <h3><a href="/product/{{ $product->product_url }}">{{ $product->product_name }}</a></h3>
                                <div class="product-price-2">
                                    <span class="new-price">€{{ number_format($product->base_price - ($product->base_price * $product->base_price_discount / 100), 2) }}</span>
                                    <span class="old-price">€{{ $product->base_price }}</span>
                                </div>
                                <div class="pro-add-to-cart">
                                    <button onclick="window.location.href='/product/{{ $product->product_url }}'">{{ trans('general.view') }}</button>
                                </div>
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
@endif