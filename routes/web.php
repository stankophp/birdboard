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

Route::resource('projects', 'ProjectsController')->only(['index', 'show', 'create', 'store'])->middleware('auth');

Route::post('projects/{project}/tasks', 'ProjectTasksController@store')->middleware('auth');
Route::patch('projects/{project}/tasks/{task}', 'ProjectTasksController@update')->middleware('auth');

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

Route::get('/home', function () {
    return redirect(route('projects.index'));
});