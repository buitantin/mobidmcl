<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Cache;
class Promotion extends Model {

	protected	$table="pro_promotion_product";
	public $timestamps =false;

	protected  $memcache=null;
	
	public static function getProduct($id_product){
		$product=Promotion::select("type_promo")->whereRaw(DB::raw("cid_product=$id_product and status='0'") )->first();
		if(!empty($product)){
			//PROMOTION TEXT
			if($product['type_promo']=='4'){
				
			
				  				return DB::table("pro_promotion_product AS a")
					  				->join("promo_text AS b",function($join){
					  					$join->on("a.cid_promotion","=","b.id");
					  				})
					  				->whereRaw("a.cid_product=$id_product AND a.status='0' AND b.active='1'")
					  				->get();

			}
			
		}else{
			return false;
		}
	}
	/*
	 * Lấy loại của promotion của 1 sản phẩm
	 */
	public static function getIDProduct($id_product){
		return Promotion::whereRaw(DB::raw("cid_product=$id_product AND status='0'") )->first();
	}
	public static function getDetail($id_product,$supplier){
			return Promotion::whereRaw(DB::raw("cid_product=$id_product AND cid_supplier=$supplier AND status='0'") )->orderBy("type_promo","ASC")->first();	
		
		
	}
	/*
	 * supplier =1 is DMCL
	 * $id_supplier_product is ID product of supplier
	 */
	public static function getProduct_Price($id_supplier_product,$supplier='1'){

		$product=Promotion::whereRaw("cid_product=$id_supplier_product AND status='0'")->orderBy("type_promo","ASC")->first();

		if(!empty($product)){
			
			//$product=$product[0];
			//1: deal, 2: online , 3: press, 4:text
				if($product->type_promo=='1'){
					$value= DB::select("
								SELECT	 b.date_end,b.quantity,b.limit_quantity
								FROM 	pro_promotion_product AS a INNER JOIN promo_deals AS b ON a.cid_promotion=b.id
								WHERE	 a.cid_product=$id_supplier_product AND a.status='0' AND b.active='1'
										AND a.cid_promotion={$product->cid_promotion}
								ORDER BY b.id DESC
							");
					return $value;
				}
				if($product->type_promo=='2'){
					
					$value=  DB::select("
							SELECT	b.date_end,b.quantity,b.limit_quantity
									
							FROM 	pro_promotion_product AS a INNER JOIN promo_online AS b ON a.cid_promotion=b.id
							WHERE 	a.cid_product=$id_supplier_product AND a.status='0' AND b.active='1'
									AND a.cid_promotion={$product->cid_promotion}
							ORDER BY b.id DESC
							");
							return $value;
					
					
				}
				if($product->type_promo=='3'){
					$press= DB::select("
							SELECT	b.date_end,b.quantity,b.limit_quantity
							
							FROM pro_promotion_product AS a INNER JOIN promo_press AS b ON a.cid_promotion=b.id
							WHERE
								a.cid_product=$id_supplier_product AND a.status='0' AND b.active='1' AND b.price!='0' AND a.cid_promotion={$product->cid_promotion}
							ORDER BY b.id DESC
							");
					return $press;
					
				
				}
			
			
	
			
		}
		
	
		return null;
// 	
	}

	public function getProduct_Price_Press($id_supplier_product,$supplier='1',$type='all'){
		if(!$result=$this->memcache->load("getProduct_Price_Press".$id_supplier_product.$supplier.$type)){
			if($type=='text'){ 	
				 $press= $this->TT_DB->fetchAll("
						SELECT	b.id,b.name,b.active,b.price,b.saleprice,b.type,b.date_end,b.date_start,b.description,
						a.cid_product,a.cid_promotion,a.status,a.type_promo
						FROM
						pro_promotion_product AS a INNER JOIN promo_press AS b ON a.cid_promotion=b.id
			
						WHERE
							a.cid_product=$id_suppli er_product AND a.status='0' AND b.active='1' AND b.name!=''
							AND ( b.date_end > '".date("Y-m-d H:i:s")."' OR b.date_end = b.date_start)
								AND a.type_promo='3'	
								ORDER BY b.id DESC
								");
				if(!empty($press)){
					$this->memcache->save($press,"getProduct_Price_Press".$id_supplier_product.$supplier.$type);
					return $press;
				}
									
			}
			
			$this->memcache->save(false,"getProduct_Price_Press".$id_supplier_product.$supplier.$type);
			return false;
	
			return false;
		}else{
			return $this->memcache->load("getProduct_Price_Press".$id_supplier_product.$supplier.$type);
		}
	}
	public static function List_Promotion_Press($limit=6){
		return Cache::remember("list_promotion_press",2,function() use($limit){


			$data=	$data_product=DB::table('pro_product AS a')
							  ->join("pro_supplier_product As b",function($join){
							  		$join->on("b.cid_product","=","a.id");
							  		
							  })
							  ->join("pro_promotion_product AS c",function($join){
							  	$join->on('c.cid_product',"=","b.id");//->where("c.type_promo","=","3")->orWhere("c.status","=","0"); //->whereRaw("c.type_promo='3' AND c.status='0'");
							  })
							  ->join("promo_press AS d",function($join){
							  		$join->on("d.id","=","c.cid_promotion");//->where("d.active","=","1")->orWhere("d.type","=","0");//->whereRaw("d.active='1' AND d.type='0'");
							  })
							  ->whereRaw(" a.is_status_series='1' AND a.is_status_cate='1' AND a.status='1' AND a.isprice='1' 
				 			  AND c.status='0'
				 			  AND d.active='1' AND d.type='0'
				 			  AND c.type_promo='3'" )
								  ->select(
							  		DB::raw("get_review(a.id,1) AS rating"),
							  		DB::raw("get_review(a.id,2) AS countrating"),
							  		"a.id AS myid","a.code","a.sap_code","a.is_hot","a.name","a.cid_series","a.cid_cate","b.id AS cid_res","d.quantity","d.saleprice","d.price","a.isprice"

							  		)
							   ->groupBy("a.id")
							  ->limit($limit)
							  

							  //->toSql();exit();
							  ->get();
							  return ($data);
			});
	}
	public static function List_Promotion_Online($limit=6){
				$data=	$data_product=DB::table('pro_product AS a')
							  ->join("pro_supplier_product As b",function($join){
							  		$join->on("b.cid_product","=","a.id");
							  		
							  })
							  ->join('pro_promotion_product AS c',function($join){
							  	$join->on('c.cid_product',"=","b.id");//->where("c.type_promo","=","3")->orWhere("c.status","=","0"); //->whereRaw("c.type_promo='3' AND c.status='0'");
							  })
							  ->join("promo_online AS d",function($join){
							  		$join->on("d.id","=","c.cid_promotion");//->where("d.active","=","1")->orWhere("d.type","=","0");//->whereRaw("d.active='1' AND d.type='0'");
							  })
							  ->whereRaw("a.is_status_series='1' AND a.is_status_cate='1' AND a.status='1'
						 	 AND c.type_promo='2' AND c.status='0' AND  d.active='1' AND d.type='0'" )
							  ->select("a.id AS myid","a.code","a.sap_code","a.is_hot","a.name","a.cid_series","a.cid_cate","b.id AS cid_res","d.quantity","d.saleprice","d.price","a.isprice")
							  ->limit($limit)
							  ->get();
							  return ($data);

	}
	public static function List_All_Promotion($limit=6){
		$data=	$data_product=DB::table('pro_product AS a')
							  ->join("pro_supplier_product As b",function($join){
							  		$join->on("b.cid_product","=","a.id");
							  		
							  })
							  ->join('pro_promotion_product AS c',function($join){
							  	$join->on('c.cid_product',"=","b.id");//->where("c.type_promo","=","3")->orWhere("c.status","=","0"); //->whereRaw("c.type_promo='3' AND c.status='0'");
							  })
							  ->join("promo_press AS d",function($join){
							  		$join->on("d.id","=","c.cid_promotion");//->where("d.active","=","1")->orWhere("d.type","=","0");//->whereRaw("d.active='1' AND d.type='0'");
							  })
							  ->whereRaw("a.is_status_series='1' AND a.is_status_cate='1' AND a.status='1' AND a.isprice='1' AND c.status='0' AND d.cid_supplier=1 AND d.status='1' AND (c.type_promo='2' OR c.type_promo='3')" )
			
			
							  ->select("a.id AS myid","a.code","a.sap_code","a.is_hot","a.name","a.cid_series","a.cid_cate","b.id AS cid_res","d.quantity","d.saleprice","d.price")
							  ->limit($limit)
							  ->get();
						 return ($data);
	}
	public function List_Promotion($sql='',$orderby=''){
		$sql="
		SELECT
			DISTINCT a.id as myid,a.code,a.sap_code,a.is_hot,a.name,a.is_new,a.is_hot,a.cid_series,a.cid_cate,a.is_home,a.isprice,a.status,a.is_status_series,a.is_status_cate,
				d.saleprice,d.discount,d.cid_supplier,d.cid_product,d.status,
				c.status,c.cid_product,c.cid_promotion,c.type_promo,
			s.id AS cid_res,s.id,s.cid_product,
			x.id,x.cid_parent
		FROM
		(   pro_product AS a INNER JOIN pro_supplier_product AS s ON a.id=s.cid_product
		INNER JOIN pro_promotion_product as c ON c.cid_product=s.id )
		INNER JOIN pro_supplier_product AS d ON d.cid_product=a.id
		INNER JOIN pro_categories AS x ON x.id=a.cid_cate
		WHERE
		a.is_status_series='1' AND a.is_status_cate='1' AND a.status='1' 
		AND c.status='0' AND d.cid_supplier=1 AND d.status='1'
		AND (c.type_promo='3')
		
		$sql
		
		GROUP BY a.id
		
		$orderby
	
			
		";
		return	$data_product=$this->TT_DB->fetchAll($sql);
	}
	public function Count_List_Promotion($s=''){
		$sql="
		SELECT
			 a.id 
			FROM
			(   pro_product AS a INNER JOIN pro_supplier_product AS s ON a.id=s.cid_product
			INNER JOIN pro_promotion_product as c ON c.cid_product=s.id )
			INNER JOIN pro_supplier_product AS d ON d.cid_product=a.id
			INNER JOIN pro_categories AS x ON x.id=a.cid_cate
			WHERE
			a.is_status_series='1' AND a.is_status_cate='1' AND a.status='1' 
			AND c.status='0' AND d.cid_supplier=1 AND d.status='1'
			AND ( c.type_promo='3')
		 	$s
	    	GROUP BY a.id
		";
		return	$data_product=$this->TT_DB->query($sql)->rowCount();
	}
	public function List_Promotion_Deal($limit=" LIMIT 0,3 "){
		$sql="
					SELECT DISTINCT a.id as myid,a.code,a.sap_code,a.is_hot,a.name,a.is_new,a.is_hot,a.cid_series,a.cid_cate,a.is_home,a.isprice,a.status,a.is_status_series,a.is_status_cate,
			 				c.status,c.cid_product,c.cid_promotion,c.type_promo,
			 				d.id,d.quantity,d.saleprice,d.price,d.active,d.date_end,d.date_start,
			 				s.id AS cid_res,s.id,s.cid_product,s.cid_supplier
					FROM (   
							pro_product AS a INNER JOIN pro_supplier_product AS s ON s.cid_product=a.id
								INNER JOIN pro_promotion_product as c ON c.cid_product=s.id )
			 					INNER JOIN promo_deals as d ON d.id=c.cid_promotion
					WHERE   a.is_status_series='1' AND a.is_status_cate='1' AND a.status='1' AND a.isprice='1' 
			 			  AND c.status='0' AND c.type_promo='1'
			 			  AND d.active='1'
			 			  AND c.type_promo='1'
						$limit
				";
		return	$data_product=$this->TT_DB->fetchAll($sql);
	}
	/*
	 * Old deal
	 */
	public function List_Promotion_Deal_Old(){
		$sql="
		SELECT DISTINCT a.id as myid,a.code,a.sap_code,a.is_hot,a.name,a.is_new,a.is_hot,a.cid_series,a.cid_cate,a.is_home,a.isprice,a.status,a.is_status_series,a.is_status_cate,
		c.status,c.cid_product,c.cid_promotion,c.type_promo,
		d.id,d.quantity,d.saleprice,d.price,d.active,d.date_end,d.date_start,
		s.id AS cid_res,s.id,s.cid_product
		
		FROM (   
			pro_product AS a INNER JOIN pro_supplier_product AS s ON a.id=s.cid_product
			INNER JOIN  pro_promotion_product as c ON c.cid_product=s.id )
			INNER JOIN promo_deals as d ON d.id=c.cid_promotion
		WHERE   a.is_status_series='1' AND a.is_status_cate='1' AND a.status='1' AND a.isprice='1'
		AND c.status='1' AND c.type_promo='1'
		AND d.active='0'
		";
		return	$data_product=$this->TT_DB->fetchAll($sql);
	}
	public static function getGift($id,$supplier='1'){

		$type_promo=Promotion::whereRaw("cid_product=$id AND status='0'")->orderBy("type_promo","ASC")->first();

		$data_product=array();

		if(!empty($type_promo->type_promo)){



            if($type_promo->type_promo=='2'){
                $sql_online = "
                    SELECT 

					d.name,d.description,d.id AS idpromotion
					FROM (  
							 pro_product AS a INNER JOIN pro_supplier_product AS s ON a.id=s.cid_product 
								INNER JOIN pro_promotion_product as c ON c.cid_product=s.id )
								INNER JOIN promo_online as d ON d.id=c.cid_promotion
					WHERE   a.is_status_series='1' AND a.is_status_cate='1' AND a.status='1'
					AND d.active='1' AND c.type_promo='2'
					AND s.id=$id AND s.cid_supplier=$supplier
                ";
                $v=DB::select($sql_online);
                if(!empty($v[0])){
                	$data_product['online']=$v;
                }
            }elseif($type_promo->type_promo=='3'){

                $sql_press = "
                    SELECT 

					d.name,d.description,d.id AS idpromotion
					
					FROM (  
							 pro_product AS a 
							 INNER JOIN pro_supplier_product AS s ON a.id=s.cid_product 
							 INNER JOIN pro_promotion_product as c ON c.cid_product=s.id 
							 INNER JOIN  promo_press as d ON d.id=c.cid_promotion
						)
					WHERE   a.is_status_series='1' AND a.is_status_cate='1' AND a.status='1'
					AND d.active='1' AND c.type_promo='3'
					AND s.id=$id AND s.cid_supplier=$supplier
                ";
                $v=DB::select($sql_press);
                if(!empty($v[0])){
                	$data_product['press']=$v;
                }
                
            }elseif($type_promo->type_promo=='4' || $type_promo->type_promo=='1'){
                $sql="
					SELECT 

					d.name,d.description,d.id AS idpromotion
					FROM (  
							 pro_product AS a INNER JOIN pro_supplier_product AS s ON a.id=s.cid_product 
								INNER JOIN pro_promotion_product as c ON c.cid_product=s.id )
								INNER JOIN promo_text as d ON d.id=c.cid_promotion
					WHERE   a.is_status_series='1' AND a.is_status_cate='1' AND a.status='1'
					AND d.active='1' AND c.type_promo='4'
					AND s.id=$id AND s.cid_supplier=$supplier
				";
				$v=DB::select($sql);

                if(!empty($v[0])){
                	$data_product['text']=$v;
                }
            }
                
			$sql_gift="
				SELECT 
				a.isprice,a.status,
				c.cid_product,c.cid_gift,c.cid_supplier,
				d.id,d.name,d.amount
				
				FROM (   pro_product AS a
					  INNER JOIN pro_supplier_product AS b ON a.id=b.cid_product
					 INNER JOIN pro_gift_product as c ON c.cid_product=a.id )
				INNER JOIN pro_gift as d ON d.id=c.cid_gift
				WHERE   a.is_status_series='1' AND a.is_status_cate='1' AND a.status='1'
				AND b.id=$id AND c.cid_supplier=$supplier
				GROUP BY d.id
			";		


			$v=DB::select($sql_gift);

				
                if(!empty($v[0])){
                	$data_product['gift']=$v;
                }	

			return $data_product;
	  }
	  return null;
	}
    
    public function getProduct_filter_khuyenmai($sql=null){
		$sql_parent="
		SELECT	a.id as myid,a.code,a.sap_code,a.name,a.is_new,a.is_hot,a.cid_series,a.cid_cate,a.is_home,a.isprice,a.status,a.is_status_series,a.is_status_cate,
		b.id,b.cid_parent,b.links_right,b.links_left,b.cid_parent,
		c.id AS cid_res,c.saleprice,c.discount,c.stock_num,c.status,c.cid_supplier,c.cid_product,c.saleprice,c.discount,d.type_promo
		FROM	
        
		( pro_product AS a INNER JOIN pro_categories AS b ON a.cid_cate=b.id
		INNER JOIN pro_supplier_product AS c ON a.id = c.cid_product
        INNER JOIN pro_promotion_product AS d ON d.cid_product=c.id )
		WHERE
		c.status='1' AND a.is_status_series='1' AND a.is_status_cate='1' AND a.status='1' 
        AND d.status='0' AND ( d.type_promo='3')
		$sql
		GROUP BY myid
		ORDER BY a.is_hot DESC,a.is_new DESC,a.id DESC			
		";
		return $this->TT_DB->fetchAll($sql_parent);
	
	}


}
