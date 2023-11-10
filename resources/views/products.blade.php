@extends('layouts.app')
<title>{{trans('general.products')}} | {{ config('app.name') }} </title>
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
                                {{ $error }}<br>
                            @endforeach
                        </ul>
                    </div>
                @endif
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ trans('general.products') }}</h1>
    <a type="button" href="{{ route('newProduct') }}" class="btn btn-primary">
        {{ trans('general.create_new_product') }}
    </a>
</div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">{{ trans('general.products') }}</div>
                    @include('includes.my_products')
            </div>
        </div>
    </div>



<!-- delete product modal -->
<div class="modal fade" id="deleteProductModal" tabindex="-1" role="dialog" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteProductModalLabel">{{ trans('general.delete_product') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            {{ trans('general.delete_product_question') }}
      </div>
      <div class="modal-footer">
        <form method="POST" action="{{ route('delete_product', $id) }}">
            @csrf
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('general.cancel') }}</button>
            <button type="submit" class="btn btn-danger">{{ trans('general.delete') }}</button>
        </form>
      </div>
    </div>
  </div>
</div>


@include('layouts.scripts')
<script type="application/javascript">
$(document).ready(function() {
    $('#deleteProductModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var product_id = button.data('id'); // Extract product ID from data-* attributes
        var form = $(this).find('form'); // Get the delete form in the modal
        var action = form.attr('action'); // Get the current value of the action attribute
        var new_action = action.replace(/\/\d+$/, '/' + product_id); // Replace the product ID in the action URL
        form.attr('action', new_action); // Update the action attribute of the form
    });
});
</script>

@endsection
