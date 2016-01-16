angular.module('starter.details', [])

.controller("DetailCtr",function($scope,$q,$http,$interval,$state,$stateParams,$ionicPopup,$ionicSlideBoxDelegate,PUBLIC_VALUE,ValidateData,$cookieStore,$ionicScrollDelegate,$ionicLoading){
	

	$ionicScrollDelegate.scrollTop();

	$scope.LINK_IMG=PUBLIC_VALUE.IMG;
	$scope.ValidateData=ValidateData;

	$scope.product=[];
	$scope.list_image=[];
	$scope.list_gift=[];
	$scope.payment=[];
	$scope.mylimit=1;
	$scope.old_new=[];
	$scope.buytogether=[];
	$scope.list_element_detail=[];
	$scope.get_total_element=0;
	$scope.list_detail_series=[];
	$scope.listproduct_element=[];
	$scope.list_similar=[];
	$scope.list_orther=[];

	$scope.myrating=5;
	


	$scope.content='';

	if($stateParams.id != undefined){

		var all_value=$stateParams.id.split("_");
		
		$http.get(PUBLIC_VALUE.URL+"detail_product/"+all_value[0]+"/"+all_value[2]).success(function(result){


			
			$scope.product=result;
			$scope.myrating=result['rating'];

			//$cookieStore.remove("ortherproduct");
			var list_orther=$cookieStore.get("ortherproduct") || [];
			var p=[ result['name'],result['myid'] ];
			p=JSON.stringify(p);
			if(list_orther.indexOf(p) === -1 ){
				list_orther.push(p);
			}
			$cookieStore.put("ortherproduct",list_orther);

			for (var i = list_orther.length - 1; i >= 0; i--) {
				$scope.list_orther.push(JSON.parse(list_orther[i]) );
			};
			
			$scope.list_orther_x=get_repeat_slide(list_orther);
			
			//str_replace(array('src="/public','src="http://dienmaycholon.vn/public'), 'src="'.LIVE_ULR.'/img', $product->content)
			$scope.content=replaceAll(result['content'],'src="/public','src="http://m.dienmaycholon//img');
			$scope.content=replaceAll($scope.content,'src="http://dienmaycholon.vn/public','src="http://m.dienmaycholon/img');



			if(result['isprice']=='1'){

				$scope.get_total_element=parseInt(result['discount']);
			}else{
				$scope.get_total_element=parseInt(result['saleprice']);
			}

				$http.get(PUBLIC_VALUE.URL+"detail_payment/"+$scope.product['cid_cate']).success(function(pay){
					if(pay[0]){
						$scope.payment=pay[0];
						$scope.permin= Math.round ( ($scope.product['discount']-Math.round($scope.payment['permin']*$scope.product['discount']/100)) * $scope.payment['rate']);
		
					}
					
				});
				$http.get(PUBLIC_VALUE.URL+"detail_series/"+$scope.product['cid_series']+"/"+$scope.product['cid_cate']).success(function(list_product){
						$scope.list_detail_series=list_product;
						
				});

				$http.get(PUBLIC_VALUE.URL+"getelement/"+$scope.product['cid_cate']+"/"+$scope.product['myid'])
						.success(function(list_element){
								$scope.list_element_similar=list_element;
						});	
				$http.get(PUBLIC_VALUE.URL+"detail_similar/"+all_value[0]+"/"+all_value[2]).success(function(similar){
									$scope.list_similar=similar;
								});


			
			$http.get(PUBLIC_VALUE.URL+"getproduct/"+$scope.product['cid_cate']+"/9").success(function(result){
						$scope.list_tt=result;
				});

			$http.get(PUBLIC_VALUE.URL+"detail_promotion_datetime/"+result['cid_res']+"/"+all_value[2]).success(function(result){
				$scope.datetimecountdown=result;

					 	var future;
	                    future = new Date(result['date_end']);

	                    $interval(function () {
	                        var diff;
	                        diff = Math.floor((future.getTime() - new Date().getTime()) / 1000);

	                         var days, hours, minutes, seconds;
			                    days = Math.floor(diff / 86400);
			                    diff -= days * 86400;
			                    hours = Math.floor(diff/ 3600) % 24;
			                    diff -= hours * 3600;
			                    minutes = Math.floor(diff / 60) % 60;
			                    diff -= minutes * 60;
			                    seconds = diff % 60;
			                    $scope.viewcd=days + '  Ngày   '+hours+":"+minutes+":"+seconds;
	                    }, 1000);


				
			});

			$http.get(PUBLIC_VALUE.URL+"detail_gift/"+result['cid_res']+"/"+all_value[2]).success(function(result){
				$scope.list_gift=result;
			});
		});
		//end detail product

		$http.get("http://dienmaycholon.vn/index/jsonimage/id/"+all_value[0]).success(function(result){
			$scope.list_image=result;
			$ionicSlideBoxDelegate.update();
		})
		.error(function(r){
			$scope.list_image=["product_"+all_value[0]+"_1.png"];
			$ionicSlideBoxDelegate.update();
		})
		;



		


		$http.get(PUBLIC_VALUE.URL+"detail_element/"+all_value[0]).success(function(result){
			$scope.list_element_detail=(result);
		//console.log(JSON.stringify ( result) );


		});



		
		$http.get(PUBLIC_VALUE.URL+"detail_price/"+all_value[0]).success(function(pri){
				$scope.old_new=pri;
				
			});

		$http.get(PUBLIC_VALUE.URL+"detail_question/"+all_value[0]+"/4").success(function(pri){
				$scope.list_question=pri;
				
			});

		$http.get(PUBLIC_VALUE.URL+"detail_review/"+all_value[0]).success(function(result){
			$scope.list_review=result;
			$scope.total_reivew=result.length;

			
			var sum=0;
			for (var i = result.length - 1; i >= 0; i--) {
				sum=sum + parseInt(result[i]['rate']);
			};
			$scope.total_rate=sum;
			$scope.total_avg_rate=Math.round(sum/result.length);

		});
		var success_ajax_buyt=$http.get(PUBLIC_VALUE.URL+"detail_buy/"+all_value[0]).success(function(b){
				$scope.buytogether=b;

				for (var i = b.length - 1; i >= 0; i--) {
						if(b[i]['isprice']=='1'){
							$scope.get_total_element=$scope.get_total_element*1+parseInt(b[i]['discount']);
						}else{
							$scope.get_total_element=$scope.get_total_element*1+parseInt(b[i]['saleprice']);
						}
						

				};
				$scope.get_total_element1=$scope.get_total_element;
				
			});

		


		$scope.check_number=function(){
			if($scope.mylimit  == 0 ){
				
					$scope.mylimit=1;
			}
			if(!angular.isNumber($scope.mylimit)){
				return false;
			}
			
		}
		$scope.change_value=function(v){
			if(v=='-1'){
				if($scope.mylimit > 1){
					$scope.mylimit=$scope.mylimit-1;
				}else{
					$scope.mylimit=1;
				}
				
			}else{
				$scope.mylimit=$scope.mylimit+1;
			}
			
		}

		
		

		//Reivew 

		var fisrt_like=[];
		var first_unlike=[];
		$scope.clicklikes=function(id_review){
			if(fisrt_like.indexOf(id_review) > -1 ){
				return ;
			}


			for (var i = $scope.list_review.length - 1; i >= 0; i--) {
				if($scope.list_review[i]['id']==id_review){
					fisrt_like.push(id_review);
					$scope.list_review[i]['likes']=parseInt($scope.list_review[i]['likes'])+1;

					$http.get(PUBLIC_VALUE.URL+"detail_pluslike/"+id_review+"/1");
					break;
				}
			};
		}
		$scope.clickunlikes=function(id_review){
			if(first_unlike.indexOf(id_review) > -1){
				return;
			}

			for (var i = $scope.list_review.length - 1; i >= 0; i--) {
				if($scope.list_review[i]['id']==id_review){
					first_unlike.push(id_review);
					$scope.list_review[i]['unlikes']=parseInt($scope.list_review[i]['unlikes'])+1;
					$http.get(PUBLIC_VALUE.URL+"detail_pluslike/"+id_review+"/2");
					break;
				}
			};
		}


		$scope.popup_review=function(){

			$scope.datareview={};

			var myPopup=	$ionicPopup.show({
				title:"Đánh giá của khách hàng",
				subTitle:"Vui lòng nhập nội dung",
				template:"Đánh giá: <div  ng-controller='RatingController as rating' ng-init='datareview.rating=5'>  <star-rating ng-model='datareview.rating' on-rating-select='5' ></star-rating> </div> <br />"
						 +"Tiêu đề: <input type='text' ng-model='datareview.title' /> <br />"	
						 +"Nội dung: <input type='text' ng-model='datareview.content' />",
			  	scope:$scope,
			  	buttons:[{
			  			text:"Đóng"
			  		},
			  		{
			  			text:"<b>ĐÁNH GIÁ</b>",
			  			type:'button-dark',
			  			onTap:function(e){
			  				
			  				if(!$scope.datareview.title && !$scope.datareview.content){
			  					e.preventDefault();
			  				}else{

			  					return $scope.datareview;
			  				}
			  				
			  				
			  			}
			  		}
			  	]
			})
			.then(function(res){
				if(res){
							$http.post(PUBLIC_VALUE.URL+"detail_save_reivew/"+all_value[0],res).then(function(r){
			  						if(r.data=='1'){
			  							$ionicPopup.show({
			  								title:"Thông báo",
			  								template:"Cảm ơn bạn đã đánh giá sản phẩm!",
			  								buttons:[{text:"Đóng"}]
			  							});
			  						}else{
			  							if(r.data=='2'){
			  								$ionicPopup.show({
			  								title:"Thông báo",
			  								template:"Vui lòng đăng nhập để dùng chức năng này!",
			  								buttons:[{text:"Đóng"}]
			  									});

			  							

				  						}else{
				  							$ionicPopup.show({
			  								title:"Thông báo",
			  								template:"Vui lòng nhập đầy đủ thông tin!",
			  								buttons:[{text:"Đóng"}]
			  							});
				  						}
			  						}
			  					});
				}
			});

		

			



		}
		//End review
		//tra gop
		$scope.popup_payment=function(){

			$scope.datepayment={};
			$scope.datepayment.id=all_value[0];
			var myPopup=	$ionicPopup.show({
				title:"(*) Quý khách vui lòng nhập đầy đủ thông tin và chọn 'Gửi thông tin'. Thông tin chi tiết về mua hàng trả góp sẽ được gửi đến địa chỉ email của quý khách.",
				subTitle:"Vui lòng nhập nội dung",
				template:"Anh chị: <select  ng-model='datepayment.gender' style='width:80px; padding:5px'><option value='0' ng-selected='selected'>Anh</option><option value='1'>Chị</option></select> <br />"
						 +"Nhập họ và tên: <input type='text' ng-model='datepayment.name' /> <br />"
						 +"Số điện thoại: <input type='number' ng-model='datepayment.phone' /> <br />"	
						 +"Email: <input type='text' ng-model='datepayment.email' />"
						 +"Bạn là:<select ng-model='datepayment.work' style='padding:5px'><option ng-selected='selected' value='0'>Sinh viên</option><option value='1'>Đã đi làm</option></select> <br />"
						 ,
			  	scope:$scope,
			  	buttons:[{
			  			text:"Đóng"
			  		},
			  		{
			  			text:"<b>Gửi thông tin</b>",
			  			type:'button-dark',
			  			onTap:function(e){
			  				
			  				if(!$scope.datepayment.gender &&  !$scope.datepayment.work &&  !$scope.datepayment.name && !$scope.datepayment.phone &&  !$scope.datepayment.email){
			  					$ionicPopup.show({
				  								title:"Thông báo",
				  								template:"Vui lòng nhập đầy đủ thông tin!",
				  								buttons:[{text:"Đóng"}]
			  								});
			  					e.preventDefault();
			  				}else{

			  					return $scope.datepayment;
			  				}
			  				
			  				
			  			}
			  		}
			  	]
			})
			.then(function(res){
						
							$ionicLoading.show({
								template:"Loading..."
							})
			
							$http.post(PUBLIC_VALUE.URL+"detail_save_payment",res).success(function(r){
			  							$ionicPopup.show({
			  								title:"Thông báo",
			  								template:"Cảm ơn bạn đã Gửi thông tin",
			  								buttons:[{text:"Đóng"}]
			  							});
			  							$ionicLoading.hide();
			  					}).error(function(r){
			  						$ionicLoading.hide();
			  					});


				
			});
		

		

			



		}
		//end
		//commend
		$scope.popup_commend=function(){
			$scope.datacommend={};
			var myPopup=	$ionicPopup.show({
				title:"Bạn có thắc mắc cần chúng tôi giải đáp? Hãy hỏi chúng tôi tại đây!",
				subTitle:"Vui lòng nhập nội dung",
				template:" <input type='text' ng-model='datacommend.value' /> <br />"	,
			  	scope:$scope,
			  	buttons:[{
			  			text:"Đóng"
			  		},
			  		{
			  			text:"<b>Gửi câu hỏi</b>",
			  			type:'button-dark',
			  			onTap:function(e){
			  				
			  				if(!$scope.datacommend.value){
			  					e.preventDefault();
			  				}else{

			  					return $scope.datacommend;
			  				}
			  				
			  				
			  			}
			  		}
			  	]
			})
			.then(function(res){
				if(res){
							$http.post(PUBLIC_VALUE.URL+"detail_save_commend/"+all_value[0],res).then(function(r){
			  						if(r.data=='1'){
			  							$ionicPopup.show({
			  								title:"Thông báo",
			  								template:"Cảm ơn bạn đã gửi câu hỏi!",
			  								buttons:[{text:"Đóng"}]
			  							});
			  						}else{
			  							if(r.data=='2'){
			  								$ionicPopup.show({
			  								title:"Thông báo",
			  								template:"Vui lòng đăng nhập để dùng chức năng này!",
			  								buttons:[{text:"Đóng"}]
			  									});

			  							

				  						}else{
				  							$ionicPopup.show({
			  								title:"Thông báo",
			  								template:"Vui lòng nhập đầy đủ thông tin!",
			  								buttons:[{text:"Đóng"}]
			  							});
				  						}
			  						}
			  					});
				}
			});

		}
		//end commend



	//End if	
	}



	$scope.infoshow='info_special_show';
	$scope.my_show_detail=function(){
		if($scope.infoshow==""){
			$scope.infoshow='info_special_show';
		}else{
			$scope.infoshow='';
		}
	}
		$scope.contentshow='info_special_show';
	$scope.viewcontentshow=function(){
		if($scope.contentshow==""){
			$scope.contentshow='contentshow';
		}else{
			$scope.contentshow='';
		}
	}

	//buy all product in list together
	var list_buy_together=[];
	$q.all([success_ajax_buyt]).then(function(result){
			angular.forEach(result[0].data,function(val,key){
				if(val['isprice']=='1'){
					list_buy_together.push({id:val['myid'],supplier:val['cid_supplier'],price:val['discount']});
				}else{
					list_buy_together.push({id:val['myid'],supplier:val['cid_supplier'],price:val['saleprice']});
				}
				
			});
			list_buy_together.push({id:$scope.product['myid'],supplier:$scope.product['cid_supplier'],price:$scope.product['discount']});
			
	});

	$scope.buy_all=function(){
		
			var ele_buy=$http.post(PUBLIC_VALUE.URL+"order_save_all",JSON.stringify(list_buy_together) );
		
		
		$q.all([ele_buy]).then(function(result){
			$state.go("firstcart");
		});
		

		//
	}
	
	$scope.change_buy_all=function(id,bool){
		var is_exists_id=false;
			angular.forEach(list_buy_together,function(val,key){
					if(val.id==id){
						is_exists_id=true;
					}
			});
		if(bool){
				if(!is_exists_id){
					angular.forEach($scope.buytogether,function(val,key){
						 if(val['myid']==id){
							if(val['isprice']=='1'){
								list_buy_together.push({id:val['myid'],supplier:val['cid_supplier'],price:val['discount']});
							}else{
								list_buy_together.push({id:val['myid'],supplier:val['cid_supplier'],price:val['saleprice']});
							}
						}
					})
				}
		}else{
			if(is_exists_id){
				var tam=[];
				angular.forEach(list_buy_together,function(val,key){

					if(val.id!=id){
						tam.push(val);
					}
				});
				list_buy_together=tam;
			}
		}	

				if($scope.product['isprice']=='1'){
					$scope.get_total_element=Math.round($scope.product['discount']);
				}else{
					$scope.get_total_element=Math.round($scope.product['saleprice']);
				}
				
				angular.forEach(list_buy_together,function(val,key){
						$scope.get_total_element=$scope.get_total_element+val.price*1;
				});
				$scope.get_total_element1=$scope.get_total_element;
	}
	

	//tooltip gift
	$scope.viewtooltip=function(na,des){
		var description="<div style='height:300px'>"+des+"</div>";
		$ionicPopup.show({
				title:na,
				template:description,
			  	scope:$scope,
			  	buttons:[{
			  			text:"Đóng"
			  		}
			  	]
			});
	}
})



function replaceAll(str, find, replace) {
  return str.replace(new RegExp(find, 'g'), replace);
}
function myparse(s){
		s = s.replace(/\\n/g, "\\n")  
               .replace(/\\'/g, "\\'")
               .replace(/\\"/g, '\\"')
               .replace(/\\&/g, "\\&")
               .replace(/\\r/g, "\\r")
               .replace(/\\t/g, "\\t")
               .replace(/\\b/g, "\\b")
               .replace(/\\f/g, "\\f");
		s = s.replace(/[\u0000-\u0019]+/g,""); 
		var o = JSON.parse(s);
		return 0;
}
 function get_repeat_slide(array_repeat){
	var result=new Array();
	var dem=0;
	result.push(dem);
	for (var i = 1;i < array_repeat.length - 1; i++) {
		if(i%3==0){
			dem=dem+3
			result.push(dem);
				
		}
	};
	return result;
}