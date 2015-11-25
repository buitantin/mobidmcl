angular.module("starter.users",[])
.controller("UserLoginCtr",function($scope,$q,$http,$stateParams,$ionicPopup,$ionicSlideBoxDelegate,PUBLIC_VALUE,ValidateData,$cookieStore,$ionicScrollDelegate){
	$scope.LINK_IMG=PUBLIC_VALUE.IMG;
	$scope.ValidateData=ValidateData;



})
.controller("UserSignupCtr",function($scope,$q,$http,$stateParams,$ionicPopup,$ionicSlideBoxDelegate,PUBLIC_VALUE,ValidateData,$cookieStore,$ionicScrollDelegate,$ionicLoading){
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
				console.log(r)
					if(r.data=='1'){
										$ionicPopup.show({
			  								title:"Thông báo",
			  								template:"Đăng ký thành công!",
			  								buttons:[{text:"Đóng"}]
			  							});
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
			$ionicLoading.hide();
		});
	}

	$http.get(PUBLIC_VALUE.URL+"list_location").success(function(r){
		$scope.list_location=r;
	});

})