<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

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
Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
    ], function()
{
    Route::get('/', "EmployeeController@index");
    Route::resource("employee","EmployeeController")->except(["destroy","update"]);
    Route::post("employee/destroy","EmployeeController@destroy")->name("employee.destroy");
    Route::post("employee/update","EmployeeController@update")->name("employee.update");
    Route::get("employee/search/{name}","EmployeeController@SearchEmployee")->name("employee.search");

    Route::get("getEmployees",'EmployeeController@GetAllEmployees')->name("employee.get");
});
