<?php namespace App;

use DB;

class Categories extends Model {

	//
	protected $table='pro_categories';
	public $timestamps =false;


	public static function getFilterPrice_Child($id_cate_child,$supplier=0){
					$MrValidateData=new MrValidateData();
					$sql_parent="
						SELECT a.cid_cate,a.is_home,a.isprice,a.status,a.is_status_series,a.is_status_cate,
						b.id,b.cid_parent,b.links_right,b.links_left,
						MAX(c.saleprice) AS mymax,MIN(c.saleprice) as mymin,c.discount,c.stock_num,c.status,c.cid_supplier,c.cid_product,c.saleprice,c.discount
						FROM
						(  pro_product AS a INNER JOIN pro_categories AS b ON a.cid_cate=b.id)
						INNER JOIN pro_supplier_product AS c ON a.id = c.cid_product
						WHERE
						b.id={$id_cate_child}";
                    if(!empty($supplier)){
                        $sql_parent.=" AND c.cid_supplier=$supplier";
                    }                    
						$sql_parent.=" AND a.is_status_series='1' AND a.is_status_cate='1' AND a.status='1'  AND c.status='1'
					";


					$data= DB::select($sql_parent);

					$data=(empty($data[0]))? null : $data[0];

				
					if($data->mymax-$data->mymin >1000000){
							$tc_min_pirce=($data->mymin<500000) ? 500000 : $MrValidateData->formod($data->mymin) ;
							$tc_max_price=($data->mymax>30000000) ? 30000000 : $MrValidateData->formod($data->mymax) ;
					}else{
						$tc_min_pirce=($data->mymin<500000) ? 500000 : ($data->mymin) ;
						$tc_max_price=($data->mymax>30000000) ? 30000000 : ($data->mymax) ;
					}
					//	print_r($data); exit;
					$gioi_han_lai=(($tc_max_price-$tc_min_pirce)<5000000) ? 3 : 7;
					$phan_doan1=  round(($tc_max_price-$tc_min_pirce)/$gioi_han_lai);
						
					$phan_doan1=$MrValidateData->formod($phan_doan1);
				
					$result=array();
					$result['scroll']=$data;
					$t=$tc_min_pirce;
					for($i=0;$i<$gioi_han_lai;$i++){
					if($i==0){
					   $sql = "SELECT a.cid_cate,a.isprice,a.status,a.is_status_series,a.is_status_cate,
    					b.id,b.cid_parent,
    					count(a.id) AS total,c.status,c.cid_supplier,c.cid_product,c.saleprice,c.discount
    					FROM
    					(  pro_product AS a INNER JOIN pro_categories AS b ON a.cid_cate=b.id)
    					INNER JOIN pro_supplier_product AS c ON a.id = c.cid_product
    					WHERE
    					b.id={$id_cate_child}";
                        if(!empty($supplier)){
                            $sql.=" AND c.cid_supplier=$supplier";
                        }
                        $sql.=" AND c.discount <= $t
    					AND a.is_status_series='1' AND a.is_status_cate='1' AND a.status='1' AND c.status='1'
    					";
    					$count= DB::select($sql);

    					$count=(empty($count[0]))? null : $count[0];

    					$result[]=array('name'=>"Thấp hơn ".$MrValidateData->toPice($t),"value"=>$t,"count"=>$count->total,'position'=>1);
				
					}elseif($i==$gioi_han_lai-1){
						$max_price=$t-$phan_doan1;
                        $sql2 = "SELECT a.cid_cate,a.isprice,a.status,a.is_status_series,a.is_status_cate,
							b.id,b.cid_parent,
							count(a.id) AS total,c.status,c.cid_supplier,c.cid_product,c.saleprice,c.discount
							FROM
							(  pro_product AS a INNER JOIN pro_categories AS b ON a.cid_cate=b.id)
							INNER JOIN pro_supplier_product AS c ON a.id = c.cid_product
							WHERE
							b.id={$id_cate_child}";
                            if(!empty($supplier)){
                                $sql2.=" AND c.cid_supplier=$supplier";
                            }
                            $sql2 .=" AND c.discount > $max_price
							AND a.is_status_series='1' AND a.is_status_cate='1' AND a.status='1' AND c.status='1'
							";
							$count= DB::select($sql2);
							$count=(empty($count[0]))? null : $count[0];
							$result[]=array('name'=>"Cao hơn ".$MrValidateData->toPice($max_price),"value"=>$max_price,"count"=>$count->total,'position'=>2);
					}else
					{
						$ff=$t-$phan_doan1;
                        $sql3="SELECT a.cid_cate,a.isprice,a.status,a.is_status_series,a.is_status_cate,
								b.id,b.cid_parent,
						count(a.id) AS total,c.status,c.cid_supplier,c.cid_product,c.saleprice,c.discount
						FROM
						(  pro_product AS a INNER JOIN pro_categories AS b ON a.cid_cate=b.id)
						INNER JOIN pro_supplier_product AS c ON a.id = c.cid_product
						WHERE
						b.id={$id_cate_child}";
                        if(!empty($supplier)){
                            $sql3.=" AND c.cid_supplier=$supplier";
                        }                        
                        $sql3.=" AND c.discount > $ff  AND c.discount <= ".($t)."
						AND a.is_status_series='1' AND a.is_status_cate='1' AND a.status='1' AND c.status='1'
						";
						$count= DB::select($sql3);
						$count=(empty($count[0]))? null : $count[0];
						 
						$result[]=array('name'=> $MrValidateData->toPice($ff)."-".$MrValidateData->toPice($t),"phandoan"=>$ff,"value"=>$t,"count"=>$count->total,'position'=>3);
				  }
				
				
					$t=$phan_doan1+$t;
				}
						return $result;
					
					
				}
}
