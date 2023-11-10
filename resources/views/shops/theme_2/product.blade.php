<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $product->product_name }} | {{ $shop->shop_name }}</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="{{ $product_description }}">
    <meta name="keywords" content="{{ $product->product_seo_keywords }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->

@include('shops.theme_2.includes.header_assets')

@include('shops.theme_2.includes.header')

        <!-- BEGIN: Shop Details Section -->
        <section class="shopDetailsPageSection" style="padding: 80px 0 117px;">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="productGalleryWrap">
                            <div class="productGallery">
                                @foreach($product_images as $image)
                                    <div class="pgImage">
                                        @if($fileExtension = substr(strrchr($image['image_url'], '.'), 1) == 'mp4')
                                            <video src="{{ $image['image_url'] }}" controls playsinline style="width:100%; max-height:400px;"></video>
                                        @else
                                            <img src="{{ $image['image_url'] }}"/>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <div class="productGalleryThumbWrap">
                                <div class="productGalleryThumb">
                                    @foreach($product_images as $image)
                                        @if($fileExtension = substr(strrchr($image['image_url'], '.'), 1) != 'mp4')
                                            <div class="pgtImage">
                                                <img src="{{ $image['image_url'] }}"/>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="productContent">
                            @foreach ($categories as $category)
                                <div class="pcCategory">
                                    @if($category->id == $product->product_category)<a href="/category/{{ $category->category_name }}">{{ $category->category_name }}</a>@endif
                                </div>
                            @endforeach
                            
                            <h2>{{ $product->product_name }}</h2>
                            <div class="pi01Price">
                                @if($product->base_price_discount)
                                    <ins>€{{ number_format($product->base_price - ($product->base_price * $product->base_price_discount / 100), 2) }}</ins>
                                    <del>€{{ $product->base_price }}</del>
                                @else
                                    <ins>€{{ $product->base_price }}</ins>
                                @endif
                            </div>

                            @include('shops.theme_2.includes.product_variants')

                            <div class="pcBtns">
                                <!-- <div class="quantity clearfix">
                                    <button type="button" name="btnMinus" class="qtyBtn btnMinus">_</button>
                                    <input type="number" class="carqty input-text qty text" name="quantity" value="01">
                                    <button type="button" name="btnPlus" class="qtyBtn btnPlus">+</button>
                                </div> -->
                                <button type="submit" id="add_to_cart" class="ulinaBTN"><span id="add_to_cart_span">{{ trans('general.add_to_cart') }}</span></button>
                                <a href="#" onclick="addToWishlist({{$product->shop_id}},{{$product->id}})" class="pcWishlist"><i class="fa-solid fa-heart"></i></a>
                                <a href="#" class="pcWishlist share_product"><i class="fa-solid fa-share"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row productTabRow">
                    <div class="col-lg-12">
                        <ul class="nav productDetailsTab" id="productDetailsTab" role="tablist">
                            <li role="presentation">
                                <button class="active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">{{ trans('general.description') }}</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="desInfoRev_content">
                            <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab" tabindex="0">
                                <div class="productDescContentArea">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="descriptionContent">
                                                <style>
                                                    .descriptionContent img {
                                                        max-width: 100%; /* Set the maximum width to 100% of the container */
                                                        height: auto; /* Maintain the aspect ratio */
                                                    }
                                                </style>
                                                {!! strip_tags($product->product_description, '<p><em><strong><img><ul><ol><li>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('shops.theme_2.includes.related_products')
            </div>
        </section>
        <!-- END: Shop Details Section -->

@include('shops.theme_2.includes.footer')



<!-- Modal -->
<div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="shareModalLabel">{{ trans('general.share') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-4 py-3 text-center">
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shop_url }}/product/{{ $product->product_url }}" target="_blank"><i class="fab fa-facebook" style="font-size: 4em;"></i></a>
            <p>Facebook</p>
          </div>
          <div class="col-4 py-3 text-center">
            <a href="https://twitter.com/intent/tweet?url={{ $shop_url }}/product/{{ $product->product_url }}" target="_blank"><i class="fab fa-twitter" style="font-size: 4em;"></i></a>
            <p>Twitter</p>
          </div>
          <div class="col-4 py-3 text-center">
            <a href="whatsapp://send?text={{ $shop_url }}/product/{{ $product->product_url }}" data-action="share/whatsapp/share"><i class="fab fa-whatsapp" style="font-size: 4em;"></i></a>
            <p>Whatsapp</p>
          </div>
          <div class="col-4 py-3 text-center">
            <a href="viber://forward?text={{ $shop_url }}/product/{{ $product->product_url }}" data-action="share/viber/share"><i class="fab fa-viber" style="font-size: 4em;"></i></a>
            <p>Viber</p>
          </div>
          <div class="col-4 py-3 text-center">
            <a href="https://t.me/share/url?url={{ $shop_url }}/product/{{ $product->product_url }}" target="_blank"><i class="fab fa-telegram" style="font-size: 4em;"></i></a>
            <p>Telegram</p>
          </div>
          <div class="col-4 py-3 text-center">
            <a href="sms:?body={{ $shop_url }}/product/{{ $product->product_url }}"><i class="fas fa-sms" style="font-size: 4em;"></i></a>
            <p>SMS</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>




<script>



document.querySelector('.share_product').addEventListener('click', function() {
  var myModal = new bootstrap.Modal(document.getElementById('shareModal'));
  myModal.show();
});






$(document).ready(function() {
    var productVariants = {!! json_encode($product_variants) !!};
    var selectedSize = null;
    var selectedColor = null;
    var selectedMaterial = null;

    var sizeExist = 'no';
    var colorExist = 'no';
    var materialExist = 'no';
    var sizeVal;
    var colorVal;
    var materialVal;

    var sizePrice;



  function updateVariantOptions() {
    var sizes = [];
    var colors = [];
    var materials = [];
    var prices = [];


    // Loop through the product variants array and extract unique values for size, color, and material
    productVariants.forEach(function(variant) {
      if (!sizes.includes(variant.size)) {
        sizes.push(variant.size);
      }
      if (!colors.includes(variant.color)) {
        colors.push(variant.color);
      }
      if (!materials.includes(variant.material)) {
        materials.push(variant.material);
      }
      if (!prices.includes(variant.price)) {
        prices.push(variant.price);
      }
    });




    // Update the size options
    var sizeHtml = '<div class="pcvContainer" style="flex-wrap: wrap;">';
    sizes.forEach(function(size) {
        var isAvailable = productVariants.some(function(variant) {
            return variant.size === size && size && variant.quantity_left > 0;
        });
        var classString = isAvailable ? 'size' : 'd-none size';
        var price;
        productVariants.forEach(function(variant) {
            if (variant.size === size && variant.quantity_left > 0) {
                price = variant.price;
                sizePrice = variant.price;
            }
        });
        sizeHtml += '<div class="pswItem" style="margin-bottom: 5px;"><label size-id="'+size+'" style="width: max-content; padding-left: 10px; padding-right: 10px;"><a href="#" class="' + classString + '" id="size-select" size-id="'+size+'" size-price="'+ price +'">' + size + '</a></label></div>';
    });
    sizeHtml += '</div>';

    var allSizesUnavailable = sizes.every(function(size) {
        return !productVariants.some(function(variant) {
            return variant.size === size && size && variant.quantity_left > 0;
        });
    });

    if (sizes.filter(Boolean).length === 0 || allSizesUnavailable) {
        $('.size.pro-details-size').addClass('d-none');
    } else {
        $('.sizes.pro-details-size-content').eq(0).html(sizeHtml);
        sizeExist = 'yes';
    }


    // Update the color options
    var colorHtml = '<div class="pcvContainer" style="flex-wrap: wrap;">';
    colors.forEach(function(color) {
        var isAvailable = productVariants.some(function(variant) {
            return variant.color === color && color && variant.quantity_left > 0;
        });
        var classString = isAvailable ? 'color' : 'd-none color';
        var colorSizesMap = {}; // initialize empty object to group sizes by color

    productVariants.forEach(function(variant) {
        if (variant.color === color && variant.quantity_left > 0) {
            if (!colorSizesMap[variant.color]) { // if the color object doesn't exist, create it
                colorSizesMap[variant.color] = {}; // initialize the sizes object for the color
            }
            if (!colorSizesMap[variant.color][variant.size]) { // if the size doesn't exist, add it to the sizes object
                colorSizesMap[variant.color][variant.size] = true;
                var belongsTo = variant.size;
                var price = variant.price;
                colorHtml += '<div class="pswItem psw-color" belongs-to="'+belongsTo+'" style="margin-bottom: 5px;"><label id="color-select" color-id="' + color + '" color-price="'+price+'" belongs-to="'+belongsTo+'" style="width: max-content; padding-left: 10px; padding-right: 5px;"><a href="#" class="pswItem ' + classString + '" id="color-select" color-id="' + color + '" color-price="'+price+'" belongs-to="'+belongsTo+'">' + color + '</a></label></div>';
            }
        }
    });


    
    });
    colorHtml += '</div>';

    var allColorsUnavailable = colors.every(function(color) {
        return !productVariants.some(function(variant) {
            return variant.color === color && color && variant.quantity_left > 0;
        });
    });

    if (colors.filter(Boolean).length === 0 || allColorsUnavailable) {
        $('.color.pro-details-size').addClass('d-none');
    } else {
        $('.colors.pro-details-size-content').eq(0).html(colorHtml);
        colorExist = 'yes';
    }

    // Update the material options
    var materialHtml = '<div class="pcvContainer">';
    materials.forEach(function(material) {
        var isAvailable = productVariants.some(function(variant) {
            return variant.material === material && material && variant.quantity_left > 0;
        });
        var classString = isAvailable ? 'material' : 'd-none material';
        var price;
        productVariants.forEach(function(variant) {
            if (variant.material === material && variant.quantity_left > 0) {
                price = variant.price;
            }
        });
        var belongsTo;
        productVariants.forEach(function(variant) {
            if (variant.material === material && variant.quantity_left > 0) {
                belongsTo = variant.size;
            }
        });
        materialHtml += '<div class="pswItem"><label material-id="' + material + '"><a href="#" class="pswItem ' + classString + '"  id="material-select" material-id="' + material + '" material-price="'+price+'" belongs-to="'+belongsTo+'">' + material + '</a></label></div>';
    });
    materialHtml += '</div>';

    var allMaterialsUnavailable = materials.every(function(material) {
        return !productVariants.some(function(variant) {
            return variant.material === material && material && variant.quantity_left > 0;
        });
    });

    if (materials.filter(Boolean).length === 0 || allMaterialsUnavailable) {
        $('.material.pro-details-size').addClass('d-none');
    } else {
        $('.materials.pro-details-size-content').eq(0).html(materialHtml);
        materialExist = 'yes';
    }
  }

  // Update the variant options when the page loads
  updateVariantOptions();

  // Handle clicks on the size options
  $('.pro-details-size-content').eq(0).on('click', 'a:not(.disabled)', function(e) {
    e.preventDefault();
    selectedSize = $(this).text();
    $('.pro-details-size-content li').removeClass('active');
    $(this).parent().addClass('active');
    updateVariantOptions();
  });

  // Handle clicks on the color options
  $('.pro-details-size-content.color').eq(0).on('click', 'a:not(.disabled)', function(e) {
    e.preventDefault();
    selectedColor = $(this).text();
    $('.pro-details-size-content.color li').removeClass('active');
    $(this).parent().addClass('active');
    updateVariantOptions();
  });

  // Handle clicks on the material options
  $('.pro-details-size-content.material').eq(0).on('click', 'a:not(.disabled)', function(e) {
    e.preventDefault();
    selectedMaterial = $(this).text();
    $('.pro-details-size-content.material li').removeClass('active');
    $(this).parent().addClass('active');
    updateVariantOptions();
  });



$(document).on('click', '#size-select', function(e) {
  e.preventDefault();
  var size = $(this).attr('size-id');
  var price = $(this).attr('size-price');
  $('.sizes.pro-details-size-content label').css('background-color', 'white').css('color', 'black');
  $('.sizes.pro-details-size-content a').css('color', 'white').css('color', '#7b9496');
  $('.sizes.pro-details-size-content label[size-id="' + size + '"]').css('background-color', '#9ebbbd');
  $('.sizes.pro-details-size-content a[size-id="' + size + '"]').css('color', 'white');
  sizeVal = size;
  $('.psw-color').not('[belongs-to="' + size + '"]').hide();
  $('.pi01Price').html('<ins>€'+price+'</ins>');
});

$(document).on('click', '#color-select', function(e) {
  e.preventDefault();
  var color = $(this).attr('color-id');
  var price = $(this).attr('color-price');
  $('.colors.pro-details-size-content label').css('background-color', 'white').css('color', '#7b9496');
  $('.colors.pro-details-size-content a').css('color', 'white').css('color', '#7b9496');
  $('.colors.pro-details-size-content label[color-id="' + color + '"]').css('background-color', '#9ebbbd');
  $('.colors.pro-details-size-content a[color-id="' + color + '"]').css('color', 'white');
  colorVal = color;
  if(sizeExist == 'no'){
    $('.pi01Price').html('<ins>€'+price+'</ins>');
  }
});

$(document).on('click', '#material-select', function(e) {
  e.preventDefault();
  var material = $(this).attr('material-id');
  var price = $(this).attr('material-price');
  $('.materials.pro-details-size-content label').css('background-color', 'white').css('color', 'black');
  $('.materials.pro-details-size-content label[material-id="' + material + '"]').css('background-color', '#9ebbbd');
  $('.materials.pro-details-size-content a[material-id="' + material + '"]').css('color', 'white');
  materialVal = material;
  if(colorExist == 'no' && sizeExist == 'no'){
    $('.pi01Price').html('<ins>€'+price+'</ins>');
  }
});


//click on one variant on page load.
$(document).ready(function() {
  if ($('.sizes.pro-details-size-content label').length > 0) {
    $('.sizes.pro-details-size-content a').eq(0).trigger('click');
  } else if ($('.colors.pro-details-color-content label').length > 0) {
    $('.colors.pro-details-color-content a').eq(0).trigger('click');
  } else if ($('.materials.pro-details-material-content label').length > 0) {
    $('.materials.pro-details-material-content a').eq(0).trigger('click');
  }else{
    //no variant exists.
  }
});



$('#add_to_cart').on('click', function(e){
    e.preventDefault();

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


    //send to cart
    if (sizeExist == 'yes' && (typeof sizeVal === 'undefined' || sizeVal === '')) {
        Toast.fire({
            icon: 'error',
            title: "{{ trans('general.select_size') }}"
        })
    } else if (colorExist == 'yes' && (typeof colorVal === 'undefined' || colorVal === '')) {
        Toast.fire({
            icon: 'error',
            title: "{{ trans('general.select_color') }}"
        })
    } else if (materialExist == 'yes' && (typeof materialVal === 'undefined' || materialVal === '')) {
        Toast.fire({
            icon: 'error',
            title: "{{ trans('general.select_material') }}"
        })
    } else {
        // Get the form data
        var formData = new FormData();
        var product_id = "{{ $product->id }}";
        var shop_id = "{{ $product->shop_id }}";
        // Append additional data to the form data
        formData.append('product_id', product_id);
        formData.append('shop_id', shop_id);
        formData.append('size', sizeVal);
        formData.append('color', colorVal);
        formData.append('material', materialVal);
        // Send the AJAX request
        $.ajax({
        url: '/add/cart/',
        type: 'POST',
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        data: formData,
        success: function(response) {
            if(response.authenticated == false){
                Toast.fire({
                    icon: 'error',
                    title: response.message
                }) 
                $('#loginModal').modal('show');
            }
            if(response.added){
                Toast.fire({
                    icon: 'success',
                    title: response.message
                })
                $('#add_to_cart_span').html("{{ trans('general.remove_from_cart') }}");
                $('.cart-count').each(function() {
                    var count = parseInt($(this).text());
                    count += 1;
                    $(this).text(count);
                });
                if(facebook_pixel){
                    fbq('track', 'AddToCart', {
                        content_ids: product_id,
                        content_type: 'product',
                    });
                }
                if(tiktok_pixel){
                    ttq.push('track', 'AddToCart', {
                        content_ids: product_id,
                        content_type: 'product',
                    });
                }
            }
            if(response.deleted){
                Toast.fire({
                    icon: 'error',
                    title: response.message
                })
                $('#add_to_cart_span').html("{{ trans('general.add_to_cart') }}");
                $('.cart-count').each(function() {
                    var count = parseInt($(this).text());
                    count -= 1;
                    $(this).text(count);
                });
            }
        },
        error: function(xhr) {
            // Handle the error response
            Toast.fire({
                icon: 'error',
                title: xhr.responseText
            })
        }
        });

    }//else end

}); // click event end.



});


</script>