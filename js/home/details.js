angular.module('starter.details', [])

.controller("DetailCtr",function($scope,$http,$stateParams,$ionicPopup,$ionicSlideBoxDelegate,PUBLIC_VALUE,ValidateData,$cookieStore,$ionicScrollDelegate){
	
	$scope.LINK_IMG=PUBLIC_VALUE.IMG;
	$scope.ValidateData=ValidateData;

	$scope.product=[];
	$scope.list_image=[];
	$scope.list_gift=[];
	$scope.payment=[];
	$scope.mylimit=1;
	$scope.old_new=[];
	$scope.buytogether=[];
	$scope.list_element=[];
	$scope.get_total_element=0;
	$scope.list_cate=[];
	$scope.content='';

	if($stateParams.id != undefined){

		var all_value=$stateParams.id.split("_");
		
		$http.get(PUBLIC_VALUE.URL+"detail_product/"+all_value[0]+"/"+all_value[2]).success(function(result){

			$scope.product=result;
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
						$scope.permin=( ($scope.product['discount']-Math.round($scope.payment['permin']*$scope.product['discount']/100)) * $scope.payment['rate']);
		
					}
					
				});
				$http.get(PUBLIC_VALUE.URL+"detail_cate/"+$scope.product['cid_cate']).success(function(list_product){
						$scope.list_cate=list_product;
						
				});

					$http.get(PUBLIC_VALUE.URL+"getelement/"+$scope.product['cid_cate']+"/"+$scope.product['myid'])
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

			

		});


		$http.get("http://dienmaycholon.vn/index/jsonimage/id/"+all_value[0]).success(function(result){
			$scope.list_image=result;
			$ionicSlideBoxDelegate.update();
		});
		$http.get(PUBLIC_VALUE.URL+"detail_gift/"+all_value[0]+"/"+all_value[2]).success(function(result){
			$scope.list_gift=result;
		});
		$http.get(PUBLIC_VALUE.URL+"detail_element/"+all_value[0]).success(function(result){
			$scope.list_element=result;
		});
		
		$http.get(PUBLIC_VALUE.URL+"detail_price/"+all_value[0]).success(function(pri){
				$scope.old_new=pri;
				
			});


		$http.get(PUBLIC_VALUE.URL+"detail_buy/"+all_value[0]).success(function(b){
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
	}
	$scope.show_detail=false;
	$scope.my_show_detail=function(){
		$scope.show_detail=!$scope.show_detail;
	}

	
})
function replaceAll(str, find, replace) {
  return str.replace(new RegExp(find, 'g'), replace);
}
