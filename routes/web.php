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
    return view('auth.login');
});

//Auth::routes();
Auth::routes(['register' => false]);
Route::get('/home', 'HomeController@index')->name('home');


//Student routes
Route::resource('students','StudentController');
Route::get('all-students','StudentController@allStudents')->name('all-students');
Route::post('upload-students','StudentController@import')->name('upload-student');
Route::get('filter-students','StudentController@filterStudents')->name('filter-students');
Route::get('trainee-upload-format','StudentController@uploadFormat')->name('format-trainees');


Route::get('config','ProgramController@config')->name('config');


//users
Route::resource('users','UserController');
Route::post('change-password','UserController@change_password')->name('password-change');
Route::post('deactivate-user','UserController@deactivateUser')->name('deactivate-user');
Route::post('reset-password','UserController@resetPassword')->name('reset-password');

Route::get('filter-user','UserController@filterUsers')->name('filter-users');
//programs
Route::resource('programs','ProgramController');
Route::post('delete-program','SectionController@deleteProgram')->name('delete-program');



//locations
Route::resource('locations','LocationController');

//ItemController
Route::resource('items','ItemController');
Route::post('filter-items','ItemController@filterItems')->name('filter-items');
Route::post('upload-items','ItemController@importItems')->name('upload-items');
Route::get('item-upload-format','ItemController@uploadFormat')->name('format-items');

//staff
Route::resource('staff','StaffController');
Route::get('all-staff','StaffController@allStaff')->name('all-staff');
Route::post('delete-staff-subject','StaffController@deleteSubject')->name('delete-staff-subject');
Route::get('filter-staff','StaffController@filterStaff')->name('filter-staff');
Route::post('add-program/{id}','StaffController@addProgram')->name('add-program');
Route::post('delete-staff-designation','StaffController@deleteDesignation')->name('delete-staff-designation');
Route::post('add-designation/{id}','StaffController@addDesignation')->name('add-designation');
//categories Route
Route::resource('categories','CategoryController');
Route::post('delete-category','CategoryController@deleteCategory')->name('delete-category');


//areas Route
Route::resource('areas','AreaController');
Route::post('delete-area','AreaController@deleteArea')->name('delete-area');


//designation Route
Route::resource('designations','DesignationController');
Route::post('delete-designation','DesignationController@deleteDesignation')->name('delete-designation');

//brands Route
Route::resource('brands','BrandController');
Route::post('delete-brand','BrandController@deleteBrand')->name('delete-brand');

//ownership Route
Route::resource('ownerships','OwnershipController');
Route::post('delete-ownership','OwnershipController@deleteOwnership')->name('delete-ownership');


//status Route
Route::resource('status','StatusController');
Route::post('delete-status','StatusController@deleteStatus')->name('delete-status');

//Item type Route
Route::resource('items','ItemController');

//Item type Route
Route::resource('item-type','ItemTypeController');
Route::post('delete-item-type','ItemTypeController@deleteType')->name('delete-item-type');


//sessions Route
Route::resource('sessions','SectionController');
Route::post('delete-session','SectionController@deleteSession')->name('delete-session');


//Stores Route
Route::resource('stores','StoreController');
Route::get('check-item','StoreController@checkItem')->name('check-item');
Route::post('issue-item','StoreController@issueItem')->name('issue-item');
Route::post('receive-item','StoreController@receiveItem')->name('receive-item');
Route::post('filter-store-items','StoreController@filterStoreItems')->name('filter-store-items');


