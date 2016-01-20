<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Product;
use Response;
use App\Promotion;
use App\Coupon;
use App\MrValidateData;
use DB;
use Session;
use App\Orbilling;
use App\Ordetail;
use App\Orgift;
use App\Ororder;
use App\Orshipping;
use Auth;


use Illuminate\Http\Request;

class OrderController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function detail($id,$supplier){
		if(!empty($id)){
			return Product::select(

						array(
								DB::raw("check_coupon(pro_product.id,pro_product.cid_cate,1) AS discountcoupon"),
								DB::raw("check_coupon(pro_product.id,pro_product.cid_cate,2) AS coupons"),
								DB::raw("get_review(pro_product.id,1) AS rating"),
							  	DB::raw("get_review(pro_product.id,2) AS countrating"),
							  	DB::raw("get_price(pro_supplier_product.id,pro_supplier_product.discount) AS discount"),
							  	DB::raw("get_sale_price(pro_supplier_product.id,pro_supplier_product.saleprice) AS saleprice"),

								"pro_product.id AS myid",
								"pro_product.code","pro_product.sap_code",
								"pro_product.name","pro_product.cid_series",
								"pro_product.cid_cate","pro_supplier_product.id AS cid_res"
							  		,"pro_product.isprice"	
							  		,"pro_supplier_product.stock_num"
							  		
							  		
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
	public function getlimit(Request $request){
			$value=$request->all();
			$total_cart=0;
			if(!empty($value['id']) && !empty($value['limit']) && $value['limit']> 0){
				if(Session::has("orderdmcl")){
					$order=Session::get("orderdmcl");
					if(array_key_exists($value['id'],$order)){
						$order[$value['id']]['order']['limit']=$value['limit'];
						Session::put("orderdmcl",$order);

						
						foreach ($order as $key => $value) {
							if(!empty($value['coupon'])){
								$c=$value['coupon'];
							}else{
								$c=0;
							}

							if($value['product']['isprice']=='1'){
								$total_cart=$total_cart+$value['product']['discount']*$value['order']['limit']-$c;
							}else{
								$total_cart=$total_cart+$value['product']['saleprice']*$value['order']['limit']-$c;
							}
							
						}
					}
				}
			}
			return $total_cart;
	}
	public function getdetroy(Request $request){
			if( $id=$request->get("id") ){
				if(Session::has("orderdmcl")){
					$order=Session::get("orderdmcl");
					if(array_key_exists($id, $order)){
							unset($order[$id]);
							Session::put("orderdmcl",$order);
					}
				}
			}


	}

	public function coupon(Request $request){
		$result=array(0);
		if($coupon=$request->get("coupon") && Session::has("orderdmcl")){
			$coupon=$request->get("coupon");
			$all_product=Session::get("orderdmcl");

			 foreach ($all_product as $key => $product) {
					
					if($discount=Coupon::check_price($coupon,$product['product'])){
						Session::put("coupon",$coupon);
						$result=$discount;
						$all_product[$key]['coupon']=$discount['price'];

					}

					

					if($get_coupon=Coupon::coupon($coupon,$product['product'])){
						if($get_coupon['coupon']->all_product=='1'){
							Session::put("coupon",$coupon);
							$all_product[$key]['coupon']=$discount['price'];
							$result=($discount);
						}else{

							if(in_array($product['product']['sap_code'],explode("-",$distict['coupon']->cid_prod ) ) && !empty($discount['coupon']->cid_prod) ){
								Session::put("coupon",$coupon);
								$all_product[$key]['coupon']=$discount['price'];
								$result=($discount);
							}
							if(in_array($product['product']['cid_cate'],explode("-",$distict['coupon']->cid_cate ) ) && !empty($discount['coupon']->cid_cate) ){
								Session::put("coupon",$coupon);
								$all_product[$key]['coupon']=$discount['price'];
								$result=($discount);
							}
						}
						
					}
					$promotion=Promotion::getDetail($product['product']['cid_res'],$product['product']['cid_supplier']);
					if($discount=Coupon::check_voucher($coupon,$product['product'] ,$promotion)){
						Session::put("coupon",$coupon);
						$result=($discount);
						$all_product[$key]['coupon']=$discount['price'];
					}

			}
			Session::put("orderdmcl",$all_product);
		}


		return Response::json($result);
	}
	public function getcoupon($id,$supplier,Request $request){
			if(Session::has("coupon") && !empty($id) && !empty($supplier)){

			}else{
				return 'not using coupon ';
			}

	}



	//First the life

	public function saveorder(Request $request){
		$id=$request->get("id");
		$supplier=$request->get("supplier");
		$limit=$request->get("limit");
		$color=$request->get("color",0);
		if(!Session::has("orderdmcl")){
			Session::put("orderdmcl",array());
		}
		if(!empty($id) && !empty($supplier)){
				
				$order=Session::get("orderdmcl");

				if(array_key_exists($id, $order)){
					$order[$id]['order']['limit']=$limit;
					Session::put("orderdmcl",$order);
				}else{
					$order[$id]=array(
							"order"=>array("id"=>$id,"supplier"=>$supplier,"limit"=>$limit,"color"=>$color),
							"product"=>OrderController::detail_product($id,$supplier)
							//"limit_quantity"=>Promotion::getProduct_Price($id,$supplier)
						);
					Session::put("orderdmcl",$order);
				}
			
		}

		return Session::get("orderdmcl");
	}
	public function saveorderall(Request $request){
		$a= $request->all();
	

	
		if(!Session::has("orderdmcl")){
			Session::put("orderdmcl",array());
		}
		if(is_array($a)){
			foreach ($a as $key => $value) {
				
				$order=Session::get("orderdmcl");

				if(array_key_exists($value['id'], $order)){
					$order[$value['id']]['order']['limit']=1;
					Session::put("orderdmcl",$order);
				}else{
					$order[$value['id']]=array(
							"order"=>array("id"=>$value['id'],"supplier"=>$value['supplier'],"limit"=>1,"color"=>0),
							"product"=>OrderController::detail_product($value['id'],$value['supplier'])
							//"limit_quantity"=>Promotion::getProduct_Price($value['id'],$value['supplier'])
						);
					Session::put("orderdmcl",$order);
				}

			}
			
		}

		return Session::get("orderdmcl");
	}
	public function getlistproduct(){

		if(Session::has("orderdmcl")){
			return Response::json(Session::get("orderdmcl"));
		}else{
			return '';
		}
		
	}


	public function orderform(Request $request){
			$a=$request->all();

			Session::put("ordercustomer",$a);

			return '0';

	}
	public function getform(){
		if(Session::has("ordercustomer")){
			return Response::json(Session::get("ordercustomer"));
		}
		return '';
		
	}

	public function four(){
		if(!Session::has("orderdmcl") && !Session::has("ordercustomer")){
			return '1';
		}
	 	 $filter=new MrValidateData();
		  $number_order = $filter->dateToNumber(date("d-m-Y H:i:s")).$filter->randd(8);
		  $data_payment=Session::get("orderdmcl");
		  $data_customer=Session::get("ordercustomer");
		  $salettotal=0;
		  $total=0;
		  $array_supplier=array();
		  foreach ($data_payment as $key => $value) {
		  		if(!array_key_exists($value['product']['cid_supplier'], $array_supplier)){
		  			$array_supplier[$value['product']['cid_supplier']]=$value['product']['cid_supplier'];	
		  		}
		  		$salettotal=$value['product']['saleprice']*$value['order']['limit']+$salettotal;
		  		if(!empty($value['coupon'])){
		  			$total=($value['product']['discount']*$value['order']['limit'] - $value['coupon'] ) +$total;
		  		}else{
		  			$total=$value['product']['discount']*$value['order']['limit']+$total;	
		  		}
		  }
		  foreach ($array_supplier as $idnhacungcap){		  
				//OR ORDER
				$news_order               =  new Ororder;
				$news_order->code_order   =   $number_order;
	    		if(Auth::check()){
	    			$news_order->id_cus       =   Auth::user()->id;	
	    		}        		
	    		$news_order->pay_type     =    ($data_customer['pay']=='2') ? "Thanh toán tại siêu thị" :"Thanh toán tại nhà";
	    		$news_order->total_or=$total;//tong gia ban kèm số lượng
	    		$news_order->date_bill    =   date("Y-m-d H:i:s");
	    		$news_order->date_ship    =  (!empty($data_customer['getdate']) ) ? date("Y-m-d H:i:s",strtotime($data_customer['getdate']) ) : date("Y-m-d H:i:s");

	    		$news_order->order_info   =   (!empty($data_customer['note']) ? $data_customer['note'] :"");
	    		$news_order->approved     =   '0';
	    		$news_order->dis_price    =   $salettotal-$total;//tong gia giảm kèm số lượng
	    		$news_order->type_payment =   $data_customer['pay'];
	    		$news_order->cid_bank     =   0;
	    	
	            $news_order->flag     =   '1';
	            
	    		$news_order->session      =   1;
	    		$news_order->code_coupon  =   "";
	    		$news_order->code_voucher  =   Session::has("coupon")? Session::get("coupon") : "";
	    		$news_order->mode         =   '0';
	            $news_order->cid_supplier =  $idnhacungcap;
	    		$news_order->save();
	            
	            //OR SHIPPING		
	    		$news_shipping=new Orshipping;

	    		$news_shipping->cid_order=$news_order->id;
	    		$news_shipping->fullname=$data_customer['name'];
	    		$news_shipping->phone=$data_customer['phone'];
	    		$news_shipping->email=$data_customer['email'];
	    		$news_shipping->address=$data_customer['address'];
	    		$news_shipping->distict=$data_customer['state'];
	    		$news_shipping->city=$data_customer['city'];
	    		$news_shipping->save();

	            //OR DETAIL
	    		foreach($data_payment as $key=>$value){
		            if($idnhacungcap == $value['product']['cid_supplier']){

			          	$Gift=Promotion::getGift($value['product']['cid_res'],$value['product']['cid_supplier']);
			          	$getpromotion=Promotion::getDetail($value['product']['cid_res'],$value['product']['cid_supplier']);
	        			$news= new Ordetail;
	        			$news->cid_order =   $news_order->id;
	        			$news->cid_product=$value['product']['myid'];
	        			$news->cid_color=$value['order']['color'];
	        			$news->cid_promotion=(!empty($getpromotion->cid_promotion)? $getpromotion->cid_promotion:"" );
	        			$news->amount=$value['order']['limit'];
	                    
	                    $news->sale_price= $value['product']['saleprice'];// ko kèm số lưog
	        			if(!empty($value['coupon'])){
	        				$news->total = $value['order']['limit'] * $value['product']['discount']-$value['coupon'];
	        				$news->dis_price = $value['coupon'];//khong kèm số lượng
	                    }else{
	                    	$news->total = $value['order']['limit'] * $value['product']['discount'];
	                    	$news->dis_price = $value['product']['saleprice']-$value['product']['discount'];//khong kèm số lượng
	                    }
	        			
	        			$news->choose=(!empty($getpromotion->type_promo)?$getpromotion->type_promo :0 );
	        			$news->code_coupon= Session::has("coupon")? Session::get("coupon") : "";
	        			$news->cid_supplier=$value['product']['cid_supplier'];	        				
	        			$news->save();

		        		if(!empty($getpromotion->type_promo)){
		        				
		        			//print_r($getpromotion->type_promo);exit;
		        			//print_r($Gift);exit;
	                    	if($getpromotion->type_promo=='2'){
		                        if(!empty($Gift['online']) || !empty($Gift['gift'])){
		                            $news->cid_gift='1';
		            				if(!empty($Gift['online'])  && is_object($Gift['online'])){
	            					 	$gift_online = $Gift['online'];
		            					$news_gift= new Orgift;
		            					$news_gift->cid_detail=$news->id;
		            					$news_gift->cid_gift=$gift_online['idpromotion'];
		            					$news_gift->type='0';
		            					$news_gift->save();
		            				}
		            				if(!empty($Gift['gift'])  && is_object($Gift['gift'])){
		            					foreach ($Gift['gift'] as $gift_pr){
			            					$news_gift= new Orgift;
			            					$news_gift->cid_detail=$news->id;
			            					$news_gift->cid_gift=$gift_pr->cid_gift;
			            					$news_gift->type='1';
			            					$news_gift->save();
		            		    		}
		            				}
			            					
		                      	}
			                }elseif($getpromotion->type_promo=='3'){
		                        if(!empty($Gift['press']) || !empty($Gift['gift']) ){
		                            $news->cid_gift='1';		                            
		            				if(!empty($Gift['press'])){
		            					$gift_press =  $Gift['press'];	            					
		            					$news_gift	=	new Orgift;
		            					$news_gift->cid_detail=$news->id;
		            					$news_gift->cid_gift=$gift_press['idpromotion'];
		            					$news_gift->type='0';
		            					$news_gift->save();		            				    
		            				}
		            				if(!empty($Gift['gift']) ){
		            					foreach ($Gift['gift'] as $gift_pr){
			            					$news_gift=new Orgift;
			            					$news_gift->cid_detail=$news->id;
			            					$news_gift->cid_gift=$gift_pr['cid_gift'];
			            					$news_gift->type='1';
			            					$news_gift->save();
		            					}
		            				}	            					
		                        }
			                }elseif($getpromotion->type_promo=='4' || $getpromotion->type_promo=='1'){
			                        if(!empty($Gift['text']) || !empty($Gift['gift'])){
			                            $news->cid_gift='1';
			            				if(!empty($Gift['text'])){
			            					foreach ($Gift['text'] as $gift_text){
				            					$news_gift=new Orgift;
				            					$news_gift->cid_detail=$news->id;
				            					$news_gift->cid_gift=$gift_text->idpromotion;
				            					$news_gift->type='0';
				            					$news_gift->save();
			            					}
			            				}
			            				
			            				if(!empty($Gift['gift']) ){
			            					foreach ($Gift['gift'] as $gift_pr){
				            					$news_gift=new Orgift;
				            					$news_gift->cid_detail=$news->id;
				            					$news_gift->cid_gift=$gift_pr->cid_gift;
				            					$news_gift->type='1';
				            					$news_gift->save();
				            				}
			            				}		            					
			            			}
			                }else{		                      
		                        if(!empty($Gift['gift']) ){
		                        	 $news->cid_gift='1';
		                        	foreach ($Gift['gift'] as $gift_pr){
			        					$news_gift=new Orgift;
			        					$news_gift->cid_detail=$news->id;
			        					$news_gift->cid_gift=$gift_pr->cid_gift;
			        					$news_gift->type='1';
			        					$news_gift->save();
		        					}
		                        }			        					
			          	    }
	        				$news->save();
	        		  	}
	            	}
	            }
          }
        Session::flush();
        return $number_order;
	}

	public static function detail_product($id,$supplier){
		if(!empty($id)){
			return Product::select(

						array(
								DB::raw("check_coupon(pro_product.id,pro_product.cid_cate,1) AS discountcoupon"),
								DB::raw("check_coupon(pro_product.id,pro_product.cid_cate,2) AS coupons"),
								DB::raw("get_review(pro_product.id,1) AS rating"),
							  	DB::raw("get_review(pro_product.id,2) AS countrating"),
							  	DB::raw("get_price(pro_supplier_product.id,pro_supplier_product.discount) AS discount"),
							  	DB::raw("get_sale_price(pro_supplier_product.id,pro_supplier_product.saleprice) AS saleprice"),

								"pro_product.id AS myid",
								"pro_product.code","pro_product.sap_code",
								"pro_product.name","pro_product.cid_series",
								"pro_product.cid_cate","pro_supplier_product.id AS cid_res"
							  	,"pro_product.isprice"
							  	,"pro_product.of_type"
							  	,"pro_supplier_product.stock_num"
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
						->first()->toArray();
		}
		return null;
	}
 	public function getBanner(){
 		return Response::json(Coupon::getBanner() ) ; 
 	}
 	public function checkorder(Request $request){
 		if($code=$request->get("code")){
 			$code=DB::raw($code);
 			$check_order=Ororder::whereRaw("code_order='$code' AND approved != 3 ")->first();
 			if(!empty($check_order)){
 				$result=array(	
 						"order"=>$check_order,
 						"detail"=>
 							DB::table("or_detail AS a")
 								->join("pro_product AS b",function($join){
 									$join->on("a.cid_product","=","b.id");
 								})
 								->selectRaw("b.name,a.dis_price,a.total,a.cid_gift,a.amount,a.cid_order") 
 								->whereRaw("cid_order={$check_order->id}")
 								->get()
 					);
 				return Response::json($result);	
 			}
 		}
 		return '';
 	}
 	public function totalcart(){

 		$total_cart=0;

 		if(Session::has("orderdmcl")){
 			$order=Session::get("orderdmcl");
 			foreach ($order as $key => $value) {
 							$c=0;
 							if(!empty($value['coupon'])){
 									$c=$value['coupon'];
 							}
 							if($value['product']['isprice']=='1'){
 								$total_cart=$total_cart+$value['product']['discount']*$value['order']['limit']-$c;	
 							}else{
 								$total_cart=$total_cart+$value['product']['saleprice']*$value['order']['limit']-$c;
 							}
							
						}
		
 		}
 		return $total_cart;
 						
 	}
}
