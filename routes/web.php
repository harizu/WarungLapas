<?php

Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});

Route::get('userVerification/{token}', 'UserVerificationController@approve')->name('userVerification');
Auth::routes();

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Warga Binaans
    Route::delete('warga-binaans/destroy', 'WargaBinaanController@massDestroy')->name('warga-binaans.massDestroy');
    Route::post('warga-binaans/media', 'WargaBinaanController@storeMedia')->name('warga-binaans.storeMedia');
    Route::post('warga-binaans/ckmedia', 'WargaBinaanController@storeCKEditorImages')->name('warga-binaans.storeCKEditorImages');
    Route::resource('warga-binaans', 'WargaBinaanController');

    // Sellers
    Route::delete('sellers/destroy', 'SellerController@massDestroy')->name('sellers.massDestroy');
    Route::resource('sellers', 'SellerController');

    // Produks
    Route::delete('produks/destroy', 'ProdukController@massDestroy')->name('produks.massDestroy');
    Route::post('produks/media', 'ProdukController@storeMedia')->name('produks.storeMedia');
    Route::post('produks/ckmedia', 'ProdukController@storeCKEditorImages')->name('produks.storeCKEditorImages');
    Route::resource('produks', 'ProdukController');

    // Penjualans
    Route::delete('penjualans/destroy', 'PenjualanController@massDestroy')->name('penjualans.massDestroy');
    Route::resource('penjualans', 'PenjualanController');

    // Pembelians
    Route::resource('pembelians', 'PembelianController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);
    Route::post('pembelians', 'PembelianController@postPembelian')->name('pembelians.post');
    Route::get('pembelians/get-warga-binaan/{nomor_registrasi}', 'PembelianController@getWargaBinaanByNomorRegistrasi')
        ->where('nomor_registrasi', '.*')
        ->name('pembelians.get.warga.binaan');
    Route::get('pembelians/get-produk-by-kategori/{kategori}', 'PembelianController@getProdukListByKategori')->name('pembelians.get.produk.by.kategori');
    Route::get('pembelians/success', 'PembelianController@success')->name('pembelians.success');


    Route::resource('riwayat-pesanan', 'RiwayatPesananController', ['only' => ['index', 'show']])
        ->parameters([
            'riwayat-pesanan' => 'order',
        ])->names([
            'index' => 'riwayatPesanan.index',
            'show' => 'riwayatPesanan.show',
        ]);
    Route::put('riwayat-pesanan/{order}/cancel', 'RiwayatPesananController@cancel')->name('riwayatPesanan.cancel');

    Route::get('messenger', 'MessengerController@index')->name('messenger.index');
    Route::get('messenger/create', 'MessengerController@createTopic')->name('messenger.createTopic');
    Route::post('messenger', 'MessengerController@storeTopic')->name('messenger.storeTopic');
    Route::get('messenger/inbox', 'MessengerController@showInbox')->name('messenger.showInbox');
    Route::get('messenger/outbox', 'MessengerController@showOutbox')->name('messenger.showOutbox');
    Route::get('messenger/{topic}', 'MessengerController@showMessages')->name('messenger.showMessages');
    Route::delete('messenger/{topic}', 'MessengerController@destroyTopic')->name('messenger.destroyTopic');
    Route::post('messenger/{topic}/reply', 'MessengerController@replyToTopic')->name('messenger.reply');
    Route::get('messenger/{topic}/reply', 'MessengerController@showReply')->name('messenger.showReply');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
// Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
