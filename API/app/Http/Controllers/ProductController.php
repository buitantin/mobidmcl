<?php namespace App\Http\Controllers;
use App\Product;
use App\Categories;
use App\Promotion;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Cache;
use Response;
use DB;
use App\Template;
use App\MrValidateData;

use Illuminate\Http\Request;

class ProductController extends Controller {

	/**
	 * Display a listing of the resource to the categories
	 *
	 * @return Response
	 */
	public function list_product_cate_parent($id,$limit)
	{
		
		$c=md5($id.$limit);
		return Cache::remember("list_product_cate_parent_".$c ,5,function() use($id,$limit){
			$child=Categories::select("id")->whereRaw("cid_parent=$id AND status='1'")->remember(120)->get()->toArray();

			$x=array();
			foreach ($child as $key => $value) {
				$x[]=$value['id'];
			}
			$x = implode(",", $x);
			 	
			 if(!empty($x)){
				return Product::select(

						array(
								DB::raw("check_coupon(pro_product.id,pro_product.cid_cate,1) AS discountcoupon"),
								DB::raw("check_coupon(pro_product.id,pro_product.cid_cate,2) AS coupons"),
								DB::raw("get_review(pro_product.id,1) AS rating"),
							  	DB::raw("get_review(pro_product.id,2) AS countrating"),
							  	DB::raw("get_price(pro_supplier_product.id,pro_supplier_product.discount) AS discount"),
							  	DB::raw("get_sale_price(pro_supplier_product.id,pro_supplier_product.saleprice) AS saleprice"),
								"pro_product.id" ,"pro_product.id AS myid","pro_product.name","pro_product.isprice"
								,"pro_supplier_product.id AS cid_res","pro_supplier_product.cid_supplier"
							)
						)
						->whereRaw("pro_product.cid_cate IN ($x)   AND pro_supplier_product.status='1' AND pro_product.status='1' AND pro_product.is_status_cate='1' AND pro_product.is_status_series='1' AND pro_product.is_home='1' ")
						->join("pro_supplier_product",function($join){
							$join->on("pro_product.id","=","pro_supplier_product.cid_product");
						})
						->orderBy("pro_supplier_product.date_mod","DESC")
						->limit($limit)->get()->toJson();
			 }
		});
		
		
	}

	public function search(Request $request){
		$MrValidateData=new MrValidateData();
			$key=$request->get("search");
			$page=$request->get("page",1);
			$sql="";
			if($key!=""){
					$key=MrValidateData::inject($key);
					$key=trim($key);
		
					$key=DB::raw("'%$key%'");

					$key=str_replace(" ","%",$key);

					$sql=" AND ((pro_product.name LIKE $key OR pro_product.code LIKE $key) )";
			}
			return Product::select(

						array(
								DB::raw("check_coupon(pro_product.id,pro_product.cid_cate,1) AS discountcoupon"),
								DB::raw("check_coupon(pro_product.id,pro_product.cid_cate,2) AS coupons"),
								DB::raw("get_review(pro_product.id,1) AS rating"),
							  	DB::raw("get_review(pro_product.id,2) AS countrating"),
							  	DB::raw("get_price(pro_supplier_product.id,pro_supplier_product.discount) AS discount"),
							  	DB::raw("get_sale_price(pro_supplier_product.id,pro_supplier_product.saleprice) AS saleprice"),
								"pro_product.id" ,"pro_product.id AS myid","pro_product.name","pro_product.isprice"
								,"pro_supplier_product.id AS cid_res","pro_supplier_product.cid_supplier",
								"pro_product.is_shopping",
								"pro_supplier_product.stock_num",
								"pro_product.is_sample"
								
							)
						)
						->whereRaw("pro_supplier_product.status='1' AND pro_product.status='1' AND pro_product.is_status_cate='1' AND pro_product.is_status_series='1'  $sql")
						->join("pro_supplier_product",function($join){
							$join->on("pro_product.id","=","pro_supplier_product.cid_product");
						})
						->orderBy("pro_supplier_product.date_mod","DESC")
						->paginate(10);
						

	}
	public function list_product_cate($id,$limit)
	{
		
		$c=md5($id.$limit);
		return Cache::remember("list_product_cate_parent_".$c,7,function() use($id,$limit){
		
			 	
			 if(!empty($id)){
				return Product::select(

						array(
								DB::raw("check_coupon(pro_product.id,pro_product.cid_cate,1) AS discountcoupon"),
								DB::raw("check_coupon(pro_product.id,pro_product.cid_cate,2) AS coupons"),
								DB::raw("get_review(pro_product.id,1) AS rating"),
							  	DB::raw("get_review(pro_product.id,2) AS countrating"),
							  	DB::raw("get_price(pro_supplier_product.id,pro_supplier_product.discount) AS discount"),
							  	DB::raw("get_sale_price(pro_supplier_product.id,pro_supplier_product.saleprice) AS saleprice"),
								"pro_product.id" ,"pro_product.id AS myid","pro_product.name","pro_product.isprice","pro_supplier_product.cid_supplier"
							)
						)
						->whereRaw("pro_product.cid_cate ={$id} AND pro_product.status='1' AND pro_product.is_status_cate='1' AND pro_product.is_status_series='1'  ")
						->join("pro_supplier_product",function($join){
							$join->on("pro_product.id","=","pro_supplier_product.cid_product");
						})
						->limit($limit)->get()->toJson(); 	
			 }
			});
		
		
	}
	public function getcate($id){
		return Categories::whereRaw("id=$id AND status='1'")->remember(120)->first()->toJson();
	}
	public function getlistcate($id){
		return Categories::whereRaw("cid_parent=$id AND status='1'")->remember(120)->get()->toJson();
	}
	public function getproduct($cate,$page){

		$a=Product::Get_Product($cate,$page);
		return Response::json($a);
	}
	public function promotionpress($limit){
		$a=Promotion::List_Promotion_Press($limit);
		return Response::json($a);
	}	
	public function promotiononline($limit){
		$a=Promotion::List_Promotion_Online($limit);
		return Response::json($a);
	}	
	public function promotion($limit){
		$a=Promotion::List_All_Promotion($limit);
		return Response::json($a);
	}	
	public function producthot($limit){
		$a=Product::List_Product_Hot($limit);
		return Response::json($a);
	}
	public function productnew($limit){
		$a=Product::List_Product_New($limit);
		return Response::json($a);
	}
	public function getelement($id_cate,$id_product){
		$a=Template::Compare_Cate($id_cate, $id_product);
		return Response::json($a);
		
	}
	public function gettemplate($id,$supplier){
		$a=Template::Compare_One($id, $supplier);
		return Response::json($a);
	}
	public function get_element_product($id,$element){
		 $a=Template::get_Product($id, $element);
		return Response::json($a);
	}
	
	public function getdetailproduct($id){
			if(!empty($id)){
				return Product::select(

						array(
								DB::raw("check_coupon(pro_product.id,pro_product.cid_cate,1) AS discountcoupon"),
								DB::raw("check_coupon(pro_product.id,pro_product.cid_cate,2) AS coupons"),
								DB::raw("get_review(pro_product.id,1) AS rating"),
							  	DB::raw("get_review(pro_product.id,2) AS countrating"),
							  	DB::raw("get_price(pro_supplier_product.id,pro_supplier_product.discount) AS discount"),
							  	DB::raw("get_sale_price(pro_supplier_product.id,pro_supplier_product.saleprice) AS saleprice"),
								"market_supplier.name AS myname","pro_product.cid_series","pro_supplier_product.cid_supplier","pro_product.id" ,"pro_product.id AS myid","pro_product.name","pro_product.isprice"
							)
						)
						->whereRaw("pro_product.id ={$id} AND pro_product.status='1' AND pro_product.is_status_cate='1' AND pro_product.is_status_series='1'  ")
						->join("pro_supplier_product",function($join){
							$join->on("pro_product.id","=","pro_supplier_product.cid_product");
						})
						->join("market_supplier",function($join){
							$join->on("market_supplier.id","=","pro_supplier_product.cid_supplier");
						})
						
						->first()->toJson(); 	
			 }

	}
	public function getcompareproduct($id,$cate){
		return array("template"=>Template::Compare_Cate($cate, $id),
					  "product"=>
					  Product::select(
						array(
								DB::raw("check_coupon(pro_product.id,pro_product.cid_cate,1) AS discountcoupon"),
								DB::raw("check_coupon(pro_product.id,pro_product.cid_cate,2) AS coupons"),
								DB::raw("get_review(pro_product.id,1) AS rating"),
							  	DB::raw("get_review(pro_product.id,2) AS countrating"),
							  	DB::raw("get_price(pro_supplier_product.id,pro_supplier_product.discount) AS discount"),
							  	DB::raw("get_sale_price(pro_supplier_product.id,pro_supplier_product.saleprice) AS saleprice"),
								"market_supplier.name AS myname","pro_product.cid_series","pro_supplier_product.cid_supplier",
								"pro_product.id" ,"pro_product.id AS myid","pro_product.name","pro_product.isprice",
								"pro_supplier_product.stock_num","pro_product.is_shopping"

							)
						)
						->whereRaw("pro_product.id ={$id} AND pro_product.status='1' AND pro_product.is_status_cate='1' AND pro_product.is_status_series='1'  ")
						->join("pro_supplier_product",function($join){
							$join->on("pro_product.id","=","pro_supplier_product.cid_product");
						})
						->join("market_supplier",function($join){
							$join->on("market_supplier.id","=","pro_supplier_product.cid_supplier");
						})
						
						->first()
				);
	}
	public function getimage($id){
		
		$file=file_get_contents("http://dienmaycholon.vn/cacheMobi/2024.txt");
	
		$obj=json_decode($file);
		return Response::json($obj);
	}
	
}
