<?php

Route::get('/', 'SiteController@index');

Route::controller('users', 'UsersController');
Route::controller('password', 'RemindersController');

/*Админка*/
Route::group(['prefix' => 'admin'], function(){
	Route::get('/', 'AdminController@index');
	Route::resource('pages', 'AdminPagesController', ['except' => ['show']]);
});