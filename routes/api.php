<?php

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
    // Permissions
    Route::apiResource('permissions', 'PermissionsApiController');

    // Roles
    Route::apiResource('roles', 'RolesApiController');

    // Users
    Route::apiResource('users', 'UsersApiController');

    // Warga Binaans
    Route::post('warga-binaans/media', 'WargaBinaanApiController@storeMedia')->name('warga-binaans.storeMedia');
    Route::apiResource('warga-binaans', 'WargaBinaanApiController');

    // Sellers
    Route::apiResource('sellers', 'SellerApiController');

    // Produks
    Route::post('produks/media', 'ProdukApiController@storeMedia')->name('produks.storeMedia');
    Route::apiResource('produks', 'ProdukApiController');

    // Penjualans
    Route::apiResource('penjualans', 'PenjualanApiController');
});
