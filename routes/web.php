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
use App\User;

Route::get('/', function () {
    return redirect('login');
});

Route::get('/mark-all-read/{user}',function(User $user){
	$user->unreadNotifications->markAsRead();
	return response(['message'=>'ok']);
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::any('/admin/{any?}', 'AdminController@index')->where('any','.*')->middleware('auth');
