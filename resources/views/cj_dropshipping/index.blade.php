@extends('layouts.app')
<title>{{trans('general.cj_dropshipping_menu_edit')}} | {{ config('app.name') }} </title>
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
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header">{{ trans('general.categories') }}</div>
            <ul class="navbar-nav" style="background-color: #4e73df; border-radius: 5px;">
            @if(isset($cj_categories['data']))
                @foreach($cj_categories['data'] as $categoryFirst)
                    <li class="nav-item" style="padding-left: 20px; border: 1px dotted white; background-color: {{ request('cid') === $categoryFirst['categoryFirstId'] ? 'grey' : '#4e73df' }}">
                        <a class="nav-link" href="?cid={{ $categoryFirst['categoryFirstId'] }}&page=1" style="color: white;">
                            {{ $categoryFirst['categoryFirstName'] }}
                        </a>
                    </li>
                @endforeach
            @endif
            </ul>
        </div>
    </div>

        <div class="col-12 col-sm-12 col-md-12 col-lg-8 mb-4 main-content">

            <div class="card shadow mb-4">
                <div class="card-header">{{ trans('general.products') }}</div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-6 col-md-9">
                            <input id="cj_search" type="text" class="form-control @error('cj_search') is-invalid @enderror" name="cj_search" value="{{ old('cj_search', request('cj_search')) }}" placeholder="{{ trans('general.cj_search') }}" autofocus>
                        </div>
                        <div class="col-6 col-md-3">
                            <button class="btn btn-primary w-100" id="cj_search_btn">{{ trans('general.cj_search') }}</button>
                        </div>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="sku" id="sku" value="sku" {{ request('sku') === 'true' ? 'checked' : '' }}>
                        <label class="form-check-label" for="sku">
                            SPU/SKU ({{ trans('general.sku_search_activate') }})
                        </label>
                    </div>
                    <button type="button" class="btn btn-primary import_selected_products mb-3 d-none" id="import_selected_products">Import 1 Selected Product</button>
                    <!-- no data in array statement-->
                    @if(isset($cj_products['data']['list']))
                    <div class="row">
                        @foreach($cj_products['data']['list'] as $product)
                            <div class="col-md-4">
                                <div class="card mb-4">
                                    <img src="{{ $product['productImage'] }}" class="card-img-top" alt="{{ $product['productNameEn'] }}" style="height: 250px; object-fit: cover;">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ strlen($product['productNameEn']) > 30 ? substr($product['productNameEn'], 0, 30) . '...' : $product['productNameEn'] }}</h5>
                                        <p class="card-text" style="font-size: 12px;"><a href="?cid={{ $product['categoryId'] }}&page=1">{{ $product['categoryName'] }}</a></p>
                                        <p class="card-text" style="font-weight: 600;">${{ $product['sellPrice'] }}</p>
                                        <div class="row">
                                            <div class="col-10">
                                                <button class="btn btn-success" onclick="view_product('{{ $product['pid'] }}')">{{ trans('general.view_product') }}</button>
                                            </div>
                                            <div class="col-2">
                                                <input class="form-check-input select_import_product" type="checkbox" value="{{ $product['pid'] }}" id="select_import_product" style="width: 30px; height: 30px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                <!-- Pagination links -->
                <div class="pagination">
                    @if ($cj_products['data']['pageNum'] > 1)
                        <a href="{{ request()->fullUrlWithQuery(['page' => $cj_products['data']['pageNum'] - 1]) }}" class="prev" style="margin-right: 20px;"><< Previous</a>
                    @endif
                    
                    @if ($cj_products['data']['total'] > $cj_products['data']['pageNum'] * $cj_products['data']['pageSize'])
                        <a href="{{ request()->fullUrlWithQuery(['page' => $cj_products['data']['pageNum'] + 1]) }}" class="next">Next >></a>
                    @endif
                </div>
                @endif
                <!-- no data in array statement-->
                <button type="button" class="btn btn-primary import_selected_products d-none" id="import_selected_products">Import 1 Selected Product</button>
            </div>
        </div>
</div>

<!-- Product Details Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- HTML FROM JS -->
            <div class="view_product_html"></div>
        </div>
    </div>
</div>


<script type="application/javascript" src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
<script type="application/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>

<script>
$(document).ready(function() {
    var products = [];
    $('.select_import_product').on('change', function() {
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
            $('.import_selected_products').removeClass('d-none');
            var translation = products.length > 1 ? {!! json_encode(__('general.import_selected_products')) !!} : {!! json_encode(__('general.import_selected_product')) !!};
            var text = translation.replace(':total', products.length);
            $('.import_selected_products').html(text);
        } else {
            $('.import_selected_products').addClass('d-none');
        }



    });



    $('.import_selected_products').on('click', function(){
        $(this).prop('disabled', true);
        $.ajax({
            method: "POST",
            url: "{{ route('cj_products_mass_import') }}",
            data: {
                products: products,
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
