@include('includes.home.header')
<div class="nk-banner nk-banner-collaboration pt-0 pt-xl-6 ">
               <div class="nk-banner-wrap has-mask bg-purple-200">
                  <div class="nk-mask shape-2" data-aos="fade-in" data-aos-delay="700"></div>
                  <div class="container">
                     <div class="row justify-content-center">
                        <div class="col-xl-9">
                           <div class="nk-banner-content text-center">
                              <h1 class="banner-title display-6 mb-2" data-aos="fade-up" data-aos-delay="100"> {{ trans('general.create') }} <span class="title-shape title-shape-2 text-primary">{{ trans('general.store') }}</span> {{ trans('general.home_header_title_create') }} </h1>
                              <p class="nk-block-text lead" data-aos="fade-up" data-aos-delay="200"> {{ trans('general.start_header_title_desc', [ 'store_name' => config('app.name') ]) }} </p>
                                @if(auth()->guest())
                                    <ul class="nk-btn-group justify-content-center pt-5 pt-lg-7" data-aos="fade-up" data-aos-delay="300">
                                        <li><a href="/register" class="btn btn-primary">{{ trans('general.register') }}</a></li>
                                        <li><a href="/login" class="btn btn-outline-primary">{{ trans('general.login') }}</a></li>
                                    </ul>
                                @else
                                    <ul class="nk-btn-group justify-content-center pt-5 pt-lg-7" data-aos="fade-up" data-aos-delay="300">
                                        <a href="{{ route('home') }}" class="btn btn-primary"><em class="icon ni ni-layout-fill"></em> {{ trans('general.my_shops') }}</a>
                                    </ul>
                                @endif
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         <main class="nk-pages">
            <section class="nk-brand-section section-space">
               <div class="container">
                  <div class="row justify-content-center">
                     <div class="col-xxl-8 col-xl-9 col-lg-10">
                        <div class="nk-block-head text-center sm">
                           <!-- <h6 class="h6 fw-normal text-light">{{ trans('general.under_header_text', [ 'store_name' => config('app.name') ]) }}</h6> -->
                        </div>
                        <!-- <div class="row justify-content-center justify-content-lg-between text-center g-gs">
                           <div class="col-4 col-sm-3 col-lg-2">
                              <div class="nk-brand" data-aos="fade-up" data-aos-delay=50 data-aos-offset="120"><img src="{{ asset('home_assets/images/brand/a.png') }}" alt="tool" class="w-auto h-24px"></div>
                           </div>
                           <div class="col-4 col-sm-3 col-lg-2">
                              <div class="nk-brand" data-aos="fade-up" data-aos-delay=100 data-aos-offset="120"><img src="{{ asset('home_assets/images/brand/b.png') }}" alt="tool" class="w-auto h-24px"></div>
                           </div>
                           <div class="col-4 col-sm-3 col-lg-2">
                              <div class="nk-brand" data-aos="fade-up" data-aos-delay=150 data-aos-offset="120"><img src="{{ asset('home_assets/images/brand/c.png') }}" alt="tool" class="w-auto h-24px"></div>
                           </div>
                           <div class="col-4 col-sm-3 col-lg-2">
                              <div class="nk-brand" data-aos="fade-up" data-aos-delay=200 data-aos-offset="120"><img src="{{ asset('home_assets/images/brand/d.png') }}" alt="tool" class="w-auto h-24px"></div>
                           </div>
                           <div class="col-4 col-sm-3 col-lg-2">
                              <div class="nk-brand" data-aos="fade-up" data-aos-delay=250 data-aos-offset="120"><img src="{{ asset('home_assets/images/brand/e.png') }}" alt="tool" class="w-auto h-24px"></div>
                           </div>
                        </div> -->
                     </div>
                  </div>
               </div>
            </section>
            <section class="nk-product-section section-space">
               <div class="container">
                  <div class="row align-items-center flex-row-reverse justify-content-between gy-6">
                     <div class="col-lg-6">
                        <div class="nk-feature-overview-img ps-lg-7" data-aos="fade-up" data-aos-delay="100"><img src="{{ asset('home_assets/images/inside-pages/collaboration-tool/cover/section-cover-1.png') }}" alt="cover-bg" class="base" style="border-radius: 20px;"></div>
                     </div>
                     <div class="col-lg-5">
                        <div class="nk-block-head m-0">
                           <div class="nk-block-head-content mb-md-6">
                              <div class="media media-lg media-middle text-bg-layout-primary mb-4 mb-md-6"><em class="icon ni ni-bar-chart-fill"></em></div>
                              <h2 class="mb-2 mb-md-1">{{ trans('general.sales_dashboard') }}</h2>
                              <p class="lead text-base">{{ trans('general.sales_dashboard_desc') }}</p>
                           </div>
                        </div>
                        <ul class="nk-timeline">
                           <li class="nk-timeline-item">
                              <div class="nk-timeline-item-inner">
                                 <div class="nk-timeline-symbol color-1"></div>
                                 <div class="nk-timeline-content">
                                    <p>{{ trans('general.sales_dashboard_1') }}</p>
                                 </div>
                              </div>
                           </li>
                           <li class="nk-timeline-item">
                              <div class="nk-timeline-item-inner">
                                 <div class="nk-timeline-symbol color-1"></div>
                                 <div class="nk-timeline-content">
                                    <p>{{ trans('general.sales_dashboard_2') }}</p>
                                 </div>
                              </div>
                           </li>
                           <li class="nk-timeline-item">
                              <div class="nk-timeline-item-inner">
                                 <div class="nk-timeline-symbol color-1"></div>
                                 <div class="nk-timeline-content">
                                    <p>{{ trans('general.sales_dashboard_3') }}</p>
                                 </div>
                              </div>
                           </li>
                        </ul>
                        <ul class="nk-btn-group pt-6 ps-2">
                           <li><a href="/register" class="btn btn-layout-primary">{{ trans('general.register') }}</a></li>
                           <li><a href="{{ route('help') }}" class="btn btn-outline-layout-primary">{{ trans('general.learn_more') }}</a></li>
                        </ul>
                     </div>
                  </div>
               </div>
            </section>
            <section class="nk-product-section section-space">
               <div class="container">
                  <div class="row align-items-center justify-content-between">
                     <div class="col-lg-6">
                        <div class="nk-feature-overview-img position-relative mb-4 mb-md-6 mb-lg-0" data-aos="fade-up" data-aos-delay="100"><img src="{{ asset('home_assets/images/inside-pages/collaboration-tool/cover/section-cover-2.png') }}" alt="cover-bg" class="base" style="border-radius: 20px;"></div>
                     </div>
                     <div class="col-lg-5">
                        <div class="ms-lg-n7">
                           <div class="nk-block-head m-0">
                              <div class="nk-block-head-content mb-md-6">
                                 <div class="media media-lg media-middle text-bg-danger mb-4 mb-md-6"><em class="icon ni ni-bag"></em></div>
                                 <h2 class="mb-2 mb-md-1">{{ trans('general.orders') }}</h2>
                                 <p class="lead text-base">{{ trans('general.orders_desc') }}</p>
                              </div>
                           </div>
                           <ul class="nk-timeline">
                              <li class="nk-timeline-item">
                                 <div class="nk-timeline-item-inner">
                                    <div class="nk-timeline-symbol color-2"></div>
                                    <div class="nk-timeline-content">
                                       <p>{{ trans('general.orders_desc_1') }}</p>
                                    </div>
                                 </div>
                              </li>
                              <li class="nk-timeline-item">
                                 <div class="nk-timeline-item-inner">
                                    <div class="nk-timeline-symbol color-2"></div>
                                    <div class="nk-timeline-content">
                                       <p>{{ trans('general.orders_desc_2') }}</p>
                                    </div>
                                 </div>
                              </li>
                              <li class="nk-timeline-item">
                                 <div class="nk-timeline-item-inner">
                                    <div class="nk-timeline-symbol color-2"></div>
                                    <div class="nk-timeline-content">
                                       <p>{{ trans('general.orders_desc_3') }}</p>
                                    </div>
                                 </div>
                              </li>
                           </ul>
                           <ul class="nk-btn-group pt-6 ps-2">
                              <li><a href="/register" class="btn btn-danger">{{ trans('general.register') }}</a></li>
                              <li><a href="{{ route('help') }}" class="btn btn-outline-danger">{{ trans('general.learn_more') }}</a></li>
                           </ul>
                        </div>
                     </div>
                  </div>
               </div>
            </section>
            <section class="nk-product-section section-space section-space-bottom">
               <div class="container">
                  <div class="row align-items-center flex-row-reverse justify-content-between gy-6">
                     <div class="col-lg-7">
                        <div class="nk-feature-overview-img ps-lg-7" data-aos="fade-up" data-aos-delay="100"><img src="{{ asset('home_assets/images/inside-pages/collaboration-tool/cover/section-cover-3.png') }}" alt="cover-bg" class="base" style="border-radius: 20px;"></div>
                     </div>
                     <div class="col-lg-5">
                        <div class="nk-block-head m-0">
                           <div class="nk-block-head-content mb-md-6">
                              <div class="media media-lg media-middle text-bg-primary mb-4 mb-md-6"><em class="icon ni ni-layer-fill"></em></div>
                              <h2 class="mb-2 mb-md-1">{{ trans('general.create_new_product') }}</h2>
                              <p class="lead text-base">{{ trans('general.new_product_desc') }}</p>
                           </div>
                        </div>
                        <ul class="nk-timeline">
                           <li class="nk-timeline-item">
                              <div class="nk-timeline-item-inner">
                                 <div class="nk-timeline-symbol"></div>
                                 <div class="nk-timeline-content">
                                    <p>{{ trans('general.new_product_1') }}</p>
                                 </div>
                              </div>
                           </li>
                           <li class="nk-timeline-item">
                              <div class="nk-timeline-item-inner">
                                 <div class="nk-timeline-symbol"></div>
                                 <div class="nk-timeline-content">
                                 <p>{{ trans('general.new_product_2') }}</p>
                                 </div>
                              </div>
                           </li>
                           <li class="nk-timeline-item">
                              <div class="nk-timeline-item-inner">
                                 <div class="nk-timeline-symbol"></div>
                                 <div class="nk-timeline-content">
                                 <p>{{ trans('general.new_product_3') }}</p>
                                 </div>
                              </div>
                           </li>
                        </ul>
                        <ul class="nk-btn-group pt-6 ps-2">
                            <li><a href="/register" class="btn btn-danger">{{ trans('general.register') }}</a></li>
                            <li><a href="{{ route('help') }}" class="btn btn-outline-danger">{{ trans('general.learn_more') }}</a></li>
                        </ul>
                     </div>
                  </div>
               </div>
            </section>
            <section class="nk-feature-section section-space-top section-space-bottom bg-lighter">
               <div class="container">
                  <div class="row justify-content-center">
                     <div class="col-lg-9 col-xl-7">
                        <div class="nk-block-head text-center">
                           <div class="nk-block-head-content">
                              <h2><span class="text-primary">{{ trans('general.features') }}</span> {{ trans('general.that_get_result') }}</h2>
                              <p class="nk-block-text lead">{{ trans('general.features_desc') }} </p>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="row gy-6 gy-lg-9 pt-5">
                    <div class="col-6 col-sm-6 col-md-4 col-lg-3">
                        <div class="nk-feature-block text-center">
                           <div class="nk-feature-block-content">
                              <div class="media media-xl media-middle text-bg-purple-300 mb-4"><em class="icon ni ni-heart-fill"></em></div>
                              <h4>{{ trans('general.responsive_design') }}</h4>
                           </div>
                        </div>
                     </div>
                     <div class="col-6 col-sm-6 col-md-4 col-lg-3">
                        <div class="nk-feature-block text-center">
                           <div class="nk-feature-block-content">
                              <div class="media media-xl media-middle text-bg-orange-300 mb-4"><em class="icon ni ni-layout-fill"></em></div>
                              <h4>{{ trans('general.different_themes') }}</h4>
                           </div>
                        </div>
                     </div>
                     <div class="col-6 col-sm-6 col-md-4 col-lg-3">
                        <div class="nk-feature-block text-center">
                           <div class="nk-feature-block-content">
                              <div class="media media-xl media-middle text-bg-green-300 mb-4"><em class="icon ni ni-star-fill"></em></div>
                              <h4>{{ trans('general.reviews') }}</h4>
                           </div>
                        </div>
                     </div>
                     <div class="col-6 col-sm-6 col-md-4 col-lg-3">
                        <div class="nk-feature-block text-center">
                           <div class="nk-feature-block-content">
                              <div class="media media-xl media-middle text-bg-yellow-300 mb-4"><em class="icon ni ni-bell-fill"></em></div>
                              <h4>{{ trans('general.notifications') }}</h4>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </section>
            <section class="nk-cta-section overlay-2 section-space-top section-space-bottom overflow-hidden bg-primary is-theme has-mask">
               <div class="nk-mask shape-5" data-aos="fade-in" data-aos-delay="300"></div>
               <div class="container">
                  <div class="nk-cta-wrap">
                     <div class="row justify-content-center">
                        <div class="col-xl-6">
                           <div class="nk-block-head-content m-0 text-center">
                              <h2 class="mb-5 mb-xl-7 ">{{ trans('general.what_are_you_waiting_for') }}</h2>
                              <ul class="nk-btn-group justify-content-center">
                                 <li><a href="/register" class="btn btn-outline-white">{{ trans('general.start_for_free') }}</a></li>
                              </ul>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </section>
            <section class="nk-newsletter-section section-space">
               <div class="container">
                  <hr class="m-0" data-aos="fade-in" data-aos-delay="400">
               </div>
            </section>
         </main>
@include('includes.home.footer')