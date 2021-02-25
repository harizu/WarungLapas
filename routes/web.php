<?php

Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});

Auth::routes(['register' => false]);

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
