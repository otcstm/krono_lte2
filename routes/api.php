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


$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {
  $api->get('salary/retABC', 'App\Api\Datamart\SalaryController@returnABC');
  $api->get('salary/getLastUpdSAP', 'App\Api\Datamart\SalaryController@returnMaxDate');
  $api->post('salary/insertSalary', 'App\Api\Datamart\SalaryController@insert');
  $api->get('userShiftPattern/getLastUpdSAP', 'App\Api\Datamart\UserShiftPatternController@returnMaxDate');
  $api->post('userShiftPattern/insert', 'App\Api\Datamart\UserShiftPatternController@insert');
  $api->post('cc/delAll', 'App\Api\Datamart\CcController@deleteAll');
  $api->post('cc/insert', 'App\Api\Datamart\CcController@insert');

  $api->get('proj/getLastUpdDM', 'App\Api\Datamart\ProjectController@returnMaxDate');
  $api->post('proj/insert', 'App\Api\Datamart\ProjectController@insert');

    //Route::get('/salary/retABC', 'Datamart\SalaryController@returnABC')->name('salary.abc');


});
/**
Route::group(['middleware' => ['auth:api']], function () {
  Route::get('/salary/list', 'Datamart\SalaryController@list')->name('salary.list');
  Route::post('/salary/insert', 'Datamart\SalaryController@insert')->name('salary.list');

});


Route::group(['middleware' => ['auth:api']], function () {
  Route::get('/salary/retABC', 'Datamart\SalaryController@returnABC')->name('salary.abc');


});


**/
