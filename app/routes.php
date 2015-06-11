<?php

Route::pattern('alias', '[A-Za-z0-9-_]+');

/* Админка */
Route::group(['prefix' => 'admin', 'before' => 'authInAdminPanel'], function(){
	Route::get('/', 'AdminController@index');
	Route::resource('pages', 'AdminPagesController');
	Route::post('pages/openTree', ['as' => 'admin.pages.openTree', 'uses' => 'AdminPagesController@openTree']);
	Route::get('pages/{id}/children', ['as' => 'admin.pages.children', 'uses' => 'AdminPagesController@children']);
	Route::resource('questions', 'AdminQuestionsController');
	Route::resource('articles', 'AdminArticlesController');
	Route::resource('comments', 'AdminCommentsController', ['except' => ['create']]);
	Route::resource('tags', 'AdminTagsController', ['except' => ['show']]);
	Route::get('tags/merge', ['as' => 'admin.tags.merge', 'uses' => 'AdminTagsController@merge']);
	Route::post('tags/merge_request', ['as' => 'admin.tags.postMerge', 'uses' => 'AdminTagsController@postMerge']);
	Route::get('tags/autocomplete', ['as' => 'admin.tags.autocomplete', 'uses' => 'AdminTagsController@autocomplete']);
	Route::post('tags/search', ['as' => 'admin.tags.search', 'uses' => 'AdminTagsController@search']);
	/* Страницы доступные только для админа */
	Route::group(['before' => 'isAdmin'], function(){
		Route::resource('users', 'AdminUsersController');
		Route::post('users/{id}/changeRole', ['as' => 'admin.users.changeRole', 'uses' => 'AdminUsersController@changeRole']);
		Route::resource('letters', 'AdminLettersController');
		Route::delete('letters/{id}', ['as' => 'admin.letters.markAsDeleted', 'uses' => 'AdminLettersController@markAsDeleted']);
		Route::post('letters/{id}/markAsNew', ['as' => 'admin.letters.markAsNew', 'uses' => 'AdminLettersController@markAsNew']);
		Route::get('letters/trash', ['as' => 'admin.letters.trash', 'uses' => 'AdminLettersController@trash']);
		Route::resource('settings', 'AdminSettingsController');
		Route::resource('honors', 'AdminHonorsController');
		Route::post('honors/toReward', ['as' => 'admin.honors.toReward', 'uses' => 'AdminHonorsController@toReward']);
		Route::get('honors/usersAutocomplete/{honorId}', ['as' => 'admin.honors.usersAutocomplete', 'uses' => 'AdminHonorsController@usersAutocomplete']);
		Route::resource('advertising', 'AdminАdvertisingController');
		Route::resource('rules', 'AdminRulesController', ['except' => ['show']]);

		// Копия базы
//		Route::get('backup', function(){
//			Artisan::call('db:backup', ['filename'=>'app/storage/dumps/avtorem_'. date('d-m-Y_H-i-s') .'.sql']);
//			Artisan::call('db:backup', ['filename'=>'app/storage/dumps/avtorem.sql']);
//		});
	});
});

/* Личный кабинет */
Route::group(['prefix' => 'users', 'before' => 'authInCabinet'], function(){
	Route::get('/', ['before' => 'authInCabinet', 'as' => 'users', 'uses' => 'CabinetController@index']);
	Route::get('autocomplete', ['before' => 'authInCabinet', 'as' => 'users.autocomplete', 'uses' => 'CabinetController@autocomplete']);
});
Route::group(['prefix' => 'honors'], function(){
	Route::get('/', ['as' => 'honors', 'uses' => 'CabinetController@honors']);
	Route::get('{alias}', ['as' => 'honor.info', 'uses' => 'CabinetController@honor']);
});
Route::group(['prefix' => 'user', 'before' => 'authInCabinet'], function(){
	Route::get('{login}/edit', ['as' => 'user.edit', 'uses' => 'CabinetUserController@edit']);
	Route::post('{login}/edit_request', ['as' => 'user.update', 'uses' => 'CabinetUserController@postEdit']);
	Route::post('{login}/delete_avatar', ['as' => 'user.deleteAvatar', 'uses' => 'CabinetUserController@deleteAvatar']);
	Route::post('{login}/gallery/uploadPhoto', ['as' => 'user.gallery.uploadPhoto', 'uses' => 'CabinetUserController@uploadPhoto']);
	Route::post('{login}/gallery/deletePhoto', ['as' => 'user.gallery.deletePhoto', 'uses' => 'CabinetUserController@deletePhoto']);
	Route::any('{login}/gallery/editPhoto/{id}', ['as' => 'user.gallery.editPhoto', 'uses' => 'CabinetUserController@editPhoto']);
	Route::get('{login}/questions/create', ['as' => 'user.questions.create', 'uses' => 'CabinetUserController@createQuestion']);
	Route::post('{login}/questions/store', ['as' => 'user.questions.store', 'uses' => 'CabinetUserController@storeQuestion']);
	Route::get('{login}/questions/{id}/edit', ['as' => 'user.questions.edit', 'uses' => 'CabinetUserController@editQuestion']);
	Route::put('{login}/questions/{id}', ['as' => 'user.questions.update', 'uses' => 'CabinetUserController@updateQuestion']);
	Route::post('{login}/questions/delete', ['as' => 'user.questions.delete', 'uses' => 'CabinetUserController@deleteQuestion']);
	Route::get('{login}/journal/create', ['as' => 'user.journal.create', 'uses' => 'CabinetUserController@createJournal']);
	Route::post('{login}/journal/store', ['as' => 'user.journal.store', 'uses' => 'CabinetUserController@storeJournal']);
	Route::get('{login}/journal/{id}/edit', ['as' => 'user.journal.edit', 'uses' => 'CabinetUserController@editJournal']);
	Route::put('{login}/journal/{id}', ['as' => 'user.journal.update', 'uses' => 'CabinetUserController@updateJournal']);
	Route::put('{login}/journal/tagAutocomplete', ['as' => 'user.journal.tagAutocomplete', 'uses' => 'CabinetUserController@tagAutocomplete']);
	Route::post('{login}/journal/delete', ['as' => 'user.journal.delete', 'uses' => 'CabinetUserController@deleteJournal']);
	Route::get('{login}/messages', ['as' => 'user.messages', 'uses' => 'CabinetUserController@messages']);
	Route::get('{login}/messages/{companion}', ['as' => 'user.dialog', 'uses' => 'CabinetUserController@dialog']);
	Route::post('{login}/messages/markMessageAsRead', ['as' => 'user.markMessageAsRead', 'uses' => 'CabinetUserController@markMessageAsRead']);
	Route::post('{login}/messages/addMessage/{companionId}', ['as' => 'user.addMessage', 'uses' => 'CabinetUserController@addMessage']);
	Route::get('{login}/saved', ['as' => 'user.savedPages', 'uses' => 'CabinetUserController@savedPages']);
	Route::post('{login}/savePage', ['as' => 'user.savePage', 'uses' => 'CabinetUserController@savePage']);
	Route::post('{login}/removePage', ['as' => 'user.removePage', 'uses' => 'CabinetUserController@removePage']);
	Route::get('{login}/subscriptions', ['as' => 'user.subscriptions', 'uses' => 'CabinetUserController@subscriptions']);
	Route::post('{login}/subscribe', ['as' => 'user.subscribe', 'uses' => 'CabinetUserController@subscribe']);
	Route::post('{login}/unsubscribe', ['as' => 'user.unsubscribe', 'uses' => 'CabinetUserController@unsubscribe']);
	Route::post('{login}/deleteNotification', ['as' => 'user.deleteNotification', 'uses' => 'CabinetUserController@deleteNotification']);
});
Route::group(['prefix' => 'user'], function() {
	Route::get('{login}', ['as' => 'user.profile', 'uses' => 'CabinetUserController@index']);
	Route::get('{login}/gallery', ['as' => 'user.gallery', 'uses' => 'CabinetUserController@gallery']);
	Route::get('{login}/questions', ['as' => 'user.questions', 'uses' => 'CabinetUserController@questions']);
	Route::get('{login}/journal', ['as' => 'user.journal', 'uses' => 'CabinetUserController@journal']);
	Route::get('{login}/comments', ['as' => 'user.comments', 'uses' => 'CabinetUserController@comments']);
});

/* Пользователи */
Route::get('register', ['as' => 'register', 'uses' => 'UsersController@getRegister']);
Route::post('register_request', ['as' => 'postRegister', 'uses' => 'UsersController@postRegister']);
Route::get('activate', ['as' => 'activate', 'uses' => 'UsersController@getActivate']);
Route::get('login', ['as' => 'login', 'uses' => 'UsersController@getLogin']);
Route::post('login_request', ['as' => 'postLogin', 'uses' => 'UsersController@postLogin']);
Route::get('logout', ['as' => 'logout', 'uses' => 'UsersController@getLogout']);

Route::controller('password', 'RemindersController');

/* Правила сайта */
Route::get('rules.html', ['as' => 'rules', 'uses' => 'UsersController@getRules']);
Route::post('rules_request', ['as' => 'postRules', 'uses' => 'UsersController@postRules']);

/* Фронт */

Route::get('/', 'SiteController@index');

Route::get('search', ['as' => 'search', 'uses' => 'SearchController@index']);

Route::get('{contactAlias}.html', 'SiteController@contact')->where('contactAlias', 'kontakty');
Route::post('contact_request', 'SiteController@contactPost');

Route::get('{sitemapHtmlAlias}.html', 'SiteController@sitemapHtml')->where('sitemapHtmlAlias', 'karta-sajta');
Route::get('sitemap.xml', 'SiteController@sitemapXml');
Route::get('rss', 'SiteController@rss');

Route::get('{journalAlias}', 'JournalController@index')->where('journalAlias', 'bortovoj-zhurnal');
Route::get('{journalAlias}/{login}', 'JournalController@journal')->where('journalAlias', 'bortovoj-zhurnal');
Route::get('{journalAlias}/{login}/{alias}.html', 'JournalController@article')->where('journalAlias', 'bortovoj-zhurnal');

Route::get('{questionsAlias}', 'SiteController@questions')->where('questionsAlias', 'vopros-otvet');
Route::get('{questionsAlias}/{alias}', 'SiteController@questionsCategory')->where('questionsAlias', 'vopros-otvet');
Route::get('{questionsAlias}/{categoryAlias}/{alias}.html', 'SiteController@question')->where('questionsAlias', 'vopros-otvet');

Route::post('add_comment/{id}', 'CommentsController@addComment');
Route::post('comment/vote/{id}', 'CommentsController@vote');
Route::post('comment/mark/{id}', 'CommentsController@mark');
Route::post('rating/stars/{id}', ['as' => 'rating.stars', 'uses' => 'RatingController@stars']);

Route::get('{alias}{suffix}', 'SiteController@firstLevel')->where('suffix', '.html');
Route::get('{categoryAlias}/{alias}.html', 'SiteController@secondLevel');

Route::get('{alias}', 'SiteController@firstLevel');
Route::get('{categoryAlias}/{alias}', 'SiteController@secondLevel');
Route::get('{parentCategoryAlias}/{categoryAlias}/{alias}.html', 'SiteController@thirdLevel');

//Route::controller('slug', 'MyController', [
//	'method'  => 'alias1',
//	'method2'  => 'alias2'
//]);