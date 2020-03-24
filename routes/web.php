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
Route::get('/ur/show/{persno}', 'URController@show')->name('ur.show');
Route::get('/ur/listAll', 'URController@listAll')->name('ur.listAll');
Route::get('/ur/show/{persno}/{dt}', 'URController@gUR')->name('ur.listAll');
// Route::get('/', 'MiscController@index')->name('misc.index');
Route::group(['middleware' => ['auth']], function () {
  Route::get('/home', 'MiscController@home')->name('misc.home');
  Route::get('/role', 'Admin\RoleController@index')->name('role.index');


  //guide
  Route::get('/guide/calendar', 'GuideController@viewCalendar')->name('guide.calendar');
  Route::get('/guide/calendar/empty', 'GuideController@viewCalendarEmpty')->name('guide.calendar.empty');
  Route::post('/guide/calendar/empty', 'GuideController@viewCalendarEmpty')->name('guide.calendar.empty');
  Route::get('/guide/date/calendar', 'GuideController@dateCalendar')->name('guide.datecalendar');
  Route::post('/guide/date/calendar', 'GuideController@dateCalendar')->name('guide.datecalendar');
  Route::get('/guide/system', 'GuideController@viewSystem')->name('guide.system');
  Route::get('/guide/calendar/payment', 'GuideController@viewPaymentCalendar')->name('guide.paymentcalendar');

  // // clock-in related OLD
  // Route::get('/punch',      'MiscController@showPunchView')->name('punch.list');
  // Route::post('/punch/in',  'MiscController@doClockIn')->name('punch.in');
  // Route::post('/punch/out', 'MiscController@doClockOut')->name('punch.out');
  // Route::post('/punch/delete', 'MiscController@delete')->name('punch.delete');

  // clock-in related NEW
  Route::get('/punch',      'MiscController@showPunchView')->name('punch.list');
  Route::get('/punch/start',  'MiscController@startPunch')->name('punch.start');
  Route::get('/punch/check',  'MiscController@checkPunch')->name('punch.check');
  Route::get('/punch/checkday',  'MiscController@checkDay')->name('punch.checkday');
  Route::get('/punch/end',  'MiscController@endPunch')->name('punch.end');
  Route::get('/punch/cancel',  'MiscController@cancelPunch')->name('punch.cancel');
  Route::post('/punch/delete', 'MiscController@delete')->name('punch.delete');

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
  Route::get('/admin/staff/auth/empty', 'Admin\StaffController@emptystaffauth')->name('staff.list.auth.empty');
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
  //Holiday
  Route::get('/admin/holiday/create', 'Admin\HolidayController@create')->name('holiday.create');
  Route::post('/admin/holiday/insert', 'Admin\HolidayController@insert')->name('holiday.insert');
  Route::get('/admin/holiday/show', 'Admin\HolidayController@show')->name('holiday.show');
  Route::post('/admin/holiday/show', 'Admin\HolidayController@show')->name('holiday.show');
  Route::get('/admin/holiday/edit/{id}', 'Admin\HolidayController@edit')->name('holiday.edit');
  Route::post('/admin/holiday/update', 'Admin\HolidayController@update')->name('holiday.update');
  Route::post('/admin/holiday/destroy', 'Admin\HolidayController@destroy')->name('holiday.destroy');
  //Payment Schedule
  Route::get( '/admin/paymentsc','Admin\PaymentScheduleController@index')->name('paymentsc.index');
  Route::post( '/admin/paymentsc','Admin\PaymentScheduleController@index')->name('paymentsc.index');
  Route::post('/admin/paymentsc/add','Admin\PaymentScheduleController@store')->name('paymentsc.store');
  Route::post( '/admin/paymentsc/update','Admin\PaymentScheduleController@update')->name('paymentsc.edit');
  Route::post('/admin/paymentsc/delete','Admin\PaymentScheduleController@destroy')->name('paymentsc.delete');

  //OT Config
  Route::get('/admin/overtime', 'Admin\OvertimeMgmtController@show')->name('oe.show');
  Route::post('/admin/overtime', 'Admin\OvertimeMgmtController@show')->name('oe.show');
  Route::get('/admin/overtime/m', 'Admin\OvertimeMgmtController@otm')->name('oe.otm');
  Route::get('/admin/overtime/getcompany', 'Admin\OvertimeMgmtController@getCompany')->name('oe.getcompany');
  Route::get('/admin/overtime/eligible/getlast', 'Admin\OvertimeMgmtController@getLast')->name('oe.eligiblegetlast');
  Route::post('/admin/overtime/eligible/store', 'Admin\OvertimeMgmtController@eligiblestore')->name('oe.eligiblestore');
  Route::post('/admin/overtime/eligible/update', 'Admin\OvertimeMgmtController@eligibleupdate')->name('oe.eligibleupdate');
  Route::post('/admin/overtime/eligible/delete', 'Admin\OvertimeMgmtController@eligibledelete')->name('oe.eligibledelete');
  Route::post('/admin/overtime/expiry/store', 'Admin\OvertimeMgmtController@expirystore')->name('oe.expirystore');
  Route::post('/admin/overtime/expiry/update', 'Admin\OvertimeMgmtController@expiryupdate')->name('oe.expiryupdate');
  Route::post('/admin/overtime/expiry/delete', 'Admin\OvertimeMgmtController@expirydelete')->name('oe.expirydelete');
  Route::post('/admin/overtime/expiry/active', 'Admin\OvertimeMgmtController@active')->name('oe.active');
  Route::get('/admin/overtime/expiry/getexpiry', 'Admin\OvertimeMgmtController@getExpiry')->name('oe.getexpiry');
  Route::get('/admin/overtime/expiry/getlast', 'Admin\OvertimeMgmtController@getLast2')->name('oe.expirygetlast');

  Route::get('/admin/overtime/eligibility', 'Admin\OvertimeMgmtController@eligibilityshow')->name('oe.eligibility.show');
  Route::post('/admin/overtime/eligibility/add', 'Admin\OvertimeMgmtController@eligibilityadd')->name('oe.eligibility.add');
  Route::post('/admin/overtime/eligibility/remove', 'Admin\OvertimeMgmtController@eligibilityremove')->name('oe.eligibility.remove');
  Route::post('/admin/overtime/eligibility/update', 'Admin\OvertimeMgmtController@eligibilityupdate')->name('oe.eligibility.update');

  //Payroll Group
  Route::get( '/admin/pygroup','Admin\PayrollgroupController@index')->name('pygroup.index');
  Route::get('/admin/pygroup/create', 'Admin\PayrollgroupController@create')->name('pygroup.create');
  Route::post('/admin/pygroup/add','Admin\PayrollgroupController@store')->name('pygroup.store');
  Route::get( '/admin/pygroup/edit/{id}','Admin\PayrollgroupController@edit')->name('pygroup.editnew');
  Route::post( '/admin/pygroup/update','Admin\PayrollgroupController@update')->name('pygroup.update');
  Route::post('/admin/pygroup/delete','Admin\PayrollgroupController@destroy')->name('pygroup.delete');
  //Report
  // Route::get('/admreport/otd', 'Admin\OtReportController@viewOTd')->name('otr.viewOTd'); //rep1
  // Route::post('/admreport/otd', 'Admin\OtReportController@viewOTd')->name('otr.viewOTd');
  // Route::get('/admreport/ot', 'Admin\OtReportController@viewOT')->name('otr.viewOT'); //rep2
  // Route::post('/admreport/ot', 'Admin\OtReportController@viewOT')->name('otr.viewOT');
  // Route::get('/admreport/otlog', 'Admin\OtReportController@viewLC')->name('otr.viewOTLog'); //rep3
  // Route::post('/admreport/otlog', 'Admin\OtReportController@viewLC')->name('otr.viewOTLog');
  // Route::get('/admreport/StEdOt', 'Admin\OtReportController@viewStEd')->name('otr.viewStEd'); //rep4
  // Route::post('/admreport/StEdOt', 'Admin\OtReportController@viewStEd')->name('otr.viewStEd');

  Route::get('/admreport/ot', 'Admin\OtReport2Controller@viewOT')->name('otr.viewOT'); //dowload rep1
  Route::post('/admreport/ot', 'Admin\OtReport2Controller@viewOT')->name('otr.viewOT');
  Route::get('/admreport/otd', 'Admin\OtReport2Controller@viewOTd')->name('otr.viewOTd'); //dowload rep2
  Route::post('/admreport/otd', 'Admin\OtReport2Controller@viewOTd')->name('otr.viewOTd');
  Route::get('/admreport/StEdOt', 'Admin\OtReport2Controller@viewStEd')->name('otr.viewStEd'); //dowload rep4
  Route::post('/admreport/StEdOt', 'Admin\OtReport2Controller@viewStEd')->name('otr.viewStEd');
  Route::get('/admreport/otlog', 'Admin\OtReport2Controller@viewLC')->name('otr.viewOTLog'); //dowload rep3
  Route::post('/admreport/otlog', 'Admin\OtReport2Controller@viewLC')->name('otr.viewOTLog');

  // /admins ------------------------------------
  //Log activity
  Route::get('/log/listUserLogs', 'MiscController@listUserLogs')->name('log.listUserLogs');
  Route::get('/log/updUserLogs', 'MiscController@logUserAct')->name('log.logUserAct');
  //OT activity - User
  Route::get('/overtime', 'OvertimeController@list')->name('ot.list');
  Route::post('/overtime/submit', 'OvertimeController@submit')->name('ot.submit');
  Route::post('/overtime/update', 'OvertimeController@update')->name('ot.update');
  Route::post('/overtime/detail', 'OvertimeController@detail')->name('ot.detail');
  Route::post('/overtime/remove', 'OvertimeController@remove')->name('ot.remove');
  Route::get('/overtime/form', 'OvertimeController@form')->name('ot.form');
  Route::post('/overtime/form', 'OvertimeController@form')->name('ot.form');
  Route::get('/overtime/form/new', 'OvertimeController@formnew')->name('ot.formnew');
  Route::post('/overtime/form/new', 'OvertimeController@formnew')->name('ot.formnew');
  Route::post('/overtime/form/date', 'OvertimeController@formdate')->name('ot.formdate');
  Route::post('/overtime/form/submit', 'OvertimeController@formsubmit')->name('ot.formsubmit');
  Route::post('/overtime/form/delete', 'OvertimeController@formdelete')->name('ot.formdelete');
  Route::get('/overtime/form/getthumbnail', 'OvertimeController@getthumbnail')->name('ot.thumbnail');
  Route::get('/overtime/form/getfile', 'OvertimeController@getfile')->name('ot.file');
  //OT activity - Verifier
  Route::get('/overtime/verify', 'OvertimeController@verify')->name('ot.verify');
  Route::post('/overtime/verify', 'OvertimeController@verify')->name('ot.verify');
  Route::get('/overtime/verify/report', 'OvertimeController@verifyrept')->name('ot.verifyrept');
  Route::post('/overtime/verify/report', 'OvertimeController@verifyrept')->name('ot.verifyrept');
  //OT activity - Approver
  Route::get('/overtime/approval', 'OvertimeController@approval')->name('ot.approval');
  Route::post('/overtime/approval', 'OvertimeController@approval')->name('ot.approval');
  Route::get('/overtime/approval/report', 'OvertimeController@approvalrept')->name('ot.approvalrept');
  Route::post('/overtime/approval/report', 'OvertimeController@approvalrept')->name('ot.approvalrept');
  Route::get('/overtime/approval/search', 'OvertimeController@search')->name('ot.search');
  Route::get('/overtime/query', 'OvertimeController@query')->name('ot.query');
  Route::post('/overtime/query', 'OvertimeController@query')->name('ot.query');
  //OT activity - Admin
  Route::get('/admin/overtime/approval', 'OvertimeController@admin')->name('ot.admin');
  Route::get('/admin/overtime/approval/view', 'OvertimeController@adminview')->name('ot.adminview');
  Route::post('/admin/overtime/approval/view', 'OvertimeController@adminview')->name('ot.adminview');
  Route::post('/admin/overtime/approval', 'OvertimeController@admin')->name('ot.admin');
  Route::post('/admin/overtime/search', 'OvertimeController@adminsearch')->name('ot.adminsearch');

  // Route::post('/overtime/query/addverifier', 'OvertimeController@addverifier')->name('ot.addverifier');
  Route::get('/overtime/query/getverifier', 'OvertimeController@getverifier')->name('ot.getverifier');

  //staff profile with subordinates reptto
  Route::get('/staff/profile', 'Admin\StaffController@showStaffProfile')->name('staff.profile');

  //set default verifier
  Route::get('/verifier', 'VerifierGroupController@index')->name('verifier.listGroup');
  Route::post('/verifier/group/create', 'VerifierGroupController@createGroup')->name('verifier.createGroup');
  Route::get('/verifier/group/view', 'VerifierGroupController@viewGroup')->name('verifier.viewGroup');
  Route::post('/verifier/group/update', 'VerifierGroupController@updateGroup')->name('verifier.updateGroup');
  Route::post('/verifier/group/del', 'VerifierGroupController@delGroup')->name('verifier.delGroup');
  Route::post('/verifier/group/staff/add', 'VerifierGroupController@addUser')->name('verifier.addUser');
  Route::post('/verifier/group/staff/remove', 'VerifierGroupController@removeUser')->name('verifier.removeUser');

  //admin verifier
  Route::post('/admin/verifier/staff', 'UserVerifierController@staffverifier')->name('verifier.staff');

  //admin ajax search
  Route::get('/admin/verifier/staffsearch', 'UserVerifierController@staffsearch')->name('verifier.staffsearch');
  Route::get('/admin/verifier/subordSearch', 'UserVerifierController@subordSearch')->name('verifier.subordSearch');
  Route::get('/admin/verifier/ajaxAdvSearchSubord', 'UserVerifierController@ajaxAdvSearchSubord')->name('verifier.ajaxAdvSearchSubord');
  Route::post('/admin/verifier/advSearch', 'UserVerifierController@advSearchSubord')->name('verifier.advSearchSubord');

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
  Route::post('/takeaction', 'ShiftPlanController@takeActionPlan')->name('takeaction');
  // shift groups
  Route::get('/group', 'ShiftGroupController@index')->name('group');
  Route::post('/group/add', 'ShiftGroupController@addGroup')->name('group.add');
  Route::get('/group/view', 'ShiftGroupController@viewGroup')->name('group.view');
  Route::post('/group/delete', 'ShiftGroupController@delGroup')->name('group.del');
  Route::post('/group/edit', 'ShiftGroupController@editGroup')->name('group.edit');
  Route::post('/staff/add', 'ShiftGroupController@addStaff')->name('staff.add');
  Route::post('/staff/del', 'ShiftGroupController@removeStaff')->name('staff.del');
  Route::get('/group/api/sstaff', 'ShiftGroupController@ApiSearchStaff')->name('group.api.searchstaff');


  // ShiftPlanStaff
  Route::get('/staff', 'ShiftPlanController@staffInfo')->name('staff');
  Route::post('/staff/push', 'ShiftPlanController@staffPushTemplate')->name('staff.push');
  Route::post('/staff/pop', 'ShiftPlanController@staffPopTemplate')->name('staff.pop');

});

  Route::get('/email/dummy', 'EmailController@dummyEmail')->name('email.dummy');
  Route::post('/email/dummy', 'EmailController@sendDummyEmail')->name('email.senddummy');
