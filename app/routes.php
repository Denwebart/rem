<?php

/* Админка */
Route::group(['prefix' => 'admin'], function(){
	Route::get('/', 'AdminController@index');
	Route::resource('pages', 'AdminPagesController', ['except' => ['show']]);
	Route::resource('users', 'AdminUsersController');
	Route::resource('letters', 'AdminLettersController');
	Route::delete('admin/letters/{id}', ['as' => 'admin.letters.markAsDeleted', 'uses' => 'AdminLettersController@markAsDeleted']);
	Route::post('admin/letters/{id}/markAsNew', ['as' => 'admin.letters.markAsNew', 'uses' => 'AdminLettersController@markAsNew']);
	Route::get('admin/letters/trash', ['as' => 'admin.letters.trash', 'uses' => 'AdminLettersController@trash']);
});

/* Личный кабинет */
Route::group(['prefix' => 'user'], function(){
	Route::get('/', 'CabinetController@index');
	Route::get('{login}', ['as' => 'user.profile', 'uses' => 'CabinetUserController@index']);
	Route::get('{login}/edit', ['as' => 'user.edit', 'uses' => 'CabinetUserController@edit']);
	Route::post('{id}/edit_request', ['as' => 'user.update', 'uses' => 'CabinetUserController@postEdit']);
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

Route::post('add_comment/{id}', 'CommentsController@addComment');

Route::get('{sitemapHtmlAlias}', 'SiteController@sitemapHtml')->where('sitemapHtmlAlias', 'karta-sajta');
Route::get('sitemap.xml', 'SiteController@sitemapXml');

Route::get('{alias}', 'SiteController@firstLevel');
Route::get('{categoryAlias}/{alias}', 'SiteController@secondLevel');
Route::get('{parentCategoryAlias}/{categoryAlias}/{alias}', 'SiteController@thirdLevel');



