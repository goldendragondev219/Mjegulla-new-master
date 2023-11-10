<footer class="nk-footer">
            <section class="nk-footer-section">
               <div class="nk-footer-top">
                  <div class="container">
                     <div class="row g-gs justify-content-between">
                        <div class="col-sm-9 col-md-6 col-lg-4 col-xxl-4">
                           <div class="nk-footer-item me-xxl-5">
                              <div class="nk-header-logo mb-3">
                                 <a href="/" class="logo-link">
                                    <div class="logo-wrap" style="line-height: 3em;"><img class="logo-img logo-dark" src="{{ asset('home_assets/logo.png') }}" srcset="{{ asset('home_assets/logo.png') }} 2x" alt="brand-logo" style="height: 50px;"></div>
                                </a>
                              </div>
                              <p class="mb-4">{{ trans('general.slogan', [ 'site_name' => config('app.name') ]) }}</p>
                              <ul class="social-links">
                                 <li><a href="https://instagram.com/mjegulla_com" target="_blank"><em class="icon ni ni-instagram"></em></a></li>
                              </ul>
                           </div>
                        </div>
                        <div class="col-lg-8 col-xl-7 col-xxl-6">
                           <div class="row justify-content-between g-gs">
                              <div class="col-lg-7 col-sm-8">
                                 <div class="nk-footer-item">
                                    <h5 class="title mb-3">{{ trans('general.pages') }}</h5>
                                    <ul class="nk-list-link column-2">
                                       <li><a href="/">{{ trans('general.home') }}</a></li>
                                       <li><a href="/login">{{ trans('general.login') }}</a></li>
                                       <li><a href="/register">{{ trans('general.register') }}</a></li>
                                       <li><a href="/help">{{ trans('general.help') }}</a></li>
                                    </ul>
                                 </div>
                              </div>
                              <div class="col-lg-4 col-sm-4">

                              </div>
                           </div>
                        </div>
                     </div>
                     <hr class="mt-6 mb-0">
                  </div>
               </div>
               <div class="nk-footer-bottom py-4">
                  <div class="container">
                     <div class="row text-center text-lg-start g-1">
                        <div class="col-lg-6">
                           <div class="nk-copyright-text">
                              <p>&copy; {{ date('Y') }} <a href="{{ env('APP_URL') }}" target="_blank">{{ config('app.name') }}</a>. {{ trans('general.all_rights_reserved') }}</p>
                           </div>
                        </div>
                        <div class="col-lg-6">
                           <ul class="nk-list-link horizontal justify-content-center justify-content-lg-end">
                              <li><a href="/p/terms">{{ trans('general.terms') }}</a></li>
                           </ul>
                        </div>
                     </div>
                  </div>
               </div>
            </section>
         </footer>
         <a href="/" class="scroll-top shadow animate animate-infinite animate-pulse animate-duration-2"><em class="icon ni ni-chevrons-up"></em></a>
      </div>
      <script src="{{ asset('home_assets/assets/js/bundle.js%3Fv1.0.0') }}"></script>
      <script src="{{ asset('home_assets/assets/js/scripts.js%3Fv1.0.0') }}"></script>


      <!-- PWA -->

      <script src="{{ asset('/sw.js') }}"></script>
      <script>
         if ("serviceWorker" in navigator) {
            // Register a service worker hosted at the root of the
            // site using the default scope.
            navigator.serviceWorker.register("/sw.js").then(
            (registration) => {
               console.log("Service worker registration succeeded:", registration);
            },
            (error) => {
               console.error(`Service worker registration failed: ${error}`);
            },
         );
         } else {
            console.error("Service workers are not supported.");
         }
      </script>

      
   </body>
</html>