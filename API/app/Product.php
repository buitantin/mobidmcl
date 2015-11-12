<?php 
namespace App;
use DB;


class Product extends Model {

	//
	protected $table='pro_product';
	public $timestamps=false;

	public static function List_Product_Hot($limit=9){
	


		return DB::table("pro_product AS a")->select(
				array(
							  		DB::raw("check_coupon(a.id,a.cid_cate,1) AS discountcoupon"),
									DB::raw("check_coupon(a.id,a.cid_cate,2) AS coupons"),
									DB::raw("get_review(a.id,1) AS rating"),
								  	DB::raw("get_review(a.id,2) AS countrating"),
								  	DB::raw("get_price(a.id,b.discount) AS discount"),
								  	DB::raw("get_sale_price(a.id,b.saleprice) AS saleprice"),

							  		"a.id AS myid","a.code","a.sap_code","a.is_hot","a.name","a.cid_series","a.cid_cate","b.id AS cid_res"
							  		,"a.isprice"	


							  			)
			)

					->join('pro_supplier_product AS b',function($join){
						$join->on("a.id","=","b.cid_product");
					})
					->whereRaw("b.status='1' AND a.is_home='1' AND a.is_status_series='1' AND a.is_status_cate='1' AND a.status='1' AND a.isprice='1' AND b.cid_supplier='1' AND a.is_hot='1'")

					->groupBy("a.id")
				    ->limit($limit)
					

							  ->get();

	}
	public static function List_Product_New($limit=9){
	

		return DB::table("pro_product AS a")->select(
				array(
							  		DB::raw("check_coupon(a.id,a.cid_cate,1) AS discountcoupon"),
									DB::raw("check_coupon(a.id,a.cid_cate,2) AS coupons"),
									DB::raw("get_review(a.id,1) AS rating"),
								  	DB::raw("get_review(a.id,2) AS countrating"),
								  	DB::raw("get_price(a.id,b.discount) AS discount"),
								  	DB::raw("get_sale_price(a.id,b.saleprice) AS saleprice"),

							  		"a.id AS myid","a.code","a.sap_code","a.is_hot","a.name","a.cid_series","a.cid_cate","b.id AS cid_res"
							  		,"a.isprice"


							  			)
			)

					->join('pro_supplier_product AS b',function($join){
						$join->on("a.id","=","b.cid_product");
					})
					->whereRaw("b.status='1' AND a.is_home='1' AND a.is_status_series='1' AND a.is_status_cate='1' AND a.status='1' AND a.isprice='1' AND b.cid_supplier='1' AND a.is_new='1'")

					->groupBy("a.id")
							  ->limit($limit)
							  

							  ->get();


	}
	public static function Get_Product($cate,$page=10){
	

		return DB::table("pro_product AS a")->select(
				array(
							  		DB::raw("check_coupon(a.id,a.cid_cate,1) AS discountcoupon"),
									DB::raw("check_coupon(a.id,a.cid_cate,2) AS coupons"),
									DB::raw("get_review(a.id,1) AS rating"),
								  	DB::raw("get_review(a.id,2) AS countrating"),
								  	DB::raw("get_price(a.id,b.discount) AS discount"),
								  	DB::raw("get_sale_price(a.id,b.saleprice) AS saleprice"),

							  		"a.id AS myid","a.code","a.sap_code","a.is_hot","a.name","a.cid_series","a.cid_cate","b.id AS cid_res"
							  		,"a.isprice"


							  			)
			)

					->join('pro_supplier_product AS b',function($join){
						$join->on("a.id","=","b.cid_product");
					})
					->whereRaw("b.status='1' AND a.is_home='1' AND a.is_status_series='1' 
								AND a.is_status_cate='1' AND a.status='1' 
								AND a.cid_cate={$cate}
								")

					->groupBy("a.id")
							  ->limit($page)
							  

							  ->get();


	}
	

}
