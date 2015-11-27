angular.module('starter.controllers', [])

.controller('DashCtrl', function($scope) {})

.controller('ChatsCtrl', function($scope, Chats) {
  $scope.chats = Chats.all();
  $scope.remove = function(chat) {
    Chats.remove(chat);
  }
})

.controller('ChatDetailCtrl', function($scope, $stateParams, Chats) {
  $scope.chat = Chats.get($stateParams.chatId);
})

.controller('FriendsCtrl', function($scope, Friends) {
  $scope.friends = Friends.all();
})

.controller('FriendDetailCtrl', function($scope, $stateParams, Friends) {
  $scope.friend = Friends.get($stateParams.friendId);
})

.controller('AccountCtrl', function($scope) {
  $scope.settings = {
    enableFriends: true
  };
})
.controller('mainController',function($scope,$ionicSideMenuDelegate,$http, $ionicActionSheet, $timeout){
	/*//For categories
	  $http.get("json/cate.json").success(function($response){
	        $scope.listCates=$response
	  });
  //For slideshow
	  $http.get("json/slideshow.json").success(function($response_slideshow){
		  	$scope.Slideshows=$response_slideshow;
	  });*/
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

}
  );
