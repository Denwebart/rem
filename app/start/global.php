<?php

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

	app_path().'/commands',
	app_path().'/controllers',
	app_path().'/models',
	app_path().'/database/seeds',

));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
*/

Log::useFiles(storage_path().'/logs/laravel.log');

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/
// Отловить ошибку в логах
//App::error(function(Exception $exception, $code)
//{
//	$pathInfo = Request::getPathInfo();
//	$message = $exception->getMessage() ?: 'Exception';
//	$previous = URL::previous();
//
//	if($code == 404){
//
//		Log::error("$code - $message @ $pathInfo (ref: $previous)");
//		return Response::view('error.404', array(), 404);
//	}else{
//		Log::error("$code - $message @ $pathInfo (ref: $previous)\r\n$exception");
//	}
//});

App::error(function(Illuminate\Session\TokenMismatchException $exception)
{
    Redirect::back();
    Log::error($exception);
});

App::error(function(Exception $exception, $code)
{
	if(403 == $code) {
		return Response::view('errors/403', ['user' => Auth::user()], 403);
	}
	Log::error($exception);
});

App::error(function(Illuminate\Database\Eloquent\ModelNotFoundException $exception)
{
	Log::error($exception);
	if(!Request::is('admin*')) {
		return Response::view('errors.404', [], 404);
	} else {
		return Response::view('admin::errors.404', [], 404);
	}
});

App::error(function(Symfony\Component\HttpKernel\Exception\NotFoundHttpException $exception)
{
	Log::error($exception);
	if(!Request::is('admin*')) {
		return Response::view('errors.404', [], 404);
	} else {
		return Response::view('admin::errors.404', [], 404);
	}
});

// Если нет связи
App::error(function(Symfony\Component\Debug\Exception\FatalErrorException $exception)
{
	Log::error($exception);
	if(!Request::is('admin*')) {
		return Response::view('errors.404', [], 404);
	} else {
		return Response::view('admin::errors.404', [], 404);
	}
});

// 405 (если попытаться зайти по роуту типа post)
App::error(function(Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException $exception)
{
	Log::error($exception);
	if(!Request::is('admin*')) {
		return Response::view('errors.404', [], 404);
	} else {
		return Response::view('admin::errors.404', [], 404);
	}
});


/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenance mode is in effect for the application.
|
*/

App::down(function()
{
	return Response::make("Be right back!", 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

require app_path().'/filters.php';

View::addNamespace('admin', app('path'). '/modules/admin/views');
View::addNamespace('cabinet', app('path'). '/modules/cabinet/views');

Paginator::setPageName('stranitsa');
//Paginator::setBaseUrl('custom/url');