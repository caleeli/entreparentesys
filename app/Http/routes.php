<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::auth();

Route::group(['middleware' => ['auth']], function () {
//    Route::get('/home', 'HomeController@index');
    Route::get('home', ['uses' => 'PivotController@index', 'as' => 'pivotTable']);

    /**
     * example pivot table
     */

    Route::get('pivotTable', ['uses' => 'PivotController@index', 'as' => 'pivotTable']);
    //Route::post('pivotTable', ['uses' => 'PivotController@postPivotTable', 'as' => 'pivotTable']);

    Route::get('importCsv', ['uses' => 'PivotController@importCsv', 'as' => 'importCsv']);
    //Route::post('importCsv', ['uses' => 'PivotController@postImportCsv', 'as' => 'importCsv']);

    //example data
    Route::get('/data/comercializacion', 'DataController@comercializacion');
});
