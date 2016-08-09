var app = angular.module('com.infantry43.database', ['ui.router', 'ui.bootstrap', 'LocalStorageModule', 'toaster', 'ngFileUpload']);

app.controller("DemoController", function($scope, $location) {
        $scope.goto = function(page) {
            $location.path(page);
        };
    });

app.config(['$stateProvider', '$urlRouterProvider',  function($stateProvider, $urlRouterProvider) {

$urlRouterProvider.otherwise('/');

$stateProvider
    
    // HOME STATES AND NESTED VIEWS ========================================
    .state('login', {
        url: '/',
        templateUrl : 'views/login.html',
        controller  : 'loginController',
        data : { pageTitle: 'LOGIN PAGE' }
    })

    .state('main', {
        url: '/main',
        templateUrl : 'views/main.html',
        controller  : 'tabController',
        data : { pageTitle: 'ฐานข้อมูล กองพันทหารที่ 3 กรมทหารราบที่ 4' }
    })

    .state('personalcontact', {
        url: '/personal_contact',
        templateUrl : 'views/content_personal_contact.html',
        controller  : 'personalController',
        data : { pageTitle: 'Personal Contact' }
    })

    
    
    // ABOUT PAGE AND MULTIPLE NAMED VIEWS =================================
    .state('about', {
        // we'll get to this in a bit       
    });
    
}]);

app.run(['$rootScope', '$state', '$location', 'localStorageService',  function($rootScope, $state, $location, localStorageService){

    $rootScope.$on('$stateChangeSuccess', function (ev, to, toParams, from, fromParams) {

        $rootScope.user = angular.fromJson(localStorageService.get('userObj'));

        //Assign current state to the global variable
        $rootScope.currentState = $state.current.name;

        if($state.current.name != 'login'){
            // console.log("loginUsername > " + localStorageService.get('loginUsername'));
            if($rootScope.user == null){
                $state.go('login');
                localStorageService.remove('userObj');
            }
        }
        document.title = (to.data && to.data.pageTitle) ? to.data.pageTitle : 'Default title';
    });


}]);