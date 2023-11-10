@if ($related_products->isNotEmpty())
<div class="row relatedProductRow">
                    <div class="col-lg-12">
                        <h2 class="secTitle">{{ trans('general.related_products') }}</h2>
                        <div class="row">
                            
                            <div class="col-lg-12">
                                <div class="productCarousel owl-carousel">
                                    @foreach($related_products as $product)
                                    @if($product->base_price_discount)
                                        <div class="productItem01">
                                            <div class="pi01Thumb">
                                                <img src="{{ $product->product_single_image }}"/>
                                                <img src="{{ $product->product_single_image }}"/>
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" onclick="addToWishlist({{$product->shop_id}},{{$product->id}})" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                </div>
                                                <div class="productLabels clearfix">
                                                    <span class="plDis">-{{ $product->base_price_discount }}%</span>
                                                </div>
                                            </div>
                                            <div class="pi01Details">
                                                <h3><a href="/product/{{ $product->product_url }}">{{ $product->product_name }}</a></h3>
                                                <div class="pi01Price123">
                                                    <ins style="font-size: 21px; line-height: 30px; font-weight: 500; color: #7b9496; text-decoration: none;">€{{ number_format($product->base_price - ($product->base_price * $product->base_price_discount / 100), 2) }}</ins>
                                                    <del>€{{ $product->base_price }}</del>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="productItem01 pi01NoRating">
                                            <div class="pi01Thumb">
                                                <img src="{{ $product->product_single_image }}"/>
                                                <img src="{{ $product->product_single_image }}"/>
                                                <div class="pi01Actions">
                                                    <a href="javascript:void(0);" onclick="addToWishlist({{$product->shop_id}},{{$product->id}})" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                </div>
                                            </div>
                                            <div class="pi01Details">
                                                <h3><a href="/product/{{ $product->product_url }}">{{ $product->product_name }}</a></h3>
                                                <div class="pi01Price123">
                                                    <ins style="font-size: 21px; line-height: 30px; font-weight: 500; color: #7b9496; text-decoration: none;">€{{ $product->base_price }}</ins>
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
@endif