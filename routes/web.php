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

Route::redirect('/', '/login');
Auth::routes(['register' => false]);

//Temporary offline login (url /login/offline)
Route::view('/login/offline', 'loginoffline',[]);
Route::post('/login/offline', 'TempController@login')->name('login.offline');
//User record controller
Route::get('/ur/popbyid/{id}', 'URController@popById')->name('ur.popbyid');
Route::get('/ur/listAll', 'URController@listAll')->name('ur.listAll');


// Route::get('/', 'MiscController@index')->name('misc.index');
Route::group(['middleware' => ['auth']], function () {

  Route::get('/home', 'MiscController@home')->name('misc.home');
  Route::get('/role', 'Admin\RoleController@index')->name('role.index');

  // clock-in related
  Route::get('/punch',      'MiscController@showPunchView')->name('punch.list');
  Route::post('/punch/in',  'MiscController@doClockIn')->name('punch.in');
  Route::post('/punch/out', 'MiscController@doClockOut')->name('punch.out');

  //List staff & search
  Route::get('/staff', 'Admin\StaffController@showStaff')->name('staff.list');
  Route::post('/staff/search', 'Admin\StaffController@searchStaff')->name('staff.search');

  // admins ------------------------------------

  Route::get('/admin/workday', 'Admin\DayTypeController@index')->name('wd.index');
  Route::post('/admin/workday/add', 'Admin\DayTypeController@add')->name('wd.add');
  Route::post('/admin/workday/edit', 'Admin\DayTypeController@edit')->name('wd.edit');
  Route::post('/admin/workday/delete', 'Admin\DayTypeController@delete')->name('wd.delete');

  Route::get('/admin/cda', 'TempController@loadDummyUser')->name('temp.cda');

  //start state admin
  Route::post('/admin/state/store'    ,'Admin\StateController@store'    )->name('state.store');
  Route::get( '/admin/restState'      ,'Admin\StateController@list'     )->name('state.list');
  Route::post('/admin/state/destroy'  ,'Admin\StateController@destroy'  )->name('state.destroy');
  Route::get( '/admin/state/show'     ,'Admin\StateController@show'   )->name('state.show');
  Route::post( '/admin/state/update'  ,'Admin\StateController@update'   )->name('state.update');
  //end state admin

  //User management
  Route::get('/admin/staff', 'Admin\StaffController@showMgmt')->name('staff.list.mgmt');
  Route::post('/admin/staff/edit', 'Admin\StaffController@updateMgmt')->name('staff.edit.mgmt');

  //User authorization
  Route::get('/admin/staff/auth', 'Admin\StaffController@showRole')->name('staff.list.auth');
  Route::post('/admin/staff/auth/edit', 'Admin\StaffController@updateRole')->name('staff.edit.auth');

  //Role management
  Route::get('admin/role', 'Admin\RoleController@show')->name('role.list');
  Route::post('admin/role/create', 'Admin\RoleController@store')->name('role.store');
  Route::post('admin/role/edit', 'Admin\RoleController@update')->name('role.edit');
  Route::post('admin/role/delete', 'Admin\RoleController@destroy')->name('role.delete');

  //Company
  Route::get( '/admin/company','Admin\CompanyController@index')->name('company.index');
  Route::post('/admin/company/add','Admin\CompanyController@store')->name('company.store');
  Route::post('/admin/company/delete','Admin\CompanyController@destroy')->name('company.delete');
  Route::post( '/admin/company/update','Admin\CompanyController@update')->name('company.update');


  //Personnel subarea
  Route::get( '/admin/psubarea','Admin\PsubareaController@index')->name('psubarea.index');
  Route::post('/admin/psubarea/add','Admin\PsubareaController@store')->name('psubarea.store');
  Route::post( '/admin/psubarea/update','Admin\PsubareaController@update')->name('psubarea.edit');
  Route::post('/admin/psubarea/delete','Admin\PsubareaController@destroy')->name('psubarea.delete');


  Route::get('/admin/holiday/create', 'Admin\HolidayController@create')->name('holiday.create');
  Route::post('/admin/holiday/insert', 'Admin\HolidayController@insert')->name('holiday.insert');
  Route::get('/admin/holiday/show', 'Admin\HolidayController@show')->name('holiday.show');

  // /admins ------------------------------------

  //Log activity
  Route::get('/log/listUserLogs', 'MiscController@listUserLogs')->name('log.listUserLogs');
  Route::get('/log/updUserLogs', 'MiscController@logUserAct')->name('log.logUserAct');

  //OT activity
  Route::get('/overtime', 'OvertimeController@showOT')->name('ot.showOT');
  Route::get('/overtime/detail', 'OvertimeController@showDetails')->name('ot.showDetails');
  Route::get('/overtime/logs', 'OvertimeController@logs')->name('ot.logs');
  Route::post('overtime/create', 'OvertimeController@create')->name('ot.create');
  Route::post('overtime/edit', 'OvertimeController@create')->name('ot.edit');
  Route::post('overtime/addtime', 'OvertimeController@addtime')->name('ot.addtime');
  Route::post('overtime/updatetime', 'OvertimeController@addtime')->name('ot.updatetime');
  Route::post('overtime/store', 'OvertimeController@store')->name('ot.store');
});

Route::group(['prefix' => 'admin/shift_pattern', 'as' => 'sp.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
  Route::get('/', 'ShiftPatternController@index')->name('index');
  Route::post('/add', 'ShiftPatternController@addShiftPattern')->name('add');
  Route::get('/detail', 'ShiftPatternController@viewSPDetail')->name('view');
  Route::post('/edit', 'ShiftPatternController@editShiftPattern')->name('edit');
  Route::post('/del', 'ShiftPatternController@delShiftPattern')->name('delete');
  Route::post('/day/push', 'ShiftPatternController@pushDay')->name('day.add');
  Route::post('/day/pop', 'ShiftPatternController@popDay')->name('day.del');
});

Route::group(['prefix' => 'shift_plan', 'as' => 'shift.', 'middleware' => ['auth']], function () {
  Route::get('/', 'ShiftPlanController@index')->name('index');
  // ShiftPlan crud
  Route::post('/add', 'ShiftPlanController@addPlan')->name('add');
  Route::get('/detail', 'ShiftPlanController@viewDetail')->name('view');
  Route::post('/edit', 'ShiftPlanController@editPlan')->name('edit');
  Route::post('/del', 'ShiftPlanController@delPlan')->name('delete');
  Route::post('/submit', 'ShiftPlanController@submitPlan')->name('submit');
  Route::post('/approve', 'ShiftPlanController@approvePlan')->name('approve');
  Route::post('/revert', 'ShiftPlanController@revertPlan')->name('revert');

  // shift groups
  Route::get('/group', 'ShiftGroupController@index')->name('group');
  Route::post('/group/add', 'ShiftGroupController@addGroup')->name('group.add');
  Route::get('/group/view', 'ShiftGroupController@viewGroup')->name('group.view');
  Route::post('/group/delete', 'ShiftGroupController@delGroup')->name('group.del');
  Route::post('/group/edit', 'ShiftGroupController@editGroup')->name('group.edit');
  Route::post('/staff/add', 'ShiftGroupController@addStaff')->name('staff.add');
  Route::post('/staff/del', 'ShiftGroupController@removeStaff')->name('staff.del');

  // ShiftPlanStaff
  Route::get('/staff', 'ShiftPlanController@staffInfo')->name('staff');
  Route::post('/staff/push', 'ShiftPlanController@staffPushTemplate')->name('staff.push');
  Route::post('/staff/pop', 'ShiftPlanController@staffPopTemplate')->name('staff.pop');
});
