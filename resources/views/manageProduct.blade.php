@extends('layouts.app')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css">
<title>{{trans('general.products')}} | {{ config('app.name') }} </title>
@section('content')
    @include('layouts.styles')
    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                        </ul>
                    </div>
                @endif
    <div class="row">
        <!-- start main -->
        <div class="col-12 col-sm-12 col-md-12 col-lg-8 mb-4 main-content">
            <div class="card shadow">
                <div class="card-header">{{ trans('general.create_new_product') }}</div>
                <div class="card-body">
                    <!-- start form for post request -->
                    <form id="update_product_form" method="POST" action="{{ route('update_product', $id) }}">
                        @csrf
                        <span>{{ trans('general.title') }}</span>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="product_name" type="name"
                                    class="form-control @error('product_name') is-invalid @enderror" name="product_name"
                                    value="{{ $product_name }}" required autocomplete="product_name" placeholder="{{ trans('general.title') }}" autofocus>

                            <span id="product_url_edit_field" style="display: flex; line-height: 2.5em; margin-top: 5px; display:none;">{{$shop_url}}/product/<input class="form-control col-6" id="product_url_edit" type="name" value="{{ $product_url }}" autofocus><a href="#" id="save_product_url" type="button" class="btn btn-primary" style="margin-left: 5px;">{{ trans('general.save') }}</a></span>
                            <a id="product_url" href="{{$shop_url}}/product/{{ $product_url }}" target="_blank">{{$shop_url}}/product/{{ $product_url }}</a> <a href="#" id="edit_product_url_btn">[{{ trans('general.edit') }}]</a>

                            </div>
                        </div>

                        <span>{{ trans('general.description') }}</span>
                        <div class="form-group row">
                            <div class="col-md-12 mb-5">
                                <div id="product_description"></div>
                                <textarea id="product_description_input" type="text"
                                    class="form-control @error('product_description') is-invalid @enderror" name="product_description_input" value="{{ $product_description }}"
                                    required autocomplete="product_description_input" placeholder="{{ trans('general.product_description') }}" autofocus style="display: none">{{ $product_description }}</textarea>
                            </div>
                        </div>

                </div>
            </div>


            <div class="card shadow shadow mt-4">
                <div class="card-header">{{ trans('general.images') }}</div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="product-images">{{ trans('general.product_images') }}</label>
                        <div class="input-group">

                            @if(auth()->user()->id == '46285' || auth()->user()->id == '46278' || auth()->user()->id == '1')
                                <input type="file" class="custom-file-input edit_product_images" id="product-images" product-id="{{ $id }}" name="product-images[]"
                                aria-describedby="product-images-preview" accept="image/*,video/mp4" multiple>
                            @else
                                <input type="file" class="custom-file-input edit_product_images" id="product-images" product-id="{{ $id }}" name="product-images[]"
                                aria-describedby="product-images-preview" accept="image/*" multiple>
                            @endif

                            <label class="upload-box1" for="product-images">
                                <span class="upload-text1">{{ trans('general.select_product_images') }}</span>
                            </label>
                            <div class="preview-container-edit-product" id="product-images-preview-edit-product">
                                @foreach($product_images as $p_image)
                                    @if($fileExtension = substr(strrchr($p_image['image_url'], '.'), 1) != 'mp4')
                                        <div class="preview-item-edit-product"><img src="{{ $p_image['image_url'] }}" alt="{{ $p_image['image_url'] }}" style="width: 120px; height: 120px; object-fit: cover; border-radius: 5px; margin-left: 5px; margin-bottom: 5px;"><button type="button" class="btn btn-danger remove-preview-button-edit-product remove-from-storage" image-id="{{ $p_image['id'] }}" style="padding: 2px;">&times;</button></div>
                                    @else
                                        <div class="preview-item-edit-product"><button type="button" class="btn btn-danger remove-preview-button-edit-product remove-from-storage" image-id="{{ $p_image['id'] }}" style="padding: 2px;">&times;</button><video src="{{ $p_image['image_url'] }}" alt="{{ $p_image['image_url'] }}" style="width: 120px; height: 120px; object-fit: cover; border-radius: 5px; margin-left: 5px; margin-bottom: 5px;"></video></div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <small class="form-text text-muted">{{ trans('general.upload_product_images') }}</small>
                    </div>
                </div>
            </div>


            <div class="card shadow shadow mt-4">
                <div class="card-header">{{ trans('general.pricing') }}</div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="product-images">{{ trans('general.base_price_and_discount') }}</label>
                        <div class="input-group mb-4">
                            <div class="input-group-prepend">
                                <span class="input-group-text">&euro;</span>
                            </div>
                            <input type="number" class="form-control" name="base_price" id="base_price" value="{{ $base_price }}" aria-label="Base price" placeholder="{{ trans('general.base_price') }}" step="0.01" min="0">
                            <div class="input-group-prepend">
                                <span class="input-group-text ml-4">%</span>
                            </div>
                            <input type="number" class="form-control" name="base_price_discount" id="base_price_discount" value="{{ $base_price_discount }}" aria-label="Base price disount" placeholder="{{ trans('general.base_price_discount') }}" step="0.01" min="0">
                        </div>
                        <label for="product-images">{{ trans('general.sku') }}</label>
                        <input type="text" class="form-control" name="product_sku" id="product_sku" value="{{ $product_sku }}" aria-label="Product SKU" placeholder="{{ trans('general.product_sku') }}">
                    </div>
                </div>
            </div>


            <div class="card shadow mt-4">
              <div class="card-header">{{ trans('general.variants') }}</div>
              <div class="card-body">
                <div class="row">
                    <div class="table-responsive-sm table-responsive-md table-responsive-xl w-100">
                <table id="edit_prod_variants" class="table table-striped">
    <thead>
        <tr>
            <th>{{ trans('general.size') }}</th>
            <th>{{ trans('general.price') }}</th>
            <th>{{ trans('general.quantity') }}</th>
            <th>{{ trans('general.color') }}</th>
            <th>{{ trans('general.material') }}</th>
            <th>{{ trans('general.action') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($product_variants as $variant)
        <tr class="variant-here" variant-id="{{ $variant['id'] }}">
            <td class="variant-size" size-id="{{ $variant['id'] }}">{{ $variant['size'] }}</td>
            <td class="variant-price" price-id="{{ $variant['id'] }}">{{ $variant['price'] }}</td>
            <td class="variant-quantity" quantity-id="{{ $variant['id'] }}">{{ $variant['quantity'] }}</td>
            <td class="variant-color" color-id="{{ $variant['id'] }}">
                {{ $variant['color'] }}
            </td>
            <td class="variant-material" material-id="{{ $variant['id'] }}">{{ $variant['material'] }}</td>
            <td style="display: flex;">
                <a class="btn btn-secondary edit-variant-btn" style="height: 38px; width: 38px; padding-top: 8px; margin-right:5px;" data-edit="{{ $variant['id'] }}"><i class="fas fa-edit"></i></a>
                <a class="btn btn-danger delete-variant-btn" delete-id="{{ $variant['id'] }}">X</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>

                </div>
                @if(!auth()->user()->isDropshipping())
                    <a class="btn btn-primary float-left mt-4" id="edit_prod_manual_variant">
                        {{ trans('general.add_new_variant') }}
                    </a>
                @endif

              </div>
              </div>
            </div>
        <!-- end main -->


        <!-- sidebar -->
        <div class="col-12 col-sm-12 col-md-12 col-lg-4 sidebar">


            <div class="card shadow d-none d-sm-none d-md-none d-lg-block">
                <div class="card-header">{{ trans('general.public_btn') }}</div>
                <div class="card-body">
                    <i class="fas fa-check-circle" style="color:green;"></i> {{ trans('general.status') }}: {{ trans('general.public') }}<br>
                    <i class="fas fa-eye" style="color:green;"></i> {{ trans('general.visibility') }}: {{ trans('general.everyone') }}
                    <button type="submit" class="btn btn-primary float-right">
                        {{ trans('general.update') }}
                    </button>
                </div>
            </div>


            <div class="card shadow mt-4">
                <div class="card-header">{{ trans('general.organization') }}</div>
                <div class="card-body">
                    <span>{{ trans('general.category') }}</span>
                    <div class="form-group">
                        <select class="form-control" id="category" name="category">
                            <option disabled selected>{{ trans('general.choose_category') }}</option>
                            @foreach ($shop_categories as $cat)
                                <option @if($cat->id == $product_category) selected @endif value="{{ $cat->id }}">{{ $cat->label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <span>{{ trans('general.sub_category') }}</span>
                    <div class="form-group">
                        <select class="form-control" id="sub_category" name="sub_category">
                            <option value="" selected>{{ trans('general.choose_sub_category') }}</option>
                            @foreach ($shop_categories as $categories)
                                <optgroup id="category_{{ $categories->id }}" label="{{ $categories->label }}">
                                    @foreach ($categories->subCategories as $cat)
                                        <option @if($cat->id == $product_admin_menu_item_id) selected @endif value="{{ $cat->id }}">{{ $cat->label }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <span>{{ trans('general.tags') }}</span>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <input id="product_seo_keywords" type="name"
                                class="form-control @error('product_seo_keywords') is-invalid @enderror"
                                name="product_seo_keywords" value="{{ $product_seo_keywords }}" required autocomplete="product_seo_keywords"
                                placeholder="{{ trans('general.tags_placeholder') }}" autofocus>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card shadow mt-4">
                <div class="card-header">{{ trans('general.featured_image') }}</div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="images">{{ trans('general.product_image') }}</label>
                        <div class="input-group">
                            <input type="file" class="custom-file-input edit_product_featured_image" id="featured_image" product-id="{{ $id }}" name="featured_image"
                                aria-describedby="images" accept="image/*" style="display: none;">
                            <label class="upload-box" for="featured_image">
                                <span class="upload-text">{{ trans('general.choose_image') }}</span>
                                <img class="preview-image" src="{{ $featured_image }}" alt="Preview Image" />
                                <button class="remove-preview-button" featured-image-id="{{ $id }}" type="button">×</button>
                            </label>
                        </div>
                        <small class="form-text text-muted">{{ trans('general.upload_best_product_image') }}</small>
                    </div>
                </div>
            </div>


            <!-- display on smaller devices -->
            <div class="card shadow d-md-block d-sm-block d-xs-block d-l-none d-xl-none mt-4 mb-4">
                <div class="card-header">Publish</div>
                <div class="card-body">
                    <i class="fas fa-check-circle" style="color:green;"></i> {{ trans('general.status') }}: {{ trans('general.public') }}<br>
                    <i class="fas fa-eye" style="color:green;"></i> {{ trans('general.visibility') }}: {{ trans('general.everyone') }}
                    <button type="submit" class="btn btn-primary float-right">
                        {{ trans('general.update') }}
                    </button>
                </div>
            </div>

            </form><!-- close form post request -->
        </div>
        <!-- end sidebar -->

    </div>
    </div>
    </div>
    @include('includes.new_product_scripts')
<script>
    $('.preview-image').show();
    $('.upload-text').addClass('d-none');
    $('.remove-preview-button').removeClass('d-none').show();
    // Retrieve the value from the database
    var productDescription = {!! json_encode($product_description) !!};

    // Set the contents of the existing Quill instance
    quill.root.innerHTML = productDescription;

    //update the quill editor before submiting the form
    $('form').on('submit', function() {
        var productDescription = quill.root.innerHTML;
        $('#product_description_input').val(productDescription);
    });

</script>


<script>

$(document).ready(function() {
  $('#edit_product_url_btn').click(function() {
    $('#product_url').hide();
    $('#edit_product_url_btn').hide();
    $('#product_url_edit_field').css('display', 'flex');
  });

  $('#cancel_edit_product_url_btn').click(function() {
    $('#product_url_edit_field').hide();
    $('#product_url').show();
    $('#edit_product_url_btn').show();
  });

  $('#save_product_url').click(function() {
    var productUrl = $('#product_url_edit').val();
    if (productUrl.length >= 4 && productUrl.length <= 191) {
      $(this).html("{{ trans('general.saving') }}");
      $(this).attr('disabled', true);

      $.ajax({
        type: 'POST',
        url: "{{ route('check_and_save_product_url', $id) }}",
        data: {
          product_url: productUrl,
          _token: "{{ csrf_token() }}"
        },
        success: function(response) {
          if (response === 'url_already_in_use') {
            alert("{{ trans('general.product_url_already_in_use') }}");
          } else {
            $('#product_url').html("{{$shop_url}}/product/"+response);
            $('#product_url').attr('href', "{{$shop_url}}/product/"+response);
            $('#product_url_edit_field').hide();
            $('#product_url').show();
            $('#edit_product_url_btn').show();
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {

        },
        complete: function() {
          $('#save_product_url').html("{{ trans('general.save') }}");
          $('#save_product_url').attr('disabled', false);
        }
      });
    } else {
      alert("{{ trans('general.product_url_max_char') }}");
    }
  });
});



//remove image from storage and DB
$('#product-images-preview-edit-product').on('click', '.remove-from-storage', function() {
    var imageId = $(this).attr('image-id');
    $.ajax({
        url: "{{ route('delete_product_image', ':id') }}".replace(':id', imageId),
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}"
        },
        success: function(response) {
            // Handle successful response here
        },
        error: function(xhr) {
            // Handle error response here
        }
    });
});



//remove featured image from storage and DB
$('.remove-preview-button').on('click', function(){
    var featuredImageId = $(this).attr('featured-image-id');
    $.ajax({
        url: "{{ route('delete_featured_image', ':id') }}".replace(':id', featuredImageId),
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}"
        },
        success: function(response) {
            // Handle successful response here
        },
        error: function(xhr) {
            // Handle error response here
        }
    });
});


//upload featured image for the product
$('.edit_product_featured_image').on('change', function() {
  var id = $(this).attr('product-id');
  var formData = new FormData();
  formData.append('image', $(this)[0].files[0]);
  var token = $('meta[name="csrf-token"]').attr('content');
  $.ajax({
    url: "{{ route('edit_product_featured_image', ':id') }}".replace(':id', id),
    type: 'POST',
    headers: {
        'X-CSRF-TOKEN': token
    },
    data: formData,
    processData: false,
    contentType: false,
    success: function(response) {
      // Handle success response
    },
    error: function(xhr, status, error) {
      // Handle error response
    }
  });
});


//upload product images
$('.edit_product_images').on('change', function(){
    var id = $(this).attr('product-id');
    var formData = new FormData();
    var files = $(this)[0].files;
    for (var i = 0; i < files.length; i++) {
        formData.append('product-images[]', files[i]);
    }
    var token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: "{{ route('edit_product_images', ':id') }}".replace(':id', id),
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': token
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            // Handle success response
            var previewTemplate = '';
            response.data.forEach(function(item) {
                var img_n = item.image_name;
                var extension = img_n.slice((img_n.lastIndexOf(".") - 1 >>> 0) + 2);
                if(extension != 'mp4'){
                    previewTemplate += '<div class="preview-item-edit-product"><img src="'+item.image_name+'" alt="'+item.image_name+'" style="width: 120px; height: 120px; object-fit: cover; border-radius: 5px; margin-left: 5px; margin-bottom: 5px;"><button type="button" class="btn btn-danger remove-preview-button-edit-product remove-from-storage" image-id="'+item.image_id+'" style="padding: 2px;">&times;</button></div>';
                }else{
                    previewTemplate += '<div class="preview-item-edit-product"><button type="button" class="btn btn-danger remove-preview-button-edit-product remove-from-storage" image-id="'+item.image_id+'" style="padding: 2px;">&times;</button><video src="'+item.image_name+'" alt="'+item.image_name+'" style="width: 120px; height: 120px; object-fit: cover; border-radius: 5px; margin-left: 5px; margin-bottom: 5px;"></div></video>';
                }
                
            });
            $('#product-images-preview-edit-product').append(previewTemplate);
        },
        error: function(xhr, status, error) {
        // Handle error response
        }
    });
});


$(document).on('click', '.remove-preview-button-edit-product', function() {
    $(this).parent('.preview-item-edit-product').remove();
});



$(document).on('click', '.edit-variant-btn', function(){
    var edit_id = $(this).attr('data-edit');
    var variant_id = $(this).attr('variant-id');

    // Replace the edit button with a save button
    $(this).removeClass('btn-secondary edit-variant-btn').addClass('btn-success save-variant-btn').attr('edit_id', edit_id).html('<i class="fas fa-save"></i>');

    $('td[size-id="'+edit_id+'"], td[price-id="'+edit_id+'"], td[quantity-id="'+edit_id+'"], td[color-id="'+edit_id+'"], td[material-id="'+edit_id+'"]').each(function() {
        var column = $(this);
        var text = column.text().trim(); // use the trim() method to remove leading/trailing spaces
        column.html('<input type="text" class="form-control" value="'+text+'" />');
    });
});

$(document).ready(function() {
    $( "optgroup" ).each(function( index ) {
        if( this.id != "category_"+$('#category').val() )
            $(this).hide();
        else
            $(this).show();
    });
});

$('#category').on('change', function(){
    $( "optgroup" ).each(function( index ) {
        if( this.id != "category_"+$('#category').val() )
            $(this).hide();
        else
            $(this).show();
    });
    $('#sub_category').prop("selectedIndex", 0).change();
});

$(document).on('click', '.save-variant-btn', function(){
    var edit_id = $(this).attr('edit_id');
    var data = {};

    $('td[size-id="'+edit_id+'"], td[price-id="'+edit_id+'"], td[quantity-id="'+edit_id+'"], td[color-id="'+edit_id+'"], td[material-id="'+edit_id+'"]').each(function() {
        var column = $(this);
        var key = column.attr('class').replace('-','_');
        var value = column.find('input').val();
        data[key] = value;
        column.html(value);
    });

    $.ajax({
        url: "{{ route('edit_variant', ['id' => ':id']) }}".replace(':id', edit_id),
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: data,
        success: function(response) {

        },
        error: function(xhr) {
                // Parse the responseJSON to extract the validation errors
                const errors = xhr.responseJSON.errors;

                // Iterate through the validation errors and concatenate them into a single string
                let errorMessage = '';
                for (let field in errors) {
                    errorMessage += errors[field][0] + '\n';
                }

                // Display the concatenated string in a SweetAlert toast
                Swal.fire({
                    icon: 'error',
                    title: "{{ trans('general.alert_title_validation_error') }}",
                    text: errorMessage,
                });
        }
    });

    $(this).removeClass('btn-success save-variant-btn').addClass('btn-secondary edit-variant-btn').html('<i class="fas fa-edit"></i>');
});



$(document).on('click', '.delete-variant-btn', function() {
    var variantId = $(this).attr('delete-id');

    $.ajax({
        url: "{{ route('delete_variant', ':variantId') }}".replace(':variantId', variantId),
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
        },
        success: function(response) {
            if(response.success){
                // handle success response
                $('.variant-here[variant-id="'+variantId+'"]').remove();

                if ($('#edit_prod_variants').find('tr').length == 1) {
                    $('#edit_prod_variants').hide();
                }
            }else{
                Swal.fire({
                    icon: 'error',
                    title: "{{ trans('general.there_was_an_error') }}",
                    text: response.message,
                });
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            // handle error response

        }
    });
});


$(document).on('click', '#edit_prod_manual_variant', function (e){
    $('#edit_prod_variants').show();
    e.preventDefault();
    // Generate a random ID
    var variantId = Math.floor(Math.random() * 1000000);

    // Create a new <tr> element with the variant data and the generated ID
    var newRow = $('<tr class="variant-here" variant-id="' + variantId + '">' +
                    '<td><input type="text" class="form-control variant-size" size-id="' + variantId + '" placeholder="' + "{{ trans('general.size') }}" + '"></td>' +
                    '<td><input type="text" class="form-control variant-price" price-id="' + variantId + '" placeholder="' + "{{ trans('general.price') }}" + '"></td>' +
                    '<td><input type="text" class="form-control variant-quantity" quantity-id="' + variantId + '" placeholder="' + "{{ trans('general.quantity') }}" + '"></td>' +
                    '<td><input type="text" class="form-control variant-color" color-id="' + variantId + '" placeholder="' + "{{ trans('general.color') }}" + '"></td>' +
                    '<td><input type="text" class="form-control variant-material" material-id="' + variantId + '" placeholder="' + "{{ trans('general.material') }}" + '"></td>' +
                    '<td style="display: flex;">' +
                        '<a class="btn btn-success save-manual-variant-btn" style="height: 38px; width: 38px; padding-top: 8px; margin-right:5px;" data-edit="' + variantId + '"><i class="fas fa-save"></i></a>' +
                        '<a class="btn btn-danger delete-unsaved-variant-btn" delete-id="' + variantId + '">X</a>' +
                    '</td>' +
                '</tr>');

    // Append the new row to the table
    $('#edit_prod_variants tbody').append(newRow);


});



$(document).on('click', '.save-manual-variant-btn', function () {
    // Get the variant ID from the button's data attribute
    var variantId = $(this).attr('data-edit');

    // Get the input values for this variant ID
    var size = $('input.variant-size[size-id="' + variantId + '"]').val();
    var price = $('input.variant-price[price-id="' + variantId + '"]').val();
    var quantity = $('input.variant-quantity[quantity-id="' + variantId + '"]').val();
    var color = $('input.variant-color[color-id="' + variantId + '"]').val();
    var material = $('input.variant-material[material-id="' + variantId + '"]').val();

    // Send a POST request to the insert_new_variant route with the variant data
    $.ajax({
        url: '{{ route('insert_new_variant', $id) }}',
        method: 'POST',
        data: {
            '_token': '{{ csrf_token() }}',
            'size': size,
            'price': price,
            'quantity': quantity,
            'color': color,
            'material': material,
            'variant_id': variantId
        },
        success: function (response) {
            if(response.success){
                $('.variant-here[variant-id="'+variantId+'"]').remove();
                // Create a new <tr> element with the variant data and the generated ID
                var newRow = $('<tr class="variant-here" variant-id="' + response.variant.id + '">' +
                        '<td class="variant-size" size-id="'+response.variant.id+'">'+response.variant.size+'</td>' +
                        '<td class="variant-price" price-id="'+response.variant.id+'">'+response.variant.price+'</td>' +
                        '<td class="variant-quantity" quantity-id="'+response.variant.id+'">'+response.variant.quantity+'</td>' +
                        '<td class="variant-color" color-id="'+response.variant.id+'">'+response.variant.color+'</td>' +
                        '<td class="variant-material" material-id="'+response.variant.id+'">'+response.variant.material+'</td>' +
                        '<td style="display: flex;">' +
                            '<a class="btn btn-secondary edit-variant-btn" style="height: 38px; width: 38px; padding-top: 8px; margin-right:5px;" data-edit="'+response.variant.id+'"><i class="fas fa-edit"></i></a>' +
                            '<a class="btn btn-danger delete-variant-btn" delete-id="'+response.variant.id+'">X</a>' +
                        '</td>' +
                    '</tr>');

                // Append the new row to the table
                $('#edit_prod_variants tbody').append(newRow);
            }
        },
        error: function(xhr, status, error) {
            // handle error response
            // Parse the responseJSON to extract the validation errors
            const errors = xhr.responseJSON.errors;

            // Iterate through the validation errors and concatenate them into a single string
            let errorMessage = '';
            for (let field in errors) {
                errorMessage += errors[field][0] + '\n';
            }

            // Display the concatenated string in a SweetAlert toast
            Swal.fire({
                icon: 'error',
                title: "{{ trans('general.alert_title_validation_error') }}",
                text: errorMessage,
            });
        }
    });
});




//delete unsaved variant
$(document).on('click', '.delete-unsaved-variant-btn', function(){
    var variantId = $(this).attr('delete-id');
    $('.variant-here[variant-id="'+variantId+'"]').remove();
    if ($('#edit_prod_variants').find('tr').length == 1) {
        $('#edit_prod_variants').hide();
    }
});


@if(empty($product_variants))
    $('#edit_prod_variants').hide();
@endif

@if(!$featured_image)
    $(document).ready(function() {
        $('.preview-image').attr('src', '').hide();
        $('.upload-text').removeClass('d-none');
        $('.remove-preview-button').addClass('d-none');
        $('#featured_image').val('');
    });
@endif


</script>


@endsection