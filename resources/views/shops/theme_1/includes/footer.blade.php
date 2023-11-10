        
        @php
            $newsletter_active = false;
        @endphp

        @foreach($plugins as $plugin)
            @if($plugin->name == 'Newsletter' && $plugin->enabled == 'yes')
            @php
                $newsletter_active = true;
            @endphp
            <div class="subscribe-area bg-gray pt-115 pb-115">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-5 col-md-5">
                            <div class="section-title">
                                <h2>{{ trans('general.keep_connected') }}</h2>
                                <p>{{ trans('general.newsletter_desc') }}</p>
                            </div>
                        </div>
                        <div class="col-lg-7 col-md-7">
                            <div id="mc_embed_signup" class="subscribe-form">
                                <form id="mc-embedded-subscribe-form" class="validate subscribe-form-style" novalidate="" target="_blank" name="mc-embedded-subscribe-form" method="post" action="https://devitems.us11.list-manage.com/subscribe/post?u=6bbb9b6f5827bd842d9640c82&amp;id=05d85f18ef">
                                    <div id="mc_embed_signup_scroll" class="mc-form">
                                        <input class="email" type="email" required="" placeholder="Enter your email address" name="EMAIL" value="">
                                        <div class="mc-news" aria-hidden="true">
                                            <input type="text" value="" tabindex="-1" name="b_6bbb9b6f5827bd842d9640c82_05d85f18ef">
                                        </div>
                                        <div class="clear">
                                            <input id="mc-embedded-subscribe" class="button" type="submit" name="subscribe" value="{{ trans('general.subscribe') }}">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @endforeach
        
        <footer class="footer-area bg-gray pb-30 @if(!$newsletter_active) pt-115 @endif">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="contact-info-wrap">
                            <div class="footer-logo">
                                <a href="/"><img src="{{ $shop->shop_image_url }}" alt="logo" style="height: 50px; width: auto;"></a>
                            </div>
                            @if(isset($social_networks['address']))
                                <div class="single-contact-info">
                                    <span>{{ trans('general.our_location') }}</span>
                                    <p>{{ $social_networks['address'] }}</p>
                                </div>
                            @endif
                            @if(isset($social_networks['phone']))
                                <div class="single-contact-info">
                                    <span>{{ trans('general.phone') }} :</span>
                                    <p>{{ $social_networks['phone'] }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="footer-right-wrap">
                            <div class="footer-menu">
                                <nav>
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
                                </nav>
                            </div>
                            @if(isset($social_switch) && $social_switch != 'off')
                                <div class="social-style-2 social-style-2-mrg">
                                    @foreach($social_networks as $social => $url)
                                        @if($social != 'phone' && $social != 'address')
                                            @if($social == 'tiktok')
                                                <a class="{{ $social }}" href="{{ $url }}" target="_blank" style="margin-top: -2px;"><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512" style="margin-top: -5px;"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M448,209.91a210.06,210.06,0,0,1-122.77-39.25V349.38A162.55,162.55,0,1,1,185,188.31V278.2a74.62,74.62,0,1,0,52.23,71.18V0l88,0a121.18,121.18,0,0,0,1.86,22.17h0A122.18,122.18,0,0,0,381,102.39a121.43,121.43,0,0,0,67,20.14Z"/></svg></a>
                                            @else
                                                <a class="{{ $social }}" href="{{ $url }}" target="_blank"><i class="icon-social-{{ $social }}"></i></a>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                            
                            @if($shop->store_type == 'dropshipping')
                                <img src="https://paymentsplugin.com/assets/blog-images/stripe-badge-transparent.png" style="width: 300px; height:auto;">
                            @endif
                            <div class="copyright">
                                <p>{{ trans('general.copyright') }} © {{ date('Y') }} | <a href="{{ config('app.url') }}">{{ trans('general.build_with') }} <span>{{ config('app.name') }}</span></a>.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>


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



    </div>



<!-- All JS is here
============================================ -->

<script src="{{ asset('theme_1/assets/js/vendor/modernizr-3.11.7.min.js') }}"></script>
<script src="{{ asset('theme_1/assets/js/vendor/jquery-v3.6.0.min.js') }}"></script>
<script src="{{ asset('theme_1/assets/js/vendor/jquery-migrate-v3.3.2.min.js') }}"></script>
<script src="{{ asset('theme_1/assets/js/vendor/popper.min.js') }}"></script>
<script src="{{ asset('theme_1/assets/js/vendor/bootstrap.min.js') }}"></script>
<script src="{{ asset('theme_1/assets/js/plugins/slick.js') }}"></script>
<script src="{{ asset('theme_1/assets/js/plugins/jquery.syotimer.min.js') }}"></script>
<script src="{{ asset('theme_1/assets/js/plugins/jquery.nice-select.min.js') }}"></script>
<script src="{{ asset('theme_1/assets/js/plugins/wow.js') }}"></script>
<script src="{{ asset('theme_1/assets/js/plugins/jquery-ui.js') }}"></script>
<script src="{{ asset('theme_1/assets/js/plugins/magnific-popup.js') }}"></script>
<script src="{{ asset('theme_1/assets/js/plugins/sticky-sidebar.js') }}"></script>
<script src="{{ asset('theme_1/assets/js/plugins/easyzoom.js') }}"></script>
<script src="{{ asset('theme_1/assets/js/plugins/scrollup.js') }}"></script>
<script src="{{ asset('theme_1/assets/js/plugins/ajax-mail.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>

<script>
$(document).ready(function() {
  $('.dropdown-toggle').dropdown();
});
</script>
<!-- Main JS -->
<script src="{{ asset('theme_1/assets/js/main.js') }}"></script>



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