@extends('layouts.app')
<title>{{trans('general.collections')}} - {{ $collection->name }} | {{ config('app.name') }} </title>
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

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <a type="button" class="btn btn-primary" data-toggle="modal" data-target="#addProductsModal">
        {{ trans('general.add_new_products') }}
    </a>
</div>

<div class="row">
        <div class="col-12">

            <div class="card shadow mb-4">
                <div class="card-header">{{ trans('general.products') }} - {{ $collection->name }}</div>
                <div class="card-body">
                    <!-- no data in array statement-->
                    @if($collection_products->isNotEmpty())
                        <div class="row">
                            @foreach($collection_products as $collection)
                                @foreach($collection->product as $product)
                                    <div class="col-md-3 col-12">
                                        <div class="card mb-4">
                                            <img src="{{ $product->product_single_image }}" class="card-img-top" alt="{{ $product->product_name }}" style="height: 250px; object-fit: cover;">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ strlen($product->product_name) > 30 ? substr($product->product_name, 0, 30) . '...' : $product->product_name }}</h5>
                                                <div class="row">
                                                    <div class="col-10">
                                                        <form action="{{ route('delete_collection_product', ['product_id' => $collection->product_id, 'collection_id' => $collection->collection_id]) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    @else
                    <style>
                      .card{
                        background: transparent;
                        border-style: none; 
                      }
                      .card-header{
                        display: none;
                      }
                    </style>
                    <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; height: 50vh;">
                        <i class="fas fa-shopping-bag" style="font-size: 5rem;"></i>
                        <p style="margin-top: 1rem;">{{ trans('general.no_products_on_collection') }}</p>
                    </div>
                    @endif
                <!-- no data in array statement-->      
                <div class="pagination" style="margin-left: 20px;">
                    {{ $collection_products->links() }}
                </div>          
            </div>
        </div>
</div>


<div class="modal" id="addProductsModal">
    <div class="modal-dialog" style="max-width: 800px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ trans('general.add_new_products') }}</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row" id="productsContainer">
                    @if($my_products->isNotEmpty())
                        @foreach($my_products as $product)
                        <div class="col-lg-3 col-md-3 col-sm-4 col-4">
                            <div class="card mb-4">
                                <img src="{{ $product->product_single_image }}" class="card-img-top" alt="{{ $product->product_name }}" style="height: 150px; object-fit: cover;">
                                <div class="card-body">
                                    <h6 class="card-title">{{ strlen($product->product_name) > 20 ? substr($product->product_name, 0, 20) . '...' : $product->product_name }}</h6>
                                    <div class="row mb-3">
                                        <div class="col-12 mb-3 ml-3">
                                            <input class="form-check-input select_import_product" type="checkbox" value="{{ $product->id }}" id="select_import_product" style="width: 30px; height: 30px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <style>
                        .card{
                            background: transparent;
                            border-style: none; 
                        }
                        .card-header{
                            display: none;
                        }
                        </style>
                        <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; height: 50vh; margin-left: 40%;">
                            <i class="fas fa-shopping-bag" style="font-size: 5rem;"></i>
                            <p style="margin-top: 1rem;">{{ trans('general.no_products') }}</p>
                        </div>
                    @endif
                </div>
            @if($my_products->isNotEmpty())
                <center><button id="loadMoreBtn" class="btn btn-primary loadMoreBtn">{{ trans('general.load_more') }}</button></center>
            @endif
            </div>
            <div class="modal-footer">
                <button type="submit" form="collectionForm" class="btn btn-primary add_products import_selected_products d-none">{{ trans('general.add') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('general.close') }}</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    var page = 1;
    var isLoading = false;
    var products = [];

    // Event delegation for dynamically added elements
    $('#productsContainer').on('change', '.select_import_product', function() {
        var checkboxValue = $(this).val();

        if ($(this).is(':checked')) {
            products.push(checkboxValue);
            Swal.fire({
                toast: true,
                icon: 'success',
                text: "{{ trans('general.product_added_on_import_list') }}",
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        } else {
            var index = products.indexOf(checkboxValue);
            if (index !== -1) {
                products.splice(index, 1);
                Swal.fire({
                    toast: true,
                    icon: 'error',
                    text: "{{ trans('general.product_removed_from_import_list') }}",
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        }

        if (products.length >= 1) {
            $('.add_products').removeClass('d-none');
            var translation = products.length > 1 ? {!! json_encode(__('general.import_selected_products')) !!} : {!! json_encode(__('general.import_selected_product')) !!};
            var text = translation.replace(':total', products.length);
            $('.add_products').html(text);
        } else {
            $('.add_products').addClass('d-none');
        }
    });

    $('#loadMoreBtn').on('click', function() {
        if (!isLoading) {
            isLoading = true;
            page++;

            $.ajax({
                url: "{{ route('get_store_products_in_collections') }}",
                type: 'POST',
                data: {
                    page: page,
                    collection_id: "{{ $collection->id }}",
                },
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                success: function(response) {
                    isLoading = false;
                    var res = response.data;
                    if (res.length > 0) {
                        var htmlContent = '';
                        res.forEach(function(product) {
                            htmlContent += '<div class="col-lg-3 col-md-3 col-sm-4 col-4">';
                            htmlContent += '<div class="card mb-4">';
                            htmlContent += '<img src="' + product.product_single_image + '" class="card-img-top" alt="' + product.product_name + '" style="height: 150px; object-fit: cover;">';
                            htmlContent += '<div class="card-body">';
                            htmlContent += '<h6 class="card-title">' + (product.product_name.length > 20 ? product.product_name.substring(0, 20) + '...' : product.product_name) + '</h6>';
                            htmlContent += '<div class="row mb-3"><div class="col-12 mb-3 ml-3"><input class="form-check-input select_import_product" type="checkbox" value="'+product.id+'" id="select_import_product" style="width: 30px; height: 30px;"></div></div>';
                            htmlContent += '</div></div></div>';
                        });

                        $('#productsContainer').append(htmlContent);
                    } else {
                        $('#loadMoreBtn').hide();
                    }
                },
                error: function() {
                    isLoading = false;
                    console.log('Error fetching products.');
                }
            });
        }
    });


    $('.import_selected_products').on('click', function(){
        $(this).prop('disabled', true);
        $.ajax({
            method: "POST",
            url: "{{ route('import_products_collection') }}",
            data: {
                products: products,
                collection_id: "{{ Illuminate\Support\Str::after(request()->path(), '/') }}"
            },
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            success: function(response){
                // Handle success response here
                $('.import_selected_products').prop('disabled', false);
                $('.select_import_product').prop('checked', false);
                $('.import_selected_products').addClass('d-none');
                products = [];
                if(response.status){
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        text: response.message,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    location.reload();
                }
            },
            error: function(xhr, status, error){
                // Handle error here
                $('.import_selected_products').prop('disabled', false);
                Swal.fire({
                    toast: true,
                    icon: 'error',
                    text: "{{ trans('general.there_was_an_error') }}",
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        });
    });




});

</script>




@endsection
