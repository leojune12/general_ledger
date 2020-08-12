<?php

Auth::routes();

Route::group(['middleware'=>['auth']], function (){

    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/dashboard', function () {
        return view('dashboard.dashboard');
    });

    Route::get('/ledgers', function () {
       return view('ledgers.view-ledgers');
    });

    Route::get('/account-codes', function () {
        return view('codes.view-account-codes');
    });

    Route::get('/product-codes', function () {
        return view('codes.view-product-codes');
    });
});
