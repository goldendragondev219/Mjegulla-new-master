<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>{{ trans('general.invoice') }} - #{{str_pad($invoice['order_id'], 4, "0", STR_PAD_LEFT)}}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">



    <link href='https://fonts.googleapis.com/css?family=Montserrat:700' rel='stylesheet' type='text/css'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link href="{{ asset('css/adminLTE.css') }}" rel="stylesheet">

    
    <style>
a,
a:focus,
a:active,
select,
button,
button:active,
button:focus,
.btn:focus,
.btn:active:focus,
.btn.active:focus,
.btn.focus,
.btn:active.focus,
.btn.active.focus,
input { outline: none; }

body{
  font-size: 15px;
}

input[type='file'] {
  opacity:0;
  position: absolute;
  top: 0;
  right: 0;
  min-width: 100%;
  min-height: 100%;
  outline: none;
  cursor: inherit;
  font-size: 100px;
}
textarea { resize: vertical; }

select:not(.cke_dialog_ui_input_select) {
    /*border: 2px solid #E6E6E6 !important;
    background: transparent;
    color: #5A5A5A;*/
   width: 100%;
  padding: 6px 5px 6px 8px;
  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-weight: normal;
  line-height: normal !important;
  color: #5A5A5A;
  border: 2px solid #E6E6E6;
  background-color: #fff;
  background-repeat: no-repeat;
  background-position: 100% 50%;
  background-image: url('../img/arrow.png') !important;
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  -webkit-border-radius: 4px;
  -moz-border-radius: 4px;
  border-radius: 4px;
  text-indent: 0.01px;
  text-overflow: '';
}

.display-none {
    display: none;
}

/*******************
 * Margin Zero
 * *****************
 */
.margin-zero {
    margin: 0 !important;
}
.margin-bottom-zero {
    margin-bottom: 0 !important;
}
.margin-top-zero {
    margin-top: 0 !important;
}
.margin-right-zero {
    margin-right: 0 !important;
}
.margin-left-zero {
    margin-left: 0 !important;
}
.margin-top-10 {
  margin-top: 10px !important;
}
.margin-bottom-10 {
  margin-bottom: 10px !important;
}

/*******************
 * Padding Zero
 * *****************
 */
.padding-zero {
    padding: 0 !important;
}
.padding-right-zero {
    padding-right: 0 !important;
}
.padding-left-zero {
    padding-left: 0 !important;
}
.padding-bottom-zero {
    padding-bottom: 0 !important;
}
.padding-top-zero {
    padding-top: 0 !important;
}

.nav-tabs-custom>.nav-tabs>li.header {
    font-size: 16px  !important;
}
.logo {
    font-family: "Montserrat",Helvetica,Arial,sans-serif !important;
    }
}
.none-overflow {
    text-overflow: inherit !important;
    overflow: visible !important;
    white-space: normal !important;
    word-wrap: break-word !important;
}
.text-overflow {
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}
.myicon-right {
    margin-right: 4px;
}
.no-found {
    margin-bottom: 20px;
    color: #999;
}
.box-file {
    position: relative;
    overflow: hidden;
}
.margin-separator {
	 margin: 0 5px;
}
.displayInline {
	display: inline;
}
.p-top-20 {
  padding-top:20px
}
.h-auto {
  height: auto !important;
}
.world-map {
  height: 350px; width: 100%;
}
.w-120 {
  width: 120px;
}
.w-150 {
  width: 150px;
}
.w-200 {
  width: 200px;
}
.w-400 {
  width: 400px;
}
.logo-theme {
  width:150px; background-color: #ccc;
}
.color-picker {
  border: none !important;
  padding: 0 !important;
  width: 40px !important;
}
.color-picker-tiny {
  width: 40px !important;
  padding: 15px !important;
  border-radius: 4px !important;
  height: 40px !important;
  border: 2px solid #FFF !important;
  box-shadow: #000 0px 0px 2px !important;
}
.d-none {
  display: none !important;
}
.border-none {
  border: none !important;
}
.margin-left-5 {
  margin-left: 5px;
}
.search-box {
  margin-top: 10px;
  width: 100%;
  display: block;
}
.bg-whitesmoke {
  background-color: whitesmoke !important;
}
.invoice {
  max-width: 750px !important;
  margin: 0 auto !important;
}
.label {
  border-radius: 10px !important;
}

    </style>

  </head>

  <body class="skin-purple-light sidebar-mini bg-whitesmoke">
    <div class="wrapper">
  <!-- Main content -->
  <section class="invoice">
    <!-- title row -->
    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
          <img src="{{ $invoice['shop_icon'] }}" width="30"><br>
          {{ $invoice['shop_name'] }}
        </h2>
      </div>
      <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
        {{ trans('general.seller') }}:
        <address>
          <strong>@if (!empty($invoice['shop_details']['company']))
              {{ $invoice['shop_details']['company'] }}
          @else
              {{ $invoice['shop_details']['full_name'] }}
          @endif
          </strong><br>
          {{ $invoice['shop_details']['address'] }} <br>
          {{ $invoice['shop_details']['city'] }}<br>
          {{ $invoice['shop_details']['country'] }}<br>
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        {{ trans('general.buyer') }}:
        <address>
          <strong>{{ $invoice['billing']['first_name'] }} {{ $invoice['billing']['last_name'] }}</strong><br>
          @if (!empty($invoice['billing']['company']))
              {{ $invoice['billing']['company'] }}
          @else
              -
          @endif
          <br>


          {{ $invoice['billing']['address'] }} <br>

          {{ $invoice['billing']['city'] }} {{ $invoice['billing']['zip'] }}<br>

          {{ $invoice['billing']['country'] }}<br>
          {{ $invoice['billing']['phone'] }}
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        <b>{{ trans('general.invoice') }}: #{{ date('Y') }}-{{str_pad($invoice['order_id'], 4, "0", STR_PAD_LEFT)}}</b><br>
        <b>{{ trans('general.payment') }}:</b> {{ $invoice['payment_method'] === 'credit_card' ? trans('general.credit_card') : trans('general.cash') }}<br>
        <b>{{ trans('general.date') }}: </b> {{ $invoice['created_at'] }}<br>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
      <div class="col-xs-12 table-responsive">
        <table class="table table-striped">
          <thead>
          <tr>
            <th>#</th>
            <th>{{ trans('general.product') }}</th>
            <th>{{ trans('general.variant') }}</th>
            <th>{{ trans('general.price') }}</th>
          </tr>
          </thead>
          <tbody>
            @php 
              $products_count = 1;
            @endphp
            @foreach($invoice['products'] as $product)
              <tr>
                <td>{{ $products_count }}</td>
                <td>{{ $product['product_name'] }}</td>
                <td>
                @if(isset($product['variant']))
                    {{ $product['variant']['size'] }}<br>
                    {{ $product['variant']['color'] }}<br>
                    {{ $product['variant']['material'] }}<br>
                @else
                    -
                @endif
                </td>
                <td>{{ $product['price'] }} €</td>
              </tr>
              @php
                $products_count++;
              @endphp
            @endforeach
          </tbody>
        </table>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
      <!-- /.col -->
      <div class="col-xs-6"></div>
      <!-- /.col -->
      <div class="col-xs-6">
        <div class="table-responsive">
          <table class="table">
            <tr>
              <th class="w-50">{{ trans('general.subtotal') }}:</th>
              <td>{{ $invoice['order_price']-$invoice['shipping_price'] }} €</td>
            </tr>


            <tr>
              <th class="w-50">{{ trans('general.shipping') }}:</th>
              <td>{{ $invoice['shipping_price'] }} €</td>
            </tr>


            <tr class="h4">
              <th>{{ trans('general.total') }}:</th>
              <td><strong>{{ $invoice['order_price'] }} €</strong></td>
            </tr>
          </table>
        </div>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
    <div class="row no-print">
        <div class="col-xs-12">
          <a href="javascript:void(0);" onclick="window.print();" class="btn btn-default"><i class="fa fa-print"></i> {{ trans('general.print') }}</a>
        </div>
      </div>
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->

  </body>
</html>
