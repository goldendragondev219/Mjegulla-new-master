<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\App;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\StripeWebHookController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\CjDropshippingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CollectionsController;
use App\Http\Controllers\ShippingRatesController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SubdomainController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\Subscriptions;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MenuController;


use App\Models\User;
use App\Models\CjDropshipping;
use App\Models\Orders;
use App\Models\Shop;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Authentication Routes


Route::get('/login/google/callback', [LoginController::class, 'loginWithGoogleCallback'])->name('login.google.callback');
Route::get('/login/google', [LoginController::class, 'redirectToGoogle'])->name('login.google');


// Stripe Webhook
Route::post('/str/wh', [StripeWebHookController::class, 'handleStripeWebhook'])->name('stripe_webhook');


// Custom Domain Shops
Route::middleware(['web', 'throttle:200,1'])->group(function () {
    $currentDomain = request()->getHost();
    $subdomain = Shop::where('custom_domain', $currentDomain)->value('my_shop_url');

    if ($subdomain) {
        Route::get('/lang/{lang}', function($subdomain, $lang){
            App::setLocale($lang);
            session(['locale' => $lang]);
            app()->setLocale($lang);
            return redirect()->back();
        });

        Route::get('/', [SubdomainController::class, 'index'])->name('store_home');

        // Customer Authentication Routes
        //Route::get('/customer/login', [CustomerController::class, 'loginView'])->name('customer.login.view');
        Route::post('/customer/login', [CustomerController::class, 'login'])->name('customer.login');
        Route::post('/customer/register', [CustomerController::class, 'register'])->name('customer.register');
        Route::get('/customer/logout', function(){
            Auth::guard('customers')->logout();
            return redirect('/');
        })->middleware('auth.customer');

        // Product Routes
        Route::get('/product/{url}', [SubdomainController::class, 'product'])->name('product');
        Route::get('/collection/{collection}', [SubdomainController::class, 'collection'])->name('collection.view');
        Route::get('/category/{category}', [SubdomainController::class, 'category'])->name('category.view');
        Route::get('/sub-category/{category}', [SubdomainController::class, 'SubCategory'])->name('sub.category.view');

        // Wishlist Routes
        Route::post('/add/wishlist/', [WishlistController::class, 'add'])->name('add_wishlist')->middleware('auth.customer');
        Route::get('/my/wishlist', [WishlistController::class, 'myWishlist'])->name('my_wishlist')->middleware('auth.customer');

        // Cart Routes
        Route::post('/add/cart/', [CartController::class, 'add'])->name('add_cart')->middleware('auth.customer');
        Route::get('/my/cart/', [CartController::class, 'myCartList'])->name('my_cart')->middleware('auth.customer');
        Route::post('/cart/delete-product/{product_id}', [CartController::class, 'deleteCartProduct'])->name('cart_delete_product')->middleware('auth.customer');

        // Checkout Routes
        Route::get('/my/checkout', [CartController::class, 'myCheckout'])->name('checkout')->middleware('auth.customer');
        Route::post('/order/checkout', [CartController::class, 'checkout'])->name('order_checkout')->middleware('auth.customer');
        Route::post('/shipping/country', [CartController::class, 'shippingCountryChange'])->name('shipping_country')->middleware('auth.customer');
        
        // Order Completion Route
        Route::get('/order/completed', [CartController::class, 'orderCompleted'])->name('order_completed')->middleware('auth.customer');

        // My Orders Route
        Route::get('/my/orders', [CartController::class, 'myOrders'])->name('my_orders')->middleware('auth.customer');

        // Catch-all for other requests
        Route::any('{any}', function () {
            abort(404);
        })->where('any', '.*');
    } else {
        if (app()->runningInConsole()) {
            // Skip the abort(404) statement for artisan commands
        } else {
            if (strpos($currentDomain, config('session.domain')) === false) {
                abort(404);
            }
        }        
    }
});

// Subdomain Shops
Route::group(['domain' => '{subdomain}.' . config('session.domain')], function ($subdomain) {

    Route::get('/lang/{lang}', function($subdomain, $lang){
        App::setLocale($lang);
        session(['locale' => $lang]);
        app()->setLocale($lang);
        return redirect()->back();
    });

    Route::get('/', [SubdomainController::class, 'index'])->name('store_home');

    // Customer Authentication Routes
    //Route::get('/customer/login', [CustomerController::class, 'loginView'])->name('customer.login.view');
    Route::post('/customer/login', [CustomerController::class, 'login'])->name('customer.login');
    Route::post('/customer/register', [CustomerController::class, 'register'])->name('customer.register');
    Route::get('/customer/logout', function(){
        Auth::guard('customers')->logout();
        return redirect('/');
    })->middleware('auth.customer');

    // Product Routes
    Route::get('/product/{url}', [SubdomainController::class, 'product'])->name('product');
    Route::get('/collection/{collection}', [SubdomainController::class, 'collection'])->name('collection.view');
    Route::get('/category/{category}', [SubdomainController::class, 'category'])->name('category.view');
    Route::get('/sub-category/{category}', [SubdomainController::class, 'SubCategory'])->name('sub.category.view');

    // Wishlist Routes
    Route::post('/add/wishlist/', [WishlistController::class, 'add'])->name('add_wishlist')->middleware('auth.customer');
    Route::get('/my/wishlist', [WishlistController::class, 'myWishlist'])->name('my_wishlist')->middleware('auth.customer');

    // Cart Routes
    Route::post('/add/cart/', [CartController::class, 'add'])->name('add_cart')->middleware('auth.customer');
    Route::get('/my/cart/', [CartController::class, 'myCartList'])->name('my_cart')->middleware('auth.customer');
    Route::post('/cart/delete-product/{product_id}', [CartController::class, 'deleteCartProduct'])->name('cart_delete_product')->middleware('auth.customer');

    // Checkout Routes
    Route::get('/my/checkout', [CartController::class, 'myCheckout'])->name('checkout')->middleware('auth.customer');
    Route::post('/order/checkout', [CartController::class, 'checkout'])->name('order_checkout')->middleware('auth.customer');
    Route::post('/shipping/country', [CartController::class, 'shippingCountryChange'])->name('shipping_country')->middleware('auth.customer');

    // Order Completion Route
    Route::get('/order/completed', [CartController::class, 'orderCompleted'])->name('order_completed')->middleware('auth.customer');

    // My Orders Route
    Route::get('/my/orders', [CartController::class, 'myOrders'])->name('my_orders')->middleware('auth.customer');

    // Catch-all for other requests
    Route::any('{any}', function () {
        abort(404);
    })->where('any', '.*');
});

// Sitemap Route
Route::get('/sitemap.xml', function (){
    return response()->view('sitemap')->header('Content-Type', 'application/xml');
});

// Main App Route
if (request()->getHost() == config('session.domain')) {
    Route::get('/', function () {
        return view('welcome');
    });
}

// Help Center Routes
Route::get('/help/{slug?}', [HelpController::class, 'index'])->name('help');

// Terms Route
Route::get('/p/terms', function() {
    return view('terms');
})->name('terms');

// Authentication Routes
Auth::routes();

//Reset password
Route::post('/reset/password', [PasswordResetController::class, 'genCode'])->name('password_reset_gen_code')->middleware('throttle:2,10');
Route::get('/reset/password/{page_key}', [PasswordResetController::class, 'confirmView'])->name('password_confirm_view');
Route::post('/reset/password/update', [PasswordResetController::class, 'update'])->name('password_update')->middleware('throttle:30,1');


Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('/home', [HomeController::class, 'handleStripeRedirect'])->name('home')->middleware('auth');

// Account Settings Routes
Route::get('/account/settings', [HomeController::class, 'viewProfile'])->name('edit')->middleware('auth');
Route::post('/profileUpdate', [HomeController::class, 'profileUpdate'])->name('profileUpdate')->middleware('auth');

// Change Password Routes
Route::get('/account/password', [HomeController::class, 'changePasswordView'])->name('change_password_view')->middleware('auth');
Route::post('/account/password', [HomeController::class, 'changePassword'])->name('changePassword')->middleware('auth');

// Billing Routes
Route::get('/account/billing', [HomeController::class, 'billingView'])->name('billing_view')->middleware('auth');
Route::post('/subscription/cancel/{id}', [Subscriptions::class, 'cancel'])->name('cancel_subscription')->middleware('auth');
Route::post('/subscription/reactivate/{id}', [Subscriptions::class, 'resubscribe'])->name('subscription_reactivate')->middleware('auth');
Route::post('/card/primary/{id}', [CardController::class, 'makeDefault'])->name('make_card_primary')->middleware('auth');
Route::post('/delete/card/{id}', [CardController::class, 'deleteCard'])->name('delete_card')->middleware('auth');

// Shop Routes
Route::get('/new', [ShopController::class, 'newView'])->name('new')->middleware('auth');
Route::post('/create/shop', [ShopController::class, 'create'])->name('create_shop')->middleware('auth');


//set default shop to manage
Route::get('/set/default/store/{store}', function($store){
    //is my store
    $shop = Shop::where('id', $store)->where('user_id', auth()->user()->id)->firstOrFail();
    if($shop->user_id === auth()->user()->id){
        auth()->user()->update(['managing_shop' => $shop->id]);

        //Create menu for categories, if it doest exist.
        $menu_exist = Harimayco\Menu\Models\Menus::where('shop_id', $shop->id)->first();
        if(!$menu_exist){
            (new \App\Http\Controllers\MenuController)->createnewmenu('PrimaryMenu', $shop->id);
        }

        return redirect()->route('shop_stats');
    }else{
        return redirect()->back()->withError(trans('general.there_was_an_error'));
    }
    
})->name('set_default_store')->middleware(['auth', 'throttle:10,1']);

Route::get('/store/customize', [ShopController::class, 'manageShop'])->name('manage_shop')->middleware('auth');

// Plugin Routes
Route::get('/store/plugins', [ShopController::class, 'pluginsView'])->name('plugins')->middleware('auth');
Route::post('plugin/activate/{id}', [ShopController::class, 'activatePlugin'])->name('activate_plugin')->middleware('auth');
Route::post('plugin/deactivate/{id}', [ShopController::class, 'deactivatePlugin'])->name('deactivate_plugin')->middleware('auth');

// Theme Routes
Route::get('/store/themes', [ShopController::class, 'themesView'])->name('themes')->middleware('auth');
Route::post('store/change-theme/{theme_id}', [ShopController::class, 'changeTheme'])->name('store_change_theme')->middleware('auth');

// Shop Payment Method Routes
Route::get('/store/payment-methods', [ShopController::class, 'ShopPaymentMethodView'])->name('shop_payment_method')->middleware('auth');
Route::post('/update/shop/{id}/payment_methods/{method}', [ShopController::class, 'updateShopPaymentMethods'])->name('update_shop_payment_method')->middleware('auth');

// Shop Upgrade Package Routes
Route::get('/store/upgrade-packages', [ShopController::class, 'ShopPackageUpgradeView'])->name('shop_package_upgrade')->middleware('auth');
Route::post('/upgrade/shop/{id}/package/{package}', [Subscriptions::class, 'upgrade'])->name('shop_package_upgrade_post_req')->middleware('auth');
Route::post('/shop/update/{id}', [ShopController::class, 'update'])->name('update_shop')->middleware('auth');


// Shop change type
Route::get('/store/change-type', [ShopController::class, 'ShopChangeTypeView'])->name('shop_change_type_view')->middleware('auth');
Route::post('/store/change-type', [ShopController::class, 'ShopChangeType'])->name('shop_change_type')->middleware('auth');

// Check Shop Name Availability Route
Route::post('/check/shop/name/{my_shop_url}', [ShopController::class, 'checkName'])->name('check_shop_name')->middleware('auth');

// Product Routes
Route::get('products', [ProductsController::class, 'view'])->name('products')->middleware('auth');
Route::get('/create/product', [ProductsController::class, 'newView'])->name('newProduct')->middleware(['auth', 'available_products']);
Route::post('/create/product/{id}', [ProductsController::class, 'create'])->name('create_product')->middleware(['auth', 'available_products']);
Route::get('/edit/product/{id}', [ProductsController::class, 'manage'])->name('manage_product')->middleware('auth')->middleware('auth');
Route::post('/delete/product/image/{id}', [ProductsController::class, 'deleteImage'])->name('delete_product_image')->middleware('auth');
Route::post('/delete/product/featured/image/{id}', [ProductsController::class, 'deleteFeaturedImage'])->name('delete_featured_image')->middleware('auth');
Route::post('/edit/product/featured/image/{id}', [ProductsController::class, 'editFeaturedImage'])->name('edit_product_featured_image')->middleware('auth');
Route::post('/edit/product/images/{id}', [ProductsController::class, 'editImages'])->name('edit_product_images')->middleware('auth');
Route::post('/edit/product/variant/{id}', [ProductsController::class, 'editVariant'])->name('edit_variant')->middleware('auth');
Route::post('/delete/product/variant/{id}', [ProductsController::class, 'deleteVariant'])->name('delete_variant')->middleware('auth');
Route::post('/create/variant/product/{id}', [ProductsController::class, 'createVariant'])->name('insert_new_variant')->middleware('auth');
Route::post('/update/product/{id}', [ProductsController::class, 'update'])->name('update_product')->middleware('auth');
Route::post('/delete/product/{id}', [ProductsController::class, 'deleteProduct'])->name('delete_product')->middleware('auth');
Route::post('/update/product/{id}/url', [ProductsController::class, 'updateProductUrl'])->name('check_and_save_product_url')->middleware('auth');

// Category Routes
Route::get('/categories', [CategoryController::class, 'view'])->name('categories')->middleware('auth');
Route::post('/create/category/{id}', [CategoryController::class, 'create'])->name('create_category')->middleware('auth');
Route::post('/delete/category/{id}', [CategoryController::class, 'delete'])->name('delete_category')->middleware('auth');
Route::post('/update/category/{id}', [CategoryController::class, 'update'])->name('update_category')->middleware('auth');

// Shop Stats Route
Route::get('/stats', [ShopController::class, 'statsView'])->name('shop_stats')->middleware('auth');
Route::get('/analytics', [ShopController::class, 'analitycs'])->name('shop_analitycs')->middleware('auth');

// Variant Route
Route::get('/variants', [ShopController::class, 'variantView'])->name('variant_view')->middleware('auth');


// Order Routes
Route::get('/orders', [ShopController::class, 'ordersView'])->name('shop_orders')->middleware('auth');
Route::get('/order/{id}', [ShopController::class, 'singleOrderView'])->name('single_order')->middleware('auth');
Route::get('/order/{id}/invoice', [ShopController::class, 'singleOrderInvoice'])->name('single_order_invoice')->middleware('auth');

// Notification Route
Route::post('/notifications/mark/seen', [Controller::class, 'markNotificationsAsRead'])->name('notifications_mark_seen')->middleware('auth');

// Language Route
Route::get('/lang/{lang}', function($lang){
    App::setLocale($lang);
    session(['locale' => $lang]);
    app()->setLocale($lang);
    if (auth()->check()) {
        auth()->user()->default_language = $lang;
        auth()->user()->save();
    }
    return redirect()->back();
});

// Cj Dropshipping Routes
Route::get('/store/cj-dropshipping', [CjDropshippingController::class, 'CjDropshippingEditView'])->name('cj_dropshipping_edit')->middleware('auth');
Route::post('/update/cj-dropshipping/api', [CjDropshippingController::class, 'update'])->name('update_cj_d')->middleware('auth');
Route::post('/delete/cj-dropshipping/api/{id}', [CjDropshippingController::class, 'delete'])->name('delete_cj_dropshipping_api')->middleware('auth');
Route::get('/cj-dropshipping', [CjDropshippingController::class, 'index'])->name('cj_dropshipping_index')->middleware('auth');
Route::post('/cj-dropshipping/view/product/{pid}', [CjDropshippingController::class, 'viewProduct'])->name('cj_dropshipping_view_product')->middleware('auth');
Route::post('/import/cj-dropshipping/product/{pid}', [CjDropshippingController::class, 'import_product'])->name('cj_dropshipping_import_product')->middleware(['auth', 'available_products']);
Route::post('/cj-dropshiping/pay-balance/{order_id}', [CjDropshippingController::class, 'payOrderBalance'])->name('pay_order_balance_cj')->middleware('auth');



// Payouts Routes
Route::get('/withdrawals', [WithdrawalController::class, 'index'])->name('withdrawals_view')->middleware('auth');
Route::post('/withdraw', [WithdrawalController::class, 'withdraw'])->name('withdraw')->middleware('auth');
Route::get('/payout-methods', [WithdrawalController::class, 'payoutMethodsView'])->name('payout_methods_view')->middleware('auth');
Route::post('/save/payment-method/{method}', [WithdrawalController::class, 'updatePaymentMethod'])->name('update_payment_method')->middleware('auth');




// Customized Domain & Subdomain Routes
Route::get('/store/domain', [ShopController::class, 'domainsView'])->name('my_domains')->middleware('auth');
Route::post('/update/subdomain', [ShopController::class, 'updateSubdomain'])->name('update_subdomain')->middleware(['auth', 'throttle:10,1']);
Route::post('/add/custom-domain', [ShopController::class, 'addCustomDomain'])->name('add_custom_domain')->middleware('auth');
Route::post('/delete/custom-domain/{domain}', [ShopController::class, 'deleteCustomDomain'])->name('delete_custom_domain')->middleware(['auth','throttle:1,10'])->where('domain', '^(?!www\.)[A-Za-z0-9-]{1,63}\.[A-Za-z]{2,6}$');


//Shipping
Route::get('/store/shipping', [ShippingRatesController::class, 'shippingView'])->name('shipping')->middleware('auth');
Route::post('/store/shipping/update/{country_id}', [ShippingRatesController::class, 'update'])->name('shipping_value_change')->middleware('auth');
Route::post('/shipping/update/price', [ShippingRatesController::class, 'update_shipping_price'])->name('update_shipping_price')->middleware('auth');



//Referal
Route::get('/account/referral', [ReferralController::class, 'index'])->name('referral_view')->middleware('auth');
Route::post('/account/referral/withdraw', [ReferralController::class, 'withdraw'])->name('referral_withdraw')->middleware('auth');
Route::get('/r/{referer_id}', function($referer_id){
    Session::put('referral', $referer_id, 7 * 24 * 60);
    return redirect('/');
});


//custom css
Route::get('/store/custom-css', [ShopController::class, 'customCSS'])->name('custom_css')->middleware('auth');
Route::post('/store/custom-css/update', [ShopController::class, 'customCSSUpdate'])->name('update_custom_css')->middleware('auth');





//Admin Routes
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {

    //users
    Route::get('users', [AdminController::class, 'usersView'])->name('admin_users');
    Route::get('stores/{user_id?}', [AdminController::class, 'storesView'])->name('admin_stores');
    Route::get('orders/{store_id?}', [AdminController::class, 'ordersView'])->name('admin_orders');

    //Login to user
    Route::post('/user/login/{id}', [AdminController::class, 'loginToUser'])->name('login_to_user');



    //Help Pages
    Route::get('help-pages', [AdminController::class, 'helpPages'])->name('admin_help_pages');
    Route::get('/help-page-create', [AdminController::class, 'helpPageCreateView'])->name('admin_help_pages_create_view');
    Route::post('/help-page-create', [AdminController::class, 'helpPageCreate'])->name('admin_help_pages_create');
    Route::get('/help-page-edit/{id}', [AdminController::class, 'helpPageEdit'])->name('admin_help_page_edit');
    Route::post('/help-page-update/{id}', [AdminController::class, 'helpPageUpdate'])->name('admin_help_page_update');
    Route::post('/help-page-delete/{id}', [AdminController::class, 'helpPageDelete'])->name('admin_help_page_delete');

    //Help pages Parent Menus
    Route::get('/help-pages-parent-menus', [AdminController::class, 'helpPagesParentMenus'])->name('admin_help_pages_parent_menus');
    Route::get('/help-pages-parent-create', [AdminController::class, 'helpPagesParentMenuCreateView'])->name('admin_help_page_parent_menu_create_view');
    Route::post('/help-pages-parent-create', [AdminController::class, 'helpPagesParentMenuCreate'])->name('admin_help_page_parent_menu_create');
    Route::get('/help-pages-parent-menu-edit/{id}', [AdminController::class, 'helpPagesParentMenuEditView'])->name('admin_help_pages_parent_menu_edit_view');
    Route::post('/help-page-parent-update/{id}', [AdminController::class, 'helpPageParentUpdate'])->name('admin_help_page_parent_menu_update');
    Route::post('/help-page-parent-delete/{id}', [AdminController::class, 'helpPageParentDelete'])->name('admin_help_page_parent_menu_delete');


    //support 
    Route::get('/support', [AdminController::class, 'SupportView'])->name('admin_support');
    Route::get('/support/ticket/{ticket_id}', [AdminController::class, 'SupportTicket'])->name('admin_support_ticket');
    Route::post('/support/ticket/reply/{ticket_id}', [AdminController::class, 'ReplyTicket'])->name('admin_support_reply_ticket');


}); // End admin routes



//Support routes


Route::get('/account/support', [SupportController::class, 'SupportView'])->name('support_view')->middleware('auth');
Route::post('/create/ticket', [SupportController::class, 'CreateTicket'])->name('create_ticket')->middleware(['auth', 'throttle:2,10']);
Route::get('/ticket/{ticket_id}', [SupportController::class, 'ViewTicket'])->name('view_ticket')->middleware('auth');
Route::post('/reply/ticket/{ticket_id}', [SupportController::class, 'ReplyTicket'])->name('reply_ticket')->middleware(['auth', 'throttle:10,5']);




// NEW Categories
Route::group(['middleware' => 'auth', 'prefix' => 'custom-categories'], function () {
    //Route::get('wmenuindex', array('uses'=>'\Harimayco\Menu\Controllers\MenuController@wmenuindex'));
    Route::post('/add-custom-menu', [MenuController::class, 'addcustommenu'])->name('cat_add_custom_menu');
    Route::post('/delete-item-menu', [MenuController::class, 'deleteitemmenu'])->name('cat_delete_menu_item');
    Route::post('/delete-menug', [MenuController::class, 'deletemenug'])->name('cat_delete_menu');
    Route::post('/create-new-menu', [MenuController::class, 'createnewmenu'])->name('cat_create_new_menu');
    Route::post('/generate-menu-control', [MenuController::class, 'generatemenucontrol'])->name('cat_gen_menu_control');
    Route::post('/update-item', [MenuController::class, 'updateitem'])->name('cat_update_item');
});



//google analytics
Route::post('/update/google-analytics', [ShopController::class, 'updateGoogleAnalytics'])->name('update_google_analytics')->middleware('auth');
Route::post('/update/facebook-pixel', [ShopController::class, 'updateFacebookPixel'])->name('update_fb_pixel_code')->middleware('auth');
Route::post('/update/tiktok-pixel', [ShopController::class, 'updateTikTokPixel'])->name('update_tiktok_pixel_code')->middleware('auth');


//import products bulk
Route::post('/import/mass/cj-products', [ProductsController::class, 'CJMassImport'])->name('cj_products_mass_import')->middleware('auth');


//collections
Route::get('/collections', [CollectionsController::class, 'collections'])->name('collections')->middleware('auth');
Route::post('/collections/create', [CollectionsController::class, 'create'])->name('create_collection')->middleware('auth');
Route::get('/collection/{collection_id}', [CollectionsController::class, 'view'])->name('view_collection')->middleware('auth');
Route::post('/collection/get-store-products', [CollectionsController::class, 'get_products'])->name('get_store_products_in_collections')->middleware('auth');
Route::post('/collection/import', [CollectionsController::class, 'import'])->name('import_products_collection')->middleware('auth');
Route::post('/collection/delete/{collection_id}', [CollectionsController::class, 'DeleteCollection'])->name('delete_collection')->middleware('auth');
Route::post('/collection/product/delete/{product_id}/{collection_id}', [CollectionsController::class, 'DeleteCollectionProduct'])->name('delete_collection_product')->middleware('auth');