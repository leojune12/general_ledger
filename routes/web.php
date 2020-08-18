<?php

use Illuminate\Support\Facades\Storage;

Auth::routes();

Route::get('/tester', function () {

});

Route::group(['middleware'=>['auth']], function (){

    Route::get('/', function () {
        //return view('welcome');
        return view('ledgers.view-ledgers');
    });

    //Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/dashboard', function () {
        return view('dashboard.dashboard');
    });

    Route::get('/ledger', function () {
       return view('ledgers.view-ledgers');
    });

    Route::get('/reports', function () {
        return view('reports.view-reports');
    });

    Route::get('/account-codes', function () {
        return view('codes.view-account-codes');
    });

    Route::get('/project-codes', function () {
        return view('codes.view-project-codes');
    });
});
