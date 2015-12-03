<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Product; 
use App\Promotion;
use App\Question;
use DB;
use Response;
use App\Review;

use Illuminate\Http\Request;
use Auth;

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
							  		,"pro_supplier_product.is_tranc"
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
	public function getsimilar($id,$supplier=1){
		
		$a=Product::Detail_Simalar($id,$supplier);
		return Response::json($a);
	}
	public function getquestion($id,$limit){
			$a=Question::getList($id,$limit);
			return Response::json($a);
	}
	public function getnamequestion($id){
			$a=Question::getNameQuestion($id);
			return Response::json($a);	
	}
	public function getreview($id){
		$a=Review::getList($id);
		return Response::json($a);
	}
	public function pluslike($id,$opt){
		$f=Review::where("id","=",$id)->first();

		if($opt=='1'){
			$f->likes=$f->likes+1;
		}else{
			$f->unlikes=$f->unlikes+1;
		}
			$f->save();
			return;

	}
	public function postreview($id,Request $request){
		$r=($request->all());
		if(empty($r['rating'])){
			return "rating";
		}
		if(empty($r['title'])){
			return "title";
		}
		if(empty($r['content'])){
			return "content";
		}
		if(Auth::check()){
					$users=Auth::user();

					$news=new Review;
					$news->cid_product=$id;
					$news->email=$users->email;
					$news->cid_user=$users->id;
					$news->title=$r['title'];
					$news->description=$r['description'];
					$news->created=date("Y-m-d H:i:s");
					$news->status='1';
					$news->cid_parent='0';
					$news->likes='1';
					$news->unlikes='0';
					$news->rate=$r['rating'];
					$news->save();

		}else{
			return '2';
		}


		return '1';
	}
}
