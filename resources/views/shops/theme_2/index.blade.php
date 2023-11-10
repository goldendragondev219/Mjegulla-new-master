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

<style>
    .tp-bullet, .tp-leftarrow, .tp-rightarrow{
        display: none;
    }
</style>

            <!-- BEGIN: Slider Section -->
            @if(isset($featured_switch) && $featured_switch != 'off')
            <section class="sliderSection02">
            <div class="rev_slider_wrapper">
                <div id="rev_slider_2" class="rev_slider fullwidthabanner" style="display:none;" data-version="5.4.1">
                <ul>
                        <li data-index="rs-3046" data-transition="random-premium" data-slotamount="default" data-hideafterloop="0" data-hideslideonmobile="off"  data-easein="Power3.easeInOut" data-easeout="Power3.easeInOut" data-masterspeed="1000"  data-thumb=""  data-rotate="0"  data-saveperformance="off"  data-title="" data-param1="01" data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9="" data-param10="" data-description="">
                            <img src="{{ $featured_details['featured_image'] }}"  alt="Ulina Slider" class="rev-slidebg" />
                            <div class="tp-caption jost theSubTitle" 
                                 data-x="['left','left','left','center']" 
                                 data-hoffset="['0','0','0','0']" 

                                 data-y="['middle','middle','middle','middle']" 
                                 data-voffset="['-38','-38','-38','-58']" 

                                 data-fontsize="['21','21','21','21']"
                                 data-fontweight="['500','500','500','500']"
                                 data-lineheight="['21','21','21','21']"
                                 data-width="['auto','auto','auto','100%']"
                                 data-height="none"
                                 data-whitespace="nowrap"
                                 data-color="['#7b9496','#7b9496','#7b9496','#7b9496']"

                                 data-type="text" 
                                 data-responsive_offset="off" 

                                 data-frames='[{"delay":1100,"speed":400,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"power4.inOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"power3.inOut"}]'

                                 data-textAlign="['left','left','left','center']"
                                 data-paddingtop="['0','0','0','0']"
                                 data-paddingright="['0','0','0','0']"
                                 data-paddingbottom="['0','0','0','0']"
                                 data-paddingleft="['0','0','0','0']"
                                 data-marginleft="['0','10','10','0']"

                                 ><span class="slider_desc">{{ $featured_details['description'] }}</span></div>
                            <div class="tp-caption jost textLayer theTitles" 
                                 data-x="['left','left','left','center']" 
                                 data-hoffset="['-5','-5','0','0']" 

                                 data-y="['middle','middle','middle','middle']" 
                                 data-voffset="['68','68','68','28']" 

                                 data-fontsize="['72','72','52','52']"
                                 data-fontweight="['400','400','400','400']"
                                 data-lineheight="['84','84','64','64']"
                                 data-width="['550','550','450','100%']"
                                 data-height="none"
                                 data-whitespace="normal"
                                 data-color="['#52586d','#52586d','#52586d','#52586d']"

                                 data-type="text" 
                                 data-responsive_offset="off" 

                                 data-frames='[{"delay":1200,"speed":500,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"power4.inOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"power3.inOut"}]'

                                 data-textAlign="['left','left','left','center']"
                                 data-paddingtop="['0','0','0','0']"
                                 data-paddingright="['0','0','0','0']"
                                 data-paddingbottom="['0','0','0','0']"
                                 data-paddingleft="['0','0','0','0']"
                                 data-marginleft="['0','0','0','0']"

                                 ><span class="slider_title">{{ $featured_details['title'] }}</span></div>
                            <div class="tp-caption ws_nowrap textLayer theBTNS" 
                                 data-x="['left','left','left','center']" 
                                 data-hoffset="['0','0','0','0']" 

                                 data-y="['middle','middle','middle','middle']" 
                                 data-voffset="['231','231','211','171']" 

                                 data-fontsize="['16','16','16','16']"
                                 data-fontweight="500"
                                 data-lineheight="['55','55','55','55']"
                                 data-width="['auto','auto','auto','100%']"
                                 data-height="auto"
                                 data-whitesapce="normal"
                                 data-color="#FFFFFF"

                                 data-type="text" 
                                 data-responsive_offset="off" 

                                 data-frames='[{"delay":1300,"speed":600,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"power4.inOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"power3.inOut"}]'

                                 data-textAlign="['center','center','center','center']"
                                 data-paddingtop="['0','0','0','0']"
                                 data-paddingright="['0','0','0','0']"
                                 data-paddingbottom="['0','0','0','0']"
                                 data-paddingleft="['0','0','0','0']"
                                 data-marginleft="['0','0','0','0']"

                                 >
                                 @if(isset($featured_details['button_name']) && isset($featured_details['button_url']))
                                    <a class="ulinaBTN ulinaSliderBTN" href="{{ $featured_details['button_url'] }}"><span>{{ $featured_details['button_name'] }}</span></a>
                                 @endif
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
        @endif
        <!-- END: Slider Section -->


        <!-- BEGIN: Collection section -->
        @foreach($collections as $collection)
            <section class="latestArrivalSection py-4">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 py-3">
                            <h2 class="secTitle">{{ $collection->name }}</h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="productCarousel owl-carousel">
                                @php
                                    $collection_products = \App\Models\CollectionProducts::where('shop_id', $shop->id)
                                    ->where('collection_id', $collection->id)
                                    ->limit(6)
                                    ->orderBy('id', 'DESC')
                                    ->get();
                                @endphp
                                    @foreach($collection_products as $c_p)
                                        @foreach($c_p->product as $product)
                                            <div class="productItem01">
                                                <div class="pi01Thumb">
                                                    <img src="{{ $product->product_single_image }}" alt="{{ $product->product_name }}"/>
                                                    <img src="{{ $product->product_single_image }}" alt="{{ $product->product_name }}"/>
                                                    <div class="pi01Actions">
                                                        <a href="javascript:void(0);" onclick="addToWishlist({{$product->shop_id}},{{$product->id}})" class="pi01Wishlist"><i class="fa-solid fa-heart"></i></a>
                                                    </div>
                                                    <div class="productLabels clearfix">
                                                        @if($product->base_price_discount)
                                                            <span class="plDis">-{{ $product->base_price_discount }}%</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="pi01Details">
                                                    <h3><a href="/product/{{ $product->product_url }}">{{ $product->product_name }}</a></h3>
                                                    <div class="pi01Price">
                                                        <ins>€{{ number_format($product->base_price - ($product->base_price * $product->base_price_discount / 100), 2) }}</ins>
                                                        @if($product->base_price_discount)
                                                            <del>€{{ $product->base_price }}</del>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endforeach
                                <div class="pi01Details" style="padding-left: 40%; padding-top: 50%;">
                                    <h3><a href="/collection/{{ $collection->slug }}">{{ trans('general.view_more') }}</a></h3>                                            
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endforeach
        <!-- END: Collection section -->


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
                                                                @if($product->base_price_discount)
                                                                    <del>€{{ $product->base_price }}</del>
                                                                @endif
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
                </div>
            </div>
        </section>
        <!-- END: Popular Products Section -->



        <div class="pagination py-3 d-flex justify-content-center">
            {{ $products->links('vendor.pagination.default') }}
        </div>



@include('shops.theme_2.includes.footer')

