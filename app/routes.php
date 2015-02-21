<?php

/* Админка */
Route::group(['prefix' => 'admin'], function(){
	Route::get('/', 'AdminController@index');
	Route::resource('pages', 'AdminPagesController', ['except' => ['show']]);
	Route::resource('letters', 'AdminLettersController');
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



