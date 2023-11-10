<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <style type="text/css" rel="stylesheet" media="all">
        /* Media Queries */
        @media  only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; width: 100%; background-color: #F2F4F6;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width: 100%; margin: 0; padding: 0; background-color: #F2F4F6;" align="center">
                <table width="100%" cellpadding="0" cellspacing="0">
                    <!-- Logo -->
                    <tr>
                        <td style="padding: 25px 0; text-align: center;">
                          <a href="{{ url('/') }}" style="display: inline-block; text-decoration: none;">
                            <img src="{{ $data['shop_data']->shop_image_url }}" style="position: relative; border: none; height: auto; width: auto; max-width: 120px !important;">
                          </a>
                        </td>
                    </tr>

        <!-- Email Body -->
        <tr>
            <td style="width: 100%; margin: 0; padding: 0; border-top: 1px solid #EDEFF2; border-bottom: 1px solid #EDEFF2; background-color: #FFF;" width="100%">
                <table style="width: auto; max-width: 570px; margin: 0 auto; padding: 0;" align="center" width="570" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="font-family: Arial, &#039;Helvetica Neue&#039;, Helvetica, sans-serif; padding: 35px;">
                            <!-- Greeting -->
                            <h1 style="margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold; text-align: left;">
                              {{trans('emails.hello')}} {{$data['customer_name']}}
                            </h1>

                            <!-- Intro -->
                            <p style="margin-top: 0; color: #74787E; font-size: 16px; line-height: 1.5em;">
                                {{trans('emails.order_status_change_with_id', ['order_id' => $data['order_id']])}} <strong>{{$data['order_status']}}</strong>
                                @if($data['tracking_number'])
                                    <p><strong>Tracking: {{ $data['tracking_number'] }}</strong></p>
                                @endif
                            </p>

                          </td>
                    </tr>
                </table>
            </td>
        </tr>

          <!-- Footer -->
            <tr>
                <td>
                    <table style="width: auto; max-width: 570px; margin: 0 auto; padding: 0; text-align: center;" align="center" width="570" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="font-family: Arial, &#039;Helvetica Neue&#039;, Helvetica, sans-serif; color: #AEAEAE; padding: 35px; text-align: center;">
                                <p style="margin-top: 0; color: #74787E; font-size: 12px; line-height: 1.5em;">
                                    &copy; <?php echo date('Y'); ?>
                                    {{$data['shop_data']->shop_name}}
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>
</table>
</body>
</html>
