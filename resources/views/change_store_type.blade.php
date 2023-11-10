@extends('layouts.app')
<title>{{trans('general.change_store_type')}} | {{ config('app.name') }} </title>
@section('content')

<style>
	
.selected-license{
	border-radius: 10px;
    padding: 10px;
    background-color: #4e73df;
    border-style: solid;
    border-width: thin;
    border-color: #4e73df;
    cursor: pointer;
}

.selected-license p {
    color: white;
}

.unselected-license{
	border-style: solid;
    border-radius: 10px;
    border-width: thin;
    padding: 10px;
    cursor: pointer;
}




</style>



<div class="row">
        @include('includes.shop_settings_sidebar')

        <div class="col-12 col-sm-12 col-md-12 col-lg-8 mb-4 main-content">

            <div class="card shadow mb-4">
                <div class="card-header">{{ trans('general.change_store_type') }}</div>
                <div class="card-body">
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
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif



                    <div class="change_store_type_div py-3">
                        <div class="row" style="text-align: left;">
                            <div class="col-md-6 col-12 leasing_row" style="padding-right: 10px; padding-bottom: 10px">
                            <!-- Leasing -->
                            <div class="dropshipping @if(auth()->user()->isDropshipping()) selected-license @else unselected-license @endif">
                                <p id="dropshipping_title" style="font-size: 20px;">{{ trans('general.dropshipping') }} @if(auth()->user()->isDropshipping()) <span class="badge badge-success float-right">{{ trans('general.activated') }}</span> @endif</p>
                                <p id="dropshipping_desc" style="font-size: 12px;">{{ trans('general.dropshipping_info') }}</p>
                            </div>
                            </div>
                            <div class="col-md-6 col-12 unlimited_row" style="padding-right: 10px;">
                            <!-- Unlimited -->
                            <div class="local_store @if(!auth()->user()->isDropshipping()) selected-license @else unselected-license @endif">
                                <p id="local_store_title" style="font-size: 20px;">{{ trans('general.local_store') }}  @if(!auth()->user()->isDropshipping()) <span class="badge badge-success float-right">{{ trans('general.activated') }}</span>  @endif</p>
                                <p id="local_store_desc" style="font-size: 12px;">{{ trans('general.local_store_info') }}</p>
                            </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        {{ trans('general.change_store_type_warning_desc') }}
                    </div>

                    <button class="btn btn-primary update-store-type" data-toggle="modal" data-target="#update-store">{{ trans('general.update') }}</button>
                    
                </div>
            </div>
        </div>
</div>


<div class="modal fade" id="update-store" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">{{ trans('general.update') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    {{ trans('general.change_store_type_warning_desc') }}
                </div>
            </div>
            <div class="modal-footer">
                <form id="post-store-type-form" method="POST" action="{{ route('shop_change_type') }}">
                    @csrf
                    <input id="store_type" type="text" class="store_type form-control d-none" name="store_type" value="@if(auth()->user()->isDropshipping()) dropshipping @else local_store @endif" required>
                    <button type="submit" class="btn btn-primary update-store-type-btn" name="submit_action" value="update">{{ trans('general.update') }}</button>
                </form>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('general.close') }}</button>
            </div>
        </div>
    </div>
</div>

@endsection



<script type="application/javascript" src="https://code.jquery.com/jquery-3.6.3.min.js"></script>

<script>

$(document).ready(function () {
    $('.dropshipping').on('click', function () {
        $(this).removeClass('unselected-license').addClass('selected-license');
        $('.local_store').removeClass('selected-license').addClass('unselected-license');
        $('.store_type').val('dropshipping');
    });

    $('.local_store').on('click', function () {
        $(this).removeClass('unselected-license').addClass('selected-license');
        $('.dropshipping').removeClass('selected-license').addClass('unselected-license');
        $('.store_type').val('local_store');
    });

    // $('.custom_branding').on('click', function(){
    //     $(this).addClass('selected-license');
    //     $('.dropshipping, .local_store').removeClass('selected-license').addClass('unselected-license');
    //     $('.store_type').val('custom_branding');
    // });
});


</script>


<script>
    $(document).ready(function () {
        $('.update-store-type-btn').on('click', function (event) {
            // Prevent the default form submission
            event.preventDefault();

            // Disable the button
            $(this).prop('disabled', true);

            // You can perform your form submission here
            // For example, triggering the form submit event
            $('#post-store-type-form').submit();
        });
    });
</script>
