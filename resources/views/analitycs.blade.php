@extends('layouts.app')
<title>{{trans('general.visitors')}} | {{ config('app.name') }} </title>
@section('content')


    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ trans('general.visitors') }}</h1>
        <div class="row">
            <button type="button" class="btn btn-warning mr-3 mb-3" data-toggle="modal" data-target="#gaModal">
                    {{ trans('general.add_google_analytics') }} @if($shop->google_analytics) ✅@endif
            </button>
            <button type="button" class="btn btn-primary mr-3 mb-3" data-toggle="modal" data-target="#fbPixel">
                {{ trans('general.add_facebook_pixel') }}@if($shop->facebook_pixel) ✅@endif
            </button>
            <button type="button" class="btn btn-secondary mb-3" data-toggle="modal" data-target="#tiktokPixel" style="background-color: black;">
                {{ trans('general.add_tiktok_pixel') }}@if($shop->tiktok_pixel) ✅@endif
            </button>
        </div>
    </div>

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
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
                    <!-- Content Row -->
                    <div class="row">


                        <!-- Available to withdraw -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                {{ trans('general.visits_today') }}</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $visitors_today }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                {{ trans('general.unique') }}</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $visitors_today_unique }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                {{ trans('general.visits_this_week') }}</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $visitors_this_week }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                {{ trans('general.unique') }}</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $visitors_this_week_unique }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{ trans('general.visits_this_month') }}
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $visitors_this_month }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{ trans('general.unique') }}
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $visitors_this_month_unique }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Requests Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                {{ trans('general.all_time_visits') }}</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $all_visitors }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                {{ trans('general.unique') }}</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $all_visitors_unique }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Chart -->
                    <div class="row">

<!-- Area Chart -->
<div class="col-xl-8 col-lg-7">
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div
            class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">{{ trans('general.top_visited_products') }}</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
        @if($top_urls->isNotEmpty())
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>{{ trans('general.url') }}</th>
                        <th>{{ trans('general.visitors') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($top_urls as $url)
                        <tr>
                            <td>{{ $url->url }}</td>
                            <td>{{ $url->visitors }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
        <style>
        .withdrawals{
            background: transparent;
            border-style: none; 
        }
        .withdrawals-header{
            display: none;
        }
        </style>
        <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; height: 50vh;">
            <i class="fas fa-chart-line" style="font-size: 5rem;"></i>
            <p style="margin-top: 1rem;">{{ trans('general.no_visits') }}</p>
        </div>
        @endif
        </div>
    </div>
</div>

<!-- Pie Chart -->
<div class="col-xl-4 col-lg-5">
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div
            class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">{{ trans('general.online_users') }}</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
            <div class="chart-pie pt-4 pb-2" style="display: flex; justify-content: center; align-items: center;">
                <h1 style="font-size: 80px;">{{ number_format(auth()->user()->onlineUsers()) }}</h1>
            </div>
            <div class="mt-4 text-center small">
                <span class="mr-2">
                    <i class="fas fa-circle text-primary"></i> Direct
                </span>
                <span class="mr-2">
                    <i class="fas fa-circle text-success"></i> Social
                </span>
                <span class="mr-2">
                    <i class="fas fa-circle text-info"></i> Referral
                </span>
            </div>
        </div>
    </div>
</div>
</div>



<!-- Modal -->
<div class="modal fade" id="gaModal" tabindex="-1" role="dialog" aria-labelledby="gaModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="gaModalLabel">{{ trans('general.google_analytics_code') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="post-google-analytics-code" method="POST" action="{{ route('update_google_analytics') }}">
        @csrf
        <div class="modal-body">
          <input type="text" id="gaCodeInput" name="code" value="{{ $shop->google_analytics }}" class="form-control" placeholder="G-XXXXXXXXXX">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('general.close') }}</button>
          <button type="button" class="btn btn-primary updateGA" id="updateGA">{{ trans('general.update') }}</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="fbPixel" tabindex="-1" role="dialog" aria-labelledby="fbModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="fbModalLabel">{{ trans('general.facebook_pixel_code') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="post-fb-pixel-code" method="POST" action="{{ route('update_fb_pixel_code') }}">
        @csrf
        <div class="modal-body">
          <input type="text" id="fbPixelCodeInput" name="pixel_code" value="{{ $shop->facebook_pixel }}" class="form-control" placeholder="XXXXXXXXXXXXXXXX">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('general.close') }}</button>
          <button type="button" class="btn btn-primary updateFbPixel" id="updateFbPixel">{{ trans('general.update') }}</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="tiktokPixel" tabindex="-1" role="dialog" aria-labelledby="tiktokModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tiktokModalLabel">{{ trans('general.tiktok_pixel_code') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="post-tiktok-pixel-code" method="POST" action="{{ route('update_tiktok_pixel_code') }}">
        @csrf
        <div class="modal-body">
          <input type="text" id="tiktokPixelCodeInput" name="pixel_code" value="{{ $shop->tiktok_pixel }}" class="form-control" placeholder="XXXXXXXXXXXXX">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('general.close') }}</button>
          <button type="button" class="btn btn-primary updatetiktokPixel" id="updatetiktokPixel">{{ trans('general.update') }}</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Page level custom scripts -->
<script>
    var earningsData = <?php echo json_encode(auth()->user()->earningsLast30Days()['earningsData']); ?>;
    var labels = <?php echo json_encode(auth()->user()->earningsLast30Days()['labels']); ?>;
</script>
<script type="application/javascript" src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
<script src="{{ asset('js/demo/chart-area-demo.js') }}"></script>
<script src="{{ asset('js/demo/chart-pie-demo.js') }}"></script>

<script>
$(document).ready(function () {
    $('.updateGA').on('click', function (event) {
        // Prevent the default form submission
        event.preventDefault();

        // Disable the button
        $(this).prop('disabled', true);

        // You can perform your form submission here
        // For example, triggering the form submit event
        $('#post-google-analytics-code').submit();
    });
});

$(document).ready(function () {
    $('.updateFbPixel').on('click', function (event) {
        // Prevent the default form submission
        event.preventDefault();

        // Disable the button
        $(this).prop('disabled', true);

        // You can perform your form submission here
        // For example, triggering the form submit event
        $('#post-fb-pixel-code').submit();
    });
});


$(document).ready(function () {
    $('.updatetiktokPixel').on('click', function (event) {
        // Prevent the default form submission
        event.preventDefault();

        // Disable the button
        $(this).prop('disabled', true);

        // You can perform your form submission here
        // For example, triggering the form submit event
        $('#post-tiktok-pixel-code').submit();
    });
});
</script>

@endsection
