<script type="application/javascript" src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script type="application/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>

<script>
$(document).ready(function() {
  $('.collapse').collapse();
});
</script>

<script src="https://cdn.quilljs.com/1.3.7/quill.js"></script>
<script>
    var quill = new Quill('#product_description', {
        theme: 'snow'
    });
    var product_description_input = document.querySelector('#product_description_input');
    quill.on('text-change', function() {
        product_description_input.value = quill.root.innerHTML;
    });
</script>

<!-- featured image -->
<script type="application/javascript">
$(document).on('change', '#featured_image', function (event) {
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
});
</script>

<!-- multiple product images -->
<script>
$(document).on('change', '#product-images', function() {
    var previewContainer = $('#product-images-preview1');
    var files = $(this)[0].files;
    for (var i = 0; i < files.length; i++) {
        var file = files[i];
        var reader = new FileReader();
        reader.onload = function(e) {
            var imgSrc = e.target.result;
            var previewTemplate = `<div class="preview-item1"><img src="${imgSrc}" alt="${file.name}" style="width: 120px; height: 120px; object-fit: cover; border-radius: 5px; margin-left: 5px; margin-bottom: 5px;"><button type="button" class="btn btn-danger remove-preview-button1" style="padding: 2px;">&times;</button></div>`;
            previewContainer.append(previewTemplate);
        }
        reader.readAsDataURL(file);
    }
});

$(document).on('click', '.remove-preview-button1', function() {
    $(this).parent('.preview-item1').remove();
});
</script>


<script>


$('#add_manual_variant').on('click', function(e){
  e.preventDefault();
  var randomTag = Math.floor(Math.random() * 9999999) + 9999;
  $('<tr>').append($('<td>').addClass('size-' + randomTag).addClass('remove-' + randomTag).html($('<input>').addClass('form-control').attr('type', 'text').val('')))
                    .append($('<td>').addClass('price-' + randomTag).addClass('remove-' + randomTag).html($('<input>').addClass('form-control').attr('type', 'number').attr('step', '0.01').val('')))
                    .append($('<td>').addClass('quantity-' + randomTag).addClass('remove-' + randomTag).html($('<input>').addClass('form-control').attr('type', 'number').prop('required', true).val('')))
                    .append($('<td>').addClass('color-' + randomTag).addClass('remove-' + randomTag).html($('<input>').addClass('form-control').attr('type', 'text').val('')))
                    .append($('<td>').addClass('material-' + randomTag).addClass('remove-' + randomTag).html($('<input>').addClass('form-control').attr('type', 'text').val('')))
                    .append($('<td>').addClass('remove-variant-' + randomTag).addClass('remove-' + randomTag).html('<a class="btn btn-danger delete-tag-btn" onclick="removeVariant('+randomTag+')">X</a>'))
                    .appendTo('#variant-combinations tbody');
});



function removeVariant(id){
  //check how many columns are there, to hide button and the table header.
  var numberOfVariants = $("#variant-combinations").find("tr").length;
  if(numberOfVariants == 2){
    //empty the arrays
    sizes = [];
    colors = [];
    materials = [];
    $('.size-header, .color-header, .material-header, .price-header, .quantity-header, #add_manual_variant, .remove-variant-header').hide();
  }
  //remove the column
  $('.remove-'+id).closest('tr').remove(); // remove only the tr containing the element with the specific id
  //remove empty table tr
  $("#variant-combinations tr").filter(function() {
    return $.trim($(this).text()) === "";
  }).remove();
}



var variant_option_selected = '';
var sizes = [];
var colors = [];
var materials = [];

$('#variant').change(function() {
  variant_option_selected = $(this).val();
  $('.custom-tags').show();
  $('#custom_tags').html("{{ trans('general.add') }} "+variant_option_selected+" {{ trans('general.separated_by_comma') }}");
  switch (variant_option_selected){
    case 'size':
      $('#tags').attr("placeholder", "{{ trans('general.sizes_placeholder') }}");
      break;
    case 'color':
      $('#tags').attr("placeholder", "{{ trans('general.colors_placeholder') }}");
      break;
    case 'material':
      $('#tags').attr("placeholder", "{{ trans('general.material_placeholder') }}");
  }
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

$(document).ready(function() {
  $('.size-header, .color-header, .material-header, .price-header, .quantity-header, #add_manual_variant, .remove-variant-header, .custom-tags').hide();
  $('#tags').on('keyup', function(e) {
    if (e.keyCode === 188) { // check for comma key
      var tag = $(this).val().slice(0, -1); // remove comma from tag
      var tagElement = $('<span>', {
        class: 'tag badge badge-secondary mr-2',
        text: tag
      });
      var removeButton = $('<button>', {
        class: 'btn btn-sm btn-danger ml-3',
        style: 'border-radius: 50%; font-size: 10px;',
        text: 'x'
      }).appendTo(tagElement);
      removeButton.on('click', function() {
        $(this).parent().remove();
      });
      switch (variant_option_selected) {
        case 'size':
          $('.size-header, .color-header, .material-header, .price-header, .quantity-header, #add_manual_variant, .remove-variant-header').show();
          if (sizes.indexOf(tag) === -1) {
            sizes.push(tag);
            var randomTag = Math.floor(Math.random() * 9999999) + 9999;
            $('<tr>').append($('<td>').attr('name', 'variants[size]').addClass('size-' + tag).addClass('remove-' + randomTag).html($('<input>').addClass('form-control').attr('type', 'text').val(tag)))
                    .append($('<td>').attr('name', 'variants[price]').addClass('price-' + tag).addClass('remove-' + randomTag).html($('<input>').addClass('form-control').attr('type', 'number').attr('step', '0.01').val('')))
                    .append($('<td>').attr('name', 'variants[quantity]').addClass('quantity-' + tag).addClass('remove-' + randomTag).html($('<input>').addClass('form-control').attr('type', 'number').prop('required', true).val('')))
                    .append($('<td>').attr('name', 'variants[color]').addClass('color-' + tag).addClass('remove-' + randomTag).html($('<input>').addClass('form-control').attr('type', 'text').val('')))
                    .append($('<td>').attr('name', 'variants[material]').addClass('material-' + tag).addClass('remove-' + randomTag).html($('<input>').addClass('form-control').attr('type', 'text').val('')))
                    .append($('<td>').addClass('remove-variant-' + tag).addClass('remove-' + randomTag).html("{{ trans('general.remove_variant_after_publication') }}"))
                    .appendTo('#variant-combinations tbody');
          }
          break;
        case 'color':
          // $('.color-header').show();
          // colors.push(tag);
          // $('.color-' + sizes.join(',.color-')).html($('<input>').addClass('form-control').attr('type', 'text').val(colors.join('/')));


          $('.color-header').show();
            if (colors.indexOf(tag) === -1) {
              colors.push(tag);
              if (colors.length === 1) {
                $('.color-' + sizes.join(',.color-')).html($('<input>').addClass('form-control').attr('type', 'text').val(tag));
              } else {

                var existingRows = $('#variant-combinations tbody tr').slice(0, sizes.length);
                var newRows = existingRows.clone();

                newRows.find('td:nth-child(4)').addClass('color-' + tag).html($('<input>').addClass('form-control').attr('type', 'text').val(tag));

                newRows.find('td:nth-child(3)').each(function(index) {
                  var randomTag = Math.floor(Math.random() * 9999999) + 9999;
                  var colorInputs = $(existingRows[index]).find('td:nth-child(3) input');
                  var clonedInputs = colorInputs.clone();
                  newRows.find('[class^="remove-"]').attr('class', function(i, className) {
                    return className.replace(/^remove-/, 'remove-' + randomTag);
                  });
                  newRows.find('td[class^="remove-"]').attr('class', function(i, className) {
                    return className.replace(/^remove-/, 'remove-' + randomTag);
                  });
                  newRows.find('a.delete-tag-btn').attr('onclick', 'removeVariant('+randomTag+')');
                  $(this).html(clonedInputs);
                });
                $('#variant-combinations tbody').append(newRows);

              }
            }


          break;
          case 'material':
            $('.material-header').show();
            if (materials.indexOf(tag) === -1) {
              materials.push(tag);
              if (materials.length === 1) {
                $('.material-' + sizes.join(',.material-')).html($('<input>').addClass('form-control').attr('type', 'text').val(tag));
              } else {

                var existingRows = $('#variant-combinations tbody tr').slice(0, colors.length*sizes.length);
                var newRows = existingRows.clone();

                newRows.find('td:nth-child(5)').addClass('material-' + tag).html($('<input>').addClass('form-control').attr('type', 'text').val(tag));

                newRows.find('td:nth-child(4)').each(function(index) {
                  var randomTag = Math.floor(Math.random() * 9999999) + 9999;
                  var colorInputs = $(existingRows[index]).find('td:nth-child(4) input');
                  var clonedInputs = colorInputs.clone();
                  newRows.find('[class^="remove-"]').attr('class', function(i, className) {
                    return className.replace(/^remove-/, 'remove-' + randomTag);
                  });
                  newRows.find('td[class^="remove-"]').attr('class', function(i, className) {
                    return className.replace(/^remove-/, 'remove-' + randomTag);
                  });
                  newRows.find('a.delete-tag-btn').attr('onclick', 'removeVariant('+randomTag+')');
                  $(this).html(clonedInputs);
                });
                $('#variant-combinations tbody').append(newRows);

              }
            }
            break;

          console.log('Invalid variant: ' + variant_option_selected);
          return;
      }
      $(this).val(''); // clear the input
    }
  });
});










$(document).ready(function() {
    $('#new_product_form').on('submit', function(event) {
        event.preventDefault(event);

        const publishBtn = $('#publish_button');
        publishBtn.prop('disabled', true);
        publishBtn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Creating...');


        var variants = [];

        $('#variant-combinations tbody tr').each(function() {
            var variant = {};
            $(this).find('input').each(function() {
                variant[$(this).parent().attr('name')] = $(this).val();
            });

            // check if price value is valid before adding to variants array
            if (variant.hasOwnProperty('variants[price]') && variant['variants[price]'] !== '' && variant['variants[price]'] !== undefined && variant['variants[price]'] !== 0) {
                variants.push(variant);
            }else{
              alert("{{ trans('general.please_enter_valid_price_variant') }}");
              return false;
            }
        });


        var productImages = $('#product-images')[0].files;
        var formData = new FormData();

        for (var i = 0; i < productImages.length; i++) {
            formData.append('product-images[]', productImages[i]);
        }

        formData.append('product_name', $('#product_name').val());
        formData.append('category', $('#category').val());
        formData.append('sub_category', $('#sub_category').val());
        formData.append('product_description_input', $('#product_description_input').val());
        formData.append('product_seo_keywords', $('#product_seo_keywords').val());
        formData.append('featured_image', $('#featured_image')[0].files[0]);
        formData.append('variants', JSON.stringify(variants));
        formData.append('base_price', $('#base_price').val());
        formData.append('base_price_discount', $('#base_price_discount').val());
        formData.append('product_sku', $('#product_sku').val());

        var token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: "{{ route('create_product', $id) }}",
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': token
            },
            contentType: false,
            processData: false,
            success: function(response) {
              publishBtn.html("{{ trans('general.public_btn') }}");
              publishBtn.prop('disabled', false);
              if(response.status == 'success'){
                window.location.href = response.url;
              }
                // handle successful response
                  Swal.fire({
                      icon: response.status,
                      title: response.message,
                      toast: true,
                      position: 'top-end',
                      showConfirmButton: false,
                      timer: 3000
                  });
            },
            error: function(xhr) {
              publishBtn.html("{{ trans('general.public_btn') }}");
              publishBtn.prop('disabled', false);
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
});
















</script>