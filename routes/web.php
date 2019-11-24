<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::post('/admin/collectionmain/gettrips','AdminCollectiondetailsController@gettrips');

Route::post('/admin/processingdetails/getwastesubtype','AdminProcessingdetailsController@getwastesubtype');
Route::post('/admin/processingdetails/getwastecapacity','AdminProcessingdetailsController@getwastecapacity');
Route::post('/admin/processingdetails/getwastecategory','AdminProcessingdetailsController@getwastecategory');
Route::post('/admin/processingdetails/getpreviousstock','AdminProcessingdetailsController@getpreviousstock');
Route::post('/admin/processingdetails/getpiths','AdminProcessingdetailsController@getpiths');
Route::post('/admin/processingdetails/getunits','AdminProcessingdetailsController@getunits');

Route::post('/admin/salesdetails/getcategory','AdminSalesdetailsController@getcategory');