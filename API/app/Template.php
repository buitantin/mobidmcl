<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Template extends Model {


	//
	protected $table='comp_template';
	public $timestamps =false;

	//
	public function getFilter_Parent($id_cate){
		$root= 	$this->TT_DB->query("
				SELECT	a.id AS idelement, a.name, a.is_filter,a.position,
						b.position, b.cid_template,b.cid_element,b.is_type,
						c.cid_cate,c.id AS idtemplate,
						count(d.cid_product) as mycount,d.cid_template
						
				FROM 
					comp_element AS a INNER JOIN comp_temp_elemt AS b ON a.id=b.cid_element
									  INNER JOIN comp_template AS c  ON c.id=b.cid_template
									  INNER JOIN comp_temp_product AS d ON d.cid_template=b.cid_template
									 
				WHERE 
					c.cid_cate=$id_cate AND a.is_filter='1' AND b.is_type='1'
			");
		$data=array();
		foreach($root as $r){
			$data[$r['idelement']]=array(
				"name"=>$r['name'],
				"value"=>$this->TT_DB->query("
							SELECT	a.id AS idelement, a.name, a.is_filter,a.position,
									b.position, b.cid_template,b.cid_element,b.is_type,
									c.cid_cate,c.id AS idtemplate,
									count(d.cid_product) as mycount,d.cid_template
							FROM 
								comp_element AS a INNER JOIN comp_temp_elemt AS b ON a.id=b.cid_element
												  INNER JOIN comp_template AS c  ON c.id=b.cid_template
												   INNER JOIN comp_temp_product AS d ON d.cid_template=b.cid_template
							WHERE 
								c.cid_cate=$id_cate AND a.is_filter='1' AND b.is_type='0' AND d.cid_template={$r['idtemplate']}
							GROUP BY d.cid_template
						")			
			);
		}
		return $data;
	}
	/*
	 * CATE  ID cho danh mục con
	*/
	public static function getFilter_Child($id_cate){

		$root=	DB::select("
				SELECT
					a.id,a.status,a.is_status_series,a.is_status_cate,a.cid_cate,
					b.id AS myid,b.cid_product,b.cid_parent,b.is_type,b.val,b.cid_element,
					c.id,c.name,c.is_filter
				FROM 
					pro_product AS a 
									 INNER JOIN comp_elemt_product AS b ON a.id=b.cid_product
									 INNER JOIN comp_element AS c ON c.id=b.cid_element
				WHERE 
					 a.is_status_series='1' AND a.is_status_cate='1' AND a.status='1' 
				 	AND c.is_filter='1'
					AND b.is_type='0'  AND a.cid_cate=$id_cate
				GROUP BY c.id
			");
		$data=array();
		foreach($root as $r){
			$child=DB::select("
				SELECT
					DISTINCT b.val,COUNT(a.id) AS total,
					c.id
					
				FROM 
					pro_product AS a INNER JOIN comp_elemt_product AS b ON a.id=b.cid_product
									INNER JOIN comp_element AS c ON c.id=b.cid_element
					
				WHERE 
					 a.is_status_series='1' AND a.is_status_cate='1' AND a.status='1'  
					AND c.is_filter='1'
					AND b.is_type='0' AND a.cid_cate=$id_cate AND b.cid_element=".$r->cid_element."
				GROUP BY b.val
			");
			if(!empty($child)){
				$data[ $r->myid ]=array(
							"name"=>$r->name,
							'child'=>(array)$child
						);
			}
		}
		return $data;
		
	}
	public function getTotal_Filter($id_element_child,$id_cate,$val){
		if(!empty($id_element_child) && !empty($id_cate))
			$val=$this->getAdapter()->quote($val);
		return $this->TT_DB->fetchRow("
				SELECT
					a.id,COUNT(a.id) as total,a.status,a.is_status_series,a.is_status_cate,a.cid_cate,
					b.cid_product,b.id,b.is_type,b.cid_element,b.val
				FROM
						pro_product AS a INNER JOIN comp_elemt_product AS b ON a.id=b.cid_product
				WHERE
					a.is_status_series='1' AND a.is_status_cate='1' AND a.status='1'
					AND b.is_type='0' AND b.cid_element={$id_element_child} AND a.cid_cate=$id_cate AND b.val LIKE $val
				");
	}
	public static function Compare_One($id,$supplier){

		if(!empty($id)){


		$supplier=(empty($supplier))? 1 :$supplier;
	

		return		DB::table("pro_product AS b")->join("pro_supplier_product AS c",function($join){
						$join->on("c.cid_product","=","b.id");
						})
						->join("market_supplier AS a",function($join){
							$join->on("a.id","=","c.cid_supplier");
						})
						->whereRaw("c.status='1' AND b.status='1' AND b.is_status_series='1' AND b.is_status_cate='1'
					AND b.id=$id AND a.id=$supplier")
						->orderBy("a.is_type","DESC")->get();


		}
		return null;
	
	}
	public static function Compare_Cate($id_cate,$id_product=null){

		$sql=(empty($id_product) ? "b.is_type='0' AND c.is_compare='1' " : "b.is_type='0' AND c.is_compare='1' AND d.cid_product=$id_product");

		return DB::table("comp_template AS a")
					->join("comp_temp_elemt AS b",function($join){
						$join->on("a.id","=","b.cid_template");
					})
					->join("comp_element AS c",function($join){
						$join->on("c.id","=","b.cid_element");
					})
					->leftJoin("comp_temp_product AS d",function($join){

						$join->on("a.id","=","d.cid_template");
					})	
					->selectRaw("a.id,a.cid_cate,b.cid_template,b.cid_element,b.is_type,c.name,c.id,c.is_compare,d.cid_template,d.cid_product")
					
					
					
					->whereRaw(DB::raw($sql))->get();
		
	
	}
	
	public static function get_Product($id,$element){
	

		$r=DB::table("comp_elemt_product AS d")->join("comp_element AS a ",function($join){
				$join->on("a.id","=","d.cid_element");
		})
			->selectRaw("a.id,a.name,d.cid_product,d.val,d.cid_element,d.is_type")
			->whereRaw("d.cid_element=$element AND d.cid_product=$id ")
			->first();
			;
		if(!empty($r->val)){
			return array("id"=> $r->cid_product,"value"=>$r->val);
		}
		return array("id"=> $r->cid_product,"value"=>"");
		
	
	}
	
	
	public function Compare($array){
		$sql="";
		$supplier="";
		foreach ($array as $a){
			$sql .= " b.id={$a['id']} OR";
		}
		foreach ($array as $a){
			$supplier .= " c.cid_supplier={$a['supplier']} OR";
		}
		if($supplier!=""){
			$supplier="AND (" .substr($supplier,strlen($supplier)-2). ")";
		}
		if($sql !=''){
			$sql="AND (" .substr($sql,strlen($sql)-2). ")";
		}
		
		$data=$this->TT_DB->fetchAll("
					SELECT a.cid_product,a.cid_element,a.val,a.is_type,a.option,a.cid_parent,
							b.id,b.name, 
							c.discount,c.stock_num,c.status,c.cid_supplier
					FROM 
							comp_elemt_product AS a INNER JOIN pro_product AS b ON a.cid_product=b.id
													INNER JOIN pro_supplier_product AS c ON a.id=c.cid_product 
					WHERE 
						c.status='1' 
						$supplier
						$sql
			");
		
	}
}
