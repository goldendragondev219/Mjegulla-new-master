<?php


return [

    'hello' => 'Përshëndetje',
    'order_status_change_with_id' => 'Statusi i porosisë :order_id është ndryshuar në - ',
    'order_status_email_subject' => 'Përditësimi i statusit të porosisë suaj - :shop_name',
    'owner_cj_dropshipping_low_balance_subject' => 'Vëmendje, balanca e ulët e CjDropshipping - :shop_name',
    'order_complete_subject' => 'Porosia është e plotësuar - :shop_name',
    'new_sale_subject' => 'Shitje - :shop_name',
    'order_complete_message_buyer' => 'Porosia është e kompletuar, do të njoftoheni kur të ndryshojë statusi i porosisë nëpërmjet emailit. <p><strong>Shuma e porosisë: €:order_amount</strong></p>',
    'order_complete_message_seller' => 'Keni pranuar një porosi të re <p><strong>Shuma e porosisë: €:order_amount</strong></p>',
    'order_complete_no_balance' => 'Llogaria juaj e CjDropshipping ka balancë të ulët për të kryer porosinë/të, ju lutemi musheni llogarinë tuaj të CjDropshipping ose porosia/të do të kthehen brenda 24 orëve!',



    //store deactivation
    'store_deactivated' => 'Dyqani u Deaktivizua',
    'store_deactivated_mail_desc' => 'Dyqani juaj, :store_name, është çaktivizuar pasi ka arritur numrin maksimal të porosive të lejuara nga pako juaj aktuale. Nëse dëshironi të vazhdoni të shërbëni klientët tuaj përmes dyqanit tuaj, ju lutemi ndryshoni pakon e dyqanit tuaj. Ose, do të jeni në gjendje të pranoni porosi të reja në fillim të muajit të ardhshëm.',
    'store_reactivated' => 'Dyqani u Riaktivizua',
    'store_reactivated_mail_desc' => 'Dyqani juaj, :store_name, është riaktivizuar. Tani mund të pranoni porosi nga klientët tuaj.',


    //subscription
    'subscription_decline' => 'Abonimi u refuzua',
    'subscription_decline_msg' => 'Abonimi juaj është refuzuar. Ju lutemi, telefononi bankën tuaj dhe kërkoni që të zhbllokojnë pagesat ose të rrisin limitin tuaj, pasi që pagesa nuk mund të kryhet!',
    'decline_stripe_message' => 'Mesazhi i Refuzimit: :message',
    'decline_after_call_message' => 'Pas telefonatës me bankën tuaj dhe zhbllokimin ose rritjen e limitit, ju lutemi provoni përsëri të krijoni dyqanin tuaj Bazik/Premium ose të ndryshoni pakon e dyqanit tuaj.',

    //reset password
    'password_code_subject' => 'Kodi - :code',
    'password_reset_body' => 'Kodi juaj për ndryshimin e fjalëkalimit është: <br><br><p>KODI: :code</p> <p>Skadon për 10 minuta</p><br><br><br> Nëse nuk e keni kërkuar të ndryshoni fjalëkalimin, ju lutemi injoroni këtë email.',


    //cj token expiration
    'cj_token_expired_subject' => 'Vëmendje API KEY është deaktiv!',
    'cj_token_expired' => 'API KEY i CJDropshipping është ndryshuar, ju lutemi shkoni në llogarinë tuaj dhe shtoni një API KEY të ri, në mënyrë që të jeni në gjendje të shisni produktet tuaja, ndryshe asgjë nuk do të funksionojë pa API KEY!',
];