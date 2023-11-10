<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('home_assets/logo.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('home_assets/logo.png') }}" type="image/x-icon">
    <!-- PWA  -->
    <link rel="apple-touch-icon" href="{{ asset('home_assets/pwa_logo.png') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.3/sweetalert2.min.css" integrity="sha512-NvuRGlPf6cHpxQqBGnPe7fPoACpyrjhlSNeXVUY7BZAj1nNhuNpRBq3osC4yr2vswUEuHq2HtCsY2vfLNCndYA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('css/sb-admin-2.min.css') }}">
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-9FH4XS2DLL"></script>
      <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-9FH4XS2DLL');
    </script>

<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/653ec02af2439e1631e9b076/1hduhtabf';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->


<!-- LOADER STYLE -->
<style>

    .loader {
        display: flex;
        flex-direction: column; /* Display elements in a column layout */
        justify-content: center;
        align-items: center;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #fff;
        z-index: 1000;
        transition: opacity 0.5s;
    }

    .loader.hidden {
        opacity: 0;
        visibility: hidden;
        transition: 0s;
    }

    #loading-logo {
        animation: flip 2s infinite;
        transform-origin: center;
        height: 200px;
        width: auto;
    }

    .loader-text {
        margin-top: 10px; /* Adjust the spacing as needed */
        font-size: 24px; /* Adjust the font size as needed */
    }

    @keyframes flip {
        0% {
            transform: scaleX(1);
        }
        50% {
            transform: scaleX(-1);
        }
        100% {
            transform: scaleX(1);
        }
    }

    .content {
        display: none;
    }

</style>


</head>

<div class="loader">
        <img id="loading-logo" src="https://mjegulla.com/home_assets/logo.png" alt="Loading Logo">
        <p class="loader-text">{{ config('app.name') }}</p>
    </div>
<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        @include('includes.shop_admin_sidebar')

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <!-- <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form> -->

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Language -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @if(auth()->user()->default_language == 'en')
                                    <img src="https://flagicons.lipis.dev/flags/4x3/us.svg" style="width: 20px;">
                                @else
                                    <img src="https://flagicons.lipis.dev/flags/4x3/al.svg" style="width: 20px;">
                                @endif
                            </a>
                            <!-- Dropdown - Language -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    {{ trans('general.select_language') }}
                                </h6>
                                <div class="langs" style="padding: 25px;">
                                    <a href="/lang/sq"><img src="https://flagicons.lipis.dev/flags/4x3/al.svg" style="width: 50px;"></a>
                                    <a href="/lang/en"><img src="https://flagicons.lipis.dev/flags/4x3/us.svg" style="width: 50px;"></a>
                                </div>
                            </div>
                        </li>


                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1 Notifications_check">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">{{ auth()->user()->notificationsCount() }}</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    {{ trans('general.notifications') }}
                                </h6>
                                @include('includes.notifications')
                            </div>
                        </li>

                        <!-- Nav Item - Messages -->
                        <!-- <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>

                                <span class="badge badge-danger badge-counter">7</span>
                            </a>

                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Message Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="{{ asset('img/undraw_profile_1.svg') }}"
                                            alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div class="font-weight-bold">
                                        <div class="text-truncate">Hi there! I am wondering if you can help me with a
                                            problem I've been having.</div>
                                        <div class="small text-gray-500">Emily Fowler · 58m</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="{{ asset('img/undraw_profile_2.svg') }}"
                                            alt="...">
                                        <div class="status-indicator"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">I have the photos that you ordered last month, how
                                            would you like them sent to you?</div>
                                        <div class="small text-gray-500">Jae Chun · 1d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="{{ asset('img/undraw_profile_3.svg') }}"
                                            alt="...">
                                        <div class="status-indicator bg-warning"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Last month's report looks great, I am very happy with
                                            the progress so far, keep up the good work!</div>
                                        <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60"
                                            alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Am I a good boy? The reason I ask is because someone
                                            told me that people say this to all dogs, even if they aren't good...</div>
                                        <div class="small text-gray-500">Chicken the Dog · 2w</div>
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
                            </div>
                        </li> -->

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                                <img class="img-profile rounded-circle"
                                    src="{{ asset('img/undraw_profile.svg') }}">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ route('home') }}">
                                    <i class="fas fa-home fa-sm fa-fw mr-2 text-gray-400"></i>
                                    {{ trans('general.home') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('edit') }}">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    {{ trans('general.account') }} {{ trans('general.settings') }}
                                </a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>{{ trans('general.logout') }}
                                    </button>
                                </form>

                            </div>
                        </li>
                        @endguest
                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid" style="margin-left:0; margin-right:0;">
                    <!-- Add unpaid orders message -->
                    @if (!isset($shop))
                        @php
                            if (auth()->user()->managing_shop) {
                                $storeType = \App\Models\Shop::find(auth()->user()->managing_shop)->value('store_type');
                            } else {
                                $storeType = false;
                            }
                        @endphp
                    @else
                        @php
                            $storeType = $shop->store_type;
                        @endphp
                    @endif

                    @if (auth()->check() && auth()->user()->unshippedOrdersCount() && auth()->user()->managing_shop && auth()->user()->isDropshipping())
                        <div class="alert alert-danger">
                            {{ trans('emails.order_complete_no_balance') }} <a href="{{ route('cj_dropshipping_edit', auth()->user()->managing_shop) }}">{{ trans('general.cj_wallet_top_up') }}</a>
                        </div>
                    @endif


                    @if(auth()->check())
                        @php
                            $is_managing_shop_deactive = \App\Models\Shop::where('id', auth()->user()->managing_shop)->where('active', 'no')->first();
                        @endphp
                        @if($is_managing_shop_deactive)
                            <div class="alert alert-danger">
                                {{ trans('emails.store_deactivated_mail_desc', ['store_name' => $is_managing_shop_deactive->shop_name]) }}
                            </div>
                        @endif
                    @endif

                    @yield('content')
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; {{ config('app.name') }} {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>



<!-- Bootstrap core JavaScript-->
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>


<!-- Core plugin JavaScript-->
<script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

<!-- Custom scripts for all pages-->
<script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

<!-- Page level plugins -->
<script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>

<!-- Page level custom scripts -->
<script src="{{ asset('js/demo/chart-area-demo.js') }}"></script>
<script src="{{ asset('js/demo/chart-pie-demo.js') }}"></script>
<script type="application/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>

{!! \App\Facades\CustomMenu::scripts() !!}

<script>

$('.Notifications_check').on('click', function(){
    // Get the CSRF token from your page
    var csrf_token = $('meta[name="csrf-token"]').attr('content');

    // Make the POST request with the CSRF token in the header
    $.ajax({
        type: "POST",
        url: "{{ route('notifications_mark_seen') }}",
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-CSRF-Token', csrf_token);
        },
        success: function(response) {
        },
        error: function(xhr) {
        }
    });

});

</script>


<script>

$('#cj_search_btn').on('click', function () {
    var searchValue = $('#cj_search').val();
    var currentUrl = window.location.href;

    var skuElement = document.querySelector('#sku');
    var sku = 'false';

    // Check if the SKU element is checked
    if (skuElement.checked) {
        sku = 'true';
    }

    // Check if there are existing query parameters in the URL
    var hasQueryParams = currentUrl.indexOf('?') !== -1;

    // Build the query string for the cj_search and sku parameters
    var cjSearchParam = hasQueryParams ? '&cj_search=' + searchValue : '&cj_search=' + searchValue;
    var skuParam = hasQueryParams ? '&sku=' + sku : '?sku=' + sku;

    if (hasQueryParams) {
        // Check if cj_search already exists in the URL
        if (currentUrl.includes('cj_search=')) {
            currentUrl = currentUrl.replace(/cj_search=[^&]*/g, 'cj_search=' + searchValue);
        } else {
            // Remove the 'page' query parameter if 'cj_search' is set
            if (currentUrl.includes('cj_search=')) {
                currentUrl = currentUrl.replace(/([?&])page=[^&]*/g, '$1');
            }

            // Remove any trailing '&' character
            if (currentUrl.endsWith('&')) {
                currentUrl = currentUrl.slice(0, -1);
            }
        }

        // Check if sku parameter exists in the URL
        if (currentUrl.includes('sku=')) {
            currentUrl = currentUrl.replace(/sku=[^&]*/g, 'sku=' + sku);
        } else {
            currentUrl += skuParam;
        }

        currentUrl += cjSearchParam;
    } else {
        currentUrl += '' + skuParam + cjSearchParam;
    }

    // Redirect the browser to the updated URL
    window.location.href = currentUrl;
});



</script>



@if(auth()->check() && auth()->user()->admin == 'yes')
<script>
// ADMIN USERS SEARCH
$('#search_user_btn').on('click', function () {
    var searchValue = $('#search').val();
    var currentUrl = window.location.href;

    // Check if there are existing query parameters in the URL
    var hasQueryParams = currentUrl.indexOf('?') !== -1;

    // Build the query string for the cj_search parameter
    var cjSearchParam = hasQueryParams ? '&search=' + searchValue : '?search=' + searchValue;

    // Check if other query parameters exist and update the URL accordingly
    if (hasQueryParams) {
        // Check if cj_search already exists in the URL
        if (currentUrl.includes('search=')) {
            currentUrl = currentUrl.replace(/search=[^&]*/g, 'search=' + searchValue);
        } else {
            // Remove the 'page' query parameter if 'cj_search' is set
            if (currentUrl.includes('search=')) {
                currentUrl = currentUrl.replace(/([?&])page=[^&]*/g, '$1');
            }

            // Remove any trailing '&' character
            if (currentUrl.endsWith('&')) {
                currentUrl = currentUrl.slice(0, -1);
            }

            currentUrl += '&search=' + searchValue;
        }
    } else {
        currentUrl += cjSearchParam;
    }

    // Redirect the browser to the updated URL
    window.location.href = currentUrl;
});
</script>
@endif


@if(auth()->check() && auth()->user()->admin == 'yes')
<script>
// ADMIN STORES SEARCH
$('#search_store_btn').on('click', function () {
    var searchValue = $('#search').val();
    var currentUrl = window.location.href;

    // Check if there are existing query parameters in the URL
    var hasQueryParams = currentUrl.indexOf('?') !== -1;

    // Build the query string for the cj_search parameter
    var cjSearchParam = hasQueryParams ? '&search=' + searchValue : '?search=' + searchValue;

    // Check if other query parameters exist and update the URL accordingly
    if (hasQueryParams) {
        // Check if cj_search already exists in the URL
        if (currentUrl.includes('search=')) {
            currentUrl = currentUrl.replace(/search=[^&]*/g, 'search=' + searchValue);
        } else {
            // Remove the 'page' query parameter if 'cj_search' is set
            if (currentUrl.includes('search=')) {
                currentUrl = currentUrl.replace(/([?&])page=[^&]*/g, '$1');
            }

            // Remove any trailing '&' character
            if (currentUrl.endsWith('&')) {
                currentUrl = currentUrl.slice(0, -1);
            }

            currentUrl += '&search=' + searchValue;
        }
    } else {
        currentUrl += cjSearchParam;
    }

    // Redirect the browser to the updated URL
    window.location.href = currentUrl;
});
</script>
@endif


@if(auth()->check())
<script>
// SHIPPING COUNTRIES SEARCH
$('#search_country_btn').on('click', function () {
    var searchValue = $('#search').val();
    var currentUrl = window.location.href;

    // Check if there are existing query parameters in the URL
    var hasQueryParams = currentUrl.indexOf('?') !== -1;

    // Build the query string for the cj_search parameter
    var cjSearchParam = hasQueryParams ? '&search=' + searchValue : '?search=' + searchValue;

    // Check if other query parameters exist and update the URL accordingly
    if (hasQueryParams) {
        // Check if cj_search already exists in the URL
        if (currentUrl.includes('search=')) {
            currentUrl = currentUrl.replace(/search=[^&]*/g, 'search=' + searchValue);
        } else {
            // Remove the 'page' query parameter if 'cj_search' is set
            if (currentUrl.includes('search=')) {
                currentUrl = currentUrl.replace(/([?&])page=[^&]*/g, '$1');
            }

            // Remove any trailing '&' character
            if (currentUrl.endsWith('&')) {
                currentUrl = currentUrl.slice(0, -1);
            }

            currentUrl += '&search=' + searchValue;
        }
    } else {
        currentUrl += cjSearchParam;
    }

    // Redirect the browser to the updated URL
    window.location.href = currentUrl;
});
</script>
@endif


<script>
function view_product(pid) {
    // Get the CSRF token from your page
    var csrf_token = $('meta[name="csrf-token"]').attr('content');

    // Make the POST request with the CSRF token in the header
    $.ajax({
        type: "POST",
        url: "{{ route('cj_dropshipping_view_product', ':pid') }}".replace(':pid', pid),
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-CSRF-Token', csrf_token);
        },
        success: function(response) {
            $('.view_product_html').html(response);
            $('#productModal').modal('show');
        },
        error: function(xhr) {
            // Handle the error here
            // You can display an error message or take other actions
        }
    });
}
</script>


<script>

function import_cj_product(pid){
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        type: "POST",
        url: "{{ route('cj_dropshipping_import_product', ':pid') }}".replace(':pid', pid),
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-Token', csrf_token);
            $('#import_cj_product').prop('disabled', true);
        },
        success: function (response) {
            if(!response.status){
                Swal.fire({
                    toast: true,
                    icon: 'error',
                    text: response.message,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            }else{
                $('#import_cj_product').prop('disabled', false);
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    text: response.message,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                $('#productModal').modal('hide');
            }
        },
        error: function (xhr) {
            $('#import_cj_product').prop('disabled', false);
            var there_was_an_err = "{{ trans('general.there_was_an_error') }}";
            Swal.fire({
                toast: true,
                icon: 'error',
                text: there_was_an_err,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        }
    });
}




</script>




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



<script>
    window.addEventListener("load", function () {
        // Simulate a human-friendly delay before hiding the loader and displaying the content
        setTimeout(function () {
            document.querySelector(".loader").style.display = "none";
            document.querySelector(".content").style.display = "block";
        }, 500); // Adjust the delay time in milliseconds (2 seconds in this example)
    });
</script>


</body>
</html>
