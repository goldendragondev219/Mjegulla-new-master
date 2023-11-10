<?php


return [

    'hello' => 'Hello',
    'order_status_change_with_id' => 'Order :order_id status changed to - ',
    'order_status_email_subject' => 'Your order status update - :shop_name',
    'owner_cj_dropshipping_low_balance_subject' => 'Attention CjDropshipping low balance - :shop_name',
    'order_complete_subject' => 'Order Completed - :shop_name',
    'new_sale_subject' => 'Sale - :shop_name',
    'order_complete_message_buyer' => 'Order has been completed, you will be notified when order status change via email. <p><strong>Order amount: €:order_amount</strong></p>',
    'order_complete_message_seller' => 'You have made a new sale <p><strong>Order amount: €:order_amount</strong></p>',
    'order_complete_no_balance' => 'Your CjDropshipping account has low balance to fulfill the order/s, please top-up your CjDropshipping account or the order/s will be refunded in 24 Hours!',


    //store deactivation
    'store_deactivated' => 'Store has been Deactivated',
    'store_deactivated_mail_desc' => 'Your store, :store_name, has been deactivated because it has reached the maximum number of orders allowed by your current package. If you wish to continue serving your customers through your store, please consider upgrading your store package. Alternatively, you will be able to accept new orders at the beginning of the next month.',
    
    'store_reactivated' => 'Store has been Re-Activated',
    'store_reactivated_mail_desc' => 'Your store, :store_name, has been reactivated. You can now accept orders from your customers.',


    //subscription
    'subscription_decline' => 'Subscription declined',
    'subscription_decline_msg' => 'Your subscription has been declined. Please call your bank and ask them to unblock payments or increase your limit, because the payment can\'t go through!',
    'decline_stripe_message' => 'Decline Message: :message',
    'decline_after_call_message' => 'After calling your bank, and unlocking or increasing your limit, please try again creating your Basic/Premium store or upgrade the package of your store',

    //reset password
    'password_code_subject' => 'Code - :code',
    'password_reset_body' => 'Your password reset code is: <br><br><p>CODE: :code</p> <p>Expires in 10 minutes</p><br><br><br> If you did not request reset password, this please ignore this email.',


    //cj token expiration
    'cj_token_expired_subject' => 'Attention API KEY deactivated!',
    'cj_token_expired' => 'Your CJDropshipping API KEY has been changed, please go into your account and add a new API KEY, in order to be able to sell your products, otherwise nothing will work without the key!',

];
