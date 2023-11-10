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

    @include('shops.theme_1.includes.header_assets')


    <div class="main-wrapper">
        @include('shops.theme_1.includes.header')
        <div class="product-details-area pt-120 pb-115 mt-50">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="product-details-tab">


                            <div class="pro-dec-big-img-slider">
                                @foreach($product_images as $image)
                                    <div class="easyzoom-style">
                                        <div class="easyzoom easyzoom--overlay">
                                            <a href="{{ $image['image_url'] }}">
                                                <img src="{{ $image['image_url'] }}" alt="">
                                            </a>
                                        </div>
                                        <a class="easyzoom-pop-up img-popup" href="{{ $image['image_url'] }}"><i class="icon-size-fullscreen"></i></a>
                                    </div>
                                @endforeach
                            </div>


                            <div class="product-dec-slider-small product-dec-small-style1">
                                @foreach($product_images as $key => $image)
                                    <div class="product-dec-small {{ $key == 0 ? 'active' : '' }}">
                                        <img src="{{ $image['image_url'] }}" alt="">
                                    </div>
                                @endforeach
                            </div>



                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="product-details-content pro-details-content-mrg">
                            <h2>{{ $product->product_name }}</h2>
                            <!-- <div class="product-ratting-review-wrap">
                                <div class="product-ratting-digit-wrap">
                                    <div class="product-ratting">
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                    </div>
                                    <div class="product-digit">
                                        <span>5.0</span>
                                    </div>
                                </div>
                                <div class="product-review-order">
                                    <span>62 {{ trans('general.reviews') }}</span>
                                    <span>242 {{ trans('general.orders') }}</span>
                                </div>
                            </div> -->
                            <div class="pro-details-price">
                                @if($product->base_price_discount)
                                    <span class="new-price">€{{ number_format($product->base_price - ($product->base_price * $product->base_price_discount / 100), 2) }}</span>
                                    <span class="old-price">€{{ $product->base_price }}</span>
                                @else
                                    <span class="new-price">€{{ $product->base_price }}</span>
                                @endif
                            </div>


                            @include('shops.theme_1.includes.product_variants')



                            <!-- <div class="pro-details-quality">
                                <span>{{ trans('general.quantity') }}:</span>
                                <div class="cart-plus-minus">
                                    <input class="cart-plus-minus-box" type="text" name="qtybutton" value="1">
                                </div>
                            </div> -->
                            <div class="product-details-meta">
                                <ul>
                                    <li><span>{{ trans('general.category') }}:</span> 
                                        @foreach ($categories as $category)
                                            @if($category->id == $product->product_category)<a href="/category/{{ $category->category_name }}">{{ $category->label }}</a>@endif
                                        @endforeach
                                    </li>
                                </ul>
                            </div>
                            <div class="pro-details-action-wrap">
                                <div class="pro-details-add-to-cart">
                                    <a title="{{ trans('general.add_to_cart') }}" id="add_to_cart" href="">{{ trans('general.add_to_cart') }} </a>
                                </div>
                                <!-- <div class="pro-details-add-to-cart">
                                    <a title="{{ trans('general.buy_now') }}" id="buy_now" href="">{{ trans('general.buy_now') }} </a>
                                </div> -->
                                <div class="pro-details-action">
                                    <a title="Add to Wishlist" onclick="addToWishlist({{$product->shop_id}},{{$product->id}})"><i class="icon-heart"></i></a>
                                    <a title="Share" class="share_product"><i class="icon-share"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="description-review-wrapper pb-110">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="dec-review-topbar nav mb-45">
                            <a class="active" data-bs-toggle="tab" href="product-details.html#des-details1">{{ trans('general.description') }}</a>
                            <!-- <a data-bs-toggle="tab" href="product-details.html#des-details4">{{ trans('general.ratings_and_reviews') }} </a> -->
                        </div>
                        <div class="tab-content dec-review-bottom">
                            <div id="des-details1" class="tab-pane active">
                                <div class="description-wrap">
                                    <style>
                                        .description-wrap img {
                                            max-width: 100%; /* Set the maximum width to 100% of the container */
                                            height: auto; /* Maintain the aspect ratio */
                                        }
                                    </style>
                                    {!! strip_tags($product->product_description, '<p><em><strong><img><ul><ol><li>') !!}
                                </div>
                            </div>
                            <div id="des-details4" class="tab-pane">
                                <div class="review-wrapper">
                                    <h2>1 review for Sleeve Button Cowl Neck</h2>
                                    <div class="single-review">
                                        <div class="review-img">
                                            <img src="{{ asset('theme_1/assets/images/product-details/client-1.png') }}" alt="">
                                        </div>
                                        <div class="review-content">
                                            <div class="review-top-wrap">
                                                <div class="review-name">
                                                    <h5><span>John Snow</span> - March 14, 2019</h5>
                                                </div>
                                                <div class="review-rating">
                                                    <i class="yellow icon_star"></i>
                                                    <i class="yellow icon_star"></i>
                                                    <i class="yellow icon_star"></i>
                                                    <i class="yellow icon_star"></i>
                                                    <i class="yellow icon_star"></i>
                                                </div>
                                            </div>
                                            <p>Donec accumsan auctor iaculis. Sed suscipit arcu ligula, at egestas magna molestie a. Proin ac ex maximus, ultrices justo eget, sodales orci. Aliquam egestas libero ac turpis pharetra, in vehicula lacus scelerisque</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="ratting-form-wrapper">
                                    <span>Add a Review</span>
                                    <p>Your email address will not be published. Required fields are marked <span>*</span></p>
                                    <div class="ratting-form">
                                        <form action="product-details.html#">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="rating-form-style mb-20">
                                                        <label>Name <span>*</span></label>
                                                        <input type="text">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="rating-form-style mb-20">
                                                        <label>Email <span>*</span></label>
                                                        <input type="email">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="star-box-wrap">
                                                        <div class="single-ratting-star">
                                                            <a href="product-details.html#"><i class="icon_star"></i></a>
                                                        </div>
                                                        <div class="single-ratting-star">
                                                            <a href="product-details.html#"><i class="icon_star"></i></a>
                                                            <a href="product-details.html#"><i class="icon_star"></i></a>
                                                        </div>
                                                        <div class="single-ratting-star">
                                                            <a href="product-details.html#"><i class="icon_star"></i></a>
                                                            <a href="product-details.html#"><i class="icon_star"></i></a>
                                                            <a href="product-details.html#"><i class="icon_star"></i></a>
                                                        </div>
                                                        <div class="single-ratting-star">
                                                            <a href="product-details.html#"><i class="icon_star"></i></a>
                                                            <a href="product-details.html#"><i class="icon_star"></i></a>
                                                            <a href="product-details.html#"><i class="icon_star"></i></a>
                                                            <a href="product-details.html#"><i class="icon_star"></i></a>
                                                        </div>
                                                        <div class="single-ratting-star">
                                                            <a href="product-details.html#"><i class="icon_star"></i></a>
                                                            <a href="product-details.html#"><i class="icon_star"></i></a>
                                                            <a href="product-details.html#"><i class="icon_star"></i></a>
                                                            <a href="product-details.html#"><i class="icon_star"></i></a>
                                                            <a href="product-details.html#"><i class="icon_star"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="rating-form-style mb-20">
                                                        <label>Your review <span>*</span></label>
                                                        <textarea name="Your Review"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-submit">
                                                        <input type="submit" value="Submit">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('shops.theme_1.includes.related_products')
        @include('shops.theme_1.includes.footer')


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
    var sizeHtml = '<ul>';
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
        sizeHtml += '<li><a href="#" style="width: 100%; padding: 5px; padding-top: 0px;" class="' + classString + '" id="size-select" size-id="'+size+'" size-price="'+ price +'">' + size + '</a></li>';
    });
    sizeHtml += '</ul>';

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
    var colorHtml = '<ul>';
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
                colorHtml += '<li><a href="#" style="width: 100%; padding: 5px; padding-top: 0px;" class="' + classString + '" id="color-select" color-id="' + color + '" color-price="'+price+'" belongs-to="'+belongsTo+'">' + color + '</a></li>';
            }
        }
    });


    
    });
    colorHtml += '</ul>';

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
    var materialHtml = '<ul>';
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
        materialHtml += '<li><a href="#" style="width: 100%; padding: 5px; padding-top: 0px;" class="' + classString + '"  id="material-select" material-id="' + material + '" material-price="'+price+'" belongs-to="'+belongsTo+'">' + material + '</a></li>';
    });
    materialHtml += '</ul>';

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
  $('.sizes.pro-details-size-content a').css('background-color', 'white').css('color', 'black');
  $('.sizes.pro-details-size-content a[size-id="' + size + '"]').css('background-color', 'red').css('color', 'white');
  sizeVal = size;
  //$('.colors.pro-details-size-content a').not('[belongs-to="' + size + '"]').hide();
  $('.colors.pro-details-size-content a').each(function() {
    if ($(this).attr('belongs-to') !== size) {
        $(this).closest('li').hide();
    }
  });
  $('.new-price').html('€'+price);
});

$(document).on('click', '#color-select', function(e) {
  e.preventDefault();
  var color = $(this).attr('color-id');
  var price = $(this).attr('color-price');
  $('.colors.pro-details-size-content a').css('background-color', 'white').css('color', 'black');
  $('.colors.pro-details-size-content a[color-id="' + color + '"]').css('background-color', 'red').css('color', 'white');
  colorVal = color;
  if(sizeExist == 'no'){
    $('.new-price').html('€'+price);
  }
});

$(document).on('click', '#material-select', function(e) {
  e.preventDefault();
  var material = $(this).attr('material-id');
  var price = $(this).attr('material-price');
  $('.materials.pro-details-size-content a').css('background-color', 'white').css('color', 'black');
  $('.materials.pro-details-size-content a[material-id="' + material + '"]').css('background-color', 'red').css('color', 'white');
  materialVal = material;
  if(colorExist == 'no' && sizeExist == 'no'){
    $('.new-price').html('€'+price);
  }
});


//click on one variant on page load.
$(document).ready(function() {
  if ($('.sizes.pro-details-size-content a').length > 0) {
    $('.sizes.pro-details-size-content a').eq(0).trigger('click');
  } else if ($('.colors.pro-details-color-content a').length > 0) {
    $('.colors.pro-details-color-content a').eq(0).trigger('click');
  } else if ($('.materials.pro-details-material-content a').length > 0) {
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
                $('#add_to_cart').html("{{ trans('general.remove_from_cart') }}");
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
                $('#add_to_cart').html("{{ trans('general.add_to_cart') }}");
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