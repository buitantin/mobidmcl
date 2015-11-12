angular.module('starter.controllers', [])


.controller('FooterCtrl', function($scope,$http,Cate,PUBLIC_VALUE,ValidateData) {

		$scope.show_dt=false;
		$scope.ValidateData=ValidateData;

		$scope.tab_footer=function(index){
			$scope.t_footer=index;

		
		}

		var childCate=[];
		Cate.parent(function(data){
			$scope.cate_footer=data;

			data.forEach(function(d){
					 $http.get(PUBLIC_VALUE.URL+"categories/"+d['id'].toString()+"/1",{cache:true}).success(function(result_cate){
					 	childCate[d['id']]=result_cate
					 });
							
						
			});
		})
		$scope.childCate=childCate;
		
		
		



		$scope.viewclass=function(r){
			var cla={
				"dientu":"dt",
				"dienlanh":"dl",
				"giadung":"gd",
				"didong-tablet":"dd",
				"vitinh":"vt",
				"vienthong":"vth",
				"noithat":"nt",
				"me&be":"mevabe",
				"dienco":"dc",
				"trangtri":"trangtri",
				"thoitrang&phukien":"phukien",
				"suckhoe&sacdep":"sacdep",
			};
			r=ValidateData.toIcon(r);
			for(c in cla){
				if(r== c){
					return cla[c];
				}
			}
			return '';
		}
		

})

.controller('HeaderController',function($scope,$ionicSideMenuDelegate,$http, $ionicActionSheet, $timeout){
			  //$scope.Mr_Data=Mr_Data;
			  $scope.toggleLeft=function(){
				  $ionicSideMenuDelegate.toggleLeft();
			  };
	  
			  $scope.show = function() {
			    var hideSheet = $ionicActionSheet.show({
			      buttons: [
			        { text: '<b>Đăng ký</b>' },
			        { text: '<b>Đăng nhập</b>' }
			      ],
			   //   destructiveText: '<img src="http://dienmaycholon.vn/public/default/img/login-via-facebook.png" />',
			      titleText: 'Đăng ký - Đăng nhập',
			      cancelText: 'Đóng',
			      cancel: function() {
			           // add cancel code..
			         },
			      buttonClicked: function(index) {
			        return true;
			      }
			    });
			  };

		})

.controller("SliderShowCtrl",function($scope,$http,PUBLIC_VALUE,$ionicSlideBoxDelegate){
							$http.get(PUBLIC_VALUE.URL+"slideshow",{cache:true})
							   .success(function(r){
							   		$scope.slideshow=r;
							   		$ionicSlideBoxDelegate.update();

		
							   });
})
.controller("HOMECtrl",function($scope,$http,PUBLIC_VALUE,ValidateData){
	
	$scope.LINK_IMG=PUBLIC_VALUE.IMG;
	$scope.ValidateData=ValidateData;


	$scope.listcate=[];
	$scope.listproduct=[];
	
	var get_data=$http.get(PUBLIC_VALUE.URL+"categories_home",{cache:true})
		.success(function(data){
			$scope.listcate=data;
			data.forEach(function(d){
			$http.get(PUBLIC_VALUE.URL+"list_product_cate_parent/"+d['id']+"/6",{cache:true})
				.success(function(r){
						$scope.listproduct[d['id']]=r;
				});
			});
			})
		 .error(function(){
	    });

		$http.get(PUBLIC_VALUE.URL+"promotionpress/12",{cache:true})		 
		.success(function(r){
			$scope.product_press=r;
		});
		$http.get(PUBLIC_VALUE.URL+"producthot/9",{cache:true})		 
		.success(function(r){
			$scope.list_hot=r;
		});
		

		$http.get(PUBLIC_VALUE.URL+"productnew/9",{cache:true})		 
		.success(function(r){
			$scope.list_new=r;
		});

		$scope.toAlias=function(alias){
			return ValidateData.toAlias(alias);
		}
})
.controller("CategoriesCtr",function($scope,$ionicSlideBoxDelegate,$state,$http,$stateParams,PUBLIC_VALUE,ValidateData,Cate){
	
	$scope.LINK_IMG=PUBLIC_VALUE.IMG;
	$scope.ValidateData=ValidateData;
	if($stateParams.parent != undefined){

		var id_cate=ValidateData.listcate($stateParams.parent);

			$http.get(PUBLIC_VALUE.URL+"/getcate/"+id_cate)
			.success(function(re){
					$scope.my_parent=re;
					

			});
			$scope.my_cate=[];
			$scope.listproduct=[];
			$http.get(PUBLIC_VALUE.URL+"/getlistcate/"+id_cate)
						.success(function(r){
								$scope.my_cate=r;
								
								

								var abc=[];
								var int_x=0;
								for(var i=0 ; i < Math.ceil(r.length/3) ;i++){
									abc.push(int_x);
									int_x=int_x+3;
								}
								$scope.total_cate=abc;
							
								r.forEach(function(d){
								$http.get(PUBLIC_VALUE.URL+"list_product_cate/"+d['id']+"/3",{cache:true})
									.success(function(r){
											$scope.listproduct[d['id']]=r;
									});
								});
								
							   $ionicSlideBoxDelegate.$getByHandle('image-viewer-cate').update();

						});
	}
	//$state.go($state.child, {}, {reload: true});

})

.controller("ChildCtr",function($scope,$http,$stateParams,PUBLIC_VALUE,ValidateData,Compare,$cookieStore){
	
	$scope.LINK_IMG=PUBLIC_VALUE.IMG;
	$scope.ValidateData=ValidateData;


	if($stateParams.child != undefined){
			$scope.my_cate=[];
			$scope.listproduct=[];
			$scope.my_child=[];

			$scope.currentPage=1;
			$scope.pageSize=10;
			$scope.show_paginate=true;

			$http.get(PUBLIC_VALUE.URL+"getcate/"+$stateParams.child)
			.success(function(re){
					$scope.my_child=re;


						$http.get(PUBLIC_VALUE.URL+"getlistcate/"+$scope.my_child['cid_parent'])
						.success(function(r){
								$scope.my_cate=r;
			

						});
					

						$http.get(PUBLIC_VALUE.URL+"getproduct/"+$stateParams.child+"/150")
						.success(function(r){
								$scope.listproduct=r;
			
								var count_max=$scope.listproduct.length;
								$scope.loadNextPage=function(){
				
									$scope.currentPage++;
									$scope.pageSize=$scope.currentPage*10;

									if($scope.pageSize > count_max){

										$scope.show_paginate=false;
									}

								}
						});


					
			});

			$scope.bool=[];
						if(Compare.first != ""){
								$scope.bool[Compare.first]=true;
								
								
							}
							if(Compare.second != ""){
								$scope.bool[Compare.second]=true;
								
							}
							if(Compare.three != ""){
								$scope.bool[Compare.three]=true;
								
							}



				$scope.changeCompare=function(bool,myid)	{
					
					
						if(bool){

							if(Compare.first == ""){
								Compare.first=myid;
								
								return true;
							}
							if(Compare.second == ""){
								Compare.second=myid;
								return true;
							}
							if(Compare.three == ""){
								Compare.three=myid;
								return true;
							}
							$cookieStore.put("compare",Compare);
							$scope.bool[myid]=false;
						}else{
							//remove value on compare
							

						}
						
					}


							

			
			
	}else{
		window.location.href="/?error=";
	}


})
 
 .controller('RatingController', RatingController)
  .directive('starRating', starRating)
  .directive("toPrice",toPrice)
  .directive("toDis",toDis
  	);


  //Detail all Function.
  function RatingController() {
   // this.rating1 = 5;
    //this.rating2 = 2;
    this.isReadonly = true;
    this.rateFunction = function(rating) {
     // console.log('Rating selected: ' + rating);
    };
  }

  function starRating() {
    return {
      restrict: 'EA',
      template:
        '<ul class="star-rating" ng-class="{readonly: readonly}">' +
        '  <li ng-repeat="star in stars" class="star" ng-class="{filled: star.filled}" ng-click="toggle($index)">' +
        '    <i class="fa fa-star"></i>' + // or &#9733
        '  </li>' +
        '</ul>',
      scope: {
        ratingValue: '=ngModel',
        max: '=?', // optional (default is 5)
        onRatingSelect: '&?',
        readonly: '=?'
      },
      link: function(scope, element, attributes) {
        if (scope.max == undefined) {
          scope.max = 5;
        }
        function updateStars() {
          scope.stars = [];
          for (var i = 0; i < scope.max; i++) {
            scope.stars.push({
              filled: i < scope.ratingValue
            });
          }
        };
        scope.toggle = function(index) {
          if (scope.readonly == undefined || scope.readonly === false){
            scope.ratingValue = index + 1;
            scope.onRatingSelect({
              rating: index + 14
            });
          }
        };
        scope.$watch('ratingValue', function(oldValue, newValue) {
          if (newValue) {
            updateStars();
          }
        });
      }
    };
  }
  function toPrice(){
  	return {
  		link:function(scope,element,attributes){
  					scope.$watch(attributes.toPrice,function(number){
  						
  						element.text(number.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") + " D" );
			  	 
  					});
  					
  					
  		}
  		
  	};
  }
  function toDis(){
  	return {
  		link:function(scope,element,attributes){
  				
  					scope.$watch(attributes.toDis,function(number){
  						
  						element.text( "- %"+Math.round(number) );
			  	 
  					});
  					
  		}
  		
  	};
  }
 



  
