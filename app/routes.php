<?php

/* Админка */
Route::group(['prefix' => 'admin', 'before' => 'authInAdminPanel'], function(){
	Route::get('/', 'AdminController@index');
	Route::resource('pages', 'AdminPagesController', ['except' => ['show']]);
	Route::resource('comments', 'AdminCommentsController', ['except' => ['show']]);
	Route::resource('users', 'AdminUsersController');
	Route::post('users/{id}/changeRole', ['as' => 'admin.users.changeRole', 'uses' => 'AdminUsersController@changeRole']);
	Route::resource('letters', 'AdminLettersController');
	Route::delete('admin/letters/{id}', ['as' => 'admin.letters.markAsDeleted', 'uses' => 'AdminLettersController@markAsDeleted']);
	Route::post('admin/letters/{id}/markAsNew', ['as' => 'admin.letters.markAsNew', 'uses' => 'AdminLettersController@markAsNew']);
	Route::get('admin/letters/trash', ['as' => 'admin.letters.trash', 'uses' => 'AdminLettersController@trash']);
});

/* Личный кабинет */
Route::get('users', ['before' => 'auth', 'as' => 'users', 'uses' => 'CabinetController@index']);
Route::get('users/autocomplete', ['before' => 'auth', 'as' => 'users.autocomplete', 'uses' => 'CabinetController@autocomplete']);
Route::group(['prefix' => 'user', 'before' => 'auth'], function(){
	Route::get('{login}', ['as' => 'user.profile', 'uses' => 'CabinetUserController@index']);
	Route::get('{login}/edit', ['as' => 'user.edit', 'uses' => 'CabinetUserController@edit']);
	Route::post('{login}/edit_request', ['as' => 'user.update', 'uses' => 'CabinetUserController@postEdit']);
	Route::post('{login}/delete_avatar', ['as' => 'user.deleteAvatar', 'uses' => 'CabinetUserController@deleteAvatar']);
	Route::get('{login}/gallery', ['as' => 'user.gallery', 'uses' => 'CabinetUserController@gallery']);
	Route::post('{login}/gallery/uploadPhoto', ['as' => 'user.gallery.uploadPhoto', 'uses' => 'CabinetUserController@uploadPhoto']);
	Route::post('{login}/gallery/deletePhoto', ['as' => 'user.gallery.deletePhoto', 'uses' => 'CabinetUserController@deletePhoto']);
	Route::any('{login}/gallery/editPhoto/{id}', ['as' => 'user.gallery.editPhoto', 'uses' => 'CabinetUserController@editPhoto']);
	Route::get('{login}/questions', ['as' => 'user.questions', 'uses' => 'CabinetUserController@questions']);
	Route::get('{login}/journal', ['as' => 'user.journal', 'uses' => 'CabinetUserController@journal']);
	Route::get('{login}/comments', ['as' => 'user.comments', 'uses' => 'CabinetUserController@comments']);
	Route::get('{login}/messages', ['as' => 'user.messages', 'uses' => 'CabinetUserController@messages']);
	Route::get('{login}/messages/{companion}', ['as' => 'user.dialog', 'uses' => 'CabinetUserController@dialog']);
	Route::post('messages/markMessageAsRead', ['as' => 'user.markMessageAsRead', 'uses' => 'CabinetUserController@markMessageAsRead']);
	Route::post('messages/addMessage/{id}', ['as' => 'user.addMessage', 'uses' => 'CabinetUserController@addMessage']);
	Route::get('{login}/subscriptions', ['as' => 'user.subscriptions', 'uses' => 'CabinetUserController@subscriptions']);
});

/* Пользователи */
Route::controller('users', 'UsersController');
Route::controller('password', 'RemindersController');


/* Фронт */
Route::get('/', 'SiteController@index');

Route::get('search', ['as' => 'search', 'uses' => 'SearchController@index']);

Route::get('{contactAlias}', 'SiteController@contact')->where('contactAlias', 'kontakty');
Route::post('contact_request', 'SiteController@contactPost');

Route::get('{sitemapHtmlAlias}', 'SiteController@sitemapHtml')->where('sitemapHtmlAlias', 'karta-sajta');
Route::get('sitemap.xml', 'SiteController@sitemapXml');

Route::get('{journalAlias}', 'JournalController@index')->where('journalAlias', 'bortovoj-zhurnal');

Route::get('{questionsAlias}', 'SiteController@questions')->where('questionsAlias', 'voprosotvet');
Route::get('{questionsAlias}/{alias}', 'SiteController@questionsCategory')->where('questionsAlias', 'voprosotvet');
Route::get('{questionsAlias}/{categoryAlias}/{alias}', 'SiteController@question')->where('questionsAlias', 'voprosotvet');

Route::post('add_comment/{id}', 'CommentsController@addComment');
Route::post('comment/vote/{id}', 'CommentsController@vote');
Route::post('rating/stars/{id}', ['as' => 'rating.stars', 'uses' => 'RatingController@stars']);

Route::get('{alias}', 'SiteController@firstLevel');
Route::get('{categoryAlias}/{alias}', 'SiteController@secondLevel');
Route::get('{parentCategoryAlias}/{categoryAlias}/{alias}', 'SiteController@thirdLevel');



