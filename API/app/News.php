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
		    return DB::table('art_categories')->where('status', '=', '1')->get();
		});	
	}
	//List art_article
	public static function List_News($id){
		return DB::select("SELECT id, name, cid_cate, description, summary, date_cre, date_mod, countview, date_to, date_from FROM art_article WHERE cid_cate= {$id} and status='1' ORDER BY id DESC");
	}
	//Detail news
	public static function get_Details_News($id){
		return DB::select("SELECT * FROM art_article WHERE id = {$id} and status='1'");
	}
}