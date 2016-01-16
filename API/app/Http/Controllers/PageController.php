<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use DB;
use Response;
use Cache;
class PageController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getListBranch($id)
	{
		return Cache::remember("page_get_list_branch_".$id,50,function() use($id){
			$result=DB::table("tm_store")->whereRaw("status='1' AND is_area=$id")->get();
			return Response::json($result);
		});
	}
	public function getDetailBranch($id){
		return Cache::remember("page_get_detail_branch_".$id,50,function() use($id){
			$result=DB::table("tm_store")->whereRaw("status='1' AND id=$id")->get();
			if(!empty($result[0])){
				return Response::json($result[0]);	
			}
			return '';
		});
		
	}
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getinstallment($id)
	{
		return Cache::remember("page_get_installment_".$id,50,function() use($id){
			$result=DB::table("tm_installment")->whereRaw("id=$id")->get();
			if(!empty($result[0])){
				return Response::json($result[0]);	
			}
			return '';
		});
	}
	public function getmemberbenefits($id)
	{
		return Cache::remember("page_get_memberbenefits_".$id,50,function() use($id){
			$result=DB::table("tm_memberbenefits")->whereRaw("id=$id")->get();
			if(!empty($result[0])){
				$result[0]->coupon=str_replace( ["http://dienmaycholon.vn/public","/public"], "http://m.dienmaycholon.vn/img", $result[0]->coupon);
				$result[0]->discount	=str_replace( ["http://dienmaycholon.vn/public","/public"], "http://m.dienmaycholon.vn/img", $result[0]->discount);
				$result[0]->birthday=str_replace( ["http://dienmaycholon.vn/public","/public"], "http://m.dienmaycholon.vn/img", $result[0]->birthday);

				$result[0]->conditions=str_replace( ["http://dienmaycholon.vn/public","/public"], "http://m.dienmaycholon.vn/img", $result[0]->conditions);

				return Response::json($result[0]);	
			}
			return '';
		});
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function getpolicy()
	{
		return Cache::remember("page_get_policy",50,function(){
			$result=DB::table("art_pagecontent")->whereRaw("name LIKE 'Quy Định Giao Hàng'")->get();
			if(!empty($result[0])){
				return Response::json($result[0]);	
			}
			return '';
		});
	}
	public function getinfo()
	{
		return Cache::remember("page_get_getinfo",50,function(){
			$result=DB::table("art_pagecontent")->whereRaw("name LIKE 'Giới thiệu công ty'")->get();
			if(!empty($result[0])){
				return Response::json($result[0]);	
			}
			return '';
		});
	}
	public function getonline()
	{
		return Cache::remember("page_get_getonline",50,function(){
				$result=DB::table("art_pagecontent")->whereRaw("name LIKE 'Hướng dẫn mua hàng online'")->get();
				if(!empty($result[0])){
					return Response::json($result[0]);	
				}
				return '';
			});
	}
	public function getmember()
	{
		return Cache::remember("page_get_getmember",50,function(){
			$result=DB::table("art_pagecontent")->whereRaw("name LIKE 'Quyền Lợi Của Thành Viên'")->get();
			if(!empty($result[0])){
				return Response::json($result[0]);	
			}
			return '';
		});
	}



}
