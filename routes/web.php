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

// Route::get('/', 'MiscController@index')->name('misc.index');
Route::group(['middleware' => ['auth']], function () {

  Route::get('/home', 'MiscController@home')->name('misc.home');
  Route::get('/role', 'RoleController@index')->name('role.index');

  // clock-in related
  Route::get('/punch',      'MiscController@showPunchView')->name('punch.list');
  Route::post('/punch/in',  'MiscController@doClockIn')->name('punch.in');
  Route::post('/punch/out', 'MiscController@doClockOut')->name('punch.out');

//test

  // admins ------------------------------------
  Route::get('/admin/shift_pattern', 'ShiftPatternController@index')->name('sp.index');
  Route::post('/admin/shift_pattern/add', 'ShiftPatternController@addShiftPattern')->name('sp.add');
  Route::post('/admin/shift_pattern/detail', 'ShiftPatternController@viewSPDetail')->name('sp.view');

  Route::get('/admin/workday', 'DayTypeController@index')->name('wd.index');
  Route::post('/admin/workday/add', 'DayTypeController@add')->name('wd.add');
  Route::post('/admin/workday/edit', 'DayTypeController@edit')->name('wd.edit');
  Route::post('/admin/workday/delete', 'DayTypeController@delete')->name('wd.delete');

  // /admins ------------------------------------
  Route::get('/admin/cda', 'TempController@loadDummyUser')->name('temp.cda');

  //start state admin
  Route::post('/admin/state/store'    ,'Admin\StateController@store'    )->name('state.store');
  Route::get( '/admin/restState'      ,'Admin\StateController@list'     )->name('state.list');
  Route::post('/admin/state/destroy'  ,'Admin\StateController@destroy'  )->name('state.destroy');
  Route::get( '/admin/state/show'     ,'Admin\StateController@show'   )->name('state.show');
  Route::post( '/admin/state/update'  ,'Admin\StateController@update'   )->name('state.update');
  //end state admin

  //List staff admin
  Route::get('/admin/staff', 'Admin\StaffController@show')->name('staff.list.admin');
  Route::post('/admin/staff', 'Admin\StaffController@search')->name('staff.list.admin');

  //Role management
  Route::get('admin/role', 'Admin\RoleController@show')->name('role.list');
  Route::post('admin/role/create', 'Admin\RoleController@store')->name('role.store');
  Route::post('admin/role/edit', 'Admin\RoleController@update')->name('role.edit');
  Route::post('admin/role/delete', 'Admin\RoleController@destroy')->name('role.delete');
  
  //Company
  Route::get( '/admin/company','Admin\CompanyController@index')->name('company.index');
  Route::post('/admin/company/add','Admin\CompanyController@store')->name('company.store');
  Route::get( '/admin/Company/list','Admin\CompanyController@list')->name('company.list');
  Route::post('/admin/company/destroy','Admin\CompanyController@destroy')->name('company.destroy');
  Route::post( '/admin/company/update','Admin\CompanyController@update')->name('company.update');
 
 
  //Log activity
  Route::get('/log/listUserLogs', 'MiscController@listUserLogs')->name('log.listUserLogs');
  Route::get('/log/updUserLogs', 'MiscController@logUserAct')->name('log.logUserAct');

 
  
  Route::get('/staff/list', 'MiscController@listStaff')->name('staff.list');
  Route::get('/staff/search', 'MiscController@searchStaff')->name('staff.search');
  Route::post('/staff/search', 'MiscController@doSearchStaff')->name('staff.dosearch');
});
