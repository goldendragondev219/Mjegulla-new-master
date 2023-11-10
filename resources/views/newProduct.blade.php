@extends('layouts.app')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css">
<title>{{trans('general.create_new_product')}} | {{ config('app.name') }} </title>
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

@if($shop_categories->isEmpty())
<div class="alert alert-danger">
    {{ trans('general.create_category_before_adding_product_info') }}
</div>
@endif

    <div class="row">
        <!-- start main -->
        <div class="col-12 col-sm-12 col-md-12 col-lg-8 mb-4 main-content">
            <div class="card shadow">
                <div class="card-header">{{ trans('general.create_new_product') }}</div>
                <div class="card-body">
                    <!-- start form for post request -->
                    <form id="new_product_form" method="POST" action="{{ route('create_product', $id) }}" enctype="multipart/form-data">
                        @csrf
                        <span>{{ trans('general.title') }}</span>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="product_name" type="name"
                                    class="form-control @error('product_name') is-invalid @enderror" name="product_name"
                                    value="" required autocomplete="product_name" placeholder="{{ trans('general.title') }}" autofocus>
                            </div>
                        </div>
                        <span>{{ trans('general.description') }}</span>
                        <div class="form-group row">
                            <div class="col-md-12 mb-5">
                                <div id="product_description"></div>
                                <textarea id="product_description_input" type="text"
                                    class="form-control @error('product_description') is-invalid @enderror" name="product_description_input" value=""
                                    required autocomplete="product_description" placeholder="{{ trans('general.product_description') }}" autofocus style="display: none"></textarea>
                            </div>
                        </div>

                </div>
            </div>


            <div class="card shadow mt-4">
                <div class="card-header">{{ trans('general.images') }}</div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="product-images">{{ trans('general.product_images') }}</label>
                        <div class="input-group">
                            <input type="file" class="custom-file-input" id="product-images" name="product-images[]"
                                aria-describedby="product-images-preview" accept="image/*" multiple>
                            <label class="upload-box1" for="product-images">
                                <span class="upload-text1">{{ trans('general.select_product_images') }}</span>
                            </label>
                            <div class="preview-container1" id="product-images-preview1"></div>
                        </div>
                        <small class="form-text text-muted">{{ trans('general.upload_product_images') }}</small>
                    </div>
                </div>
            </div>



            <div class="card shadow mt-4">
                <div class="card-header">{{ trans('general.pricing') }}</div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="product-images">{{ trans('general.base_price_and_discount') }}</label>
                        <div class="input-group mb-4">
                            <div class="input-group-prepend">
                                <span class="input-group-text">&euro;</span>
                            </div>
                            <input type="number" class="form-control" name="base_price" id="base_price" value="" aria-label="Base price" placeholder="{{ trans('general.base_price') }}" step="0.01" min="0">
                            <div class="input-group-prepend">
                                <span class="input-group-text ml-4">%</span>
                            </div>
                            <input type="number" class="form-control" name="base_price_discount" id="base_price_discount" value="" aria-label="Base price disount" placeholder="{{ trans('general.base_price_discount') }}" step="0.01" min="0">
                        </div>
                        <label for="product-images">SKU</label>
                        <input type="text" class="form-control" name="product_sku" id="product_sku" value="" aria-label="Product SKU" placeholder="{{ trans('general.product_sku') }}">
                    </div>
                    <span>{{ trans('general.discount_only_without_variant_info') }}</span>
                </div>
            </div>



            <div class="card shadow mt-4">
              <div class="card-header">{{ trans('general.variants') }}</div>
              <div class="card-body">

<div class="container">
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label for="variant">{{ trans('general.variant') }}:</label>
        <select class="form-control" id="variant">
          <option value="" disabled="disabled" selected>{{ trans('general.select_variant') }}</option>
          <option value="size">{{ trans('general.size') }}</option>
          <option value="color">{{ trans('general.color') }}</option>
          <option value="material">{{ trans('general.material') }}</option>
        </select>
      </div>
    </div>
    <div class="col-md-6 custom-tags">
      <div class="form-group">
        <label for="tags" id="custom_tags">Custom Tags:</label>
        <textarea class="form-control" id="tags" rows="4"></textarea>
      </div>
      <div id="tag-container"></div>
    </div>
    <div class="table-responsive-sm table-responsive-md table-responsive-xl">
    <table id="variant-combinations" class="table table-striped">
      <thead>
        <tr>
          <th class="size-header">{{ trans('general.size') }}</th>
          <th class="price-header">{{ trans('general.price') }}</th>
          <th class="quantity-header">{{ trans('general.quantity') }}</th>
          <th class="color-header">{{ trans('general.color') }}</th>
          <th class="material-header">{{ trans('general.material') }}</th>
          <th class="remove-variant-header">{{ trans('general.remove') }}</th>
        </tr>
      </thead>
      <tbody>
        <!-- Variant combinations will be added dynamically here -->
      </tbody>
    </table>
    </div>
  </div>
  <span>{{ trans('general.variants_info') }}</span>
</div>


              </div>
              </div>
            </div>
        <!-- end main -->


        <!-- sidebar -->
        <div class="col-12 col-sm-12 col-md-12 col-lg-4 sidebar">
            <div class="card shadow d-none d-sm-none d-md-none d-lg-block">
                <div class="card-header">{{ trans('general.public_btn') }}</div>
                <div class="card-body">
                    <i class="fas fa-check-circle"></i> {{ trans('general.status') }}: {{ trans('general.public') }}<br>
                    <i class="fas fa-eye"></i> {{ trans('general.visibility') }}: {{ trans('general.everyone') }}
                    <button type="submit" id="publish_button" class="btn btn-primary float-right">
                        {{ trans('general.public_btn') }}
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
                                <option value="{{ $cat->id }}">{{ $cat->label }}</option>
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
                                        <option value="{{ $cat->id }}">{{ $cat->label }}</option>
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
                                name="product_seo_keywords" value="" required autocomplete="product_seo_keywords"
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
                            <input type="file" class="custom-file-input" id="featured_image" name="featured_image"
                                aria-describedby="images" accept="image/*" style="display: none;">
                            <label class="upload-box" for="featured_image">
                                <span class="upload-text">{{ trans('general.choose_image') }}</span>
                                <img class="preview-image" src="" alt="Preview Image" />
                                <button class="remove-preview-button" type="button">×</button>
                            </label>
                        </div>
                        <small class="form-text text-muted">{{ trans('general.upload_best_product_image') }}</small>
                    </div>
                </div>
            </div>

            <!-- display on smaller devices -->
            <div class="card shadow d-md-block d-sm-block d-xs-block d-l-none d-xl-none mt-4 mb-4">
                <div class="card-header">{{ trans('general.public_btn') }}</div>
                <div class="card-body">
                    <i class="fas fa-check-circle"></i> {{ trans('general.status') }}: {{ trans('general.public') }}<br>
                    <i class="fas fa-eye"></i> {{ trans('general.visibility') }}: {{ trans('general.everyone') }}
                    <button type="submit" id="publish_button" class="btn btn-primary float-right">
                        {{ trans('general.public_btn') }}
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
@endsection