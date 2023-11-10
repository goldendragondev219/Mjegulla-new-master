@extends('layouts.app')

<title>{{trans('general.collections')}} | {{ config('app.name') }} </title>

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


<!-- Button trigger modal -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ trans('general.collections') }}</h1>
    <a type="button" class="btn btn-primary" data-toggle="modal" data-target="#collectionModal">
        {{ trans('general.add_collection') }}
    </a>
</div>
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">{{ trans('general.collections') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
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

                    @if ($collections->isNotEmpty())
                    <div class="table-responsive-sm table-responsive-md table-responsive-xl">
                    <table class="table table-striped">
                    <thead>
                        <tr>
                        <th>{{ trans('general.name') }}</th>
                        <th>{{ trans('general.total_products') }}</th>
                        <th>{{ trans('general.action') }}</th>
                        </tr>
                    </thead>
                        @foreach($collections as $collection)
                            <tbody>
                                <tr>
                                    <td>{{ $collection->name }}</td>
                                    <td>{{ $collection->products->count() }}</td>
                                    <td class="d-flex">
                                        <a href="{{$shop_url}}/collection/{{ $collection->slug }}" class="btn btn-secondary mr-2" data-toggle="tooltip" data-placement="top" title="{{ trans('general.view') }}" target="_blank" style="margin-bottom: 20px;"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('view_collection', $collection->id) }}" class="btn btn-primary mr-2" data-toggle="tooltip" data-placement="top" title="{{ trans('general.edit') }}" style="margin-bottom: 20px;"><i class="fas fa-upload"></i></a>
                                        <form action="{{ route('delete_collection', $collection->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            </tbody>
                        @endforeach
                    </table>
                    </div>
                        @else
                        <style>
                            .card{
                            background: transparent;
                            border-style: none; 
                            }
                            .card-header{
                            display: none;
                            }
                        </style>
                        <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; height: 50vh;">
                            <i class="fas fa-layer-group" style="font-size: 5rem;"></i>
                            <p style="margin-top: 1rem;">{{ trans('general.no_collections') }}</p>
                        </div>
                        @endif
                </div>
                <div class="pagination" style="margin-left: 20px;">
                    {{ $collections->links() }}
                </div>
            </div>
        </div>
    </div>




@include('sweetalert::alert')



@include('layouts.scripts')
    <script type="application/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.3/sweetalert2.min.js" integrity="sha512-eN8dd/MGUx/RgM4HS5vCfebsBxvQB2yI0OS5rfmqfTo8NIseU+FenpNoa64REdgFftTY4tm0w8VMj5oJ8t+ncQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>



<div class="modal" id="collectionModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ trans('general.add_collection') }}</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="collectionForm" action="{{ route('create_collection') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="collectionName">{{ trans('general.collection_name') }}:</label>
                        <input type="text" class="form-control" id="collectionName" name="collectionName" placeholder="{{ trans('general.collection_name') }}" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="collectionForm" class="btn btn-primary create_collection">{{ trans('general.create') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('general.close') }}</button>
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function () {
        $('.create_collection').on('click', function (event) {
            // Prevent the default form submission
            event.preventDefault();

            // Disable the button
            $(this).prop('disabled', true);

            // You can perform your form submission here
            // For example, triggering the form submit event
            $('#collectionForm').submit();
        });
    });
</script>


@endsection
