<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use DB;
use Auth;
use Response;
class UserController extends Controller {

	public function listlocation(){
		return DB::select("SELECT * FROM tm_cities WHERE cid_parent='0'");
	}
	
	public function getlocation($id){
		$a= 	DB::select("SELECT * FROM tm_cities WHERE id=$id");
		return Response::json($a[0]);
	
	}
	public function liststate($id){
		return DB::select("SELECT * FROM tm_cities WHERE cid_parent=$id");
	}
	public function saveusers(Request $request){
		$value=$request->all();
		$error=array();
		if(empty($value['username'])){
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
			$news->is_send_mail='0';
			$news->save();
			return "1";


		}
		return $error;


	}
	public function saveuserfacebook(Request $request){
			$all=$request->all();

			if(!empty($all['id']) && !empty($all['email']) && !empty($all['name'])){
				$check_user=User::where("facebook_id","=",$all['id'])->first();

				if(empty($check_user)){
					$news=new User;
					$news->username=$all['email'];
					$news->sex="name";
					$news->password=md5($all['id']);
					$news->fullname=$all['name'];
					$news->email=$all['email'];
					$news->date_cre=date("d-m-y");
					$news->lastlogin=date("d-m-y");
					$news->country="VN";
					$news->distict=784;
					$news->city=30;


					$news->facebook_id=$all['id'];
					$news->is_facebook='1';
					$news->date_login=date("Y-m-d");
					$news->is_send_mail='0';

					$news->save();
					Auth::login($news);
					return Response::json($news);
				}else{

					$check_user->date_login=date("Y-m-d");
					$check_user->is_send_mail='0';

					$check_user->save();	
					Auth::login($check_user);
					return Response::json($check_user);
				}	
				return Response::json(array("error"=>1));
					
			}
	}
	public function saveprofile(Request $request){
		$all=$request->all();

		$error=array();
		if(empty($all['fullname'])){
			$error['fullname']="Vui lòng nhập họ và tên";
		}
		if(empty($all['phone'])){
			$error['phone']="Vui lòng nhập sồ điện thoại";
		}
		if(empty($all['city'])){
			$error['city']="Vui lòng chọn  Tỉnh/Thành phố";
		}
		if(empty($all['distict'])){
			$error['distict']="Vui lòng chọn  Quận/Huyện";
		}
		if(empty($error)){
			if(Auth::check()){
				$user=User::find(Auth::user()->id);
				$user->phone=$all['phone'];
				$user->fullname=$all['fullname'];
				if(!empty($all['birthday'])){	
					$user->birthday=date("Y-m-d",strtotime($all['birthday'] ) );	
				}
				
				$user->city=$all['city'];
				$user->distict=$all['distict'];
				$user->save();
				Auth::login($user);

				return '1';
			}else{
				return '2';
			}
		}else{
			return Response::json($error);
		}
	}
	public function logout(){
		Auth::logout();
		return '1';
	}
	public function login(Request $request){
		$a=$request->all();
		$error=array();
		if(empty($a['username'])){
			$error['username']="Vui lòng nhập Tên đăng nhập";
		}
		if(empty($a['password'])){
			$error['password']="Vui lòng nhập mặt khẩu";
		}
		if(empty($error)){
			$pa=md5($a['password']);
 			$a=User::whereRaw(DB::raw("username='{$a['username']}' AND password='{$pa}'")) ->first();
 			if(!empty($a)){
 					Auth::login($a);
				    return Response::json(Auth::user());

 			}else{
 						$a=User::whereRaw(DB::raw("email='{$a['username']}' AND password='{$pa}'") )->first();
 						if(!empty($a)){
 							Auth::login($a);
 							return Response::json(Auth::user());
 						}
 						
 			}
			
			return Response::json(['error' => '1', 'username' => "Tên đăng nhập hoặc mật khẩu không chính xác"]);	
		}
		$error['error']='1';
		return Response::json($error); 
	}
} 