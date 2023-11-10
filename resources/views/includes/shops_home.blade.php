    @foreach (auth()->user()->shops as $shop)
        <div class="col-md-4 mt-4 mb-4 h-150">
            <div class="card box-shadow" style="background-image: url('{{ $shop->shop_image_url }}'); object-fit: contain; background-position: center;">
                <div class="card-body" style="background-color: rgba(128, 128, 128, 0.5);">
                    <h5 class="card-title text-white my-glowing-text">{{ $shop->shop_name ?? '' }}</h5>
                    
                    <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('set_default_store', $shop->id) }}" class="btn btn-sm btn-secondary text-white">{{ trans('general.manage') }}</a>
                            <a href="{{ $shop->custom_domain_activated ? "https://{$shop->custom_domain}" : "https://{$shop->my_shop_url}." . parse_url(config('app.url'), PHP_URL_HOST) . '/' }}" class="btn btn-sm btn-primary text-white" target="_blank" data-toggle="tooltip" data-placement="top" title="{{ trans('general.go_to_shop') }}"><i class="fas fa-external-link-alt"></i></a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
