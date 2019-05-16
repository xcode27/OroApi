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




Route::post('login', 'UserController@authenticate');
Route::get('open', 'UserController@open');


Route::group(['middleware' => ['jwt.verify']], function() {
        Route::get('user', 'UserController@getAuthenticatedUser');
        Route::post('register', 'UserController@store');
        Route::get('users', 'UserController@index');
		Route::get('mappedModules', 'UserController@displayMappedMenu');
		Route::delete('user/{id}','UserController@destroy');
		Route::get('getMenus', 'UserController@getMenus');
		Route::get('getUser', 'UserController@getUser');
		Route::post('getUserMenu', 'UserController@getUserMenu');
		Route::post('getParentMenu', 'UserController@getParentMenu');
		Route::get('checkUserControl', 'UserController@checkUserControl');
		Route::post('createChartOfAccounts', 'ChartOfAccounts@createChartOfAccounts');
		Route::get('ListOfAccounts', 'ChartOfAccount@ListOfAccounts');
		Route::post('removeAccounts','ChartOfAccounts@removeAccounts');
		Route::get('getProducts', 'OroStore@getProducts');
		Route::post('getDrSr', 'OroStore@getDrSr');
		Route::post('displayrawData', 'OroStore@displayrawData');
		Route::get('getSupervisor', 'OroStore@getSupervisor');
		Route::post('getStore', 'OroStore@getStore');
		Route::get('allgetStore', 'OroStore@allgetStore');
		Route::post('saveAdjustmentEntry', 'OroStore@saveAdjustmentEntry');
		Route::get('displayAdjusment', 'OroStore@displayAdjusment');
		Route::post('displayList', 'OroStore@displayList');
		Route::post('addProductToList', 'OroStore@addProductToList');
		Route::post('deleteEntry','OroStore@deleteEntry');
		Route::post('updateProductToList', 'OroStore@updateProductToList');
		Route::post('deleteProductEntry','OroStore@deleteProductEntry');
		Route::post('updateAdjustmentEntry', 'OroStore@updateAdjustmentEntry');
		Route::get('getAllProducts', 'OroStore@getAllProducts');
		Route::get('getProdGroup', 'OroStore@getProdGroup');
		Route::post('loadProductFromGroup', 'OroStore@loadProductFromGroup');
		Route::get('getUserAccess', 'UserController@getUserAccess');
		Route::post('deleteUserMapped','UserController@deleteUserMapped');
    });

