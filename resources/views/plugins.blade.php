@extends('layouts.app')
<title>{{trans('general.plugins')}} | {{ config('app.name') }} </title>
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

            <div class="card shadow mb-4 @if ($my_plugins->isEmpty()) d-none @endif">
                <div class="card-header">{{ trans('general.available_plugins') }}</div>
                <div class="card-body">
                    <div class="card-body">

                    <div class="row">
                        @foreach ($my_plugins as $p_plugin)
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mb-4">
                                <div class="card plugin-card" style="height: 250px; overflow: hidden;">
                                <img src="{{ $p_plugin->image }}" class="card-img-top plugin-image" style="object-fit: contain; padding: 10px; max-height: 150px;">
                                <div class="card-body plugin-info" style="font-size: 14px;">
                                    <h6 class="card-title plugin-title" style="font-size: 16px;">{{ $p_plugin->name }}</h6>

                                    @if($p_plugin->enabled == 'yes')
                                        <form method="POST" action="{{ route('deactivate_plugin', $p_plugin->id) }}" style="display: inline-flex;">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="btn btn-sm btn-danger" style="font-size: 12px;">{{ trans('general.deactivate') }}</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('activate_plugin', $p_plugin->id) }}" style="display: inline-flex;">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="btn btn-sm btn-primary" style="font-size: 12px;">{{ trans('general.activate') }}</button>
                                        </form>
                                    @endif
                                    <button type="button" class="btn btn-sm btn-secondary" style="font-size: 12px;" data-toggle="modal" data-target="#pluginDetails" data-description="{{ $p_plugin->description }}">{{ trans('general.details') }}</button>
                                </div>
                                </div>
                            </div>
                        @endforeach
                        </div>
                    </div>

                </div>
            </div>


            <div class="card @if ($purchase_plugins->isEmpty()) d-none @endif">
                <div class="card-header">{{ trans('general.purchase_plugins') }}</div>
                <div class="card-body">
                    <div class="card-body">

                    <div class="row">
                        @foreach ($purchase_plugins as $p_plugin)
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mb-4">
                                <div class="card plugin-card" style="height: 250px; overflow: hidden;">
                                <img src="{{ $p_plugin->image }}" class="card-img-top plugin-image" style="object-fit: contain; padding: 10px; max-height: 150px;">
                                <div class="card-body plugin-info" style="font-size: 14px;">
                                    <h6 class="card-title plugin-title" style="font-size: 16px;">{{ $p_plugin->name }}</h6>
                                    <form method="POST" action="{{ route('activate_plugin', $p_plugin->id) }}" style="display: inline-flex;">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-sm btn-primary" style="font-size: 12px;">{{ trans('general.activate') }}</button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-secondary" style="font-size: 12px;" data-toggle="modal" data-target="#pluginDetails" data-description="{{ $p_plugin->description }}">{{ trans('general.details') }}</button>
                                </div>
                                </div>
                            </div>
                        @endforeach
                        </div>
                    </div>

                </div>
            </div>


        </div>
    </div>

    <div class="modal fade" id="pluginDetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{ trans('general.plugin_details') }}</h5>
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
    var detailsButton = $('button[data-target="#pluginDetails"]');
    
    // Get the value of the data-description attribute
    var description = detailsButton.data('description');
    
    // Set the value of the modal body to the description
    $('#pluginDetails .modal-body').text(description);
  });
</script>
@endsection
