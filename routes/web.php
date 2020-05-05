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
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::get('/home', function () {
    return view('home');
})->middleware('auth');

Route::get('/login/passwordless', 'Auth\PasswordlessLoginController@index')->name('passwordlessLoginPage');
Route::post('/login/passwordless', 'Auth\PasswordlessLoginController@sendToken')->name('passwordlessLogin');
Route::get('/login/passwordless/{token}', 'Auth\PasswordlessLoginController@validateToken');
