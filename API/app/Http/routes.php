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
Route::get('/detail_similar/{id}/{supplier}', 'DetailController@getsimilar');
Route::get('/detail_question/{id}/{limit}', 'DetailController@getquestion');
Route::get('/getnamequestion/{id}', 'DetailController@getnamequestion');
Route::get('/detail_review/{id}', 'DetailController@getreview');
Route::get('/detail_pluslike/{id}/{opt}', 'DetailController@pluslike');
Route::post('/detail_save_reivew/{id}', 'DetailController@postreview');

//For search
Route::get('/search', 'ProductController@search');



//For user
Route::get('/list_location', 'UserController@listlocation');
Route::get('/list_state/{id}', 'UserController@liststate');
Route::post('/save_users', 'UserController@saveusers');
Route::post('/save_facebook_user', 'UserController@saveuserfacebook');
Route::post('/save_profile', 'UserController@saveprofile');
Route::get('/thoat', 'UserController@logout');
Route::post('/login', 'UserController@login');


Route::get('/detailnewscate/{id}', 'NewsController@detailnewscate');
/**
 * News
 */
//List art_categories
Route::get('/getlistcatenews', 'NewsController@getlistcatenews');
//List art_article
Route::get('/getlistnews/{id}', 'NewsController@getlistnews');
Route::get('/getlistnewslimit/{id}/{limit}', 'NewsController@getlistnewslimit');

//List details art_article
Route::get('/getdetailnews/{id}', 'NewsController@getdetailnews');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
