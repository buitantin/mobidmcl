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



use Illuminate\Http\Request;

class FilterController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getcate($id)
	{
	
		return Cache::remember("get_filter_cate_".$id,20,function() use($id){


			return		DB::table("pro_product AS a")
						->selectRaw(DB::raw("b.id,b.name,count(a.id) AS total") )
						->whereRaw("a.is_status_series='1' AND a.is_status_cate='1' AND a.status='1'  AND b.cid_parent=$id ")
						->join("pro_categories AS b",function($join){
							$join->on("a.cid_cate","=","b.id");
						})
						
						->groupBy("b.id")
						->get();
		});


	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getseries($id_child)
	{
		//
		return Cache::remember("get_filter_series_".$id_child,30,function() use($id_child){


            return DB::table("pro_product AS a")
            		->selectRaw("b.id,b.name,count(a.id) AS total")
            		->whereRaw("a.cid_cate={$id_child}   AND a.is_status_series='1' AND a.is_status_cate='1' AND a.status='1'  AND b.status='1'")
            		->join("pro_series AS b",function($join){
            			$join->on("b.id","=","a.cid_series");
            		})
            		->groupBy("b.id")	
            		->orderBy("b.name","ASC")
            		->get();

         });   		



	}
	public function getprice($id){
		return Categories::getFilterPrice_Child($id);
	}

	public function gettemplate($id){
		return Template::getFilter_Child($id);
	}
	public function getproduct($id,Request $request){
		$series= $request->get("series");
		$template= $request->get("template");
		$price= $request->get("price");

		$sql=array();
		$s='';
		$check=false;
		if(!empty($series)){
			$sql[1]  = " a.cid_series IN (".implode(",", $series)." ) ";
				
		}
		if(!empty($template)){
			$sql[2]  = " (".implode(" OR ", $template)." ) ";
			$check=true;	
		}
		if(!empty($price)){
			$sql[3]  = "  ".implode(" OR ", $price)."  ";
		}
		if(!empty($sql)){
			$s=" AND (" .implode(" AND ", $sql)." ) ";
		
		}
		//echo $s;exit;
		$a=Product::Get_Product_Filter($id,$s,$check);
		return Response::json($a);


	}

}
