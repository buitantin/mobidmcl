// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
// 'starter.services' is found in services.js
// 'starter.controllers' is found in controllers.js
angular.module('starter', ['ionic','ngCookies','ngOpenFB', 'starter.controllers', 'starter.services','starter.details','starter.users','starter.page','starter.news'])

.run(function($ionicPlatform,ngFB) {
  ngFB.init({appId:'149331555423303'});
  
  $ionicPlatform.ready(function() {
    // Hide the accessory bar by default (remove this to show the accessory bar above the keyboard
    // for form inputs)

    if (window.cordova && window.cordova.plugins.Keyboard) {
      cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
    }
    if (window.StatusBar) {
      // org.apache.cordova.statusbar required
      StatusBar.styleDefault();
    }
  });
  
})

.constant("PUBLIC_VALUE",{
        URL:"/API/",
        IMG:"http://static.dienmaycholon.vn/product/"
})
.config(function($stateProvider, $urlRouterProvider,$locationProvider) {
 
  /*  $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
$httpProvider.defaults.withCredentials = true;
*/

  // Ionic uses AngularUI Router which uses the concept of states
  // Learn more here: https://github.com/angular-ui/ui-router
  // Set up the various states which the app can be in.
  // Each state's controller can be found in controllers.js
 // $stateProvider

  $stateProvider
    
    .state("home",
        {
          url:"/",
           templateUrl : '/templates/front/index.html',
           controller: 'HOMECtrl'
       
         
        }
      )

     .state("allcategories",
        {
           url:"/tat-ca-danh-muc",
           templateUrl : '/templates/front/menu.html',
           controller: 'HOMECtrl' 
        }
      )

        //for use
        .state("signup",
            {
               url:"/dang-ky",
               templateUrl:"/templates/users/signup.html",
               controller:"UserSignupCtr"
            }
        )
         .state("login",
            {
               url:"/dang-nhap",
               templateUrl:"/templates/users/login.html",
               controller:"UserLoginCtr"
            }
        )
         .state("profile",
            {
               url:"/thong-tin-ca-nhan",
               templateUrl:"/templates/users/profile.html",
               controller:"UserProfileCtr"
            }
        )
         .state("logout",
            {
               url:"/thoat",
               controller:"UserLogoutCtr"
            }
        )
        
        //for page

         .state("checkorder",
            {
               url:"/kiem-tra-don-hang",
               templateUrl:"/templates/page/checkorder.html",
               controller:"CheckorderCtr"
            }
        )
          .state("installment",
            {
               url:"/danh-sach-mua-tra-gop",
               templateUrl:"/templates/page/installment.html",
               controller:"InstallmentCtr"
            }
        )
          .state("cardmember",
              {
                url:"/quyenloi-the-thanh-vien",
                templateUrl:"/templates/page/cardmember.html",
                controller:"CardmemberCtr"
              }
            )
         


        //for search

        .state("search",
          {
            url : "/tim-kiem?:search",
            templateUrl:"/templates/front/search.html",
            controller:"SearchCtr"

          }
        ) 

        //for news
         
        .state("newslist",
          {
            url : "/tin-khuyen-mai",
            templateUrl:"/templates/news/list.html",
            controller:"NewsCtr"

          }
        ) 
         .state("newscate",
          {
            url : "/tin-tuc/:id/:alias",
            templateUrl:"/templates/news/cate.html",
            controller:"NewsCateCtr"

          }
        ) 
           .state("newsdetail",
          {
            url : "/khuyen-mai/:id/:alias",
            templateUrl:"/templates/news/detail.html",
            controller:"NewsDetailCtr"

          }
        ) 




       .state("parent",
        {
          url:"/:parent",
          templateUrl : '/templates/front/cate.html',
           controller: 'CategoriesCtr'
        }
      )
     .state("detail",
          { 
         
              url: "/chi-tiet-san-pham/:id",
              templateUrl : '/templates/front/detail.html',
              controller: 'DetailCtr'
          }
        )
      .state("child",
          { 
         
              url: "/danh-muc/:child/:alias",
              templateUrl : '/templates/front/child.html',
              controller: 'ChildCtr'
          }
        )
       .state("compare",
         {
             url:"/so-sanh/:compare",
             templateUrl : '/templates/front/compare.html',
             controller: 'CompareCtr' 
          }
        )
        .state("filter",
         {
             url:"/tieu-chi-tim-kiem/:filter",
             templateUrl : '/templates/front/filter.html',
             controller: 'FilterCtr' 
          }
        )
        



     
    

    //$locationProvider.html5Mode(true);
   

    $locationProvider.html5Mode({
        enabled: true,
        requireBase: false
    });

  //  event.preventDefault();

    $urlRouterProvider.otherwise("/");
    /*  $urlRouterProvider.otherwise(function($injector, $location){
          var $state = $injector.get("$state");
          $state.go('home');
     });
*/

});
