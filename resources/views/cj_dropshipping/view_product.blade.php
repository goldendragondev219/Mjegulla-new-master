<div class="modal-header">
    <h5 class="modal-title" id="productModalLabel">
        @if(isset($cj_product['data']['productNameEn']))
            {{ $cj_product['data']['productNameEn'] }}
        @endif
    </h5>
</div>
<div class="modal-body">


    <div class="row">
        @foreach($cj_product['data']['productImageSet'] as $p_images)
            <div class="col-md-4 col-6">
                <img src="{{ $p_images }}" style="width: 200px; height: 200px; object-fit:contain; border-radius: 5px; margin-left: 5px; margin-bottom: 5px;">
            </div>
        @endforeach
    </div>

    <ul class="nav nav-tabs mb-2 mt-2" id="myTabs" role="tablist">
        <li class="nav-item">
        <a class="nav-link active" id="product-info-tab" data-toggle="tab" href="#product-info" role="tab" aria-controls="product-info" aria-selected="true">{{ trans('general.cj_modal_product_info') }}</a>
        </li>
        <li class="nav-item">
        <a class="nav-link" id="description-tab" data-toggle="tab" href="#description" role="tab" aria-controls="description" aria-selected="false">{{ trans('general.description') }}</a>
        </li>
        <li class="nav-item">
        <a class="nav-link" id="variants-tab" data-toggle="tab" href="#variants" role="tab" aria-controls="variants" aria-selected="false">{{ trans('general.variants') }}</a>
        </li>
    </ul>
    

    <div class="tab-content" id="myTabsContent">
        <div class="tab-pane fade show active" id="product-info" role="tabpanel" aria-labelledby="product-info-tab">

            <p><strong>Price:</strong> ${{ $cj_product['data']['sellPrice'] }}</p>
            <p><strong>SKU:</strong> {{ $cj_product['data']['productSku'] }}</p>
            <p><strong>Listed:</strong> {{ $cj_product['data']['listedNum'] }}</p>


        </div>
        <div class="tab-pane fade" id="description" role="tabpanel" aria-labelledby="description-tab">
            <style>
                #description img {
                    max-width: 100%; /* Set the maximum width to 100% of the container */
                    height: auto; /* Maintain the aspect ratio */
                }
            </style>
            {!! $cj_product['data']['description'] !!}
        </div>

        <div class="tab-pane fade" id="variants" role="tabpanel" aria-labelledby="variants-tab">
            <div class="row">
                @foreach($cj_product['data']['variants'] as $variant)
                
                    <div class="col-3">
                        <p><span class="badge badge-info">{{ $variant['variantKey'] }}</span> <span class="badge badge-success">${{ $variant['variantSellPrice'] }}</span></p>
                    </div>
                @endforeach
            </div>

        </div>

    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" onclick="$('#productModal').modal('hide');" data-bs-dismiss="modal">{{ trans('general.close') }}</button>
    <button type="button" class="btn btn-primary" id="import_cj_product" onclick="import_cj_product('{{ $cj_product['data']['pid'] }}')">{{ trans('general.cj_modal_add_product') }}</button>
</div>