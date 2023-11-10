<div class="card-body d-flex flex-wrap">
    @if (!is_null($product_images))
        @foreach ($product_images as $image)
            <div class="col-md-4 col-lg-3 col-sm-6 col-12 mt-4" style="width: auto; height: 200px;">
                <div class="card mb-4 box-shadow h-100" style="background-image: url('{{ $image->image_url ?? '' }}'); width: auto; height: 200px; background-repeat: no-repeat; background-size: cover;">
                    <div class="card-body">
                        <div class="d-flex justify-content-end">
                            <form action="{{ route('delete_product_image', $image->id) }}" method="POST">
                                @csrf
                                
                                <button type="submit" class="btn btn-sm btn-danger" style="margin-top: -20px; margin-right: -15px;">{{ trans('general.delete') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <p>{{ trans('general.product_no_images') }}</p>
    @endif
</div>
