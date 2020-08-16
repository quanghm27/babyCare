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
Route::get('/history/{shareKey}', 'PatientController@showHistory')->middleware('operator');

Route::get('/checkNewShareKey', 'ShareKeyController@checkNewShareKey')->middleware('operator');
Route::get('/checkEditShareKey', 'ShareKeyController@checkEditShareKey')->middleware('operator');
Route::get('/sharekeys', 'ShareKeyController@index')->middleware('operator')->name('sharekeys.index');
Route::get('/sharekeys/{shareKey}/edit', 'ShareKeyController@edit')->middleware('operator')->name('sharekeys.edit');
Route::get('/sharekeys/{shareKey}/detail', 'ShareKeyController@show')->middleware('operator')->name('sharekeys.show');
Route::post('/release', 'ShareKeyController@toggleReleasePatient')->middleware('operator');

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

Route::get('/download', function(){
    if(isset($_SERVER['HTTP_USER_AGENT'])) {
        $useragent=$_SERVER['HTTP_USER_AGENT'];
        $iPod = stripos($useragent,"iPod");
        $iPhone = stripos($useragent,"iPhone");
        $iPad = stripos($useragent,"iPad");
        $android= stripos($useragent,"Android");

        if( $iPod || $iPhone || $iPad ){
            return redirect()->away('https://apps.apple.com/vn/app/babycare-nhiet-ke-thong-minh/id1445659397');
        } else if ($android) {
            return redirect()->away('https://play.google.com/store/apps/details?id=app.nhietkethongminh.babycare');
        }
    }

    return ' Not support OS: ' . $useragent ;
});