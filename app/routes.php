<?php

/* Заполнение данных */
Route::get('/fill', function() {
    $result_data = [
    ];
	$i = 0;
	foreach($result_data as $item) {
		if(Comment::whereId($item['id'])->update($item)) {
			echo 'Данные заполнены! ';
			echo $item['id'];
			echo '</br>';
			$i++;
		}
	}
	echo '</br>-----------</br>';
	echo 'Заполнено ' . $i . ' из ' . count($result_data);
});


Route::pattern('alias', '[A-Za-z0-9-_]+');

/* Админка */
Route::group(['prefix' => 'admin', 'before' => 'authInAdminPanel'], function(){

	Route::get('/', 'AdminController@index');
    Route::get('pages/search', ['as' => 'admin.pages.search', 'before' => 'csrf-ajax', 'uses' => 'AdminPagesController@search']);
	Route::get('pages/articlesAutocomplete', ['as' => 'admin.pages.articlesAutocomplete', 'uses' => 'AdminPagesController@articlesAutocomplete']);
	Route::get('pages/questionsAutocomplete', ['as' => 'admin.pages.questionsAutocomplete', 'uses' => 'AdminPagesController@questionsAutocomplete']);
	Route::post('pages/checkRelated', ['as' => 'admin.pages.checkRelated', 'before' => 'csrf-ajax', 'uses' => 'AdminPagesController@checkRelated']);
	Route::post('pages/deleteImage/{id}', ['as' => 'admin.pages.deleteImage', 'before' => 'csrf-ajax', 'uses' => 'AdminPagesController@deleteImage']);
	Route::get('pages/metadata', ['as' => 'admin.pages.metadata', 'uses' => 'AdminPagesController@metadata']);
	Route::resource('pages', 'AdminPagesController');
	Route::post('pages/openTree', ['as' => 'admin.pages.openTree', 'before' => 'csrf-ajax', 'uses' => 'AdminPagesController@openTree']);
    Route::get('questions/search', ['as' => 'admin.questions.search', 'before' => 'csrf-ajax', 'uses' => 'AdminQuestionsController@search']);
    Route::resource('questions', 'AdminQuestionsController');
    Route::get('articles/search', ['as' => 'admin.articles.search', 'before' => 'csrf-ajax', 'uses' => 'AdminArticlesController@search']);
    Route::resource('articles', 'AdminArticlesController');
    Route::get('comments/search', ['as' => 'admin.comments.search', 'before' => 'csrf-ajax', 'uses' => 'AdminCommentsController@search']);
	Route::post('comments/markAsDelete/{id}', ['as' => 'admin.comments.markAsDelete', 'before' => 'csrf-ajax', 'uses' => 'AdminCommentsController@ajaxMarkAsDeleted']);
	Route::post('comments/ajaxDelete/{id}', ['as' => 'admin.comments.ajaxDelete', 'before' => 'csrf-ajax', 'uses' => 'AdminCommentsController@ajaxDelete']);
	Route::resource('comments', 'AdminCommentsController', ['except' => ['create']]);
    Route::get('tags/search', ['as' => 'admin.tags.search', 'before' => 'csrf-ajax', 'uses' => 'AdminTagsController@search']);
    Route::post('tags/deleteImage/{id}', ['as' => 'admin.tags.deleteImage', 'before' => 'csrf-ajax', 'uses' => 'AdminTagsController@deleteImage']);
	Route::resource('tags', 'AdminTagsController', ['except' => ['show']]);
	Route::get('tags/merge', ['as' => 'admin.tags.merge', 'uses' => 'AdminTagsController@merge']);
	Route::post('tags/merge_request', ['as' => 'admin.tags.postMerge', 'before' => 'csrf-ajax', 'uses' => 'AdminTagsController@postMerge']);
	Route::get('tags/autocomplete', ['as' => 'admin.tags.autocomplete', 'uses' => 'AdminTagsController@autocomplete']);
	Route::post('tags/search', ['as' => 'admin.tags.search', 'before' => 'csrf-ajax', 'uses' => 'AdminTagsController@search']);
	/* Страницы доступные только для админа */
	Route::group(['before' => 'isAdmin'], function(){
        Route::get('users/search', ['as' => 'admin.users.search', 'before' => 'csrf-ajax', 'uses' => 'AdminUsersController@search']);
		Route::get('users/banned', ['as' => 'admin.users.banned', 'uses' => 'AdminUsersController@banned']);
        Route::post('users/ban/{id}', ['as' => 'admin.users.ban', 'before' => 'csrf-ajax', 'uses' => 'AdminUsersController@ban']);
        Route::post('users/unban/{id}', ['as' => 'admin.users.unban', 'before' => 'csrf-ajax', 'uses' => 'AdminUsersController@unban']);
        Route::resource('users', 'AdminUsersController');
        Route::get('ips', ['as' => 'admin.ips.index', 'uses' => 'AdminIpsController@index']);
        Route::get('ips/search', ['as' => 'admin.ips.search', 'before' => 'csrf-ajax', 'uses' => 'AdminIpsController@search']);
        Route::get('ips/bannedIps', ['as' => 'admin.ips.bannedIps', 'uses' => 'AdminIpsController@bannedIps']);
		Route::post('ips/banIp', ['as' => 'admin.ips.banIp', 'before' => 'csrf-ajax', 'uses' => 'AdminIpsController@banIp']);
		Route::post('ips/banIp/{ipId}', ['as' => 'admin.ips.banIp', 'before' => 'csrf-ajax', 'uses' => 'AdminIpsController@banIp']);
		Route::post('ips/unbanIp/{id}', ['as' => 'admin.ips.unbanIp', 'before' => 'csrf-ajax', 'uses' => 'AdminIpsController@unbanIp']);
		Route::get('ips/ipsAutocomplete', ['as' => 'admin.ips.ipsAutocomplete', 'uses' => 'AdminIpsController@ipsAutocomplete']);
		Route::post('users/{id}/changeRole', ['as' => 'admin.users.changeRole', 'before' => 'csrf-ajax', 'uses' => 'AdminUsersController@changeRole']);
        Route::get('letters/search', ['as' => 'admin.letters.search', 'before' => 'csrf-ajax', 'uses' => 'AdminLettersController@search']);
        Route::get('letters/trash', ['as' => 'admin.letters.trash', 'uses' => 'AdminLettersController@trash']);
		Route::resource('letters', 'AdminLettersController');
		Route::post('letters/markAsDeleted/{id}', ['as' => 'admin.letters.markAsDeleted', 'before' => 'csrf', 'uses' => 'AdminLettersController@markAsDeleted']);
		Route::post('letters/{id}/markAsNew', ['as' => 'admin.letters.markAsNew', 'before' => 'csrf', 'uses' => 'AdminLettersController@markAsNew']);
        Route::get('settings/search', ['as' => 'admin.settings.search', 'before' => 'csrf-ajax', 'uses' => 'AdminSettingsController@search']);
        Route::resource('settings', 'AdminSettingsController');
        Route::get('honors/search', ['as' => 'admin.honors.search', 'before' => 'csrf-ajax', 'uses' => 'AdminHonorsController@search']);
        Route::post('honors/deleteImage/{id}', ['as' => 'admin.honors.deleteImage', 'before' => 'csrf-ajax', 'uses' => 'AdminHonorsController@deleteImage']);
		Route::resource('honors', 'AdminHonorsController');
		Route::post('honors/toReward', ['as' => 'admin.honors.toReward', 'before' => 'csrf-ajax', 'uses' => 'AdminHonorsController@toReward']);
		Route::post('honors/removeReward', ['as' => 'admin.honors.removeReward', 'before' => 'csrf-ajax', 'uses' => 'AdminHonorsController@removeReward']);
		Route::get('honors/usersAutocomplete/{honorId}', ['as' => 'admin.honors.usersAutocomplete', 'uses' => 'AdminHonorsController@usersAutocomplete']);
        Route::get('advertising/search', ['as' => 'admin.advertising.search', 'before' => 'csrf-ajax', 'uses' => 'AdminАdvertisingController@search']);
        Route::post('advertising/changeActiveStatus/{advertisingId}', ['as' => 'admin.advertising.changeActiveStatus', 'before' => 'csrf-ajax', 'uses' => 'AdminАdvertisingController@changeActiveStatus']);
		Route::resource('advertising', 'AdminАdvertisingController', ['except' => ['show']]);
        Route::get('rules/search', ['as' => 'admin.rules.search', 'before' => 'csrf-ajax', 'uses' => 'AdminRulesController@search']);
		Route::resource('rules', 'AdminRulesController', ['except' => ['show']]);
        Route::get('notificationsMessages/search', ['as' => 'admin.notificationsMessages.search', 'before' => 'csrf-ajax', 'uses' => 'AdminNotificationsMessagesController@search']);
		Route::resource('notificationsMessages', 'AdminNotificationsMessagesController', ['except' => ['show', 'create', 'destroy']]);
		Route::get('menus', ['as' => 'admin.menus.index', 'uses' => 'AdminMenusController@index']);
		Route::get('menus/items/{type}', ['as' => 'admin.menus.items', 'uses' => 'AdminMenusController@items']);
		Route::post('menus/items/{type}/changePosition', ['as' => 'admin.menus.changePosition', 'before' => 'csrf-ajax', 'uses' => 'AdminMenusController@changePosition']);
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

/* Награды */
Route::get('nagrady', ['as' => 'honors', 'uses' => 'CabinetController@honors']);
Route::get('nagrady.html', 'SiteController@error404');
Route::get('nagrady/{alias}', ['as' => 'honor.info', 'uses' => 'CabinetController@honor']);

Route::group(['prefix' => 'user', 'before' => 'authInCabinet'], function(){
	Route::get('{login}/settings', ['as' => 'user.settings', 'uses' => 'CabinetUserController@getSettings']);
	Route::post('{login}/settings_request', ['as' => 'user.postSettings', 'before' => 'csrf', 'uses' => 'CabinetUserController@postSettings']);
	Route::get('{login}/changePassword', ['as' => 'user.changePassword', 'uses' => 'CabinetUserController@getChangePassword']);
	Route::post('{login}/change_password_request', ['as' => 'user.postChangePassword', 'before' => 'csrf', 'uses' => 'CabinetUserController@postChangePassword']);
	Route::get('{login}/edit', ['as' => 'user.edit', 'uses' => 'CabinetUserController@edit']);
	Route::post('{login}/edit_request', ['as' => 'user.update', 'before' => 'csrf', 'uses' => 'CabinetUserController@postEdit']);
	Route::post('{login}/delete_avatar', ['as' => 'user.deleteAvatar', 'before' => 'csrf-ajax', 'uses' => 'CabinetUserController@deleteAvatar']);
	Route::post('{login}/gallery/uploadPhoto', ['as' => 'user.gallery.uploadPhoto', 'before' => 'csrf', 'uses' => 'CabinetUserController@uploadPhoto']);
	Route::post('{login}/gallery/deletePhoto', ['as' => 'user.gallery.deletePhoto', 'before' => 'csrf-ajax', 'uses' => 'CabinetUserController@deletePhoto']);
	Route::get('{login}/gallery/editPhoto/{id}', ['as' => 'user.gallery.editPhoto', 'uses' => 'CabinetUserController@editPhoto']);
	Route::post('{login}/gallery/editPhoto/{id}', ['as' => 'user.gallery.editPhoto', 'before' => 'csrf', 'uses' => 'CabinetUserController@editPhoto']);
	Route::get('{login}/questions/create', ['as' => 'user.questions.create', 'uses' => 'CabinetUserController@createQuestion']);
	Route::post('{login}/questions/store', ['as' => 'user.questions.store', 'before' => 'csrf', 'uses' => 'CabinetUserController@storeQuestion']);
	Route::get('{login}/questions/{id}/edit', ['as' => 'user.questions.edit', 'uses' => 'CabinetUserController@editQuestion']);
	Route::put('{login}/questions/{id}', ['as' => 'user.questions.update', 'before' => 'csrf', 'uses' => 'CabinetUserController@updateQuestion']);
	Route::post('{login}/questions/delete', ['as' => 'user.questions.delete', 'before' => 'csrf-ajax', 'uses' => 'CabinetUserController@deleteQuestion']);
	Route::get('{login}/journal/create', ['as' => 'user.journal.create', 'uses' => 'CabinetUserController@createJournal']);
	Route::post('{login}/journal/store', ['as' => 'user.journal.store', 'before' => 'csrf', 'uses' => 'CabinetUserController@storeJournal']);
	Route::post('{login}/preview', ['as' => 'user.preview', 'before' => 'csrf-ajax', 'uses' => 'CabinetUserController@preview']);
	Route::get('{login}/journal/{id}/edit', ['as' => 'user.journal.edit', 'uses' => 'CabinetUserController@editJournal']);
	Route::put('{login}/journal/{id}', ['as' => 'user.journal.update', 'before' => 'csrf', 'uses' => 'CabinetUserController@updateJournal']);
	Route::post('{login}/journal/delete', ['as' => 'user.journal.delete', 'before' => 'csrf-ajax', 'uses' => 'CabinetUserController@deleteJournal']);
	Route::post('{login}/deleteImageFromPage/{id}', ['as' => 'user.deleteImageFromPage', 'before' => 'csrf-ajax', 'uses' => 'CabinetUserController@deleteImageFromPage']);
	Route::get('{login}/messages', ['as' => 'user.messages', 'uses' => 'CabinetUserController@messages']);
	Route::get('{login}/messages/{companion}', ['as' => 'user.dialog', 'uses' => 'CabinetUserController@dialog']);
	Route::post('{login}/messages/markMessageAsRead', ['as' => 'user.markMessageAsRead', 'before' => 'csrf-ajax', 'uses' => 'CabinetUserController@markMessageAsRead']);
	Route::post('{login}/messages/addMessage/{companionId}', ['as' => 'user.addMessage', 'before' => 'csrf-ajax', 'uses' => 'CabinetUserController@addMessage']);
	Route::post('{login}/messages/{companion}', ['as' => 'user.reloadMessages', 'uses' => 'CabinetUserController@reloadMessages']);
	Route::get('{login}/comments/{id}/edit', ['as' => 'user.comments.edit', 'uses' => 'CabinetUserController@editComment']);
	Route::put('{login}/comments/{id}', ['as' => 'user.comments.update', 'before' => 'csrf', 'uses' => 'CabinetUserController@updateComment']);
	Route::post('{login}/deleteComment', ['as' => 'user.deleteComment', 'before' => 'csrf-ajax', 'uses' => 'CabinetUserController@deleteComment']);
	Route::get('{login}/answers/{id}/edit', ['as' => 'user.answers.edit', 'uses' => 'CabinetUserController@editAnswer']);
	Route::post('{login}/deleteAnswer', ['as' => 'user.deleteAnswer', 'before' => 'csrf-ajax', 'uses' => 'CabinetUserController@deleteAnswer']);
	Route::get('{login}/saved', ['as' => 'user.savedPages', 'uses' => 'CabinetUserController@savedPages']);
	Route::post('{login}/savePage', ['as' => 'user.savePage', 'before' => 'csrf-ajax', 'uses' => 'CabinetUserController@savePage']);
	Route::post('{login}/removePage', ['as' => 'user.removePage', 'before' => 'csrf-ajax', 'uses' => 'CabinetUserController@removePage']);
	Route::post('{login}/removeAllPages', ['as' => 'user.removeAllPages', 'before' => 'csrf-ajax', 'uses' => 'CabinetUserController@removeAllPages']);
	Route::get('{login}/subscriptions', ['as' => 'user.subscriptions', 'uses' => 'CabinetUserController@subscriptions']);
	Route::post('{login}/subscribe', ['as' => 'user.subscribe', 'before' => 'csrf-ajax', 'uses' => 'CabinetUserController@subscribe']);
	Route::post('{login}/unsubscribe', ['as' => 'user.unsubscribe', 'before' => 'csrf-ajax', 'uses' => 'CabinetUserController@unsubscribe']);
	Route::post('{login}/unsubscribeFromAll', ['as' => 'user.unsubscribeFromAll', 'before' => 'csrf-ajax', 'uses' => 'CabinetUserController@unsubscribeFromAll']);
	Route::post('{login}/deleteSubscriptionNotification', ['as' => 'user.deleteSubscriptionNotification', 'before' => 'csrf-ajax', 'uses' => 'CabinetUserController@deleteSubscriptionNotification']);
	Route::get('{login}/notifications', ['as' => 'user.notifications', 'uses' => 'CabinetUserController@notifications']);
	Route::post('{login}/deleteNotification', ['as' => 'user.deleteNotification', 'before' => 'csrf-ajax', 'uses' => 'CabinetUserController@deleteNotification']);
	Route::post('{login}/deleteAllNotifications', ['as' => 'user.deleteAllNotifications', 'before' => 'csrf-ajax', 'uses' => 'CabinetUserController@deleteAllNotifications']);
});
Route::group(['prefix' => 'user'], function() {
	Route::get('{login}', ['as' => 'user.profile', 'uses' => 'CabinetUserController@index']);
	Route::get('{login}/gallery', ['as' => 'user.gallery', 'uses' => 'CabinetUserController@gallery']);
	Route::get('{login}/questions', ['as' => 'user.questions', 'uses' => 'CabinetUserController@questions']);
	Route::get('{login}/comments', ['as' => 'user.comments', 'uses' => 'CabinetUserController@comments']);
	Route::get('{login}/answers', ['as' => 'user.answers', 'uses' => 'CabinetUserController@answers']);
});

/* Пользователи */
Route::get('register', ['as' => 'register', 'uses' => 'UsersController@getRegister']);
Route::post('register_request', ['as' => 'postRegister', 'before' => 'csrf', 'uses' => 'UsersController@postRegister']);
Route::get('activate/{userId}/{activationCode}', ['as' => 'activate', 'uses' => 'UsersController@getActivate']);
Route::get('login', ['as' => 'login', 'uses' => 'UsersController@getLogin']);
Route::post('login_request', ['as' => 'postLogin', 'before' => 'csrf', 'uses' => 'UsersController@postLogin']);
Route::get('logout', ['as' => 'logout', 'uses' => 'UsersController@getLogout']);

Route::controller('password', 'RemindersController');

/* Правила сайта */
Route::get('{rulesAlias}.html', ['as' => 'rules', 'uses' => 'UsersController@getRules'])->where('rulesAlias', 'rules');
Route::post('rules_request', ['as' => 'postRules', 'before' => ['csrf', 'authInCabinet'], 'uses' => 'UsersController@postRules']);

/* Загрузка изображений TinyMCE */
Route::post('uploadIntoTemp', ['as' => 'uploadIntoTemp', 'before' => 'csrf-ajax', 'uses' => 'ImageUploadController@uploadIntoTemp']);
Route::post('deleteFromTemp', ['as' => 'deleteFromTemp', 'before' => 'csrf-ajax', 'uses' => 'ImageUploadController@deleteFromTemp']);

/* Фронт */
Route::get('/', 'SiteController@index');

Route::get('search', ['as' => 'search', 'uses' => 'SearchController@index']);
Route::get('top', ['as' => 'top', 'uses' => 'TopController@index']);

Route::get('{contactAlias}.html', 'SiteController@contact')->where('contactAlias', 'kontakty');
Route::post('contact_request', ['before' => 'csrf', 'uses' => 'SiteController@contactPost']);

Route::get('{sitemapHtmlAlias}.html', 'SiteController@sitemapHtml')->where('sitemapHtmlAlias', 'karta-sajta');
Route::get('sitemap.xml', 'SiteController@sitemapXml');
Route::get('rss.xml', ['as' => 'rss', 'uses' => 'SiteController@rss']);

Route::get('{journalAlias}', 'JournalController@index')->where('journalAlias', 'bortovoj-zhurnal');
Route::get('{journalAlias}/tag', ['as' => 'journal.tags', 'uses' => 'JournalController@tags'])->where('journalAlias', 'bortovoj-zhurnal');
Route::get('{journalAlias}/tag/{tag}', ['as' => 'journal.tag', 'uses' => 'JournalController@tag'])->where('journalAlias', 'bortovoj-zhurnal');
Route::get('{journalAlias}/{login}', ['as' => 'user.journal', 'uses' => 'JournalController@journal'])->where('journalAlias', 'bortovoj-zhurnal');
Route::get('{journalAlias}/{login}/{alias}.html', 'JournalController@article')->where('journalAlias', 'bortovoj-zhurnal');

Route::get('tagAutocomplete', ['as' => 'tagAutocomplete', 'uses' => 'JournalController@tagAutocomplete']);

Route::get('{questionsAlias}', 'SiteController@questions')->where('questionsAlias', 'vopros-otvet');
Route::get('{questionsAlias}/{alias}', 'SiteController@questionsCategory')->where('questionsAlias', 'vopros-otvet');
Route::get('{questionsAlias}/{alias}.html', 'SiteController@error404')->where('questionsAlias', 'vopros-otvet');
Route::get('{questionsAlias}/{categoryAlias}/{alias}.html', 'SiteController@question')->where('questionsAlias', 'vopros-otvet');

Route::post('add_comment/{id}', [ 'before' => 'csrf-ajax', 'uses' => 'CommentsController@addComment']);
Route::post('comment/vote/{id}', [ 'before' => 'csrf-ajax', 'uses' => 'CommentsController@vote']);
Route::post('comment/mark/{id}', [ 'before' => 'csrf-ajax', 'uses' => 'CommentsController@mark']);
Route::post('rating/stars/{id}', [ 'before' => 'csrf-ajax', 'as' => 'rating.stars', 'uses' => 'RatingController@stars']);

Route::get('{alias}{suffix}', 'SiteController@firstLevel')->where('suffix', '.html');
Route::get('{categoryAlias}/{alias}{suffix}', 'SiteController@secondLevel')->where('suffix', '.html');

Route::get('{alias}', 'SiteController@firstLevel');
Route::get('{categoryAlias}/{alias}', 'SiteController@secondLevel');
Route::get('{parentCategoryAlias}/{categoryAlias}/{alias}.html', 'SiteController@thirdLevel');

//Route::controller('slug', 'MyController', [
//	'method'  => 'alias1',
//	'method2'  => 'alias2'
//]);