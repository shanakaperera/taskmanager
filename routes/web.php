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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('tasks', 'TaskController')->except(['index', 'show']);

Route::post('tasks/order', 'TaskController@order')->name('tasks.order');

Route::resource('projects', 'ProjectController')->except(['show','delete']);

Route::get('projects/{id}/tasks', 'ProjectController@tasks')->name('projects.tasks');
