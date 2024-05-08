<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    //return view('welcome');
	abort(404);
});

Route::get('/linetoday/news', function () {
    $xml = simplexml_load_file(public_path()."/xml_linetoday/news.xml");
	$xml_string = $xml->asXML();
	return \Response::make($xml_string , '200')->header('Content-Type', 'text/xml');
});

Route::get('/linetoday/video', function () {
    $xml = simplexml_load_file(public_path()."/xml_linetoday/video.xml");
	$xml_string = $xml->asXML();
	return \Response::make($xml_string , '200')->header('Content-Type', 'text/xml');
});
