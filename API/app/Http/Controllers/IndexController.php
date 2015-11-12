<?php namespace App\Http\Controllers;
use App;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Route;
use Illuminate\Http\Request;

class IndexController extends Controller {



	public function categories(Request $request){
		$id= $request->route("id");
		
		$option=$request->route("option");
		if($option=='1'){
			$data=\App\Categories::select(array('id','name'))->whereRaw("cid_parent=$id AND status='1'")->orderBy("id","ASC")->remember(120)->get()->toJson();
		}else{
			$data=\App\Categories::select()->whereRaw("cid_parent=$id AND status='1'")->orderBy("name","DESC")->remember(120)->get()->toJson();
		}
		
		return $data;
	}
	public function categorieshome(){
		//dien tu, dien lanh,gia dung, di dong va tablet,vi tinh, vien thong
		$data=\App\Categories::select(array('id','name'))->whereRaw("cid_parent= 0 AND status='1' AND id IN (1,2,9,19,20,15) ")->remember(120)->get()->toJson();
		return $data;

	}
	public function slideshow(){
		return \App\Slideshow::where("type","=","1")->orderBy("position","DESC")->remember(10)->get()->toJson();
	}
	public function cachecate(){

		$data=\App\Categories::select(array('id','name'))->whereRaw("cid_parent=0 AND status='1'")->orderBy("id","ASC")->get();
		$j=array();
		foreach ($data as $key => $value) {
			$j[]=array($value['id'], IndexController::toAlias2($value['name']));
		}
		echo json_encode($j);


	}
	public static  function toAlias2($string)
	{
		$tmp = array("”","–","~","`","!","@","#","$","%",'%',"^","&","*","(",")","-","_","=","+","{","[","]","}","|","\\",":",";","'","\"","<",",",">",".","?","/");
		$string=mb_strtolower($string,"UTF-8");
		$string = IndexController::tranferData2($string);
		$string = strip_tags($string);
		$string = trim($string, " \n\t.");
		$string = str_replace($tmp,"",$string);
		
		
		$arr = explode(" ", $string);
		
		$string = "";
		foreach ($arr as $key)
		{
			if(!empty($key))
				$string.= "-".preg_replace('/[^A-Za-z0-9\-]/', '', $key);
		}
		$string = str_replace("-–-","",$string);
	
		return mb_strtolower(substr($string, 1));
	}
	public static function tranferData2($string)
	{
		$trans = array(
					'à'=>'a','á'=>'a','á'=>'a','ả'=>'a','ã'=>'a','ạ'=>'a',
					'ă'=>'a','ằ'=>'a','ắ'=>'a','ẳ'=>'a','ẵ'=>'a','ặ'=>'a',
					'â'=>'a','ầ'=>'a','ấ'=>'a','ẩ'=>'a','ẫ'=>'a','ậ'=>'a',
					'À'=>'a','Á'=>'a','Ả'=>'a','Ã'=>'a','Ạ'=>'a',
					'Ă'=>'a','Ằ'=>'a','Ắ'=>'a','Ẳ'=>'a','Ẵ'=>'a','Ặ'=>'a',
					'Â'=>'a','Ầ'=>'a','Ấ'=>'a','Ẩ'=>'a','Ẫ'=>'a','Ậ'=>'a',    
					'đ'=>'d','Đ'=>'d',
					'è'=>'e','é'=>'e','ẻ'=>'e','ẽ'=>'e','ẹ'=>'e',
					'ê'=>'e','ề'=>'e','ế'=>'e','ể'=>'e','ễ'=>'e','ệ'=>'e',
					'È'=>'e','É'=>'e','Ẻ'=>'e','Ẽ'=>'e','Ẹ'=>'e',
					'Ê'=>'e','Ề'=>'e','Ế'=>'e','Ể'=>'e','Ễ'=>'e','Ệ'=>'e',
					'ì'=>'i','í'=>'i','ỉ'=>'i','ĩ'=>'i','ị'=>'i',
					'Ì'=>'i','Í'=>'i','Ỉ'=>'i','Ĩ'=>'i','Ị'=>'i',
					'ò'=>'o','ó'=>'o','ỏ'=>'o','õ'=>'o','ọ'=>'o',
					'ô'=>'o','ồ'=>'o','ố'=>'o','ổ'=>'o','ỗ'=>'o','ộ'=>'o',
					'ơ'=>'o','ờ'=>'o','ớ'=>'o','ở'=>'o','ỡ'=>'o','ợ'=>'o',
					'Ò'=>'o','Ó'=>'o','Ỏ'=>'o','Õ'=>'o','Ọ'=>'o',
					'Ô'=>'o','Ồ'=>'o','Ố'=>'o','Ổ'=>'o','Ỗ'=>'o','Ộ'=>'o',
					'Ơ'=>'o','Ờ'=>'o','Ớ'=>'o','Ở'=>'o','Ỡ'=>'o','Ợ'=>'o',
					'ù'=>'u','ú'=>'u','ủ'=>'u','ũ'=>'u','ụ'=>'u',
					'ư'=>'u','ừ'=>'u','ứ'=>'u','ử'=>'u','ữ'=>'u','ự'=>'u',
					'Ù'=>'u','Ú'=>'u','Ủ'=>'u','Ũ'=>'u','Ụ'=>'u',
					'Ư'=>'u','Ừ'=>'u','Ứ'=>'u','Ử'=>'u','Ữ'=>'u','Ự'=>'u',
					'ỳ'=>'y','ý'=>'y','ỷ'=>'y','ỹ'=>'y','ỵ'=>'y',
					'Y'=>'y','Ỳ'=>'y','Ý'=>'y','Ỷ'=>'y','Ỹ'=>'y','Ỵ'=>'y'
				  );
  		$string = strtr(html_entity_decode($string,null,"UTF-8"), $trans);
  		return $string;		
	}

}
