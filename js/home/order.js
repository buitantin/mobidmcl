angular.module("starter.order",[])
.controller("CheckorderCtr",function($scope,$q,$http,PUBLIC_VALUE,ValidateData,$ionicPopup){
	
    $scope.view_content=false;
    $scope.check_order=function(){

        if($scope.code!=undefined && $scope.code != ""){
            $http.post(PUBLIC_VALUE.URL+"check_order",JSON.stringify({code:$scope.code})).success(function(result){
                if(result!=null && result!=""){
                    $scope.view_content=true;
                }else{
                	$ionicPopup.show({
                		template:"Mã đơn hàng không chính xác",
                		title:"Thông báo",
                		buttons:[
                			{text:"OK"}
                		]
                	})
                }
                $scope.content=result;

            })
        }
    }
	$scope.viewStatusOrder=function(st){
		var comp={
			"0":"Chưa xác nhận",
			"1":"Xác nhận đơn hàng",
			"4":"Đang xử lý",
			"5":"Đóng gói đơn hàng",
			"6":"Đang giao hàng",
			"7":"Đang giao hàng",
			"2":"Giao hàng thành công",
			

		}
	}
	$scope.viewdate=function(dt){
		if(dt!="" && dt!=undefined){
			return dt.slice(0,10);	
		}
		return '';
		
	}
	$scope.viewsession=function(s){
		if(s=='1'){
			return "Buổi sáng";
		}else{
			return "Buổi chiều";	
		}
	}
})
.controller("BannerorderCtr",function($scope,$http,PUBLIC_VALUE){
	$http.get(PUBLIC_VALUE.URL+"order_banner").success(function(result){
		$scope.mybanner=result;
		
	})

})
.controller("TotalCartCtr",function($scope,$q,$http,PUBLIC_VALUE){
	var total_cart=0;
	
	$http.get(PUBLIC_VALUE.URL + "totalcart").success(function(result){
 			$scope.total_cart=result;

 	});

 		
})
.controller("OrderdetailproductCtr",function($scope,$state,$q,$cookieStore,$ionicScrollDelegate,$http,PUBLIC_VALUE,$ionicLoading,$ionicPopup){
	$ionicScrollDelegate.scrollTop();
	$scope.list_product=[];

	//var tam=[];
	var get_list_product=$http.get(PUBLIC_VALUE.URL + "order_get_list_product").success(function(result){
 			$scope.list_product=result;

 	});
 	$q.all([get_list_product]).then(function(result){
 		var total_cart=0;
 		angular.forEach($scope.list_product ,function(val,key){
 			if(val['product']['isprice']=='1'){
 				total_cart=parseFloat(val['product']['discount']*val['order']['limit'])+total_cart;
 			}else{
 				total_cart=parseFloat(val['product']['saleprice']*val['order']['limit'])+total_cart;
 			}
 			
 						if(val['coupon']!=undefined){
				 				total_cart=parseFloat(total_cart)-parseFloat(val['coupon'])
				 			}
 			if(val['product']['coupons']!=""){
 				$scope.viewcoupon=val['product']['coupons'];
 				$scope.viewdiscountwcoupon=val['product']['discountcoupon'];
 			}
 			
 		});
 		if(total_cart==0){
 			
 			if($cookieStore.get("coupondmcl")!=undefined){
 				$cookieStore.put("coupondmcl",null);
 			}
 			$state.go('home');
 		}
 		$scope.total_cart=total_cart;
 		$scope.count_product=Object.keys($scope.list_product).length;

 
 		
 	});


 	$scope.changelimitorder=function(id,l){
			$http.post(PUBLIC_VALUE.URL+"order_save_limit",{id:id,limit:l}).success(function(total_cart){
				if(total_cart!='0'){
					$scope.total_cart=total_cart;	
				}
				
			});
			
		}

	
	$scope.detroy=function(id){


		var dmcl_list=$http.post(PUBLIC_VALUE.URL+"order_detroy",{id:id});

		$q.all([dmcl_list]).then(function(result){

					var get_list_product=$http.get(PUBLIC_VALUE.URL + "order_get_list_product").success(function(result){
				 			$scope.list_product=result;
				 	});
				 	$q.all([get_list_product]).then(function(result){
				 		var total_cart=0;
				 		angular.forEach($scope.list_product ,function(val,key){
				 			if(val['product']['isprice']=='1'){
				 				total_cart=parseFloat(val['product']['discount']*val['order']['limit'])+total_cart;
				 			}else{
				 				total_cart=parseFloat(val['product']['saleprice']*val['order']['limit'])+total_cart;
				 			}
				 			
				 			if(val['coupon']!=undefined){
				 				total_cart=parseFloat(total_cart)-parseFloat(val['coupon'])
				 			}
				 		});
				 		if(total_cart==0){
					 			if($cookieStore.get("coupondmcl")!=undefined){
					 				$cookieStore.put("coupondmcl",null);
					 			}

					 			$state.go('home');
					 	}
				 		$scope.total_cart=total_cart;
				 	});
		});

																																									
	}

																																																				


})
.controller("FirstCtr",function($scope,$state,$q,$cookieStore,$ionicScrollDelegate,$http,PUBLIC_VALUE,$ionicLoading,$ionicPopup){
	$ionicScrollDelegate.scrollTop();
	

 	
	$scope.second_pay=function(){
		$state.go("secondcart");
	}


	//Coupon
	$scope.check_coupon=false;
	if( $cookieStore.get("coupondmcl")){
		$scope.check_coupon=true;
		$scope.voucher = $cookieStore.get("coupondmcl");

		
	}
	$scope.usingCoupon=function(voucher){
		$ionicLoading.show({template:"Đang sử dụng....."} );
			if(voucher != undefined){

				$http.post(PUBLIC_VALUE.URL+"check_coupon",JSON.stringify({coupon:voucher}) )
					 .success(function(result){
					 	$ionicLoading.hide();
					 	if(result=='0'){
					 		$ionicLoading.hide();
							$ionicPopup.show({
								template:"Mã Coupon không chính xác hoặc hết hạn!",
								title:"Thông báo",
													buttons:[
														{text:"OK",
															type:"button-positive"
															}
													]
							});
							$scope.check_coupon=false;
					 	}else{
					 		$scope.check_coupon=true;
					 		$cookieStore.put("coupondmcl",voucher);
					 		angular.forEach($scope.list_product,function(val,key){
					 			var get_coupon=$http.get(PUBLIC_VALUE.URL+"get_value_coupon/"+val['myid']+"/"+val['cid_supplier'] )
					 								.success(function(r){

					 								});
					 		});
					 		$state.reload();
					 	}
					 })
					 .error(function(){
					 	$ionicLoading.hide();
						$ionicPopup.show({
							template:"Mã Coupon không chính xác hoặc hết hạn!",
							title:"Thông báo",
												buttons:[
													{text:"OK",
														type:"button-positive"
														}
												]
						})
					 })
					 ;
			

		}else{
			$ionicLoading.hide();
			$ionicPopup.show({
				template:"Vui lòng nhập mã Coupon!",
				title:"Thông báo",
									buttons:[
										{text:"OK",
											type:"button-positive"
											}
									]
			})
		}
	}	

	//End coupon

	$scope.continue=function(){
		$state.go("home");
	}
})
.controller("SecondCtr",function($scope,$filter,$state,$q,$cookieStore,$ionicScrollDelegate,$http,PUBLIC_VALUE,$ionicLoading,$ionicPopup){
	$ionicScrollDelegate.scrollTop();
		
	$scope.orderform={};


	var dt = new Date();

	//$scope.orderform.getdate=$filter("date")(Date.now(), 'dd/MM/yyyy');
	$scope.defaultdate=$filter("date")(Date.now(), 'dd/MM/yyyy');	

	if($cookieStore.get("dmclaccount")){
					var user=$cookieStore.get("dmclaccount");
				
					$scope.orderform.name=user.fullname;
					$scope.orderform.email=user.email;
					if(user.phone!=undefined){
						$scope.orderform.phone=parseFloat(user.phone);	
					}
					
					$scope.orderform.address=user.address;
					$scope.orderform.city=user.city;
					$scope.orderform.state=user.distict;
		}
	


	$scope.orderform.pay=1;
	$scope.minDate=Date.now();
	$scope.three_pay=function(){
		$ionicScrollDelegate.scrollTop();
		var error=[];
		$scope.error=[];

		if($scope.orderform.name=="" || $scope.orderform.name==null){
			error['name']="Vui lòng nhập Họ và Tên";
		}
		if($scope.orderform.email=="" || $scope.orderform.email==null){
			error['email']="Vui lòng nhập Email";
		}
		if($scope.orderform.phone=="" || $scope.orderform.phone==null){
			error['phone']="Vui lòng nhập số điện thoại";
		}
		if($scope.orderform.address=="" || $scope.orderform.address==null){
			error['address']="Vui lòng nhập địa chỉ";
		}
		if($scope.orderform.city=="" || $scope.orderform.city==null){
			error['city']="Vui lòng nhập Tỉnh/Thành phố";
		}
		if($scope.orderform.getdate=="" || $scope.orderform.getdate==null || $scope.orderform.getdate==undefined){
			error['getdate']="Vui lòng nhập Thời gian muốn lấy hàng";
		}
		if($scope.orderform.state=="" || $scope.orderform.state==null ){
			error['state']="Vui lòng nhập Quận/Huyện";
		}
		
	
		$scope.error=error;
		if(Object.keys(error).length==0){
			$http.post(PUBLIC_VALUE.URL+"order_form",JSON.stringify($scope.orderform)).success(function(result){
				$state.go("threecart")
			});
		}
		
	}

	//address 
	$scope.change_cities=function(){
		$ionicLoading.show();
		$http.get(PUBLIC_VALUE.URL+"list_state/"+$scope.orderform.city).success(function(r){
			$scope.list_state=r;
			
			if(r[0].id != undefined){
				$scope.orderform.state=r[r.length-1].id;
			}
			
			$ionicLoading.hide();
		});
	}

	$http.get(PUBLIC_VALUE.URL+"list_location").success(function(r){
		$scope.list_location=r;
	});


	

	$scope.continue=function(){
		$state.go("home");
	}
})
.controller("ThreeCtr",function($scope,$state,$ionicScrollDelegate,$http,PUBLIC_VALUE){
	$ionicScrollDelegate.scrollTop();

	$http.get(PUBLIC_VALUE.URL+"order_get_form").success(function(result){
		if(result=="" || result==undefined || result== null){
			$state.go("home");
		}
		$scope.list_form=result;

		$http.get(PUBLIC_VALUE.URL+"get_name_location/"+result.city).success(function(r){
			$scope.city=r
		});
		$http.get(PUBLIC_VALUE.URL+"get_name_location/"+result.state).success(function(r){
			$scope.state=r;
		});

	}).error(function(){
		$state.go("home");
	});

	$scope.four_pay=function(){
		$state.go("fourcart");
	}
})
.controller("FourCtr",function($scope,$state,$q,$ionicScrollDelegate,$http,PUBLIC_VALUE){
	$ionicScrollDelegate.scrollTop();

	var a=$http.get(PUBLIC_VALUE.URL+"order_get_form").success(function(result){
		if(result=="" || result==undefined || result== null){
			$state.go("home");
		}
		$scope.list_form=result;

		$http.get(PUBLIC_VALUE.URL+"get_name_location/"+result.city).success(function(r){
			$scope.city=r
		});
		$http.get(PUBLIC_VALUE.URL+"get_name_location/"+result.state).success(function(r){
			$scope.state=r;
		});

	}).error(function(){
		$state.go("home");
	});
	$q.all([a]).then(function(result){
		$http.get(PUBLIC_VALUE.URL+"order_four").success(function(r){
			if(r=='1'){
				$state.go("home")
			}else{
				$scope.number_order=r;
			}
		})
	})


})
.controller("ButtonOrder",function($scope,$cookieStore,$http,$state,PUBLIC_VALUE){
	$scope.buyproduct=function(id,supplier,limit,color){


		$http.post(PUBLIC_VALUE.URL+"order_save",{"id":id,"supplier":supplier,"limit":limit,"color":color} ).success(function(){
			$state.go("firstcart");
		})
	/*	var get_old_order=$cookieStore.get("orderdmcl") || [];
		
		if(!check_key(get_old_order,id) ){
			get_old_order.push( {"id":id,"supplier":supplier,"limit":limit,"color":color} );
			$cookieStore.put("orderdmcl",get_old_order);
		}else{
			get_old_order=put_limit(get_old_order,id,limit);
			$cookieStore.put("orderdmcl",get_old_order);
		}*/

		


	}
})

.directive('onlyDigits', function () {
    return {
      require: 'ngModel',
      restrict: 'A',
      link: function (scope, element, attr, ctrl) {
     		
   		}
    };
});



function check_key(obj,k){
	for (var i = obj.length - 1; i >= 0; i--) {
		if(obj[i].id==k){
			return true;
		}else{
			return false;
		}
	};
}
function put_limit(obj,k,limit){
	if(typeof limit === "number"){
		for (var i = obj.length - 1; i >= 0; i--) {
			if(obj[i].id==k){
				obj[i].limit=limit;

				return obj;
			}
		};	
	}
	return obj;
	
}
function delete_order(obj){

	if(typeof obj==='object'){
		var tam=[];
		for (var i = obj.length - 1; i >= 0; i--) {
			if(! (obj[i].id==undefined || obj[i].supplier==undefined || obj[i].limit==undefined) ){
				tam.push(obj[i]);
			}
		};
		return tam;
	}
	return obj;
}