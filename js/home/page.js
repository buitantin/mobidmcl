angular.module("starter.page",[])
.controller("CheckorderCtr",function($scope,$q,$http,$stateParams,$ionicPopup,$ionicSlideBoxDelegate,PUBLIC_VALUE,ValidateData,$cookieStore,$ionicScrollDelegate){
	


	

})
.controller("InstallmentCtr",function($scope,$location,$http,PUBLIC_VALUE){
    var id=1;
	if($location.search().pay!=undefined){
         id=$location.search().pay;
    }

        $http.get(PUBLIC_VALUE.URL+"getinstallment/"+id).success(function(result){
            $scope.detail=result;
        })
    $scope.change_instal=function(id){
        $http.get(PUBLIC_VALUE.URL+"getinstallment/"+id).success(function(result){
            $scope.detail=result;
        })
    }

})
.controller("CardmemberCtr",function($scope,$http,PUBLIC_VALUE){
    $http.get(PUBLIC_VALUE.URL+"getmemberbenefits/2",{cache:true}).success(function(result){
        $scope.detail=result;
    })
  
             $http.get(PUBLIC_VALUE.URL+"getmemberbenefits/1").success(function(result){
                $scope.detail1=result;
             });
  
})
.controller("BranchCtr",function($scope,$http,$location,PUBLIC_VALUE,$q,$ionicScrollDelegate){
         $ionicScrollDelegate.scrollTop();
        
        var t1=$http.get(PUBLIC_VALUE.URL+"get_list_branch/1").success(function(reuslt){
            $scope.hcm=reuslt;
        });
        var t2= $http.get(PUBLIC_VALUE.URL+"get_list_branch/2").success(function(reuslt){
        	$scope.mtay=reuslt;
        })
        var t3=  $http.get(PUBLIC_VALUE.URL+"get_list_branch/3").success(function(reuslt){
        	$scope.mtr=reuslt;
        })
        $q.all([t1,t2,t3]).then(function(r){
            $scope.total_bran=$scope.hcm.length+$scope.mtay.length+$scope.mtr.length;
        });

        var t=4;
        if($location.search().element){
            t=$location.search.element;
        }
        var t4=    $http.get(PUBLIC_VALUE.URL+"get_detail_branch/"+t).success(function(reuslt){
            $scope.detail=reuslt;
                          $scope.lng=reuslt['lng'];
                         $scope.lat=reuslt['lat'];
        })

        $scope.change_br=function(id){
            $ionicScrollDelegate.scrollTop();
                 var t4=$http.get(PUBLIC_VALUE.URL+"get_detail_branch/"+id).success(function(reuslt){
                         $scope.detail=reuslt;
                         $scope.lng=reuslt['lng'];
                         $scope.lat=reuslt['lat'];
                  });


                 $q.all([t4]).then(function(result){
                     

                     var myLatlng = new google.maps.LatLng( $scope.lng, $scope.lat);
                 
                        var mapOptions = {
                            center: myLatlng,
                            zoom: 16,
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        };
                 
                        var map = new google.maps.Map(document.getElementById("map"), mapOptions);
                    
                        navigator.geolocation.getCurrentPosition(function(pos) {
                            map.setCenter(new google.maps.LatLng(pos.coords.latitude, pos.coords.longitude));
                            var myLocation = new google.maps.Marker({
                                position: new google.maps.LatLng(pos.coords.latitude, pos.coords.longitude),
                                map: map,
                                title: "Dien may cho lon"
                            });
                        });
                 
                        $scope.map = map;
                })
        }

        $q.all([t4]).then(function(result){
             /*google.maps.event.addDomListener(window, 'load', function() {
              
                });*/

             var myLatlng = new google.maps.LatLng( $scope.lng, $scope.lat);
         
                var mapOptions = {
                    center: myLatlng,
                    zoom: 16,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
         
                var map = new google.maps.Map(document.getElementById("map"), mapOptions);
            
                navigator.geolocation.getCurrentPosition(function(pos) {
                    map.setCenter(new google.maps.LatLng(pos.coords.latitude, pos.coords.longitude));
                    var myLocation = new google.maps.Marker({
                        position: new google.maps.LatLng(pos.coords.latitude, pos.coords.longitude),
                        map: map,
                        title: "Dien may cho lon"
                    });
                });
         
                $scope.map = map;
        })
             
    

        




})