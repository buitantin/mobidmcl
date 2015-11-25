<?php namespace App\Http\Controllers;

use App\News;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Cache;
use Response;
use DB;
use App\Template;

use Illuminate\Http\Request;

class NewsController extends Controller {

	//List art_categories
	public function getlistcatenews(){
		$a=News::List_Cate_News();
		
		//Cách 1
		$value = Cache::pull('art_categories');
		return Response::json($value);
	}
	//List art_article
	public function getlistnews($id){
		$a=News::List_News($id);
		return Response::json($a);
	}
	//Details news
	public function getdetailnews($id){
		$a = News::get_Details_News($id);
		return Response::json($a);
	}
}
