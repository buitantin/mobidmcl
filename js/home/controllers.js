angular.module('starter.controllers', [])


.controller("ONTOP",function($scope,$ionicScrollDelegate) {
	$ionicScrollDelegate.scrollTop();
	$scope.ontoppage=function(){
			$ionicScrollDelegate.scrollTop();
		}
})
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

.controller('HeaderController',function($scope,$ionicSideMenuDelegate,$http, $state, $ionicActionSheet, $timeout){
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
			      	if(index==0){
			      		$state.go("signup");
			      	}
			      	if(index==1){
			      		$state.go("login");
			      	}
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
.controller("HOMECtrl",function($scope,$http,PUBLIC_VALUE,ValidateData,$ionicScrollDelegate){
	$ionicScrollDelegate.scrollTop();


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
.controller("CategoriesCtr",function($scope,$ionicSlideBoxDelegate,$state,$http,$stateParams,PUBLIC_VALUE,ValidateData,Cate,$ionicScrollDelegate){
	$ionicScrollDelegate.scrollTop();
	

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

.controller("ChildCtr",function($scope,$http,$stateParams,$ionicPopup,PUBLIC_VALUE,ValidateData,$cookieStore,$ionicScrollDelegate){
	$ionicScrollDelegate.scrollTop();	


	$scope.LINK_IMG=PUBLIC_VALUE.IMG;
	$scope.ValidateData=ValidateData;


	if($stateParams.child != undefined){
			$scope.my_cate=[];
			$scope.my_parent=[];
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
						$http.get(PUBLIC_VALUE.URL+"getcate/"+$scope.my_child['cid_parent'])
						.success(function(parent){
								$scope.my_parent=parent;
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
			var compare=$cookieStore.get("compare") || [];


							if(compare.length > 0){
								for (var i = compare.length - 1; i >= 0; i--) {
									$scope.bool [ compare[i] ] =true;
								};
							}



				$scope.changeCompare=function(bool,myid)	{
					
					
						if(bool){

							if(compare.length < 2){
								compare.push(myid);
								$cookieStore.put("compare",compare);

								return true;
							}else{
								$ionicPopup.show({
									template:"Chỉ tối đa 2 sản phẩm ",
									title:"Thông báo",
									buttons:[
										{text:"OK",
											type:"button-positive"
											}
									]
								})
							}
							
							$scope.bool[myid]=false;
						}else{
							//remove value on compare
							if(compare.length > 0 ){
								var tm=[];
								for (var i = compare.length - 1; i >= 0; i--) {
									if(compare[i]!=myid){
										tm.push(compare[i]);
									}
								};
								compare=tm;
							}

						}
						
					}
					


							

			
			
	}else{
		window.location.href="/?error=";
	}


})
.controller("CompareCtr",function($scope,$http,$stateParams,$ionicPopup,PUBLIC_VALUE,ValidateData,$cookieStore){
	
	$scope.LINK_IMG=PUBLIC_VALUE.IMG;
	
	$scope.ValidateData=ValidateData;


	var compare=$cookieStore.get("compare") || [];

	$scope.compare=compare;

	if($stateParams.compare != undefined && compare.length > 0 ){
			$scope.my_cate=[];
			$scope.my_parent=[];
			$scope.listproduct=[];
			$scope.listproduct_element=[];
			$scope.my_child=[];
			$scope.list_element=[];


			$scope.currentPage=1;
			$scope.pageSize=10;
			$scope.show_paginate=true;


			
			$http.get(PUBLIC_VALUE.URL+"getcate/"+$stateParams.compare)
			.success(function(re){
					$scope.my_child=re;


						$http.get(PUBLIC_VALUE.URL+"getlistcate/"+$scope.my_child['cid_parent'])
						.success(function(r){
								$scope.my_cate=r;
						});
						$http.get(PUBLIC_VALUE.URL+"getcate/"+$scope.my_child['cid_parent'])
						.success(function(parent){
								$scope.my_parent=parent;
						});

						$http.get(PUBLIC_VALUE.URL+"getelement/"+$scope.my_child['id']+"/"+compare[0])
						.success(function(list_element){
								$scope.list_element=list_element;




								for (var i = compare.length - 1; i >= 0; i--) {

									//$scope.listproduct_element[i]=[];
										for (var j = list_element.length - 1; j >= 0; j--) {
											$http.get(PUBLIC_VALUE.URL+"get_element_product/"+compare[i]+"/"+list_element[j]["id"])
											.success(function(e){
													$scope.listproduct_element.push(e)
													
											});	

										};
									
									

								};



									

									

						});	

						for (var i = compare.length - 1; i >= 0; i--) {
							
							$http.get(PUBLIC_VALUE.URL+"getdetailproduct/"+compare[i])
							.success(function(product){
									$scope.listproduct.push(product);

									
							});	


							
						};
						
						
						



						


					
			});

		


							

			
			
	}else{
			$ionicPopup.show({
									template:"Vui lòng chọn sản phẩm để so sánh.",
									title:"Thông báo",
									buttons:[
										{text:"OK",
											type:"button-positive"
											}
									]
								})
		window.history.back();
	}



})
 
.controller("FilterCtr",function($scope,$http,$stateParams,$ionicPopup,$ionicSlideBoxDelegate,PUBLIC_VALUE,ValidateData,$cookieStore,$ionicScrollDelegate,$ionicLoading){
	
	$scope.LINK_IMG=PUBLIC_VALUE.IMG;
	$scope.ValidateData=ValidateData;


	if($stateParams.filter != undefined){
			$scope.my_cate=[];
			$scope.my_parent=[];
			$scope.listproduct=[];
			$scope.my_child=[];

			$scope.my_series=[];
			$scope.my_price=[];
			$scope.list_template=[];
			
			$scope.valueseries=[];
			$scope.valuetemplate=[];
			$scope.valueprice=[];

			$scope.hide_tag=[];

			$scope.currentPage=1;
			$scope.pageSize=6;
			$scope.show_paginate=true;

			$http.get(PUBLIC_VALUE.URL+"getcate/"+$stateParams.filter)
			.success(function(re){
					$scope.my_child=re;

								$http.get(PUBLIC_VALUE.URL+"filter_cate/"+$scope.my_child['cid_parent'])
								.success(function(r){
										$scope.my_cate=r;
								});
								$http.get(PUBLIC_VALUE.URL+"filter_series/"+$scope.my_child['id'])
								.success(function(s){
										$scope.my_series=s;
								});	
								$http.get(PUBLIC_VALUE.URL+"filter_price/"+$scope.my_child['id'])
								.success(function(s){
										$scope.my_price=s;
								});	

								$http.get(PUBLIC_VALUE.URL+"filter_template/"+$scope.my_child['id'])
								.success(function(s){
										$scope.list_template=s;
								});	


							$http.get(PUBLIC_VALUE.URL+"getcate/"+$scope.my_child['cid_parent'])
								.success(function(parent){
										$scope.my_parent=parent;
								});



								$http.get(PUBLIC_VALUE.URL+"getproduct/"+$scope.my_child['id']+"/12")
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
	

			$scope.click_filter=function(){
						$ionicLoading.show({
							template:"Loading...."
						})
						var t='';
						if($scope.valuetemplate.length > 0){

							for (var i = $scope.valuetemplate.length - 1; i >= 0; i--) {
								if($scope.valuetemplate[i] == null){
									$scope.valuetemplate.splice(i,1);
								}
							};

							t=$scope.valuetemplate.join(" OR ");
						}
						
						
						var d=JSON.stringify({
							series:$scope.valueseries,
							template:$scope.valuetemplate,
							price:$scope.valueprice
						});

						$http.post(PUBLIC_VALUE.URL+"filter_product/"+$scope.my_child['id'],d)
								.success(function(r){
										$scope.listproduct=r;
											$ionicSlideBoxDelegate.next();
											$ionicLoading.hide();
											if($scope.pageSize > $scope.listproduct.length){

													$scope.show_paginate=false;
												}

										$scope.loadNextPage=function(){
								
											$scope.currentPage++;
											$scope.pageSize=$scope.currentPage*10;

												if($scope.pageSize > $scope.listproduct.length){

													$scope.show_paginate=false;
												}

											}	
									});
				
				$ionicScrollDelegate.scrollTop();
			}		
			$scope.changecheckbox=function(opt,idx,bool){
					//for series
					if(opt==1){
						if(bool){
							$scope.valueseries.push(idx);
						}else{
							$scope.valueseries.splice( $scope.valueseries.indexOf(idx),1);
						}

					}
											

			}
			$scope.changecheckboxtemplate=function(idx,k,bool){
						if(bool){
							$scope.valuetemplate[idx+k]="(e.cid_element="+idx+" AND e.val LIKE '"+bool+"' )";
						}else{
							//$scope.valuetemplate.splice($scope.valuetemplate[idx+k],1);
							delete $scope.valuetemplate[idx+k];
						}
						

						
			}
			$scope.changecheckboxprice=function(value,k){
				if(value){
					$scope.valueprice[k]=value;
				}else{
				
					delete $scope.valueprice[k];
				}

				
			}


			$scope.hidden=function(curre,ind){
				 $scope.hide_tag[ind] = !curre;
			}
			
		 	$scope.slidePrevious = function() {

	      	  $ionicSlideBoxDelegate.previous();
	    	}
			
			
	}else{
		window.location.href="/?error=";
	}


})












 .controller('RatingController', RatingController)
  .directive('starRating', starRating)
  .directive("toPrice",toPrice)
  .directive("toDiscount",toDiscount)

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
  						if(number != null && number != undefined)
  						element.text(number.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") + " Đ" );
			  	 
  					});
  					
  					
  		}
  		
  	};
  }
  function toDiscount(){
  	return {
  		link:function(scope,element,attributes){
  			scope.$watch(attributes.toDiscount,function(s){
  				if(s != undefined){
  					element.text( Math.round(s).toString()+ "%")
  				}
  			})
  		}
  	}
  }

 



  
