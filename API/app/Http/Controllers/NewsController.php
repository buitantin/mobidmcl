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
		$value=News::List_Cate_News();
		
		//Cách 1
		$value = Cache::pull('art_categories');
		return Response::json($value);
	}
	//List art_article
	public function getlistnews($id,Request $request){
		$page=$request->get("page",1);
		$a=News::List_News($id);
		return Response::json($a);
	}
	public function getlistnewslimit($id,$limit){
		$a=News::List_News_limit($id,$limit);
		return Response::json($a);
	}
	
	//Details news
	public function getdetailnews($id){
		$a = News::get_Details_News($id);
		return Response::json($a);
	}
	public function detailnewscate($id){
			$a = News::get_Cate($id);
			return Response::json($a);
	}
}
