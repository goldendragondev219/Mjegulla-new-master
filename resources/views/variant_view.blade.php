@extends('layouts.app')

<title>{{trans('general.variants')}} | {{ config('app.name') }} </title>

@section('content')
<!-- Button trigger modal -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ trans('general.variants') }}</h1>
</div>
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">{{ trans('general.variants') }}</div>

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

                    @if ($variants->isNotEmpty())
                    <div class="table-responsive-sm table-responsive-md table-responsive-xl">
                    <table class="table table-striped">
                    <thead>
                        <tr>
                        <th>ID</th>
                        <th>{{ trans('general.sku') }}</th>
                        <th>{{ trans('general.size') }}</th>
                        <th>{{ trans('general.price') }}</th>
                        <th>{{ trans('general.quantity') }}</th>
                        <th>{{ trans('general.quantity_left') }}</th>
                        <th>{{ trans('general.color') }}</th>
                        <th>{{ trans('general.material') }}</th>
                        <th>{{ trans('general.action') }}</th>
                        </tr>
                    </thead>
                        @foreach($variants as $variant)
                            <tbody>
                                <tr>
                                    <td>{{ $variant->variant_id }}</td>
                                    <td>{{ $variant->product_sku ? $variant->product_sku : trans('general.variant_no_sku') }}</td>
                                    <td>{{ $variant->size }}</td>
                                    <td>{{ $variant->price }}</td>
                                    <td>{{ $variant->quantity }}</td>
                                    <td>{{ $variant->quantity_left }}</td>
                                    <td>{{ $variant->color }}</td>
                                    <td>{{ $variant->material }}</td>
                                    <td><a class="btn btn-secondary" data-toggle="tooltip" data-placement="top" title="{{ trans('general.view') }}" href="{{ route('manage_product', $variant->product->id) }}" {{ $variant->product ? '' : 'disabled' }} target="_blank"><i class="fas fa-eye"></i></a></td>
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
                            <i class="fas fa-dna" style="font-size: 5rem;"></i>
                            <p style="margin-top: 1rem;">{{ trans('general.no_variants') }}</p>
                        </div>
                        @endif
                </div>
                <div class="pagination" style="margin-left: 20px;">
                    {{ $variants->links('vendor.pagination.default') }}
                </div>
            </div>
        </div>
    </div>




@include('sweetalert::alert')



@include('layouts.scripts')
    <script type="application/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.3/sweetalert2.min.js" integrity="sha512-eN8dd/MGUx/RgM4HS5vCfebsBxvQB2yI0OS5rfmqfTo8NIseU+FenpNoa64REdgFftTY4tm0w8VMj5oJ8t+ncQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


@endsection
