@extends('layouts.app')
<title>{{trans('general.sales_dashboard')}} | {{ config('app.name') }} </title>
@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ trans('general.sales_dashboard') }}</h1>
    </div>

                    <!-- Content Row -->
                    <div class="row">


                        @if(auth()->user()->isDropshipping())
                        <!-- Available to withdraw -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                {{ trans('general.available_balance') }}</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">€{{ number_format(auth()->user()->balanceAvailable(),2) }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-euro-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Earnings (Monthly) Card Example -->
                        @if(auth()->user()->isDropshipping()) 
                            <div class="col-xl-3 col-md-6 mb-4">
                        @else
                            <div class="col-xl-4 col-md-4 mb-4">
                        @endif
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                {{ trans('general.earnings_this_month') }}</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">€{{ number_format(auth()->user()->earningsThisMonth(),2) }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        @if(auth()->user()->isDropshipping()) 
                            <div class="col-xl-3 col-md-6 mb-4">
                        @else
                            <div class="col-xl-4 col-md-4 mb-4">
                        @endif
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{ trans('general.products_left') }}
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    @php
                                                        if(auth()->user()->products_available() !== 'unlimited'){
                                                            $products_available = auth()->user()->products_available();
                                                            if($products_available == 0){
                                                                $percentage = 100;
                                                            }else{
                                                                $total_products = auth()->user()->total_products();
                                                                $percentage = (($total_products / $products_available) * 100)/2;
                                                            }
                                                        }
                                                    @endphp

                                                    @if(auth()->user()->products_available() !== 'unlimited')
                                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $products_available }}</div>
                                                    @else
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">∞</div>
                                                    @endif
                                                </div>
                                                @if(auth()->user()->products_available() !== 'unlimited')
                                                <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                        <div class="progress-bar bg-info" role="progressbar"
                                                            style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-shopping-bag fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Requests Card Example -->
                        @if(auth()->user()->isDropshipping()) 
                            <div class="col-xl-3 col-md-6 mb-4">
                        @else
                            <div class="col-xl-4 col-md-4 mb-4">
                        @endif
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                {{ trans('general.pending_orders') }}</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ auth()->user()->unshippedOrdersCount() }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-shipping-fast fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Chart -->
                    <div class="row">

<!-- Area Chart -->
<div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div
            class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">{{ trans('general.earnings_overview') }}</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
            <div class="chart-area">
                <canvas id="myAreaChart"></canvas>
            </div>
        </div>
    </div>
</div>
</div>



<!-- Page level custom scripts -->
<script>
    var earningsData = <?php echo json_encode(auth()->user()->earningsLast30Days()['earningsData']); ?>;
    var labels = <?php echo json_encode(auth()->user()->earningsLast30Days()['labels']); ?>;
</script>
<script src="{{ asset('js/demo/chart-area-demo.js') }}"></script>
<script src="{{ asset('js/demo/chart-pie-demo.js') }}"></script>
@endsection
