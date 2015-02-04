<?php

Route::get('/', 'SiteController@index');

Route::controller('users', 'UsersController');
Route::controller('password', 'RemindersController');