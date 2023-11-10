        <!-- BEGIN: Footer Section -->
        <footer class="footer footerMode2">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <aside class="widget aboutWidget2">
                            <div class="footerLogo2">
                                <a href="/"><img src="{{ $shop->shop_image_url }}" alt="logo" style="height: 50px; width: auto; object-fit: contain;"></a>
                            </div>
                            @if(isset($social_switch) && $social_switch != 'off')
                                <div class="footerSocial">
                                    @foreach($social_networks as $social => $url)
                                        @if($social != 'phone' && $social != 'address')
                                            <a class="{{ $social }}" href="{{ $url }}"><i class="fa-brands fa-{{ $social }}"></i></a>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </aside>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <aside class="widget">
                        @if(isset($social_networks['address']) || isset($social_networks['phone']))
                            <h3 class="widgetTitle">Address</h3>
                            <div class="addressContent">
                                <div class="singleAddress">
                                    <i class="fa-solid fa-location-dot"></i>
                                    {{ $social_networks['address'] }}
                                </div>
                                @if(isset($social_networks['phone']))
                                <div class="singleAddress">
                                    <i class="fa-solid fa-phone"></i>
                                    {{ $social_networks['phone'] }}
                                </div>
                                @endif
                            </div>
                        @endif
                        </aside>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <aside class="widget twoColMenu">
                            <h3 class="widgetTitle">{{ trans('general.categories') }}</h3>
                            <ul>
                                <li><a href="/">{{ trans('general.home') }}</a></li>
                                @foreach ($categories as $cat)
                                    @if ($cat->depth === 0)
                                        <li>
                                            <a href="/category/{{ $cat->link }}">{{ $cat->label }}</a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </aside>
                    </div>
                </div>
                <div class="row footerAccessRow">
                    <img src="https://paymentsplugin.com/assets/blog-images/stripe-badge-transparent.png" style="width: 300px; height:auto;">
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="footerBar"></div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- END: Footer Section -->

        <!-- BEGIN: Site Info Section -->
        <section class="siteInfoSection sisMode2">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="siteInfo">
                        <p>{{ trans('general.copyright') }} © {{ date('Y') }} | <a href="{{ config('app.url') }}">{{ trans('general.build_with') }} <span>{{ config('app.name') }}</span></a>.</p>
                        </div>
                    </div>
                    <!-- <div class="col-md-6">
                        <div class="footerNav">
                            <ul>
                                <li><a href="javascript:void(0);">Terms & Condition</a></li>
                                <li><a href="javascript:void(0);">Privacy Policy</a></li>
                                <li><a href="javascript:void(0);">Legal</a></li>
                            </ul>
                        </div>
                    </div> -->
                </div>
            </div>
        </section>
        <!-- END: Site Info Section -->

        <!-- BEGIN: Back To Top -->
        <a href="javascript:void(0);" id="backtotop"><i class="fa-solid fa-angles-up"></i></a>
        <!-- END: Back To Top -->



<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 500px;">
    <div class="modal-content">
        <div class="modal-header text-white">
            <h5 class="modal-title" id="loginModalLabel">{{ trans('general.login') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <!-- Login Form -->
            <form id="loginForm">
            <div class="mb-3">
                <label for="loginEmail" class="form-label">{{ trans('general.email') }}</label>
                <input type="email" class="form-control" id="loginEmail" aria-describedby="emailHelp" required>
            </div>
            <div class="mb-3">
                <label for="loginPassword" class="form-label">{{ trans('general.password') }}</label>
                <input type="password" class="form-control" id="loginPassword" required>
            </div>
            <div class="mb-3">
                <label for="loginPassword" class="form-label loginErrorMessage d-none"></label>
            </div>
            <button type="submit" class="btn btn-primary">{{ trans('general.login') }}</button>
            </form>
            <hr>
            <p class="text-center">{{ trans('general.no_account_yet') }} <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#registerModal" data-bs-dismiss="modal">{{ trans('general.register') }}</a></p>
        </div>
        </div>
    </div>
    </div>
  </div>
</div>



<!-- Registration Modal -->
<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 500px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="registerModalLabel">{{ trans('general.register') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Registration Form -->
        <form id="registerForm">
          <div class="mb-3">
            <label for="registerName" class="form-label">{{ trans('general.name') }}</label>
            <input type="text" class="form-control" id="registerName" required>
          </div>
          <div class="mb-3">
            <label for="registerEmail" class="form-label">{{ trans('general.email') }}</label>
            <input type="email" class="form-control" id="registerEmail" aria-describedby="emailHelp" required>
          </div>
          <div class="mb-3">
            <label for="registerPassword" class="form-label">{{ trans('general.password') }}</label>
            <input type="password" class="form-control" id="registerPassword" required>
          </div>
          <div class="mb-3">
            <label for="confirmPassword" class="form-label">{{ trans('general.confirm_password') }}</label>
            <input type="password" class="form-control" id="confirmPassword" required>
          </div>
            <div class="mb-3">
                <label for="loginPassword" class="form-label registerErrorMessage d-none"></label>
            </div>
          <button type="submit" class="btn btn-primary">{{ trans('general.register') }}</button>
        </form>
        <hr>
            <p class="text-center">{{ trans('general.have_an_account') }} <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">{{ trans('general.login') }}</a></p>
      </div>
    </div>
  </div>
</div>



        <!-- BEGIN: JS -->
        <script src="{{ asset('theme_2/js/jquery.js') }}"></script>
        <script src="{{ asset('theme_2/js/jquery-ui.js') }}"></script>
        <script src="{{ asset('theme_2/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('theme_2/js/shuffle.min.js') }}"></script>
        <script src="{{ asset('theme_2/js/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('theme_2/js/owl.carousel.filter.js') }}"></script>
        <script src="{{ asset('theme_2/js/jquery.appear.js') }}"></script>
        <script src="{{ asset('theme_2/js/lightcase.js') }}"></script>
        <script src="{{ asset('theme_2/js/jquery.nice-select.js') }}"></script>
        <script src="{{ asset('theme_2/js/slick.js') }}"></script>
        <script src="{{ asset('theme_2/js/jquery.plugin.min.js') }}"></script>
        <script src="{{ asset('theme_2/js/jquery.countdown.min.js') }}"></script>
        <script src="{{ asset('theme_2/js/circle-progress.js') }}"></script>
        <script src="{{ asset('theme_2/js/gmaps.js') }}"></script>
        <script src="https://maps.google.com/maps/api/js?key=AIzaSyCA_EDGVQleQtHIp2fZ-V56QFRbRL8cXT8"></script>

        <script src="{{ asset('theme_2/js/jquery.themepunch.tools.min.js') }}"></script>
        <script src="{{ asset('theme_2/js/jquery.themepunch.revolution.min.js') }}"></script>

        <script src="{{ asset('theme_2/js/extensions/revolution.extension.actions.min.js') }}"></script>
        <script src="{{ asset('theme_2/js/extensions/revolution.extension.carousel.min.js') }}"></script>
        <script src="{{ asset('theme_2/js/extensions/revolution.extension.kenburn.min.js') }}"></script>
        <script src="{{ asset('theme_2/js/extensions/revolution.extension.layeranimation.min.js') }}"></script>
        <script src="{{ asset('theme_2/js/extensions/revolution.extension.migration.min.js') }}"></script>
        <script src="{{ asset('theme_2/js/extensions/revolution.extension.navigation.min.js') }}"></script>
        <script src="{{ asset('theme_2/js/extensions/revolution.extension.parallax.min.js') }}"></script>
        <script src="{{ asset('theme_2/js/extensions/revolution.extension.slideanims.min.js') }}"></script>
        <script src="{{ asset('theme_2/js/extensions/revolution.extension.video.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
        <script src="{{ asset('theme_2/js/theme.js') }}"></script>
        <!-- END: JS -->


<script>
function addToWishlist(shop_id, product_id) {
  var token = '{{ csrf_token() }}';
  
  // Create form data object
  var formData = new FormData();
  
  // Append shop_id and product_id to form data
  formData.append('shop_id', shop_id);
  formData.append('product_id', product_id);
  
  $.ajax({
    type: "POST",
    url: '/add/wishlist/',
    headers: {
      'X-CSRF-TOKEN': token
    },
    data: formData,
    processData: false,
    contentType: false,
    success: function(response) {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
        })

        //not authenticated
        if(response.authenticated == false){
            Toast.fire({
                icon: 'error',
                title: response.message
            }) 
            $('#loginModal').modal('show');
        }

        // Handle success response
        if(response.added){
            $('.wishlist-count').each(function() {
                var count = parseInt($(this).text());
                count += 1;
                $(this).text(count);
            });


            $('.icon-heart[product-id="' + product_id + '"]').css('color', 'red');
            Toast.fire({
                icon: 'success',
                title: response.message
            })
        }
        if(response.deleted){
            $('.wishlist-count').each(function() {
                var count = parseInt($(this).text());
                count -= 1;
                $(this).text(count);
            });
            $('.icon-heart[product-id="' + product_id + '"]').css('color', 'black');
            Toast.fire({
                icon: 'error',
                title: response.message
            })
        }
        if(facebook_pixel){
            fbq('track', 'AddToWishlist', {
                content_ids: product_id,
                content_type: 'product',
            });
        }
        if(tiktok_pixel){
            ttq.push('track', 'AddToWishlist', {
                content_ids: product_id,
                content_type: 'product',
            });
        }
    },
    error: function(xhr, status, error) {
        // Handle error response
        Toast.fire({
            icon: 'error',
            title: xhr.responseText
        })
    }
  });
}

</script>




<script>

//login  
$('#loginForm').submit(function(event) {
    event.preventDefault(); // prevent the default form submit action
    $('.loginErrorMessage').addClass('d-none');
    // Get the form data
    var formData = {
        'email': $('input[id=loginEmail]').val(),
        'password': $('input[id=loginPassword]').val(),
        'shop_id': "{{ $shop->id }}"
    };

    // Send the login POST request
    $.ajax({
        type: 'POST',
        url: '/customer/login',
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}" // add CSRF token to request header
        },
        data: formData,
        dataType: 'json',
        encode: true,
        success: function(response) {
            // Handle the response from the server here
            if(response.authenticated){
                $('.loginErrorMessage').removeClass('d-none').html(response.message).css('color', 'green');
                location.reload();
            }
            if(response.authenticated == false){
                //display the message
                $('.loginErrorMessage').removeClass('d-none').html(response.message).css('color', 'red');
            }
        }, 
        error: function(xhr, status, error) {
            // Handle any error that occurred during the request here
            $('.loginErrorMessage').removeClass('d-none').html(xhr.responseText).css('color', 'red');
        }
    });
});



//register
$('#registerForm').submit(function(event) {
    event.preventDefault(); // prevent the default form submit action
    $('.registerErrorMessage').addClass('d-none');
    // Get the form data
    var formData = {
        'name': $('input[id=registerName]').val(),
        'email': $('input[id=registerEmail]').val(),
        'password': $('input[id=registerPassword]').val(),
        'password_confirmation': $('input[id=confirmPassword]').val(),
        'shop_id': "{{ $shop->id }}"
    };

    // Send the login POST request
    $.ajax({
        type: 'POST',
        url: '/customer/register',
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}" // add CSRF token to request header
        },
        data: formData,
        dataType: 'json',
        encode: true,
        success: function(response) {
            // Handle the response from the server here
            if(response.authenticated){
                $('.registerErrorMessage').removeClass('d-none').html(response.message).css('color', 'green');
                location.reload();
            }
            if(!response.authenticated){
                //display the message
                $('.registerErrorMessage').removeClass('d-none').html(response.message).css('color', 'red');
            }
        }, 
        error: function(xhr, status, error) {
            // Handle any error that occurred during the request here
            var errors = xhr.responseJSON.errors;
            var errorMessages = "";
            $.each(errors, function(key, value) {
                errorMessages += value[0] + "\n";
            });
            $('.registerErrorMessage').removeClass('d-none').html(errorMessages).css('color', 'red');
        }
    });
});

</script>



    </body>
</html>