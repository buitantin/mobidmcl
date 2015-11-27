angular.module("starter.news",[])
.controller("NewsCtr",function($scope,$http,PUBLIC_VALUE,ValidateData,$ionicScrollDelegate){
	$ionicScrollDelegate.scrollTop();


	$scope.LINK_IMG=PUBLIC_VALUE.IMG;
	$scope.ValidateData=ValidateData;
	$scope.list_cate=[];
	$scope.list_news=[];

	$http.get(PUBLIC_VALUE.URL+"getlistcatenews",{cache:true}).success(function(result){
		$scope.list_cate=result;
	
	});

})
.controller("NewsCateCtr",function($scope,$http,$stateParams,PUBLIC_VALUE,ValidateData,$ionicScrollDelegate,$ionicLoading){
	
	$ionicScrollDelegate.scrollTop();

	$scope.LINK_IMG=PUBLIC_VALUE.IMG;
	$scope.ValidateData=ValidateData;


	$scope.list_news=[];
	$scope.cate=[];


	$scope.show_paginate_next=true;


	var id= $stateParams.id;
    $scope.mypage=1;
					$http.get(PUBLIC_VALUE.URL+"getlistnews/"+id).success(function(r){
								$scope.list_news=r;

								if(r.total < 10 ){
									$scope.show_paginate_next=false;
								}
								
						});
					$http.get(PUBLIC_VALUE.URL+"detailnewscate/"+id).success(function(r){
								$scope.cate=r;

						});

				$scope.loadNextPage=function(){
							$ionicLoading.show();
							$ionicScrollDelegate.scrollTop();
							$scope.show_paginate_prev=true;
							$scope.mypage=parseInt($scope.mypage)+1;
								$http.get(PUBLIC_VALUE.URL+"getlistnews/"+id+"?page="+$scope.mypage).success(function(r){
										$scope.list_news=r;
										if(r.total==r.to){
											$scope.show_paginate_next=false;
										}
										$ionicLoading.hide();
								})
								.error(function(){
										$ionicLoading.hide();
								});
								
								
						}
				$scope.loadPrePage=function(){
							
							$ionicScrollDelegate.scrollTop();
							$scope.show_paginate_next=true;
							if(parseInt($scope.mypage)>1){
								$ionicLoading.show();
								$scope.mypage=parseInt($scope.mypage)-1;
								$http.get(PUBLIC_VALUE.URL+"getlistnews/"+id+"?page="+$scope.mypage).success(function(r){
										$scope.list_news=r;
										$ionicLoading.hide();
								}).error(function(){$ionicLoading.hide();});
								
								
						
								
							}
							if(parseInt($scope.mypage)==1){
								$scope.show_paginate_prev=false;
							}	
				}			
							



})
.controller("NewsDetailCtr",function($scope,$http,$stateParams,PUBLIC_VALUE,ValidateData,$ionicScrollDelegate){
	
	$ionicScrollDelegate.scrollTop();

	$scope.LINK_IMG=PUBLIC_VALUE.IMG;
	$scope.ValidateData=ValidateData;

	var id=$stateParams.id;

	 $scope.news=[];
	 $scope.list_orther=[];


	 $http.get(PUBLIC_VALUE.URL+"getdetailnews/"+id,{cache:true}).success(function(result){
	 	$scope.news=result;
	 		$http.get(PUBLIC_VALUE.URL+"getlistnewslimit/"+result['cid_cate']+"/3",{cache:true}).success(function(result){
	 				$scope.list_orther=result;
	 		});

	 });

})