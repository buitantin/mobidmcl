<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use DB;
class UserController extends Controller {

	public function listlocation(){
		return DB::select("SELECT * FROM tm_cities WHERE cid_parent='0'");
	}
	public function liststate($id){
		return DB::select("SELECT * FROM tm_cities WHERE cid_parent=$id");
	}
	public function saveusers(Request $request){
		$value=$request->all();
		$error=array();
		if(!empty($value['username'])){
			$error['username']= "Vui lòng nhập tên đăng nhập"; 
		}else{
			$check_username=User::where("username","=",$value['username'])->first();
			if(!empty($check_username)){
				$error['username']="Tên đăng nhập đã tổn tại";
			}
		}
		if(empty($value['password'])){
			$error['password']="Vui lòng nhập mật khẩu";
		}
		if(empty($value['email'])){
			$error['email']="Vui lòng nhập E-mail";
		}else{
			$check_email=User::where("email","=",$value['email'])->first();
			if(!empty($check_email)){
				$error['email']="E-mail đã tồn tại";
			}
		}
		if(empty($value['name'])){
			$error['name']="Vui lòng nhập tên và họ ";
		}
		if(empty($value['phone'])){
			$error['phone']="Vui lòng nhập số điện thoại";
		}
		if(empty($error)){
			$news=new User;
			$news->username=$value['username'];
			$news->birthday=(empty($value['birthday'])?"":$value['birthday']);
			$news->sex="name";
			$news->password=md5($value['password']);
			$news->fullname=$value['name'];
			$news->phone=$value['phone'];
			$news->fax="";
			$news->email=$value['email'];
			$news->date_cre=date("d-m-y");
			$news->lastlogin=date("d-m-y");
			$news->country="VN";
			$news->distict=$value['state'];
			$news->city=$value['city'];
			$news->date_login=date("Y-m-d");
			$news->save();
			return "1";


		}
		return $error;


	}
} 