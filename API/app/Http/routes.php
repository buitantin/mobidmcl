<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/categories/{id}/{option}', 'IndexController@categories');
Route::get('/categories_home', 'IndexController@categorieshome');
Route::get('/slideshow', 'IndexController@slideshow');

Route::get('/cachecate', 'IndexController@cachecate');


//For all product
Route::get('/list_product_cate_parent/{id}/{limit}', 'ProductController@list_product_cate_parent')
	  ;
Route::get('/promotionpress/{limit}', 'ProductController@promotionpress');
Route::get('/promotiononline/{limit}', 'ProductController@promotiononline');
Route::get('/promotion/{limit}', 'ProductController@promotion');
Route::get('/producthot/{limit}', 'ProductController@producthot');
Route::get('/getproduct/{cate}/{limit}', 'ProductController@getproduct');

Route::get('/productnew/{limit}', 'ProductController@productnew');
Route::get('/getcate/{id}', 'ProductController@getcate');
Route::get('/getlistcate/{id}', 'ProductController@getlistcate');
Route::get('/list_product_cate/{id}/{limit}', 'ProductController@list_product_cate');



Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
