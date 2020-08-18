<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group(function () {

    Route::resource('/ledger', 'API\LedgerController');

    Route::resource('/medias', 'API\MediaController');

    Route::get('/medias/download/{id}', 'API\MediaController@download');

    Route::resource('reports', 'API\ReportController');

    Route::post('reports-filter', 'API\ReportController@filterData');

    Route::resource('/account-codes', 'API\Codes\AccountCodeController');

    Route::resource('/project-codes', 'API\Codes\ProjectCodeController');
});
