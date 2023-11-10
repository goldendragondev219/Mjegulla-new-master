@section('title', $data->title)
@include('includes.home.header')
      <main class="nk-pages">
        <section class="nk-section-terms section-space-banner-top section-space-bottom pb-md-7 has-mask">
          <div class="nk-mask blur-1 d-none d-md-block"></div>
          <div class="container">
            <div class="nk-block-head md">
              <div class="nk-block-head-content m-0">
                <nav>
                  <ol class="breadcrumb mb-3 mb-md-4">
                    <li class="breadcrumb-item">
                      <a href="/help/">{{ trans('general.help') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $data->title }}</li>
                  </ol>
                </nav>
                <h2 class="nk-block-title mt-0">{{ $data->title }}</h2>
              </div>
              <hr class="mt-5 mb-0">
            </div>
            <div class="row g-gs">
              <div class="col-lg-3">
                @include('help_center.sidebar')
              </div>
                @include('help_center.content')
            </div>
          </div>
        </section>
        @include('help_center.contact_container')
      </main>
@include('includes.home.footer')