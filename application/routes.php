<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Simply tell Laravel the HTTP verbs and URIs it should respond to. It is a
| breeze to setup your application using Laravel's RESTful routing and it
| is perfectly suited for building large applications and simple APIs.
|
| Let's respond to a simple GET request to http://example.com/hello:
|
|		Route::get('hello', function()
|		{
|			return 'Hello World!';
|		});
|
| You can even respond to more than one URI:
|
|		Route::post(array('hello', 'world'), function()
|		{
|			return 'Hello World!';
|		});
|
| It's easy to allow URI wildcards using (:num) or (:any):
|
|		Route::put('hello/(:any)', function($name)
|		{
|			return "Welcome, $name.";
|		});
|
*/


Route::get('(:any)/obtenerId/(:any)', function($tabla, $tag)
{
	$banda = DB::table($tabla)->where('urltag', '=', $tag)->get(array("id"));


	if(is_null($banda)){
	    return Response::json(array('Banda no encontrada', 404));
	} else {
	        return Response::json($banda);
	}
});
Route::get('/', function()
{
	return View::make('home.index');
});

Route::any('/fechas/(:num?)', array('as' => 'api.fechas', 'uses' => 'api.fechas@index'));
Route::any('/noticias/(:num?)', array('as' => 'api.noticias', 'uses' => 'api.noticias@index'));
Route::any('/artistas/(:num?)', array('as' => 'api.bandas', 'uses' => 'api.bandas@index'));
Route::any('/canciones/(:num?)', array('as' => 'api.canciones', 'uses' => 'api.canciones@index'));
Route::any('/videos/(:num?)', array('as' => 'api.videos', 'uses' => 'api.videos@index'));
Route::any('/fechas/nuevas', array('as' => 'api.fechas', 'uses' => 'api.fechas@index_nuevas'));
Route::any('/fechas/interior', array('as' => 'api.fechas', 'uses' => 'api.fechas@index_interior'));
Route::any('/fechas/gallegos', array('as' => 'api.fechas', 'uses' => 'api.fechas@index_gallegos'));
Route::any('/fechas/banda/(:num)', array('as' => 'api.fechas', 'uses' => 'api.fechas@index_bandas'));
Route::any('/noticias/banda/(:num)', array('as' => 'api.noticias', 'uses' => 'api.noticias@index_bandas'));


// Route::any('{tabla}/obtenerId/(tag)', array('uses' => 'api.obtenerId@index'));
/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
|
| To centralize and simplify 404 handling, Laravel uses an awesome event
| system to retrieve the response. Feel free to modify this function to
| your tastes and the needs of your application.
|
| Similarly, we use an event to handle the display of 500 level errors
| within the application. These errors are fired when there is an
| uncaught exception thrown in the application. The exception object
| that is captured during execution is then passed to the 500 listener.
|
*/

Event::listen('404', function()
{
	return Response::error('404');
});

Event::listen('500', function($exception)
{
	return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
|
| Filters provide a convenient method for attaching functionality to your
| routes. The built-in before and after filters are called before and
| after every request to your application, and you may even create
| other filters that can be attached to individual routes.
|
| Let's walk through an example...
|
| First, define a filter:
|
|		Route::filter('filter', function()
|		{
|			return 'Filtered!';
|		});
|
| Next, attach the filter to a route:
|
|		Route::get('/', array('before' => 'filter', function()
|		{
|			return 'Hello World!';
|		}));
|
*/

Route::filter('before', function()
{
	// Do stuff before every request to your application...
});

Route::filter('after', function($response)
{
	// Do stuff after every request to your application...
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::to('login');
});