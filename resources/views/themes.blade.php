@extends('layouts.app')
<title>{{trans('general.themes')}} | {{ config('app.name') }} </title>
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
        @include('includes.shop_settings_sidebar')

        <div class="col-12 col-sm-12 col-md-12 col-lg-8 mb-4 main-content">

            <div class="card shadow mb-4">
                <div class="card-header">{{ trans('general.available_themes') }}</div>
                <div class="card-body">
                    <div class="card-body">

                    <div class="row">

                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mb-4">
                                <div class="card plugin-card" style="height: 250px; overflow: hidden;">
                                <img src="/img/theme_1.jpg" class="card-img-top plugin-image" style="object-fit: contain; padding: 10px; max-height: 150px;">
                                <div class="card-body plugin-info" style="font-size: 14px;">
                                    <h6 class="card-title plugin-title" style="font-size: 16px;">Theme #1</h6>
                                    
                                    @if($active_theme == 1)
                                      <button type="submit" class="btn btn-sm btn-primary" style="font-size: 12px;" disabled>{{ trans('general.activated') }}</button>
                                    @else
                                      <form id="themeForm" action="{{ route('store_change_theme', 1) }}" method="POST">
                                          @csrf
                                          <button type="submit" class="btn btn-sm btn-primary" style="font-size: 12px;">{{ trans('general.activate') }}</button>
                                      </form>
                                    @endif
                                </div>
                                </div>
                            </div>


                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mb-4">
                                <div class="card plugin-card" style="height: 250px; overflow: hidden;">
                                <img src="/img/theme_2.jpeg" class="card-img-top plugin-image" style="object-fit: contain; padding: 10px; max-height: 150px;">
                                <div class="card-body plugin-info" style="font-size: 14px;">
                                    <h6 class="card-title plugin-title" style="font-size: 16px;">Theme #2</h6>
                                    
                                    @if($active_theme == 2)
                                      <button type="submit" class="btn btn-sm btn-primary" style="font-size: 12px;" disabled>{{ trans('general.activated') }}</button>
                                    @else
                                      <form id="themeForm" action="{{ route('store_change_theme', 2) }}" method="POST">
                                          @csrf
                                          <button type="submit" class="btn btn-sm btn-primary" style="font-size: 12px;">{{ trans('general.activate') }}</button>
                                      </form>
                                    @endif
                                </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="themeDetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{ trans('general.theme_details') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- The modal body will be set dynamically using JavaScript -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">{{ trans('general.ok') }}</button>
      </div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script>
  $(document).ready(function() {
    // Get the Details button element
    var detailsButton = $('button[data-target="#themeDetails"]');
    
    // Get the value of the data-description attribute
    var description = detailsButton.data('description');
    
    // Set the value of the modal body to the description
    $('#themeDetails .modal-body').text(description);
  });
</script>
@endsection
