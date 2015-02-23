<?php

/* Админка */
Route::group(['prefix' => 'admin'], function(){
	Route::get('/', 'AdminController@index');
	Route::resource('pages', 'AdminPagesController', ['except' => ['show']]);
	Route::resource('letters', 'AdminLettersController');
});

/* Личный кабинет */
Route::group(['prefix' => 'user'], function(){
	Route::get('/', 'CabinetController@index');
	Route::get('{login}', ['as' => 'user.profile', 'uses' => 'CabinetUserController@index']);
	Route::get('{login}/edit', ['as' => 'user.edit', 'uses' => 'CabinetUserController@edit']);
	Route::get('{login}/gallery', ['as' => 'user.gallery', 'uses' => 'CabinetUserController@gallery']);
	Route::get('{login}/questions', ['as' => 'user.questions', 'uses' => 'CabinetUserController@questions']);
	Route::get('{login}/comments', ['as' => 'user.comments', 'uses' => 'CabinetUserController@comments']);
	Route::get('{login}/messages', ['as' => 'user.messages', 'uses' => 'CabinetUserController@messages']);
	Route::get('{login}/friends', ['as' => 'user.friends', 'uses' => 'CabinetUserController@friends']);
});

/* Пользователи */
Route::controller('users', 'UsersController');
Route::controller('password', 'RemindersController');


/* Фронт */
Route::get('/', 'SiteController@index');
Route::get('{contactAlias}', 'SiteController@contact')->where('contactAlias', 'kontakty');
Route::post('contact_request', 'SiteController@contactPost');

Route::get('{sitemapHtmlAlias}', 'SiteController@sitemapHtml')->where('sitemapHtmlAlias', 'karta-sajta');
Route::get('sitemap.xml', 'SiteController@sitemapXml');

Route::get('{alias}', 'SiteController@firstLevel');
Route::get('{categoryAlias}/{alias}', 'SiteController@secondLevel');
Route::get('{parentCategoryAlias}/{categoryAlias}/{alias}', 'SiteController@thirdLevel');



