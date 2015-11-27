<?php
namespace App;
use DB;
use Cache;

use Illuminate\Http\Request;

class News extends Model {
	protected $table='art_article';
	public $timestamps =false;


	//List art_categories
	public static function List_Cate_News(){
		//return DB::select("SELECT * FROM art_categories WHERE status='1' ORDER BY id ASC");
		
		//Cach 1
		$value = Cache::remember('art_categories', 100, function() {
			$result=array();
		    $a= DB::table('art_categories')->selectRaw("id,name")->where('status', '=', '1')->get();
		    foreach ($a as $value) {

		    	$c=DB::table("art_article")
							->selectRaw("id, name, cid_cate, countview, date_to, date_from")
							->whereRaw("cid_cate= ".$value->id." and status='1'")->limit(3)
							->orderBy("id","DESC")
							->get();
				if(!empty($c)){
		    	$result[$value->id]=array(
		    			"data"=>$value,
		    			"value"=>$c
		    		);
		    	}
		    	
		    }
		    return $result;
		});	
	}
	//List art_article
	public static function List_News($id){
		return News::whereRaw("status='1' AND cid_cate=$id")->
			selectRaw("id, name, cid_cate, description, summary, date_cre, date_mod, countview, date_to, date_from")
			->orderBy("id","DESC")->paginate(10);
		
	}
	public static function List_News_limit($id,$limit){
		return DB::table("art_article")
				->selectRaw("id, name, cid_cate, description, summary, date_cre, date_mod, countview, date_to, date_from")
				->whereRaw("cid_cate= {$id} and status='1'")->limit($limit)->orderBy("id","DESC")->get();
	}
	//Detail news
	public static function get_Details_News($id){

		if(!empty($id) && is_numeric($id)){
			$a= News::whereRaw("id={$id} AND status='1'")->first();	
			$a->summary=str_replace('src="/public', 'src="http://m.dienmaycholon.vn/img', $a->summary);

			return $a;
		}
		return null;
		
	}
	public static function get_Cate($id){
		$a= DB::table("art_categories")->where("id","=",$id)->get();
		if(!empty($a[0])){
			return $a[0];
		}
		return 0;
	}
}