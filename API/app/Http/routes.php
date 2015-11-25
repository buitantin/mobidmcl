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
Route::get('/getelement/{id_cate}/{id_product}', 'ProductController@getelement');
Route::get('/gettemplate/{id}/{supplier}', 'ProductController@gettemplate');
Route::get('/getdetailproduct/{id}', 'ProductController@getdetailproduct');

Route::get('/get_element_product/{id}/{element}', 'ProductController@get_element_product');

Route::get('/filter_cate/{id}', 'FilterController@getcate');
Route::get('/filter_series/{id}', 'FilterController@getseries');
Route::get('/filter_price/{id}', 'FilterController@getprice');
Route::get('/filter_template/{id}', 'FilterController@gettemplate');
Route::any('/filter_product/{id}', 'FilterController@getproduct');

Route::any('/detail_product/{id}/{supplier}', 'DetailController@detail');
Route::get('/detail_gift/{id}/{supplier}', 'DetailController@getgift');
Route::get('/detail_payment/{id_cate}', 'DetailController@getpayment');
Route::get('/detail_price/{id}', 'DetailController@getpricenewold');
Route::get('/detail_buy/{id}', 'DetailController@getbuytogether');
Route::get('/detail_element/{id}', 'DetailController@getelement');
Route::get('/detail_cate/{id}', 'DetailController@getcate');

/**
 * News
 */
//List art_categories
Route::get('/getlistcatenews', 'NewsController@getlistcatenews');
//List art_article
Route::get('/getlistnews/{id}', 'NewsController@getlistnews');
//List details art_article
Route::get('/getdetailnews/{id}', 'NewsController@getdetailnews');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
