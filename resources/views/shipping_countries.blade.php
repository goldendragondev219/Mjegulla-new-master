@extends('layouts.app')

<title>{{trans('general.shipping')}} | {{ config('app.name') }} </title>

@section('content')
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

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="alert alert-info">
    {{ trans('general.shipping_description') }}
</div>

<div class="card shadow mt-4">
    <div class="card-header">{{ trans('general.shipping') }}</div>
    <div class="card-body">
    <div class="form-group row">
                        <div class="col-6 col-md-9">
                            <input id="search" type="text" class="form-control @error('search') is-invalid @enderror h-100" name="search" placeholder="{{ trans('general.cj_search') }}" value="{{ old('search', request('search')) }}" autofocus>
                        </div>
                        <div class="col-6 col-md-3">
                            <button class="btn btn-primary w-100" id="search_country_btn">{{ trans('general.cj_search') }}</button>
                        </div>
                    </div>
        <div class="table-responsive-sm table-responsive-md table-responsive-xl">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">{{ trans('general.country') }}</th>
                        @if(!auth()->user()->isDropshipping())
                        <th scope="col">{{ trans('general.price') }}</th>
                        <th scope="col">{{ trans('general.edit') }}</th>
                        @endif
                        <th scope="col">{{ trans('general.action') }}</th>
                    </tr>
                </thead>
                <tbody>                    
                    @foreach($countries as $country)
                    <tr>
                        <td>
                            <img src="https://flagicons.lipis.dev/flags/4x3/{{ strtolower($country->country_code) }}.svg" width="30" height="30"> {{ $country->country }}
                        </td>
                        @if(!auth()->user()->isDropshipping())
                            <td>€{{ $country->price }}</td>
                        @endif

                        @if(!auth()->user()->isDropshipping())
                        <td>
                            <button class="btn btn-primary edit-price-button" onclick="edit_country({{ $country->id }}, {{ $country->price }})">{{ trans('general.edit') }}</button>    
                        </td>
                        @endif

                        <td>
                            <form method="POST" action="{{ route('shipping_value_change', $country->id) }}">
                                @csrf
                                <button type="submit" class="btn {{ $country->enabled == 'yes' ? 'btn-danger' : 'btn-success' }}">
                                    {{ $country->enabled == 'yes' ? trans('general.deactivate') : trans('general.enable') }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table> 
            <div class="pagination" style="margin-left: 20px;">
                {{ $countries->links() }}
            </div>  
        </div> 
    </div>
</div>


<!-- Bootstrap Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">{{ trans('general.update') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="post-shipping-change-form" method="POST" action="{{ route('update_shipping_price') }}">
        @csrf
        <div class="modal-body">
          <p>{{ trans('general.price') }}<span id="editedRow"></span></p>
          <div class="form-group">
            <div class="input-group mb-4">
              <div class="input-group-prepend">
                <span class="input-group-text">&euro;</span>
              </div>
              <input type="number" class="form-control" name="price" id="price" value="" aria-label="Price" placeholder="{{ trans('general.price') }}" step="0.01" min="0">
            </div>
            <input type="text" class="form-control d-none" id="country" name="country">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('general.close') }}</button>
          <button type="button" class="btn btn-primary" id="saveChanges">{{ trans('general.update') }}</button>
        </div>
      </form>
    </div>
  </div>
</div>



@endsection
<script type="application/javascript" src="https://code.jquery.com/jquery-3.6.3.min.js"></script>


<script>
  function edit_country(countryId, price) {
    document.getElementById('country').value = countryId;
    document.getElementById('price').value = price;
    
    // Show the modal
    $('#editModal').modal('show');
  }
</script>

<script>
    $(document).ready(function () {
        $('#saveChanges').on('click', function (event) {
            // Prevent the default form submission
            event.preventDefault();

            // Disable the button
            $(this).prop('disabled', true);

            // You can perform your form submission here
            // For example, triggering the form submit event
            $('#post-shipping-change-form').submit();
        });
    });
</script>
