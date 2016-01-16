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
Route::group(array("before"=>"cache", "after"=>"cache"),function(){

		Route::get('/clear_all_cache', 'IndexController@clearcache');


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
		Route::get('/getimage/{id}', 'ProductController@getimage');



		Route::get('/productnew/{limit}', 'ProductController@productnew');
		Route::get('/getcate/{id}', 'ProductController@getcate');
		Route::get('/getlistcate/{id}', 'ProductController@getlistcate');
		Route::get('/list_product_cate/{id}/{limit}', 'ProductController@list_product_cate');
		Route::get('/getelement/{id_cate}/{id_product}', 'ProductController@getelement');
		Route::get('/gettemplate/{id}/{supplier}', 'ProductController@gettemplate');
		Route::get('/getdetailproduct/{id}', 'ProductController@getdetailproduct');
		Route::get('/getcompareproduct/{id}/{cate}', 'ProductController@getcompareproduct');

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
		Route::get('/detail_series/{id}/{cate}', 'DetailController@getseries');
		Route::get('/detail_promotion_datetime/{id}/{supplier}', 'DetailController@getdatetime');
		

		Route::get('/detail_similar/{id}/{supplier}', 'DetailController@getsimilar');
		Route::get('/detail_question/{id}/{limit}', 'DetailController@getquestion');
		Route::get('/getnamequestion/{id}', 'DetailController@getnamequestion');
		Route::get('/detail_review/{id}', 'DetailController@getreview');
		Route::get('/detail_pluslike/{id}/{opt}', 'DetailController@pluslike');
		Route::post('/detail_save_reivew/{id}', 'DetailController@postreview');
		Route::post('/detail_save_commend/{id}', 'DetailController@postcomment');
		Route::post('/detail_save_payment', 'DetailController@postpayment');
		
		//For search
		Route::get('/search', 'ProductController@search');

		//For ORder
		Route::get('/order_detail/{id}/{supplier}', 'OrderController@detail');
		Route::post('/check_coupon', 'OrderController@coupon');
		Route::get('/get_value_coupon/{id}/{supplier}', 'OrderController@getcoupon');
		Route::post('/check_order', 'OrderController@checkorder');
		Route::get('/totalcart', 'OrderController@totalcart');
		
		Route::post('/order_save', 'OrderController@saveorder');
		Route::post('/order_save_all', 'OrderController@saveorderall');
		Route::get('/order_get_list_product', 'OrderController@getlistproduct');
		Route::post('/order_detroy', 'OrderController@getdetroy');
		Route::post('/order_save_limit', 'OrderController@getlimit');
		Route::post('/order_form', 'OrderController@orderform');
		Route::get("/order_get_form","OrderController@getform");
		Route::get("/order_four","OrderController@four");
		Route::get("/order_banner","OrderController@getBanner");




		//For user

		Route::get('/list_location', 'UserController@listlocation');
		Route::get('/get_name_location/{id}', 'UserController@getlocation');
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


		//Page

		Route::get('/get_list_branch/{id}', 'PageController@getListBranch');
		Route::get('/get_detail_branch/{id}', 'PageController@getDetailBranch');
		Route::get('/getinstallment/{id}', 'PageController@getinstallment');
		Route::get('/getmemberbenefits/{id}', 'PageController@getmemberbenefits');
		Route::get('/getpolicy', 'PageController@getpolicy');
		Route::get('/getinfo', 'PageController@getinfo');
		Route::get('/getonline', 'PageController@getonline');
		Route::get('/getmember', 'PageController@getmember');


		//List details art_article
		Route::get('/getdetailnews/{id}', 'NewsController@getdetailnews');

		Route::controllers([
			'auth' => 'Auth\AuthController',
			'password' => 'Auth\PasswordController',
		]);
});