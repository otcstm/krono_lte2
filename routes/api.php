<?php

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();




Route::group(['middleware' => ['auth:api']], function () {
  Route::get('/salary/list', 'Datamart\SalaryController@list')->name('salary.list');
  Route::post('/salary/insert', 'Datamart\SalaryController@insert')->name('salary.list');

});



Route::group(['middleware' => ['auth:api']], function () {
  Route::get('/salary/retABC', 'Datamart\SalaryController@returnABC')->name('salary.abc');


});
