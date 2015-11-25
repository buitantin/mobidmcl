<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Product; 
use App\Promotion;
use DB;
use Response;

use Illuminate\Http\Request;

class DetailController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function detail($id,$supplier)
	{
		if(!empty($id)){
			return Product::select(

						array(
								DB::raw("check_coupon(pro_product.id,pro_product.cid_cate,1) AS discountcoupon"),
								DB::raw("check_coupon(pro_product.id,pro_product.cid_cate,2) AS coupons"),
								DB::raw("get_review(pro_product.id,1) AS rating"),
							  	DB::raw("get_review(pro_product.id,2) AS countrating"),
							  	DB::raw("get_price(pro_product.id,pro_supplier_product.discount) AS discount"),
							  	DB::raw("get_sale_price(pro_product.id,pro_supplier_product.saleprice) AS saleprice"),

								"pro_product.id AS myid","pro_product.code","pro_product.sap_code","pro_product.is_hot","pro_product.name","pro_product.cid_series","pro_product.cid_cate","pro_supplier_product.id AS cid_res"
							  		,"pro_product.isprice"	
							  		,"pro_supplier_product.stock_num"
							  		,"pro_product.is_sample"
							  		,"pro_product.is_shopping"
							  		,"pro_supplier_product.content"
							  		,"m.name AS name_supplier"
								,"pro_supplier_product.id AS cid_res","pro_supplier_product.cid_supplier"
							)
						)
						->whereRaw("pro_product.id={$id}  AND  pro_supplier_product.cid_supplier={$supplier} AND pro_supplier_product.status='1' AND pro_product.status='1' AND pro_product.is_status_cate='1' AND pro_product.is_status_series='1' ")
						
						->join("pro_supplier_product",function($join){
							$join->on("pro_product.id","=","pro_supplier_product.cid_product");
						})
						->join("market_supplier AS m",function($join){
							$join->on("m.id","=","pro_supplier_product.cid_supplier");
						})
						->orderBy("pro_supplier_product.date_mod","DESC")
						->first()->toJson();
		}
	}
	public function getgift($id,$supplier){
		$a=Promotion::getGift($id,$supplier);
		return Response::json($a);
	}
	public function getpayment($id_cate){
		$a=Product::getPayment($id_cate);
		return Response::json($a);
	}

	public function getpricenewold($id){
		$a=Product::getMINPRICE($id);
		return Response::json($a);
	}	
	public function getbuytogether($id){
		$a=Product::Buy_Together($id);
		return Response::json($a);
	}	
	public function getelement($id){
		$a=Product::Detail_Element($id);
		return Response::json($a);
		
	}
	public function getcate($id_cate){
		return Product::select(

						array(
								DB::raw("check_coupon(pro_product.id,pro_product.cid_cate,1) AS discountcoupon"),
								DB::raw("check_coupon(pro_product.id,pro_product.cid_cate,2) AS coupons"),
								DB::raw("get_review(pro_product.id,1) AS rating"),
							  	DB::raw("get_review(pro_product.id,2) AS countrating"),
							  	DB::raw("get_price(pro_product.id,pro_supplier_product.discount) AS discount"),
							  	DB::raw("get_sale_price(pro_product.id,pro_supplier_product.saleprice) AS saleprice"),
								"pro_product.id" ,"pro_product.id AS myid","pro_product.name","pro_product.isprice"
								,"pro_supplier_product.id AS cid_res","pro_supplier_product.cid_supplier"
							)
						)
						->whereRaw("pro_supplier_product.status='1' AND pro_product.status='1' AND pro_product.is_status_cate='1' AND pro_product.is_status_series='1' AND pro_product.cid_cate={$id_cate} ")
						->join("pro_supplier_product",function($join){
							$join->on("pro_product.id","=","pro_supplier_product.cid_product");
						})
						->orderBy("pro_supplier_product.date_mod","DESC")
						->limit(9)->get()->toJson();
		
	}
}
