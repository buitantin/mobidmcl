<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Question extends Model {

	//
	protected $table='question_content';
	public $timestamps=false;
	public static function getList($id_product,$limit=4){
	

		return	DB::table("question_content AS a")
				->join("question_comment AS b",function($join){
					$join->on("a.id","=","b.cid_content");
				})

				->selectRaw("a.id,a.cid_user,a.cid_product,a.status,a.content,a.type_user,a.created,a.is_view,a.like,a.unlike,b.cid_content,b.cid_user AS idusercomment,b.type_user AS typeusercomment,b.comment,b.created AS createdcomment,b.status,b.name")
				->whereRaw("b.status='1' AND a.status='1' AND a.cid_product=$id_product")
				->orderBy("a.is_view","DESC")
				->groupBy("a.id")
				->limit($limit)
				->get();
	}
	public static function getNameQuestion($id,$type_user='1'){
		if($type_user=='1'){
			$data= DB::select("SELECT id,fullname FROM tm_customer WHERE id={$id}");
			
			if(!empty($data[0]->fullname)){
				return $data[0]->fullname;
			}
			
		}
		if($type_user=='2'){
			$data= DB::select("SELECT id,name FROM market_supplier WHERE id={$id}");
			if(!empty($data[0]->name)){
				return $data[0]->name;
			}
		}
		if($type_user=='3'){
			$data= DB::select("SELECT id,fullname FROM tm_webmaster WHERE id={$id}");
			if(!empty($data[0]->fullname)){
				return $data[0]->fullname;
			}
		}
		return "DIENMAYCHOLON";
			
	}
}
