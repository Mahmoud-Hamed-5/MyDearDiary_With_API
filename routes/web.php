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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/profile', 'ProfileController@index')->name('profile');
Route::Put('/profile/update', 'ProfileController@update')->name('profile.update');
Route::Put('/profile/store', 'ProfileController@store')->name('profile.store');

Route::get('/posts', 'PostController@index')->name('posts');
Route::get('/posts/trashed', 'PostController@trashedPosts')->name('posts.trashed');
Route::get('/post/create', 'PostController@create')->name('post.create');
Route::post('/post/store', 'PostController@store')->name('post.store');
Route::get('/post/show/{slug}', 'PostController@show')->name('post.show');
Route::get('/post/edit/{id}', 'PostController@edit')->name('post.edit');
Route::post('/post/update/{id}', 'PostController@update')->name('post.update');
Route::get('/post/destroy/{id}', 'PostController@destroy')->name('post.destroy');
Route::get('/post/hdelete/{id}', 'PostController@hdelete')->name('post.hdelete');
Route::get('/post/restore/{id}', 'PostController@restore')->name('post.restore');

Route::get('/post/isliked/{id}', 'PostController@isliked')->name('post.isliked');
Route::post('/post/like/{id}', 'PostController@like')->name('post.like');
Route::post('/post/dislike/{id}', 'PostController@dislike')->name('post.dislike');

Route::get('/post/likenames/{id}', 'PostController@likenames')->name('post.likenames');

Route::resource('comments' , 'CommentController');


