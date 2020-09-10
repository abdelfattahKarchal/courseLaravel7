<?php

use Illuminate\Support\Facades\Auth;
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

/* ---------- cours 82 de laravel free à voir ------------- */



Route::get('/', function () {
    return view('welcome');
});
//Route::view('/', 'home');
//Route::get('/home', 'HomeController@home')->name('home');
Route::get('/about', 'HomeController@about')->name('about');
Route::get('/secret', 'HomeController@secret')
        ->name('secret')
        ->middleware('can:secret.page');

Route::get('/posts/archive','PostController@archive');
Route::get('/posts/all','PostController@all');
Route::patch('/posts/{id}/restore','PostController@restore');
Route::delete('/posts/{id}/forcedelete','PostController@forcedelete');
Route::resource('posts', 'PostController')->only(['index','show','create','store','edit','update','destroy']);

Route::get('posts/tag/{id}', 'PostTagController@index')->name('posts.tag.index');

 

/*Route::get('/about', function () {
    return view('about');
});*/
//Route::view('/about', 'about');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/contact', 'HomeController@contact')->name('contact');
