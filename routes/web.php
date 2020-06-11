<?php

use Illuminate\Support\Facades\Route;

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

    if (!Auth::user()) {
        return view('auth.login');
    }

    if( Auth::user()->isAdmin()) {
        return redirect()->route('users.index');
    } else if (Auth::user()->isOperator()) {
        return redirect()->route('patients.index');
    }
});

Auth::routes();

Route::resource('patients', 'PatientController')->middleware('operator');
Route::get('/checkShareKey', 'ShareKeyController@checkShareKey')->middleware('operator');
Route::get('/sharekeys', 'ShareKeyController@index')->middleware('operator')->name('sharekeys.index');
Route::get('/history/{shareKey}', 'PatientController@showHistory')->middleware('operator');

Route::resource('users', 'UserController', ['except' => 'edit,update'])->middleware('admin');
Route::resource('users', 'UserController', [
    'only' => [
        'edit',
        'update'
    ]
]);
Route::post('/users/checkPassword', 'UserController@checkPassword')->middleware('admin');
Route::post('/users/checkEmail', 'UserController@checkEmail')->middleware('admin');

Route::get('/departments', 'DepartmentController@index')->name('departments.index')->middleware('admin');
Route::get('/api/departments', 'DepartmentController@apiGetDepartments')->middleware('admin');
Route::post('/departments', 'DepartmentController@store')->name('departments.store')->middleware('admin');
Route::post('/departments/{id}', 'DepartmentController@update')->name('departments.update')->middleware('admin');
Route::post('/departments/{id}/delete', 'DepartmentController@destroy')->name('departments.destroy')->middleware('admin');
