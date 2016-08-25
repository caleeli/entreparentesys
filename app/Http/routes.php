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
    Route::get('/home', ['uses' => 'PivotController@index', 'as' => 'pivotTable']);
    Route::get('/setting', ['uses' => 'SettingController@index', 'as' => 'setting']);

    /**
     * example pivot table
     */

    Route::get('pivotTable', ['uses' => 'PivotController@index', 'as' => 'pivotTable']);
    //Route::post('pivotTable', ['uses' => 'PivotController@postPivotTable', 'as' => 'pivotTable']);

    Route::get('importCsv', ['uses' => 'PivotController@importCsv', 'as' => 'importCsv']);
    //Route::post('importCsv', ['uses' => 'PivotController@postImportCsv', 'as' => 'importCsv']);

    /**
     * Folders
     */
    Route::get('foldersTree', ['uses' => 'FoldersController@getFolders', 'as' => 'foldersTree']);
    //Route::post('foldersTree', ['uses' => 'FoldersController@postPivotTable', 'as' => 'foldersTree']);

    //example data
    Route::get('/data/comercializacion', 'DataController@comercializacion');

    //import-excel
    Route::resource('/import-excel', 'ImportExcelController');

    //apis
    Route::group(['prefix' => 'api/v1'], FUNCTION(){
        Route::resource('user', 'UserController');
        Route::resource('roles', 'RolesController');
        Route::resource('permissions', 'PermissionsController');
        Route::resource('reports', 'ReportsController');
        Route::get('tree-reports', 'ReportsController@treeReports');
        Route::resource('folders', 'FoldersController');
        Route::get('sharedVariable/email', 'SharedVariableController@showEmail');
        Route::resource('sharedVariable', 'SharedVariableController');
        Route::resource('variable', 'VariablesController');
        Route::resource('fileCsv', 'FileCsv', ['only' => ['store', 'show']]);
    });

});
