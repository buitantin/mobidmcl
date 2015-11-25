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

							  		"a.id AS myid","a.code","a.sap_code","a.is_hot","a.name",
							  		"a.cid_series","a.cid_cate","b.id AS cid_res"
							  		,"a.isprice","b.cid_supplier"


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

	public static function Get_Product_Filter($cate,$sql="",$ele=false){
		
					if($ele){
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
									->leftjoin("comp_elemt_product AS e",function($join){
										$join->on("a.id","=","e.cid_product");
									})
								
									->whereRaw(DB::raw("e.val!='' AND b.status='1' AND e.is_type='0'  AND a.is_status_series='1' AND a.is_status_cate='1' AND a.status='1' AND a.cid_cate={$cate} $sql") )
									

									->groupBy("a.id")
									->get();
					}else{
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
									
									->whereRaw(DB::raw("b.status='1'  AND a.is_status_series='1' AND a.is_status_cate='1' AND a.status='1' AND a.cid_cate={$cate} $sql") )
									

									->groupBy("a.id")
									->get();
					}
					

	}
	public static function getPayment($id_cate){
		return DB::select("SELECT	 
				a.permin,b.month_pay,b.rate
			FROM
				tm_payment AS a INNER JOIN tm_payment_typedetail AS b ON a.cid_pay_type=b.cid_pay_type
			WHERE 
			 a.status='1' AND a.cid_cate={$id_cate} 
			GROUP BY a.id");
	}
	public static function getMINPRICE($id_product){
		$data=array();
		$sql_new="
			SELECT
			
				 MIN(c.discount) AS minprice,COUNT(c.id) AS total
			FROM
				(  pro_product AS a INNER JOIN pro_supplier_product AS c ON a.id = c.cid_product)
			WHERE
				c.status='1' AND  a.is_status_series='1' AND a.is_status_cate='1' AND a.status='1' AND a.isprice='1'
			AND  a.id=$id_product AND c.is_old='0'
		";
		$min_price= DB::select($sql_new);
		
		if(!empty($min_price[0]->minprice)){
			$data['new']=array("price"=>$min_price[0]->minprice,"count"=>$min_price[0]->total);
		}
		$sql_old="
			SELECT
			
			MIN(c.discount) AS minprice,COUNT(c.id) AS total
			FROM
			(  pro_product AS a INNER JOIN pro_supplier_product AS c ON a.id = c.cid_product)
			WHERE
			c.status='1' AND  a.is_status_series='1' AND a.is_status_cate='1' AND a.status='1' AND a.isprice='1'
			AND  a.id=$id_product AND c.is_old='1'
		";
		$old_price= DB::select($sql_old);
		
		if(!empty($old_price[0]->minprice)){
			$data['old']=array("price"=>$old_price[0]->minprice,"count"=>$old_price[0]->total);
		}
		return $data;
		
	}
	public static function Buy_Together($id_product){
		
	//if(!$result=$this->memcache->load("Buy_Together".$id_product)){
			
		
		$get_id_one=DB::select("
					SELECT a.cid_product_one,a.cid_product_two,
							b.id
					FROM pro_buy_together AS a INNER JOIN pro_product AS b ON b.id=a.cid_product_one
					WHERE a.cid_product_one=$id_product
				");
		$get_id_two=DB::select("
				SELECT a.cid_product_one,a.cid_product_two,
							b.id
					FROM pro_buy_together AS a INNER JOIN pro_product AS b ON b.id=a.cid_product_two
				WHERE a.cid_product_two=$id_product
				");
		$sql='';
		foreach($get_id_one as $one){
			$sql .= " a.id={$one->cid_product_two} OR";
		}
		foreach($get_id_two as $two){
			$sql .= " a.id={$two->cid_product_one} OR";
		}
		if($sql!=''){
			$sql=" AND (".substr($sql, 0,strlen($sql)-2).")";
		}else{
			$sql=" AND a.id=0";
		}
		$sql_query="
		SELECT
				a.id AS myid,a.is_status_cate,a.status,a.isprice,a.name,a.code,a.sap_code,a.cid_cate,a.is_vat,a.cid_series,a.note_price,a.is_sample,
				b.id AS cid_res,b.cid_product,b.saleprice,b.discount,b.status,
				b.cid_supplier,b.stock_num,
				c.id AS idsupplier, c.fullname, c.is_type,c.name AS namesupplier
		FROM 
			(  pro_product AS a INNER JOIN pro_supplier_product AS b ON a.id = b.cid_product )
							    INNER JOIN market_supplier AS c ON c.id=b.cid_supplier
		WHERE     b.status='1' AND a.is_status_cate='1' AND a.status='1' AND a.isprice='1' 
		$sql 
		ORDER BY c.is_type DESC
		
		";
		
			$value=	DB::select($sql_query);
			//$this->memcache->save($value,"Buy_Together".$id_product);
			return $value;
// 		}else{
// 			$this->memcache->load("Buy_Together".$id_product);
// 		}
		
	}

	/*
	 * Thuộc tính của sản phẩm
	*/
	public static function Detail_Element($id_product){
		
			$sql= "SELECT 	a.id AS myid, a.cid_product,a.cid_element,a.val,a.is_type,
							b.id,b.name
				 FROM
				 	(SELECT cid_element,position FROM comp_temp_elemt  WHERE 1 ORDER BY position ASC) AS c 
				 	INNER JOIN
				 	 (comp_elemt_product  AS a INNER JOIN comp_element AS b  ON  b.id=a.cid_element)
				 	  ON c.cid_element=b.id
				WHERE 
					a.cid_product=$id_product AND a.is_type='1'
				GROUP BY b.id
				ORDER BY c.position ASC
			";
				
				
			
		$parent=	DB::select($sql);
		$data=array();
		
		foreach($parent as $value){
			$data[$value->myid]=array(
				"name"=>$value->name,
				"value"=>DB::select
					("SELECT  a.cid_product,a.cid_element,a.val,a.is_type,a.cid_parent,
						b.id,b.name
						
				 FROM 
							(SELECT cid_element,position FROM comp_temp_elemt  WHERE 1 ORDER BY position ASC) AS c
							INNER JOIN
							(comp_elemt_product  AS a INNER JOIN comp_element AS b  ON  b.id=a.cid_element)
						  ON c.cid_element=b.id
				WHERE a.cid_product=$id_product AND a.is_type='0' AND a.cid_parent={$value->myid}
				GROUP BY b.id
				ORDER BY c.position ASC
				")
			);
		
		}
		return $data;
	
	}

	/*
	 * Sản phẩm tượng tự trong trang chi tiết
	 */
	public static function Detail_Simalar($id_product,$supplier=1){
		$query_similar="
					SELECT
						d.cid_product_one,d.cid_product_two,d.cid_product_three,d.cid_product_four
					FROM pro_similar AS d
					WHERE 
						d.cid_product_one=$id_product OR d.cid_product_two =$id_product OR d.cid_product_three=$id_product OR d.cid_product_four=$id_product 
				
				";
			$sql='';
			$TSililar=DB::select($query_similar);
			if(!empty($TSililar[0]->cid_product_one) )
				$sql=" b.id={$TSililar[0]->cid_product_one} OR";
			if(!empty($TSililar[0]->cid_product_two) )
				$sql .= " b.id={$TSililar[0]->cid_product_two} OR";
			if(!empty($TSililar[0]->cid_product_three) )
				$sql .= " b.id={$TSililar[0]->cid_product_three} OR";
			if(!empty($TSililar[0]->cid_product_four) )
				$sql .= " b.id={$TSililar[0]->cid_product_four} OR";
			if($sql!=''){
				
			
				$sql=" AND (".substr($sql, 0,strlen($sql)-2).")";
				$sql1="
						SELECT 
								a.id as mysupplier, a.name AS namesupplier,
								b.id AS myid,b.name,
								b.is_status_series,b.is_status_cate,b.cid_series,c.is_tranc,
								c.id AS cid_res,c.cid_supplier,
								get_review(b.id,1) AS rating,
							  	get_review(b.id,2) AS countrating,
							  	get_price(b.id,c.discount) AS discount,
							  	get_sale_price(b.id,c.saleprice) AS saleprice
							FROM
									pro_product AS b  INNER JOIN pro_supplier_product AS c ON c.cid_product=b.id
													   INNER JOIN market_supplier AS a ON a.id=c.cid_supplier
													
							WHERE
								c.status='1' AND b.status='1' AND b.is_status_series='1' AND b.is_status_cate='1'
								$sql
							GROUP BY b.id
							ORDER BY a.is_type DESC
					
					";
				
				$value=	DB::select(DB::raw($sql1) );
				return $value;
			}
			return null;
	 
		
	}

	
}
