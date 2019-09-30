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

//start state admin
//Route::get( '/admin/states'         ,'StateController@create'   )->name('state.create');
Route::post('/admin/states'         ,'StateController@store'    )->name('state.store');
Route::get( '/admin/restState'      ,'StateController@list'     )->name('state.list');
Route::post('/admin/state/destroy'  ,'StateController@destroy'  )->name('state.destroy');
Route::get( '/admin/state/show'    ,'StateController@show'   )->name('state.show');
Route::post( '/admin/state/update'    ,'StateController@update'   )->name('state.update');
//end state admin


Auth::routes(['register' => false]);

// Route::get('/', 'MiscController@index')->name('misc.index');
Route::group(['middleware' => ['auth']], function () {
  Route::get('/home', 'MiscController@home')->name('misc.home');
  Route::get('/role', 'RoleController@index')->name('role.index');

  // clock-in related
  Route::get('/punch', 'MiscController@showPunchView')->name('punch.list');
  Route::post('/punch/in', 'MiscController@doClockIn')->name('punch.in');
  Route::post('/punch/out', 'MiscController@doClockOut')->name('punch.out');

  // admins ------------------------------------
  // Route::get('/admin/shift_pattern', 'ShiftPatternController@index')->name('sp.index');
  // Route::post('/admin/shift_pattern/add', 'ShiftPatternController@addShiftPattern')->name('sp.add');
  // Route::post('/admin/shift_pattern/detail', 'ShiftPatternController@viewSPDetail')->name('sp.view');



  // /admins ------------------------------------
  Route::get('/admin/cda', 'TempController@loadDummyUser')->name('temp.cda');
});
