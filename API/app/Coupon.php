<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use SoapClient;
class Coupon extends Model {

	//all coupon on the page  1
	public static function check_price($coupon,$product){
		if(!empty($coupon)){
				$coupon = DB::raw($coupon);
				 $data= DB::table("tm_coupon_product AS a")
					   ->join('tm_coupon_price AS b',function($join){
					   		$join->on("b.id","=","a.cid_coupon");
					   })
					   ->select("a.discount")
					   ->whereRaw("b.code='$coupon' AND a.cid_product={$product['myid']}")->get();

				
				if(!empty($data[0])){
					return   array('type'=>1,'price'=>$data[0]->discount);
				}


				
				$data=DB::table("tm_coupon_cate AS a")
					->join("tm_coupon_price AS b",function($join){
						$join->on("b.id","=","a.cid_coupon");
					})
					->select("b.discount")
					->whereRaw("b.code='$coupon' AND a.cid_cate={$product['cid_cate']}")
					
					->get();

				if(!empty($data[0])){
					return array('type'=>1,'price'=>$data[0]->discount);
				}
		}
		return false;
	}
	//general coupon 2 , 3
	public static function coupon($coupon,$product){
		$coupon = DB::raw ($coupon );
		$now = date ( "Y-m-d H:i:s" );
		$now =DB::raw($now);


		$data = DB::table("tm_coupon")
					->whereRaw("code='$coupon' AND apply > 0 AND status=1 AND active=1 AND date_end > '$now'  AND date_start < '$now' ")
					->get();
		
		if(!empty($data[0]->apply)){
			$apply=$data[0]->apply - 1;
			DB::table("tm_coupon")->where("code","=","'$coupon'")->update(["apply"=>$apply]);

			if(!empty($data[0])){
				return  array('type'=>2,'price'=>$data[0]->dis_price,'coupon'=>$data[0]);

			}else{
				return  array('type'=>3,'price'=>round($data[0]->dis_percent*$product['discount']/100) ,'coupon'=>$data[0]);
			}

		}
		return false;
	}

	//voucher server 5,6,7
	public static function check_voucher($vouche,$product,$promotion){
				$array_vouche=array();


				$server=$_SERVER['SERVER_NAME'];
				$options = array();
				$options['cache_wsdl'] = WSDL_CACHE_NONE;
				if($server=='dienmaycholon.local'){
					$client = new SoapClient("http://192.168.1.71:8477/Service.asmx?WSDL",$options);
				}else{
					$client = new SoapClient("http://192.168.100.246:8477/service.asmx?WSDL",$options);
				}

					try {
						$ex=	$client->Promotion_VoucherSS(
							array("strVoucherID"=>$vouche,"strKey"=>"iuqwesas312bdjw837qheiq376uhwieuh")
								
						);
					} catch (Exception $x) {
						echo $x->getMessage();exit;
					}
						/*echo $vouche;echo "<br />";
						var_dump($ex->Promotion_VoucherSSResult);exit;
*/
					if($ex->Promotion_VoucherSSResult){

							$check_dien_tu=DB::table("pro_categories")->select("id")->whereRaw("cid_parent=1 AND id={$product['cid_cate']}")->take(1)->get();
							$check_dien_lanh=DB::table("pro_categories")->select("id")->whereRaw("cid_parent=2 AND id={$product['cid_cate']}")->take(1)->get();
							$check_gia_dung=DB::table("pro_categories")->select("id")->whereRaw("cid_parent=9 AND id={$product['cid_cate']}")->take(1)->get();
							$check_noi_that=DB::table("pro_categories")->select("id")->whereRaw("cid_parent=21 AND id={$product['cid_cate']}")->take(1)->get();
							


							if($ex->Promotion_VoucherSSResult=="PRO100000001"){
								//10 000 dientu dienlanh 5%
								if( ( !empty($check_dien_tu) || !empty($check_dien_lanh) )  && empty($promotion->cid_promotion)  && ( $product['of_type']=='a' || $product['of_type']=='b')  ){
									return array('type'=>5,'price'=>round($myprice['price']*5/100));
								}	
								//10 000 gia dung 10%
								if( ( !empty($check_gia_dung) || !empty($check_noi_that) ) ){
									return array('type'=>5,'price'=>round($myprice['price']*10/100) );
								}
							}
							if($ex->Promotion_VoucherSSResult=="PRO100000002"){


								return array('type'=>6,'price'=>100000);
								
								
							}

						}

					foreach($array_vouche as $value){
							//if($product['sap_code']==$value['sap_code'] && ( $product['of_type']=='a' || $product['of_type']=='b')  ){
							//if($product['sap_code']==$value['sap_code']) {
								try {
									$e=	$client->VoucherSS(
										array("strVoucherID"=>$vouche,"strKey"=>"iuqwesas312bdjw837qheiq376uhwieuh")
											
									);
								} catch (Exception $x) {
									//echo $x->getMessage();exit;
								}
							
								if($e->VoucherSSResult){
										return array('type'=>7,'price'=> $value['voucher']);
								

								}
							//}

					}

				return false;	



	}

	/*
	 * GET BANNER
	 */
	public static function getBanner() {
		$a= DB::table("tm_popup")->whereRaw("category='2' AND active='1'")->orderBy("id","DESC")->get();
		return $a;
	}
}
