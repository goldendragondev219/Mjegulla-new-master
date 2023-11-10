<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>{{ trans('general.login') }} | {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body class="bg-gray-50 dark:bg-gray-900">
  <br>
    <br>
    <br>
    <br>
  <section class="bg-gray-50 dark:bg-gray-900">
  <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
      <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
          <img class="w-20 mr-2" src="{{ asset('home_assets/logo.png') }}" alt="logo">
          {{ config('app.name') }}
      </a>
      <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
          <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
              <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                  {{ trans('general.login') }}
              </h1>
              @if(session('success'))
                    <span class="block text-green-600 text-sm font-medium mt-2">{{ session('success') }}</span>
              @endif
              <form class="space-y-4 md:space-y-6" method="POST" action="{{ route('login') }}">
                @csrf
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ trans('general.email') }}</label>
                        <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="{{ trans('general.email') }}" required="">
                            @error('email')
                                <span class="block text-red-600 text-sm font-medium mt-2">{{ $message }}</span>
                            @enderror
                    </div>
                  <div>
                      <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ trans('general.password') }}</label>
                      <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="">
                  </div>
                    @error('password')
                        <span class="block text-red-600 text-sm font-medium mt-2">{{ $message }}</span>
                     @enderror
                  <div class="flex items-center justify-between">
                      <a href="{{ route('password.request') }}" class="text-sm font-medium text-primary-600 hover:underline dark:text-primary-500">{{ trans('general.forgot_password') }}</a>
                  </div>
                  <button type="submit" class="w-full text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-700 dark:hover:bg-blue-800 dark:focus:ring-blue-800">{{ trans('general.login') }}</button>        
                    <div class="flex items-center justify-center">
                        <div class="h-px bg-gray-400 w-full mr-4"></div>
                        <span class="text-gray-400 font-medium">{{ trans('general.or') }}</span>
                        <div class="h-px bg-gray-400 w-full ml-4"></div>
                    </div>

          
                  <button onclick="window.location='{{ url('login/google') }}'" id="login_google" class="w-full text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-700 dark:hover:bg-blue-800 dark:focus:ring-blue-800">{{ trans('general.login_with_google') }}</button>
                  <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                      {{ trans('general.no_account_yet') }} <a href="{{ route('register') }}" class="font-medium text-primary-600 hover:underline dark:text-primary-500">{{ trans('general.register') }}</a>
                  </p>
              </form>
              
          </div>
      </div>
  </div>
</section>
  </body>
  <br>
    <br>
    <br>
    <br>
</html>

<script>
  // Wait for the DOM to be ready
  document.addEventListener("DOMContentLoaded", function() {
    // Find the element with the ID "login_google"
    var loginGoogleLink = document.querySelector("#login_google");

    // Add a click event listener to the link
    loginGoogleLink.addEventListener("click", function(event) {
      // Prevent the default action (e.g., following the link)
      event.preventDefault();
      
      // Redirect to the specified URL
      window.location.href = "{{ url('login/google') }}"; // Use the URL you provided
    });
  });
</script>
