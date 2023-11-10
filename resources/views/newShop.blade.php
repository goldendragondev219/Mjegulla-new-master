@extends('layouts.app')
<title>{{trans('general.create_new_shop')}} | {{ config('app.name') }} </title>
@section('content')
@include('layouts.styles')

<style>
	
.selected-license{
	border-radius: 10px;
    padding: 10px;
    background-color: #4e73df;
    border-style: solid;
    border-width: thin;
    border-color: #4e73df;
    cursor: pointer;
}

.selected-license p {
    color: white;
}

.unselected-license{
	border-style: solid;
    border-radius: 10px;
    border-width: thin;
    padding: 10px;
    cursor: pointer;
}


</style>


@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </ul>
    </div>
@endif

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">{{ trans('general.create_new_shop') }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('create_shop') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row mb-4">
                            <label for="image" class="upload-box1">
                                <span class="upload-text1">{{ trans('general.change_shop_logo') }}</span>
                            </label>
                            <input type="file" id="image" class="custom-file-input edit_product_images" name="image" aria-describedby="product-images-preview" accept="image/*">
                            <div class="preview-container-edit-product" id="product-images-preview-edit-product">
                            </div>
                        </div>

                        <div class="change_store_type_div py-3">
                            <p class="change_store_type_title" style="font-size: 20px; text-align: left;">{{ trans('general.choose_type_of_store') }}</p>
                            <div class="row" style="text-align: left;">
                                <div class="col-md-6 col-12 leasing_row" style="padding-right: 10px; padding-bottom: 10px">
                                <!-- Leasing -->
                                <div class="dropshipping selected-license">
                                    <p id="dropshipping_title" style="font-size: 20px;">{{ trans('general.dropshipping') }}</p>
                                    <p id="dropshipping_desc" style="font-size: 12px;">{{ trans('general.dropshipping_info') }}</p>
                                </div>
                                </div>
                                <div class="col-md-6 col-12 unlimited_row" style="padding-right: 10px;">
                                <!-- Unlimited -->
                                <div class="local_store unselected-license">
                                    <p id="local_store_title" style="font-size: 20px;">{{ trans('general.local_store') }}</p>
                                    <p id="local_store_desc" style="font-size: 12px;">{{ trans('general.local_store_info') }}</p>
                                </div>
                                </div>
                                <div class="col-md-4 col-6 d-none" style="padding-right: 10px;">
                                <!-- Exclusive -->
                                <div class="custom_branding unselected-license exclusive_row" style="border-style: solid; border-radius: 10px; border-width: thin; padding: 10px; cursor: pointer;">
                                    <p id="custom_branding_title" style="font-size: 20px;">{{ trans('general.custom_branding') }}</p>
                                    <p id="custom_branding_desc" style="font-size: 12px;">{{ trans('general.custom_branding_info') }}</p>
                                </div>
                                </div>
                            </div>
                        </div>

                        <input id="store_type" type="text" class="store_type form-control d-none" name="store_type" value="dropshipping" required>

                        <span class="fs-10">{{ trans('general.shop_owner_information') }}</span>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="full_name" type="name" class="form-control @error('full_name') is-invalid @enderror" name="full_name" value="{{ old('full_name') }}" required autocomplete="full_name" placeholder="{{ trans('general.full_name') }}" autofocus>
                            </div>
                        </div>

                        <!-- <div class="form-group row">
                            <div class="col-md-12">
                                <input id="company" type="name" class="form-control @error('company') is-invalid @enderror" name="company" value="{{ old('company') }}" autocomplete="company" placeholder="{{ trans('general.company_name') }}" autofocus>
                            </div>
                        </div> -->

                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="city" type="name" class="form-control @error('city') is-invalid @enderror" name="city" value="{{ old('city') }}" autocomplete="city" placeholder="{{ trans('general.city') }}" autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="address" type="name" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" autocomplete="address" placeholder="{{ trans('general.address') }}" autofocus>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="phone" type="phone" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" autocomplete="phone" placeholder="{{ trans('general.phone') }}" autofocus>
                            </div>
                        </div>


                        <br>
                        <span class="fs-10">{{ trans('general.shop_information') }}</span>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="shop_name" type="name" class="form-control @error('shop_name') is-invalid @enderror" name="shop_name" value="{{ old('shop_name') }}" required autocomplete="shop_name" placeholder="{{ trans('general.shop_name') }}" autofocus>
                            </div>
                        </div>
                        <span class="fs-10">{{ trans('general.shop_add_domain_info') }}</span>
                        <div class="input-group mb-3">
                        <input type="text" class="form-control" id="my_shop_url" name="my_shop_url" placeholder="{{ trans('general.shop_url') }}" aria-describedby="basic-addon3" value="{{ old('my_shop_url') }}">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon3">.{{ str_replace(['http://', 'https://', 'www.', ':8000'], '', url('/')) }}</span>
                        </div>
                        </div>
                        
                        
                        <div class="form-group row">
                            <div class="col-md-12">
                                <textarea id="shop_description" type="name" class="form-control @error('shop_description') is-invalid @enderror" name="shop_description" value="" required autocomplete="shop_description" placeholder="{{ trans('general.shop_description') }}" autofocus>{{ old('shop_description') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="shop_seo_keywords" type="name" class="form-control @error('shop_seo_keywords') is-invalid @enderror" name="shop_seo_keywords" value="{{ old('shop_seo_keywords') }}" required autocomplete="shop_seo_keywords" placeholder="{{ trans('general.shop_keywords') }}" autofocus>
                            </div>
                        </div>

                        <br>
                        <span>{{ trans('general.choose_package_head_info') }}</span>
                        <div class="row">
                        <div class="col-md-6 col-xs-12 col-l-4 col-xl-4">
                            <div class="card shadow mb-4 shadow-sm">
                                <div class="card-header bg-primary text-white">
                                <h4 class="my-0 font-weight-normal">{{ trans('general.free') }}</h4>
                                </div>
                                <div class="card-body">
                                <h1 class="card-title pricing-card-title">{{ trans('general.free') }}</h1>
                                <ul class="list-unstyled mt-3 mb-4">
                                    <li><i class="fas fa-store"></i> 1 {{ trans('general.sub_shop') }}</li>
                                    <li><i class="fas fa-shopping-bag"></i> 2 {{ trans('general.sub_products') }}</li>
                                    <li><i class="fas fa-chart-line"></i> 10 {{ trans('general.sub_sales') }} / {{ trans('general.month') }}</li>
                                    <li class="cash_on_delivery"><i class="fas fa-credit-card"></i> {{ trans('general.credit_cards') }}</li>
                                    <li class="trans_percentage"><i class="fas fa-percentage"></i> 10% + 0.50cent {{ trans('general.per_transaction_fee') }}</li>
                                    <li><i class="fas fa-headset"></i> 24/7 {{ trans('general.support') }}</li>
                                </ul>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="subscription" id="freeSubscription" value="free">
                                    <label class="form-check-label" for="freeSubscription">
                                        {{ trans('general.select_free_plan') }}
                                    </label>
                                </div>
                                </div>
                            </div>
                            </div>
                            <div class="col-md-6 col-xs-12 col-l-4 col-xl-4">
                            <div class="card shadow mb-4 shadow-sm">
                                <div class="card-header bg-primary text-white">
                                <h4 class="my-0 font-weight-normal">{{ trans('general.basic') }}</h4>
                                </div>
                                <div class="card-body">
                                <h1 class="card-title pricing-card-title">€19.99<small class="text-muted">/ {{ trans('general.month') }}</small></h1>
                                <ul class="list-unstyled mt-3 mb-4">
                                    <li><i class="fas fa-store"></i> 1 {{ trans('general.sub_shop') }}</li>
                                    <li><i class="fas fa-shopping-bag"></i> 50 {{ trans('general.sub_products') }}</li>
                                    <li><i class="fas fa-chart-line"></i> 1000 {{ trans('general.sub_sales') }} / {{ trans('general.month') }}</li>
                                    <li><i class="fas fa-globe"></i> {{ trans('general.custom_domain') }}</li>
                                    <li><i class="fas fa-code"></i> {{ trans('general.custom_design') }}</li>
                                    <li class="cash_on_delivery"><i class="fas fa-credit-card"></i> {{ trans('general.credit_cards') }}</li>
                                    <li class="trans_percentage"><i class="fas fa-percentage"></i> 8% + 0.50cent {{ trans('general.per_transaction_fee') }}</li>
                                    <li><i class="fas fa-headset"></i> 24/7 {{ trans('general.support') }}</li>
                                </ul>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="subscription" id="basicSubscription" value="basic" checked>
                                    <label class="form-check-label" for="basicSubscription">
                                        {{ trans('general.select_basic_plan') }}
                                    </label>
                                </div>
                                </div>
                            </div>
                            </div>
                            <div class="col-md-6 col-xs-12 col-l-4 col-xl-4">
                            <div class="card shadow mb-4 shadow-sm">
                                <div class="card-header bg-primary text-white">
                                <h4 class="my-0 font-weight-normal">{{ trans('general.premium') }}</h4>
                                </div>
                                <div class="card-body">
                                <h1 class="card-title pricing-card-title">€29.99<small class="text-muted">/ {{ trans('general.month') }}</small></h1>
                                <ul class="list-unstyled mt-3 mb-4">
                                    <li><i class="fas fa-store"></i> 1 {{ trans('general.sub_shop') }}</li>
                                    <li><i class="fas fa-shopping-bag"></i> ∞ {{ trans('general.sub_products') }}</li>
                                    <li><i class="fas fa-chart-line"></i> 5000 {{ trans('general.sub_sales') }} / {{ trans('general.month') }}</li>
                                    <li><i class="fas fa-globe"></i> {{ trans('general.custom_domain') }}</li>
                                    <li><i class="fas fa-code"></i> {{ trans('general.custom_design') }}</li>
                                    <li class="cash_on_delivery"><i class="fas fa-credit-card"></i> {{ trans('general.credit_cards') }}</li>
                                    <li class="trans_percentage"><i class="fas fa-percentage"></i> 5% + 0.50cent {{ trans('general.per_transaction_fee') }}</li>
                                    <li><i class="fas fa-headset"></i> 24/7 {{ trans('general.support') }}</li>
                                </ul>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="subscription" id="premiumSubscription" value="premium">
                                    <label class="form-check-label" for="premiumSubscription">
                                        {{ trans('general.select_premium_plan') }}
                                    </label>
                                </div>
                                </div>
                            </div>
                            </div>
                        </div>

                        <button id="create_store_btn" type="submit" class="btn btn-primary float-right">
                            {{ trans('general.create') }}
                        </button>
                    </form>
                    <br>
                    <br>
                    <span class="mt-4">{{ trans('general.creating_shop_footer_info') }}</span>
                    <br>
                    <br>
                    <div class="alert alert-info subscription_cancelation_info">
                        {{ trans('general.can_stop_subscription_at_any_time') }}
                    </div>
                </div>
            </div>
        </div>
    </div>


<script type="application/javascript" src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script>
$(document).ready(function() {
  $('[data-toggle="tooltip"]').tooltip();
});
$('.edit_product_images').on('change', function(){
    var formData = new FormData();
    var image = $(this)[0].files[0]; // only the first file is used
    var previewTemplate = '<div class="preview-item-edit-product"><img src="'+URL.createObjectURL(image)+'" style="width: 120px; height: 120px; object-fit: contain; border-radius: 5px; margin-left: 5px; margin-bottom: 5px;"><button type="button" class="btn btn-danger remove-preview-button-edit-product remove-from-storage" style="padding: 2px;">&times;</button></div>';
    $('#product-images-preview-edit-product').append(previewTemplate);
    $('.edit_product_images, .upload-box1').addClass('d-none');
});


$(document).on('click', '.remove-preview-button-edit-product', function() {
    $(this).parent('.preview-item-edit-product').remove();
    $('.edit_product_images, .upload-box1').removeClass('d-none');
});

//update shop url, on shop name keyup
$(document).ready(function(){
    $('#shop_name, #my_shop_url').on('keyup', function(){
        var name = $(this).val();
        var url = name.toLowerCase().replace(/[^a-z0-9]+/g, '-');
        $('#my_shop_url').val(url);
    });
 });


 $(document).ready(function() {
  var existingHtml = $('#basic-addon3').html();
  var typingTimer;
  var doneTypingInterval = 500; // time in ms

  $('#shop_name, #my_shop_url').on('keydown', function() {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(doneTyping, doneTypingInterval);
  });

  function doneTyping() {
    var shopName = $('#my_shop_url').val();
    var url = "{{ route('check_shop_name', ':my_shop_url') }}".replace(':my_shop_url', shopName);

    $.ajax({
        type: 'POST',
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url,
        success: function(response) {
            if (response == true) {
                $('#basic-addon3').removeClass('text-danger').removeClass('domain-validator').addClass('text-success').html(existingHtml + '<i class="fas fa-check-circle ml-2 domain-validator" data-toggle="tooltip" data-placement="top" title="'+"{{ trans('general.shop_url_available') }}"+'"></i>');
                $('button[type="submit"]').prop('disabled', false);
            } else {
                $('#basic-addon3').removeClass('text-success').removeClass('domain-validator').addClass('text-danger').html(existingHtml + '<i class="fas fa-exclamation-circle ml-2 domain-validator" data-toggle="tooltip" data-placement="top" title="'+"{{ trans('general.shop_url_taken') }}"+'"></i>');
                $('button[type="submit"]').prop('disabled', true);
            }
            $('.domain-validator').tooltip();
        }
    });
  }
});




</script>



<script>
    $(document).ready(function() {
        // Initially, check the selected radio button and update the button text
        updateButtonText();

        // Listen for radio button changes
        $('input[name="subscription"]').change(function() {
            updateButtonText();
        });

        function updateButtonText() {
            var selectedValue = $('input[name="subscription"]:checked').val();
            var buttonText = '';

            switch (selectedValue) {
                case 'premium':
                    buttonText = "{{ trans('general.pay_to_create_store_btn', ['price' => '29.99']) }}";
                    $('.subscription_cancelation_info').show();
                    break;
                case 'basic':
                    buttonText = "{{ trans('general.pay_to_create_store_btn', ['price' => '19.99']) }}";
                    $('.subscription_cancelation_info').show();
                    break;
                case 'free':
                    buttonText = "{{ trans('general.create') }}";
                    $('.subscription_cancelation_info').hide();
                    break;
                default:
                buttonText = "{{ trans('general.create') }}";
                $('.subscription_cancelation_info').hide();
                    break;
            }

            // Update the button text
            $('#create_store_btn').text(buttonText);
        }
    });
</script>





<script>

$('.dropshipping').on('click', function(){
    $(this).addClass('selected-license');
    $('.local_store, .custom_branding').removeClass('selected-license').addClass('unselected-license');
    $('.store_type').val('dropshipping');
    $('.trans_percentage').show();
    var cc_trans = "{{ trans('general.credit_cards') }}";
    $('.cash_on_delivery').html('<li class="cash_on_delivery"><i class="fas fa-credit-card"></i> '+cc_trans+'</li>');
});


$('.local_store').on('click', function(){
    $(this).addClass('selected-license');
    $('.dropshipping, .custom_branding').removeClass('selected-license').addClass('unselected-license');
    $('.store_type').val('local_store');
    $('.trans_percentage').hide();
    var cash_trans = "{{ trans('general.cash') }}";
    $('.cash_on_delivery').html('<li class="cash_on_delivery"><i class="fas fa-money-bill-wave"></i> '+cash_trans+'</li>');
});

// $('.custom_branding').on('click', function(){
//     $(this).addClass('selected-license');
//     $('.dropshipping, .local_store').removeClass('selected-license').addClass('unselected-license');
//     $('.store_type').val('custom_branding');
// });

</script>



@endsection
