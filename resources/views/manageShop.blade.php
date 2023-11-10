@extends('layouts.app')
@include('layouts.styles')

<style>
.divider-text {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background-color: white;
  padding: 0 10px;
}
</style>
<title>{{trans('general.manage')}} | {{ config('app.name') }} </title>
@section('content')
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
    @include('includes.shop_settings_sidebar')

        <div class="col-12 col-sm-12 col-md-12 col-lg-8 mb-4 main-content">
            
            <div class="card shadow">
                <div class="card-header">{{ trans('general.customize') }}</div>

                <div class="card-body">
                    
                    <div class="card-body">
                        <form method="POST" action="{{ route('update_shop', $id) }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group row mb-4">

                                <div class="col-md-4 col-l-3 col-xl-3 col-xs-6 col-sm-6" style="margin-top: -10px;">
                                    <div class="preview-container-edit-product" id="product-images-preview-edit-product">
                                        <div class="preview-item-edit-product"><img src="{{ $image_url }}" style="width: 120px; height: 120px; object-fit: contain; border-radius: 5px; margin-left: 5px; margin-bottom: 5px;"></div>
                                    </div>
                                </div>

                                <div class="col-md-7 col-l-9 col-xl-9 col-xs-6 col-sm-6">
                                    <label for="image" class="upload-box1">
                                        <span class="upload-text1">{{ trans('general.change_shop_logo') }}</span>
                                    </label>
                                    <input type="file" id="image" class="custom-file-input edit_product_images" name="image" aria-describedby="product-images-preview" accept="image/*">
                                </div>

                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input id="shop_name" type="name" class="form-control @error('shop_name') is-invalid @enderror" name="shop_name" value="{{ $name }}" required autocomplete="shop_name" placeholder="{{ trans('general.shop_name') }}" autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <textarea id="shop_description" type="name" class="form-control @error('shop_description') is-invalid @enderror" name="shop_description" required autocomplete="shop_description" placeholder="{{ trans('general.shop_description') }}" autofocus>{{ $description }}</textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input id="shop_seo_keywords" type="name" class="form-control @error('shop_seo_keywords') is-invalid @enderror" name="shop_seo_keywords" value="{{ $seo_keywords }}" required autocomplete="shop_seo_keywords" placeholder="{{ trans('general.shop_keywords') }}" autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input id="header_message" type="name" class="form-control @error('header_message') is-invalid @enderror" name="header_message" value="{{ $header_message }}" autocomplete="header_message" placeholder="{{ trans('general.shop_header_message') }}" autofocus>
                                </div>
                            </div>


                            <hr class="mt-4">
                            <p class="mb-4" style="font-size:25px; font-weight:600;">{{ trans('general.social_networks') }}</p>
                            
                                <div class="form-group row">
                                    <div class="col-md-6 col-xs-12 mb-3">
                                        <input id="instagram_url" type="name" class="form-control @error('instagram_url') is-invalid @enderror" name="instagram_url" value="{{ isset($social_networks['instagram']) ? $social_networks['instagram'] : '' }}" autocomplete="instagram_url" placeholder="{{ trans('general.instagram_url') }}" autofocus>
                                    </div>

                                    <div class="col-md-6 col-xs-12 mb-3">
                                        <input id="tiktok_url" type="name" class="form-control @error('tiktok_url') is-invalid @enderror" name="tiktok_url" value="{{ isset($social_networks['tiktok']) ? $social_networks['tiktok'] : '' }}" autocomplete="tiktok_url" placeholder="{{ trans('general.tiktok_url') }}" autofocus>
                                    </div>

                                    <div class="col-md-6 col-xs-12 mb-3">
                                        <input id="facebook_url" type="name" class="form-control @error('facebook_url') is-invalid @enderror" name="facebook_url" value="{{ isset($social_networks['facebook']) ? $social_networks['facebook'] : '' }}" autocomplete="facebook_url" placeholder="{{ trans('general.facebook_url') }}" autofocus>
                                    </div>

                                    <div class="col-md-6 col-xs-12 mb-3">
                                        <input id="twitter_url" type="name" class="form-control @error('twitter_url') is-invalid @enderror" name="twitter_url" value="{{ isset($social_networks['twitter']) ? $social_networks['twitter'] : '' }}" autocomplete="twitter_url" placeholder="{{ trans('general.twitter_url') }}" autofocus>
                                    </div>

                                    <div class="col-md-6 col-xs-12 mb-3">
                                        <input id="pinterest_url" type="name" class="form-control @error('pinterest_url') is-invalid @enderror" name="pinterest_url" value="{{ isset($social_networks['pinterest']) ? $social_networks['pinterest'] : '' }}" autocomplete="pinterest_url" placeholder="{{ trans('general.pinterest_url') }}" autofocus>
                                    </div>

                                    <div class="col-md-6 col-xs-12 mb-3">
                                        <input id="phone_nr" type="name" class="form-control @error('phone_nr') is-invalid @enderror" name="phone_nr" value="{{ isset($social_networks['phone']) ? $social_networks['phone'] : '' }}" autocomplete="phone_nr" placeholder="{{ trans('general.phone') }}" autofocus>
                                    </div>

                                    <div class="col-md-6 col-xs-12 mb-3">
                                        <input id="address" type="name" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ isset($social_networks['address']) ? $social_networks['address'] : '' }}" autocomplete="address" placeholder="{{ trans('general.shop_address') }}" autofocus>
                                    </div>

                                </div>

                            <hr class="mt-4">
                            <p class="mb-4" style="font-size:25px; font-weight:600;">{{ trans('general.shop_home_featured_section') }}</p>
                            
                            <div class="form-group row mb-4">
                                <div class="col-md-6 col-xs-12 col-l-6 col-xl-6 col-sm-12">

                                    <div class="col-md-12 mb-4">
                                        <input id="shop_front_page_featured_image_title" type="name" class="form-control @error('shop_front_page_featured_image_title') is-invalid @enderror" name="shop_front_page_featured_image_title" value="{{ isset($header_section['title']) ? $header_section['title'] : '' }}" autocomplete="shop_front_page_featured_image_title" placeholder="{{ trans('general.title') }}">
                                    </div>

                                    <div class="col-md-12 mb-4">
                                        <textarea id="shop_front_page_featured_image_description" rows="4" type="name" class="form-control @error('shop_front_page_featured_image_description') is-invalid @enderror" name="shop_front_page_featured_image_description" autocomplete="shop_seo_keywords" placeholder="{{ trans('general.description') }}">{{ isset($header_section['description']) ? $header_section['description'] : '' }}</textarea>
                                    </div>

                                    <div class="col-md-12 mb-4">
                                        <input id="shop_front_page_featured_image_button_name" type="name" class="form-control @error('shop_front_page_featured_image_button_name') is-invalid @enderror" name="shop_front_page_featured_image_button_name" value="{{ isset($header_section['button_name']) ? $header_section['button_name'] : '' }}" autocomplete="shop_front_page_featured_image_button_name" placeholder="{{ trans('general.shop_button_name') }}">
                                    </div>

                                    <div class="col-md-12 mb-4">
                                        <input id="shop_front_page_featured_image_button_call_url" type="name" class="form-control @error('shop_front_page_featured_image_button_call_url') is-invalid @enderror" name="shop_front_page_featured_image_button_call_url" value="{{ isset($header_section['button_url']) ? $header_section['button_url'] : '' }}" autocomplete="shop_front_page_featured_image_button_call_url" placeholder="{{ trans('general.shop_button_call_url') }}">
                                    </div>

                                </div>

                                <div class="col-md-6 col-xs-12 col-l-6 col-xl-6 col-sm-12">
                                    <div class="input-group">
                                        <input type="file" class="custom-file-input" id="featured_image" name="home_featured_image"
                                            aria-describedby="images" accept="image/*" style="display: none;">
                                        <label class="upload-box" for="featured_image" style="height:300px;">
                                            <span class="upload-text">{{ trans('general.choose_image') }}</span>
                                            <div class="featured_header_image_removed d-none"></div>
                                            <img class="preview-image" src="{{ isset($header_section['featured_image']) ? $header_section['featured_image'] : '' }}" />
                                            <button class="remove-preview-button" type="button">×</button>
                                        </label>
                                    </div>
                                </div>

                            </div>


                            <button type="submit" class="btn btn-primary float-right">
                                {{ trans('general.save') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script type="application/javascript" src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script>
$(document).on('change', '.edit_product_images', function() {
    var previewContainer = $('#product-images-preview-edit-product');
    var files = $(this)[0].files;
    var file = files[0];
    var reader = new FileReader();
    reader.onload = function(e) {
        var imgSrc = e.target.result;
        var previewTemplate = `<div class="preview-item-edit-product"><img src="${imgSrc}" alt="${file.name}" style="width: 120px; height: 120px; object-fit: cover; border-radius: 5px; margin-left: 5px; margin-bottom: 5px;"></div>`;
        previewContainer.html(previewTemplate);
    }
    reader.readAsDataURL(file);
});


$(document).on('change', '#featured_image', function (event) {
    $('.featured_header_image_removed').html('');
    var file = event.target.files[0];
    var reader = new FileReader();
    reader.onload = function(e) {
    $('.preview-image').attr('src', e.target.result).show();
    $('.upload-text').addClass('d-none');
    $('.remove-preview-button').removeClass('d-none').show();
    }
    reader.readAsDataURL(file);
});

$(document).on('click', '.remove-preview-button', function (event) {
    $('.preview-image').attr('src', '').hide();
    $('.upload-text').removeClass('d-none');
    $('.remove-preview-button').addClass('d-none');
    $('#featured_image').val('');
    $('.featured_header_image_removed').html('<input name="header_featured_img_removed" value="1">');
});


@if(isset($header_section['featured_image']))
    $(document).ready(function () {
        $('.upload-text').addClass('d-none');
        $('.remove-preview-button').css('display','inline');
        $('.preview-image').css('display','inline');
    });
@endif
</script>