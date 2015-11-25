<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model {

	//

	protected $table='pro_review';
	public $timestamps=false;
	public static function getList($id_product,$limit=3){

		return Review::selectRaw("pro_review.*,b.fullname")->join("tm_customer AS b",function($join){
					$join->on("pro_review.cid_user","=","b.id");
				})
				->whereRaw("pro_review.cid_product = $id_product AND pro_review.status='1'")
				->orderBy("pro_review.created","DESC")
				->limit($limit)
				->get();
	}


}
