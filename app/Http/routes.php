<?php

Route::bind('order', function($id) {
    $order = \App\Models\Order::withTrashed()->find($id);
    if ($order) {
        return $order;
    }
    throw new \Illuminate\Database\Eloquent\ModelNotFoundException();
});
Route::bind('smsTemplate', function($id) {
    return \App\Models\SmsTemplate::findOrFail($id);
});
Route::bind('cartSetCase', function($id) {
    return \App\Models\CartSetCase::findOrFail($id);
});

Route::bind('cartSetProduct', function($id) {
    return \App\Models\CartSetProduct::findOrFail($id);
});

Route::bind('payment', function($id) {
    return \App\Models\Payment::findOrFail($id);
});

Route::bind('specialItem', function($id) {
    return \App\Models\SpecialOrderItem::findOrFail($id);
});

Route::bind('transactionType', function($id) {
    return \App\Models\TransactionType::findOrFail($id);
});

Route::bind('paymentHash', function($hash) {
    $result = \App\Models\Payment::whereHash($hash)->first();
    if (!$result) {
        throw new \Illuminate\Database\Eloquent\ModelNotFoundException();
    }
    return $result;
});

Route::bind('orderStatus', function($id) {
    $result = \App\Models\OrderStatus::whereStatusId($id)->withTrashed()->first();
    if (!$result) {
        throw new \Illuminate\Database\Eloquent\ModelNotFoundException();
    }
    return $result;
});

/**
 * Site
 */
Route::group(['middleware' => ['utm']], function () {

    Route::get('s/{id}', 'OhcaseyController@goToShare');
    Route::any('go', 'OhcaseyController@go');
    Route::any('instaprofile', 'OhcaseyController@instaprofile');
    Route::any('info', 'OhcaseyController@info');
    Route::any('save', 'OhcaseyController@save');
    Route::get('cart/img/{id}', 'Admin@cartImg')->name('cart.case.image');

    Route::group(['prefix' => 'custom'], function () {
        Route::get('/', 'OhcaseyController@index')->name('home');

        // Delivery
        Route::get('delivery', 'OhcaseyController@delivery');

        // About
        Route::get('about', 'OhcaseyController@about');
    });

    // Order
    Route::get('/orders/{order}/{hash}/print', function ($order, $hash) {
        return redirect()->route('admin.order.print', \Request::all() + ['order' => $order, 'hash' => $hash]);
    });
    Route::get('orders/{order}/{hash}/{img}', 'OrderController@showImage')->name('orders.showImage');

    // Cart
    Route::get('custom/cart', 'OhcaseyController@cart')->name('cart');
    Route::post('custom/cart/order', 'OhcaseyController@order')->name('cart.order.create');
    Route::post('cart/put/{sku}/{count?}', 'OhcaseyController@cartPut')->name('cart.put');
    Route::post('cart/{id}/delete', 'OhcaseyController@cartDelete');
    Route::post('cart/code/apply/{code}', 'OhcaseyController@cartAddCode');
    Route::post('cart/code/remove', 'OhcaseyController@cartRemoveCode');

    Route::group(['middleware' => 'notEmptyCart'], function () {
        Route::group(['prefix' => 'shop/cart'], function () {
            Route::post('update_count/{cartSetCase}', 'CartController@updateCount')->name('shop.cart.update_count');
            Route::post('update_count_product/{cartSetProduct}', 'CartController@updateCountProduct')->name('shop.cart.update_count_product');
            Route::get('remove_case/{cartSetCase}', 'CartController@removeCase')->name('shop.cart.remove_case');
            Route::get('remove_product/{cartSetProduct}', 'CartController@removeProduct')->name('shop.cart.remove_product');
            Route::post('update_delivery_info', 'CartController@updateDeliveryInfo')->name('shop.cart.update_delivery_info');
        });
        Route::get('cart/process', 'CartController@process')->name('shop.cart.process');
        Route::get('cart', 'CartController@index')->name('shop.cart.items');
     });
    Route::get('cart/success/{order}', 'CartController@success')->name('shop.cart.order.success_created');
    Route::get('cart/empty', 'CartController@emptyCart')->name('shop.cart.empty_cart_page');

    // Route::get('cart/delivery', function(){
    //     return \App\Models\Delivery::with('payment_methods')->get();
    // });
    // Route::get('cart/payment/{paymentHash}', 'PaymentController@processPay')->name('cart.payment');

    // Cart2 - AB testing
    Route::get('cart2', 'OhcaseyController@cart2')->name('cart2');
    Route::post('cart2/order', 'OhcaseyController@order2');
    Route::get('cart2/delivery', function(){
        return \App\Models\Delivery::with('payment_methods')->get();
    });
    Route::get('cart2/payment/{paymentHash}', 'PaymentController@processPay')->name('cart.payment');

    // Cdek
    Route::any('cdek/cities', 'OhcaseyController@cdekCities');
    Route::any('cdek/cities/{id}/pvz/{code?}', 'OhcaseyController@cdekPvz');
    Route::any('cdek/cities/{id}/cost', 'OhcaseyController@cdekCost');

    // Country
    Route::any('countries', 'OhcaseyController@countries');

    // Device
    Route::any('cp/device', 'ControlPanel@device');
    Route::any('cp/device/helper', 'ControlPanel@deviceHelper');

    // Casey
    Route::any('cp/casey', 'ControlPanel@casey');
    Route::any('cp/casey/helper', 'ControlPanel@caseyHelper');

    // Backgrounds
    Route::any('cp/bg', 'ControlPanel@bg');
    Route::any('cp/bg/helper', 'ControlPanel@bgHelper');
    Route::any('cp/bg/{cat}', 'ControlPanel@bgList');

    // Smiles
    Route::any('cp/smile', 'ControlPanel@smile');
    Route::any('cp/smile/helper', 'ControlPanel@smileHelper');
    Route::any('cp/smile/{cat}', 'ControlPanel@smileList');

    // Fonts
    Route::any('cp/font', 'ControlPanel@font');
    Route::any('cp/font/helper', 'ControlPanel@fontHelper');

    // SVG text
    Route::get('font/image', 'OhcaseyController@fontToImage');

    // Image
    Route::get('storage/sz/{type}/{size}/{name}', 'OhcaseyController@image')->where('name', '.+');

    // Compile
    Route::any('compile', 'OhcaseyController@compile');

    // Upload
    Route::post('upload', 'OhcaseyController@upload');

    // Payment
    Route::group(['prefix' => 'payment'], function () {
        Route::get('failure',               'PaymentController@failureView')->name('payment.failure_view');
        Route::get('{paymentHash}',         'PaymentController@doPay')->name('payment.do_pay');
        Route::get('success/{paymentHash}', 'PaymentController@successView')->name('payment.success_view');
        Route::post('failure',              'PaymentController@failure')->name('payment.failure');
        Route::post('success',              'PaymentController@success')->name('payment.success');
        Route::post('check',                'PaymentController@check')->name('payment.check');
        Route::post('{paymentHash}',        'PaymentController@processPay')->name('payment.process_pay');
    });

});

/**
 * Admin
 */
Route::get('login', 'Auth\AuthController@showLoginForm');
Route::post('login', 'Auth\AuthController@login');
Route::get('logout', 'Auth\AuthController@logout');

Route::group(['middleware' => ['auth'], 'prefix' => 'admin'], function () {
    Route::get('/', 'Admin@index')->name('admin.order.list');
    Route::get('cart', "Admin@cart");
    Route::get('cart/img/{id}', "Admin@cartImg");
    Route::get('get_csv', 'Admin@getCsv')->name('admin.get_csv');
    Route::get('get_back_csv', 'Admin@getBackCsv')->name('admin.get_back_csv');


    Route::group(['middleware' => 'userrole'], function () {
        Route::get('dashboards', 'Admin@dashboards')->name('admin.dashboards');
    });

    Route::get('bg', 'Admin@bg');
    Route::post('bg', 'Admin@bgUpload');
    Route::delete('bg', 'Admin@bgDestroy');
    Route::patch('bg', 'Admin@bgUpdate');
    Route::post('bgSaveOrder', 'Admin@bgSaveOrder');
    
    Route::get('bgstat', 'Admin@bgStat');

    Route::get('smile', 'Admin@smile');
    Route::post('smile', 'Admin@smileUpload');
    Route::delete('smile', 'Admin@smileDestroy');
    Route::patch('smile', 'Admin@smileUpdate');

    Route::get('font', 'Admin@font');
    Route::post('font', 'Admin@fontUpload');
    Route::delete('font', 'Admin@fontDestroy');
    Route::patch('font', 'Admin@fontUpdate');

    Route::get('promotion', 'Admin@promotion');
    Route::post('promotion', 'Admin@promotionCreate');
    Route::delete('promotion/{id}', 'Admin@promotionDestroy');
    Route::patch('promotion/{id}', 'Admin@promotionUpdate');

    Route::group(['namespace' => 'Admin'], function () {
		
		Route::get('/cdek/form', ['uses' => 'CdekController@showForm', 'as' => 'admin.cdekForm']);
		Route::post('/cdek/form', ['uses' => 'CdekController@execute', 'as' => 'admin.execute']);
		Route::post('/courier-export', ['uses' => 'CourierExportController@execute', 'as' => 'admin.courierExport']);
        Route::post('/period-export', ['uses' => 'PeriodExportController@execute', 'as' => 'admin.periodExport']);
        Route::post('/period-cdek-export', ['uses' => 'PeriodExportController@executeOnlyCdek', 'as' => 'admin.periodCdekExport']);
        Route::group(['middleware' => 'userrole'], function () {
            Route::get('/users', 'UserController@index')->name('admin.users');
            Route::get('/users/{user}/remove', 'UserController@delete')->name('admin.users.delete');
            Route::get('/users/{user}/show', 'UserController@show')->name('admin.users.show');
            Route::get('/users/create', 'UserController@create')->name('admin.users.create');
            Route::post('/users/store', 'UserController@store')->name('admin.users.store');
            Route::post('/users/{user}/update', 'UserController@update')->name('admin.users.update');
        });


        Route::group(['prefix' => 'payments'], function () {
            Route::get('/',                            'PaymentController@index')->name('admin.payment.payment_list');
            Route::get('{order}/list',                 'PaymentController@order')->name('admin.payment.order_list');
            Route::get('{payment}/send',               'PaymentController@emailSend')->name('admin.payment.email_send');
            Route::get('{payment}/sms_send',           'PaymentController@smsSend')->name('admin.payment.sms_send');
            Route::post('{order}/createPayment',       'PaymentController@createPayment')->name('admin.payment.create_payment');
            Route::get('{payment}/delete',             'PaymentController@ajaxDelete')->name('admin.payment.ajax_delete');
        });

        Route::group(['prefix' => 'special_items'], function () {
            Route::get('{order}/{specialItem}/delete', 'SpecialItemController@delete')->name('admin.special_item.delete');
            Route::post('{order}/store',               'SpecialItemController@store')->name('admin.special_item.store');
        });

        Route::group(['prefix' => 'orders'], function () {
            Route::post('{order}/cartSetProduct/put',                    'OrderController@putCartSetProduct')->name('admin.order.cart_set_product.put');
            Route::post('cartSetProductUpdate',  						 'OrderController@cartSetProductUpdate')->name('admin.order.cart_set_product_store');
            Route::get('ajax_size_list',                                 'OrderController@ajaxSizeList');
            Route::post('ajax_update_deliverytime',                      'OrderController@ajaxUpdateDeliveryTime');
            Route::post('ajax_update_print_info',                        'OrderController@ajaxUpdatePrintinfo');
            Route::post('ajax_edit_item_cost',                           'OrderController@ajaxEditItemCost');
            Route::post('ajax_edit_delivery_cost',                       'OrderController@ajaxEditDeliveryCost');
            Route::post('ajax_edit_item_count',                          'OrderController@ajaxEditItemCount');
            Route::post('ajax_case_update_print_info',                   'OrderController@ajaxCaseUpdatePrintInfo');
            Route::get('{order}/cartSetCase/{cartSetCase}/remove',       'OrderController@removeCartSetCase')->name('admin.order.cart_set_case.remove');
            Route::get('{order}/cartSetProduct/{cartSetProduct}/remove', 'OrderController@removeCartSetProduct')->name('admin.order.cart_set_product.remove');
            Route::get('{order}/cartSetCase/{cartSetCase}/edit',         'OrderController@cartSetCaseEdit')->name('admin.order.cart_set_case.edit');
            Route::post('{order}/cartSetCase/{cartSetCase}/store',       'OrderController@cartSetCaseStore')->name('admin.order.cart_set_case.store');
            Route::get('{order}/{hash}/print',                           'OrderController@orderPrint')->name('admin.order.print');
            Route::get('{order}/{hash}/send',                            'OrderController@send')->name('admin.order.send');
            Route::get('{order}/{hash}/{img}',                           'OrderController@image')->name('admin.order.image');
            Route::get('cancel_swallow',                                 'OrderController@cancelSwallow')->name('admin.order.cancel_swallow');
            Route::get('ajax_products_list',                             'OrderController@ajaxProductList');
            Route::get('ajax_offers_list',                               'OrderController@ajaxOfferList');
            Route::get('last',                                           'OrderController@orderLast');
            Route::get('{order}/recompile',                              'OrderController@recompile')->name('admin.order.recompile');
            Route::get('{order}/mail',                                   'OrderController@orderMail')->name('admin.order.mail');
            Route::get('{order}/swallow',                                'OrderController@swallow')->name('admin.order.swallow');
            Route::get('{order}',                                        'OrderController@show')->name('admin.order.show');
            Route::post('{order}',                                       'OrderController@ajaxStatusUpdate');
            Route::post('{order}/update',                                'OrderController@update')->name('admin.order.update');
            Route::post('{order}/delete',                                'OrderController@delete')->name('admin.order.delete');
        });

        Route::group(['prefix' => 'cart_set_product'], function() {
            Route::get('last',                                           'CartSetProduct@lastItem');
        });

        Route::group(['prefix' => 'sms_templates'], function () {
            Route::get('/',                     'SmsTemplatesController@index')->name('admin.sms_templates.index');
            Route::get('create',                'SmsTemplatesController@create')->name('admin.sms_templates.create');
            Route::get('edit/{smsTemplate}',    'SmsTemplatesController@edit')->name('admin.sms_templates.edit');

            Route::post('store/{smsTemplate?}', 'SmsTemplatesController@store')->name('admin.sms_templates.store');
            Route::post('send/{order}',         'SmsTemplatesController@send')->name('admin.sms_templates.send');
        });

        Route::group(['prefix' => 'order_statuses'], function () {
            Route::get('/',                     'OrderStatusController@index')->name('admin.order_statuses.index');
            Route::get('create',                'OrderStatusController@edit')->name('admin.order_statuses.create');
            Route::get('edit/{orderStatus}',    'OrderStatusController@edit')->name('admin.order_statuses.edit');
            Route::post('store/{orderStatus?}', 'OrderStatusController@store')->name('admin.order_statuses.store');
        });

        Route::group(['prefix' => 'economic', 'middleware' => 'superadmin'], function () {
            Route::get('/', 'EconomicController@index')->name('admin.economic.index');

            Route::get('/transaction_type',               'EconomicController@index')->name('admin.economic.transaction_type.index');
            Route::get('/transaction_type/create',        'EconomicController@index')->name('admin.economic.transaction_type.create');
            Route::get('/transaction_type/{id}/edit',     'EconomicController@index')->name('admin.economic.transaction_type.edit');
            Route::get('/transaction_category',           'EconomicController@index')->name('admin.economic.transaction_category.index');
            Route::get('/transaction_category/create',    'EconomicController@index')->name('admin.economic.transaction_category.create');
            Route::get('/transaction_category/{id}/edit', 'EconomicController@index')->name('admin.economic.transaction_category.edit');
            Route::get('/transaction',                    'EconomicController@index')->name('admin.economic.transaction.index');
            Route::get('/transaction/report',             'EconomicController@index')->name('admin.economic.transaction.report');
            Route::get('/transaction/create',             'EconomicController@index')->name('admin.economic.transaction.create');
            Route::get('/transaction/{id}/edit',          'EconomicController@index')->name('admin.economic.transaction.edit');
        });

        Route::resource('category', 'CategoryController', ['except' => ['show']]);
        Route::post('/category/ajax_meta_update', ['uses' => 'CategoryController@updateMeta']);
    });
});

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function(){

    Route::get('/prints', 'PrintsController@index')->name('admin.prints');
    Route::post('/updatePrintStatus', 'PrintsController@updatePrintStatus');

    Route::group(['prefix' => 'ecommerce'], function(){
        Route::get('/', 'EcommerceController@showView');

        Route::get('/product', 'EcommerceController@showView')->name('admin.ecommerce.product.index');
        Route::get('/product/create', 'EcommerceController@showView')->name('admin.ecommerce.product.create');
        Route::get('/product/{id}/edit', 'EcommerceController@showView')->name('admin.ecommerce.product.edit');

        Route::get('/option', 'EcommerceController@showView')->name('admin.ecommerce.option.index');
        Route::get('/option/create', 'EcommerceController@showView')->name('admin.ecommerce.option.create');
        Route::get('/option/{id}/edit', 'EcommerceController@showView')->name('admin.ecommerce.option.edit');

        Route::get('/tag', 'EcommerceController@showView')->name('admin.ecommerce.tag.index');
        Route::get('/tag/create', 'EcommerceController@showView')->name('admin.ecommerce.tag.create');
        Route::get('/tag/{id}/edit', 'EcommerceController@showView')->name('admin.ecommerce.tag.edit');

        Route::get('/setting', 'EcommerceController@showView')->name('admin.ecommerce.setting.index');
        Route::get('/setting/create', 'EcommerceController@showView')->name('admin.ecommerce.setting.create');
        Route::get('/setting/{id}/edit', 'EcommerceController@showView')->name('admin.ecommerce.setting.edit');

        Route::get('/option_value', 'EcommerceController@showView')->name('admin.ecommerce.option_value.index');
        Route::get('/option_value/create', 'EcommerceController@showView')->name('admin.ecommerce.option_value.create');
        Route::get('/option_value/{id}/edit', 'EcommerceController@showView')->name('admin.ecommerce.option_value.edit');

        Route::get('/option_group', 'EcommerceController@showView')->name('admin.ecommerce.option_group.index');
        Route::get('/option_group/create', 'EcommerceController@showView')->name('admin.ecommerce.option_group.create');
        Route::get('/option_group/{id}', 'EcommerceController@showView')->name('admin.ecommerce.option_group.show');
        Route::get('/option_group/{id}/edit', 'EcommerceController@showView')->name('admin.ecommerce.option_group.edit');

        Route::get('/offer/create', 'EcommerceController@showView')->name('admin.ecommerce.offer.create');
        Route::get('/offer/{id}/edit', 'EcommerceController@showView')->name('admin.ecommerce.offer.edit');

        Route::get('/page', 'EcommerceController@showView')->name('admin.ecommerce.page.index');
        Route::get('/page/create', 'EcommerceController@showView')->name('admin.ecommerce.page.create');
        Route::get('/page/{id}/edit', 'EcommerceController@showView')->name('admin.ecommerce.page.edit');

        Route::get('/offer/generator', 'CaseController@index')->name('admin.ecommerce.generator.index');
        Route::get('/offer/generator/cases', 'CaseController@generate');

        Route::get('/print', 'EcommerceController@showView')->name('admin.ecommerce.print.index');
    });

    Route::group(['prefix' => 'menu_links'], function() {
        Route::get('/',         'MenuLinkController@showView')->name('admin.menu_links.index');
        Route::get('create',    'MenuLinkController@showView')->name('admin.menu_links.create');
        Route::get('{id}/edit', 'MenuLinkController@showView')->name('admin.menu_links.edit');
    });
});

Route::group(['prefix' => 'api'], function (){

    Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function(){

        Route::group(['prefix' => 'ecommerce'], function(){
            Route::resource('product', 'ProductController', ['except' => 'show']);
            Route::get('/category/list', 'CategoryController@categoriesList')->name('api.admin.ecommerce.category.list');
            Route::resource('photo', 'PhotoController');
            Route::resource('option', 'OptionController', ['except' => ['show']]);
            Route::get('/option/list', 'OptionController@optionsList')->name('api.admin.ecommerce.option.list');
            Route::resource('option_value', 'OptionValueController', ['except' => ['show']]);
            Route::resource('option_group', 'OptionGroupController');
            //Route::get('/tag/list', 'TagController@tagsList')->name('api.admin.ecommerce.tags.list');
            Route::resource('tag', 'TagController');
            Route::resource('setting', 'SettingController');

            // Route::get('/option_group/searchValues', 'OptionGroupController@searchValues')->name('api.admin.ecommerce.option_group.search_values');
            Route::get('/option_group/product/{id}', 'OptionGroupController@getProductOffers')->name('api.admin.ecommerce.option_group.product_offers');

            // Route::get('/option_group/{id}', 'OptionGroupController@show')->name('api.admin.ecommerce.option_group.show');
            // Route::get('/option_group/{id}', 'OptionGroupController@getOffers')->name('api.admin.ecommerce.option_group.show');

            //Route::post('/option_gorup_value/{id}', 'OptionGroupValueController@update')->name('api.admin.ecommerce.option_group_value.update');
            Route::resource('offer', 'OfferController', ['except' => 'show']);
            Route::resource('page', 'PageController', ['except' => 'show', 'create']);
            Route::post('/product/{id}/generate', 'ProductController@generateCaseOffers')->name('api.admin.ecommerce.product.generate');
        });
        Route::resource('menu_links', 'MenuLinkController', ['except' => 'show']);

        Route::resource('transaction_category', 'TransactionCategoryController', ['except' => 'show']);
        Route::resource('transaction_type', 'TransactionTypeController', ['except' => 'show']);
        Route::resource('transaction', 'TransactionController', ['except' => 'show']);
        Route::get('/transaction/report', 'TransactionController@report')->name('api.admin.transaction.report');

    });

    Route::get('/product/image', 'ProductController@image')->name('api.product.image');
    Route::get('/product/{product}/options', 'ProductController@getOptions')->name('api.product.options');
});

//New Design Site
//Route::group(['prefix' => 'shop'], function() {

        /*Route::get('/', function() {
        $page = \App\Models\Shop\Page::whereSlug('/')->first();

        $bestsellers = \App\Models\Shop\Product::where('bestseller', true)->with(['photos' => function($q){
            return $q->orderBy('updated_at', 'desc');
        }])->with('background')->active()->hasOffer()->orderBy('order')->limit(7)->latest()->get();


        if (\Request::input('version') == 'old') {
            return view('site.home', compact('bestsellers', 'menu', 'page'));
        } else {
            return view('site.home-new', compact('bestsellers', 'menu', 'page'));
        }
    })->name('shop.index');
	*/
        Route::get('/catalog-test', function() {
           return view('site.catalog-test');
        });
	Route::get('/', ['uses' => 'IndexController@show', 'as' => 'shop.index']);
	Route::get('/index-top/{offset}', ['uses' => 'IndexController@topItems', 'as' => 'shop.index-top']);

    Route::get('/product/{product}', 'ProductController@show')->name('shop.product.show');
    Route::get('/sitemap.xml', 'ProductController@sitemap');
    Route::get('/api/product', 'ProductController@index')->name('shop.product.index');
    Route::get('/tag/{tag}', 'TagController@show')->name('shop.tag.show');

    // Route::get('delivery', function () {
    //     return view('site.shop.delivery');
    // })->name('shop.delivery');

    Route::get('/{slug}', function($slug) {
        $page = \App\Models\Shop\Page::whereSlug($slug)->first();
        if(!empty($page)){
            $controller = app()->make('\App\Http\Controllers\PageController');
            return $controller->callAction('show', [$page]);
        }

        $tag = \App\Models\Shop\Tag::whereSlug($slug)->first();
        if(!empty($tag)){
            $controller = app()->make('\App\Http\Controllers\TagController');
            return $controller->callAction('show', [$tag]);
        }

        $controller = app()->make('\App\Http\Controllers\CategoryController');
        return $controller->callAction('show', [$slug]);

    })->where('slug', '(.*)?')->name('shop.slug');

    // Route::get('/{pageSlug}', 'PageController@show')->name('page.category');

    // Route::get('/{categorySlug}', 'CategoryController@show')->where('categorySlug', '(.*)?')->name('shop.category');
//});