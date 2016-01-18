angular.module("starter.users",[])
.controller("UserLoginCtr",function($scope,$q,$http,$state,PUBLIC_VALUE,$ionicLoading,$ionicPopup,$cookieStore,$ionicScrollDelegate){
	$ionicScrollDelegate.scrollTop();
	$scope.LINK_IMG=PUBLIC_VALUE.IMG;
	$scope.login={};
	$scope.myerror=[];
	if($cookieStore.get("dmclaccount")){
		var uc=$cookieStore.get("dmclaccount");
		$scope.my_check_user=uc['fullname'];
		$state.go("profile");
	}
	$scope.clicklogin=function(){
		$scope.myerror=[];
		$ionicLoading.show();
		$http.post(PUBLIC_VALUE.URL+"login",$scope.login).success(function(r){
				if(!r['error']){
							$cookieStore.put("dmclaccount",r);
										$ionicPopup.show({
			  								title:"Thông báo",
			  								template:"Đăng nhập thành công!",
			  								buttons:[{text:"Đóng"}]
			  							});
			  							$state.reload();
							$scope.my_check_user=r['fullname'];
							$state.go("profile");

						
			    	
				
				}else{
					$scope.myerror=r;
				}
				$ionicLoading.hide();
		})
		.error(function(){
				$ionicLoading.hide();
		})
	}

})
.controller("UserSignupCtr",function($scope,$q,$http,$stateParams,$ionicPopup,$ionicSlideBoxDelegate,PUBLIC_VALUE,ValidateData,$cookieStore,$ionicScrollDelegate,$ionicLoading){
	$ionicScrollDelegate.scrollTop();
	$scope.LINK_IMG=PUBLIC_VALUE.IMG;
	$scope.ValidateData=ValidateData;
	$scope.list_state=[];
	$scope.signup={};
	
	$scope.myerror=[];
	$scope.signupsubmit=function(){
		var myerror=[];
		if($scope.signup.username=="" || $scope.signup.username == null){
			myerror['username']="Vui lòng nhập tên đăng nhập!";
		}
		if($scope.signup.password=="" || $scope.signup.password == null){
			myerror['password']="Vui lòng nhập Mật khẩu!";
		}
		if($scope.signup.reset=="" || $scope.signup.reset == null){
			myerror['reset']="Vui lòng nhập Mật khẩu!";
		}
		if($scope.signup.password != null && $scope.signup.reset != null){
			if($scope.signup.password != $scope.signup.reset){
				myerror['reset']="Vui lòng nhập lại mật khẩu chính xác!";
			}
		}
		if($scope.signup.email=="" || $scope.signup.email == null){
			myerror['email']="Vui lòng nhập E-mail!";
		}
		if($scope.signup.name=="" || $scope.signup.name == null){
			myerror['name']="Vui lòng nhập họ và tên!";
		}
		if($scope.signup.phone=="" || $scope.signup.phone == null){
			myerror['phone']="Vui lòng nhập số điện thoại!";
		}
		if($scope.signup.city=="" || $scope.signup.city == null){
			myerror['city']="Vui lòng nhập Tỉnh/Thành phố!";
		}
		if($scope.signup.state=="" || $scope.signup.state == null){
			myerror['state']="Vui lòng nhập Quận/Huyện!";
		}
		if($scope.signup.agree ==null){
			myerror['agree']="Vui lòng chọn";
		}
		
	

		if(Object.keys(myerror)==0){

			$http.post(PUBLIC_VALUE.URL+"save_users",$scope.signup).success(function(r){
					if(r=='1'){
										$ionicPopup.show({
			  								title:"Thông báo",
			  								template:"Đăng ký thành công!",
			  								buttons:[{text:"Đóng"}]
			  							});
					}else{
						$scope.myerror=r;
					}
									
			})
		}else{
			$scope.myerror=myerror;
		}

	}

	$scope.change_cities=function(){
		$ionicLoading.show();
		$http.get(PUBLIC_VALUE.URL+"list_state/"+$scope.signup.city).success(function(r){
			$scope.list_state=r;
			if(r[0].id != undefined){
				$scope.signup.state=r[0].id;	
			}
			
			$ionicLoading.hide();
		});
	}

	$http.get(PUBLIC_VALUE.URL+"list_location").success(function(r){
		$scope.list_location=r;
	});

})
.controller("FacebookCtr",function($scope,PUBLIC_VALUE,$ionicSideMenuDelegate,$state,$http,ValidateData,$cookieStore,ngFB,$ionicPopup){

					//event when open menu left
				 $scope.$watch(function() { 
          			return $ionicSideMenuDelegate.getOpenRatio();
		        }, 
		        function(ratio) {
		            $scope.data=ratio;
		            if( ratio == 1){
				            	if($cookieStore.get("dmclaccount")){
									var user=$cookieStore.get("dmclaccount");
									$scope.my_check_user=user['fullname'];
								}
							$http.get(PUBLIC_VALUE.URL + "totalcart").success(function(result){
						 			$scope.total_cart=result;
						 	});
		            }

		        });

				


	$scope.my_check_user=null;
	$scope.LINK_IMG=PUBLIC_VALUE.IMG;
	$scope.ValidateData=ValidateData;
	if($cookieStore.get("dmclaccount")){
		var user=$cookieStore.get("dmclaccount");
		$scope.my_check_user=user['fullname'];
	}
					 

	$scope.facebooklogin=function(){
		 ngFB.login({scope: 'email,publish_actions'}).then(
	        function (response) {
	            if (response.status === 'connected') {
		               	   ngFB.api({
						        path: '/me',
						        params: {fields: 'id,name,email'}
						    }).then(
					        function (user) {
					         	
					         	$http.post(PUBLIC_VALUE.URL+"/save_facebook_user",user).success(function(result){
					         			if(!result['error']){
					         				$cookieStore.put("dmclaccount",result);
					         				//console.log(result)
					         				$ionicPopup.show({
							                	title:"Facebook",
							                	template:"Đăng nhập Facebook thành công.",
							                	buttons:[
							                		{text:"Đóng"}
							                	]

							                });
							                $scope.my_check_user=result['fullname'];
							                $state.go("profile");

					         			}
					         	});

					        },
					        function (error) {
					        		$ionicPopup.show({
					                	title:"Facebook",
					                	template:"Đăng nhập facebook không thành công. Vui lòng thử lại:"+error.error_description,
					                	buttons:[
					                		{text:"Đóng"}
					                	]

					                });
					        });
	              
	            } else {
	                $ionicPopup.show({
	                	title:"Facebook",
	                	template:"Đăng nhập facebook không thành công. Vui lòng thử lại",
	                	buttons:[
	                		{text:"Đóng"}
	                	]

	                });
	            }
	        });


			
	}

	$scope.mylogout=function(){
		$scope.my_check_user=null;
		$state.go("logout");
	}

})
.controller("UserProfileCtr",function($scope,$ionicScrollDelegate,PUBLIC_VALUE,$state,$http,ValidateData,$cookieStore,ngFB,$ionicPopup,$ionicLoading){
	$ionicScrollDelegate.scrollTop();
	$scope.LINK_IMG=PUBLIC_VALUE.IMG;
	$scope.ValidateData=ValidateData;

	if($cookieStore.get("dmclaccount")){
		$scope.list_state=[];
		$scope.myprofile={};
		var user=$cookieStore.get("dmclaccount");
		$scope.myprofile=user;

		


		$scope.change_cities=function(){
			$ionicLoading.show();
			$http.get(PUBLIC_VALUE.URL+"list_state/"+$scope.myprofile.city).success(function(r){
				$scope.list_state=r;

				$ionicLoading.hide();
			});
		}
		$http.get(PUBLIC_VALUE.URL+"list_location").success(function(r){
			$scope.list_location=r;
			$scope.myprofile.city=user['city'];
			$scope.mycity=user['city'];
		});

		if(user['city']!=null){
			

			$http.get(PUBLIC_VALUE.URL+"list_state/"+user['city']).success(function(r){
				$scope.list_state=r;
				$scope.myprofile.distict=user['distict'];
			});
		}

		$scope.profilesubmit=function(){
			$ionicLoading.show();
			$http.post(PUBLIC_VALUE.URL+"save_profile",$scope.myprofile).success(function(r){
				
					$ionicLoading.hide();
				if(r=='1'){

					user=$cookieStore.get("dmclaccount");

					user['phone']	=$scope.myprofile.phone;
					user['fullname']	=$scope.myprofile.fullname;
					user['birthday']	=$scope.myprofile.birthday;
					user['city']	=$scope.myprofile.city;
					user['distict']	=$scope.myprofile.distict;

					$cookieStore.put("dmclaccount",user);

					 $ionicPopup.show({
	                	title:"Thông tin cá nhân",
	                	template:"Cập nhật thành công.",
	                	buttons:[
	                		{text:"Đóng"}
	                	]

	                 });
				}else{
					if(r=='2'){

					}else{
						$scope.myerror=r;
					}
				}

					

			});
		}	


	}else{
		$state.go("login");
	}
})
.controller("UserLogoutCtr",function($scope,PUBLIC_VALUE,$state,$http,ValidateData,$cookieStore,ngFB,$ionicPopup,$ionicLoading){
	$scope.LINK_IMG=PUBLIC_VALUE.IMG;
	$scope.ValidateData=ValidateData;
	$http.get(PUBLIC_VALUE.URL+"thoat").success(function(r){
		$cookieStore.remove("dmclaccount");
		$state.go("home");
	
	});
	

})
