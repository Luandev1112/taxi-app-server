<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/*
 * These routes use the root namespace 'App\Http\Controllers\Web'.
 */
Route::namespace('Web')->group(function () {

    // All the folder based web routes
    include_route_files('web');

    // Website home route
    Route::get('/', 'HomeController@index')->name('home');
});

Route::view('/adhoc1', 'adhoc/adhoc1')->name('adhoc1');
// Route::view('/adhoc2', 'adhoc/adhoc2')->name('adhoc2');
Route::view('/adhoc3', 'adhoc/adhoc3')->name('adhoc3');


Route::get('/adhoc', 'AdhocController@index');
Route::get('/adhoc2', 'AdhocController@create');
