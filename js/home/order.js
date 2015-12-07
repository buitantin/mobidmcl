angular.module("starter.order",[])
.controller("FirstCtr",function($scope,$state,$q,$cookieStore,$ionicScrollDelegate,$http,PUBLIC_VALUE){
	$ionicScrollDelegate.scrollTop();
	$scope.list_product=[];
	var tam=[];
	if($cookieStore.get("orderdmcl")){

		var list_product=$cookieStore.get("orderdmcl") || [];
		var xx=[];
		var total_cart=0;
		for (var i = list_product.length - 1; i >= 0; i--) {
			if(list_product[i].id==undefined){
				 var delete_order1=delete_order(list_product);
				 $cookieStore.put("orderdmcl",delete_order1);
			}else{
				var y=	$http.get(PUBLIC_VALUE.URL+"order_detail/"+list_product[i].id+"/"+list_product[i].supplier).success(function(result){
					tam.push(result);
				});
				xx.push(y);


			}
			
		};

	
		$q.all(xx).then(function(x){
			$scope.list_product=tam;
			angular.forEach(x,function(val,key){
					$scope.list_product[key]['limit']=list_product[key].limit;
					
			});
		});
		$scope.changelimitorder=function(id,l){
			if(l!=null && l != undefined && angular.isNumber(l) && l > 0){
				angular.forEach(list_product,function(val,key){
					if(val.id==id){
						list_product[key].limit=l;		
					}
				});
				$cookieStore.put("orderdmcl",list_product);
			}
		}
			
		$scope.istypesNumber=function($event){
			$event.preventDefault();
			return true;

		}
		
/*
			angular.forEach($scope.list_product,function(val,key){
  				console.log(val);
 		    });*/
	}else{
		$state.go("home");
	}

	$scope.detroy=function(id){
		if($cookieStore.get("orderdmcl")){
			var o=$cookieStore.get("orderdmcl");

				var tam=[];
				for (var i = o.length - 1; i >= 0; i--) {
					if(o[i].id!=id){
						tam.push(o[i]);
					}



				};
				$cookieStore.put("orderdmcl",tam);
				$state.reload();
			
		}
	}
	$scope.continue=function(){
		$state.go("home");
	}
})
.controller("ButtonOrder",function($scope,$cookieStore,$state){
	$scope.buyproduct=function(id,supplier,limit,color){


		var get_old_order=$cookieStore.get("orderdmcl") || [];
		
		if(!check_key(get_old_order,id) ){
			get_old_order.push( {"id":id,"supplier":supplier,"limit":limit,"color":color} );
			$cookieStore.put("orderdmcl",get_old_order);
		}else{
			get_old_order=put_limit(get_old_order,id,limit);
			$cookieStore.put("orderdmcl",get_old_order);
		}

		$state.go("firstcart")


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