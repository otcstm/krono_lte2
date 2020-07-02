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

  $api->get('pm/getLastUpdPM', 'App\Api\Datamart\PMController@returnMaxDate');
  $api->post('pm/insert', 'App\Api\Datamart\PMController@insert');

  $api->get('io/getLastUpdIO', 'App\Api\Datamart\IOController@returnMaxDate');
  $api->post('io/insert', 'App\Api\Datamart\IOController@insert');

  $api->get('oti/getLastUpd', 'App\Api\Datamart\OtIndicatorController@returnMaxDate');
  $api->post('oti/insert', 'App\Api\Datamart\OtIndicatorController@insert');

  $api->get('leave/getLastUpd', 'App\Api\Datamart\LeaveController@returnMaxDate');
  $api->post('leave/insert', 'App\Api\Datamart\LeaveController@insert');

  $api->post('paid_ot/insert', 'App\Api\Datamart\PaidOtController@insert');

  $api->post('persData/insert', 'App\Api\Datamart\PersDataController@insert');


  // for batch thingy
  $api->get('batch/reminder', 'App\Api\Batch\ReminderController@process');

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
