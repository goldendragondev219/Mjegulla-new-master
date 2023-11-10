<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>{{ trans('general.change_password') }} | {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>
  <section class="bg-gray-50 dark:bg-gray-900">
  <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
      <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
        <img class="w-20 mr-2" src="{{ asset('home_assets/logo.png') }}" alt="logo">
          {{ config('app.name') }}
      </a>
      <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
          <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
              <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                  {{ trans('general.change_password') }}
              </h1>
              <form class="space-y-4 md:space-y-6" method="POST" action="{{ route('password.update') }}">
                @csrf
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ trans('general.email') }}</label>
                        <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com" value="{{ $email ?? old('email') }}" required="">
                            @error('email')
                                <span class="block text-red-600 text-sm font-medium mt-2">{{ $message }}</span>
                            @enderror
                    </div>

                    <div>
                      <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ trans('general.password') }}</label>
                      <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="">
                  </div>
                  <div>
                      <label for="confirm-password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ trans('general.confirm_password') }}</label>
                      <input type="password" name="password_confirmation" id="password_confirmation" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="">
                  </div>
                    @error('password')
                        <span class="block text-red-600 text-sm font-medium mt-2">{{ $message }}</span>
                     @enderror
                  <button type="submit" class="res_pass w-full text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-700 dark:hover:bg-blue-800 dark:focus:ring-blue-800">{{ trans('general.update') }}</button>
                  <div class="loader hidden animate-spin w-6 h-6 border-t-4 border-blue-500 border-solid rounded-full mx-auto my-4"></div>
                  <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                      {{ trans('general.have_an_account') }} <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:underline dark:text-primary-500">{{ trans('general.login') }}</a>
                  </p>
              </form>
          </div>
      </div>
  </div>
</section>
  </body>
</html>


<script>
const regBtn = document.querySelector('.res_pass');
const loader = document.querySelector('.loader');
const form = document.querySelector('form'); // Change 'form' to the actual selector of your form

regBtn.addEventListener('click', function() {
  regBtn.disabled = true; // Disable the button
  regBtn.style.display = 'none';
  loader.style.display = 'block'; // Show the loader

  // Replace the setTimeout code with this line to trigger the form submit
  form.submit();

  // You can still keep the setTimeout if you want a delay before re-enabling the button and hiding the loader
  setTimeout(function() {
    regBtn.disabled = false; // Re-enable the button
    regBtn.style.display = 'block';
    loader.style.display = 'none'; // Hide the loader
  }, 3000);
});
</script>