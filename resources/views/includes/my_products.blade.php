
@if ($products->isNotEmpty())
<div class="table-responsive-sm table-responsive-md table-responsive-xl">
<table class="table table-striped">
  <thead>
    <tr>
      <th>#</th>
      <th>{{ trans('general.image') }}</th>
      <th>{{ trans('general.name') }}</th>
      <th>{{ trans('general.category') }}</th>
      <th>{{ trans('general.sub_category') }}</th>
      <th>{{ trans('general.sku') }}</th>
      <th>{{ trans('general.quantity') }}</th>
      <th>{{ trans('general.sales') }}</th>
      <th>{{ trans('general.views') }}</th>
      <th>{{ trans('general.action') }}</th>
    </tr>
  </thead>
  <tbody>
        @foreach ($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td><img class="card-img-top" src="{{ $product->product_single_image ?? '' }}" alt="{{ $product->product_name ?? '' }}" style="height: 100px; width:auto;"></td>
                <td>{{ $product->product_name }}</td>
                <td>
                    @php
                        $category = \App\Models\MenuItem::find($product->product_category);
                    @endphp
                    {{ $category ? $category->label : '-' }}
                </td>
                <td>
                    @php
                        $sub_category = \App\Models\MenuItem::find($product->admin_menu_item_id);
                    @endphp
                    {{ $sub_category ? $sub_category->label : '-' }}
                </td>
                <td>{{ $product->product_sku }}</td>
                <td>{{ $product->quantity }}</td>
                <td>{{ $product->product_sales }}</td>
                <td>{{ $product->product_views }}</td>
                <td class="d-flex">
                    <a href="{{$shop_url}}/product/{{ $product->product_url }}" class="btn btn-secondary mr-2" data-toggle="tooltip" data-placement="top" title="{{ trans('general.view') }}" target="_blank"><i class="fas fa-eye"></i></a>
                    <a href="{{ route('manage_product', $product->id) }}" class="btn btn-primary mr-2" data-toggle="tooltip" data-placement="top" title="{{ trans('general.edit') }}"><i class="fas fa-edit"></i></a>
                    <a href="#" class="btn btn-danger" data-id="{{ $product->id }}" data-toggle="modal" data-target="#deleteProductModal"><i class="fas fa-trash-alt"></i></a>
                </td>
            </tr>
        @endforeach
        </tbody>
</table>
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
        <i class="fas fa-box" style="font-size: 5rem;"></i>
        <p style="margin-top: 1rem;">{{ trans('general.no_products') }}</p>
    </div>
    @endif
    <div class="pagination" style="margin-left: 20px;">
        {{ $products->links('vendor.pagination.default') }}
    </div>