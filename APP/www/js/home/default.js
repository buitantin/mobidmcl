function toggleCate($scope,$http){
	$http.get("json/cate.json").success(function(response){
			$scope.listCates=response;
	});
}
