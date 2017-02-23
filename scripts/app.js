var app = angular.module('com.infantry43.database', ['ui.router', 'ui.bootstrap', 'LocalStorageModule', 'toaster', 'ngFileUpload', 'monospaced.qrcode']);

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

    .state('printconfirm', {
        url: '/print_qr',
        templateUrl : 'views/content_print_confirm.html',
        controller  : 'PrintConfirmCtrl',
        data : { pageTitle: 'Print Confirmation' }
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

app.value('myConfig', {
    'Permission':
        // {
        //     id: 1,
        //     title: 'ผู้ดูแลระบบ'
        // },
        // {
        //     id: 2,
        //     title: 'ผู้บังคับบัญชา'
        // },
        // {
        //     id: 3,
        //     title: 'นายทหาร'
        // },
        { 
        '0':'Administrator',
        '1':'ผู้ดูแลระบบ',
        '2':'ผู้บังคับบัญชา' ,
        '3':'นายทหาร' ,
        '4':'นายสิบ' ,
        '5':'พลทหาร' ,
        '6':'ครอบครัว' ,
        '7':'จนท.คลังอาวุธ' ,
        '8':'จนท.สื่อสาร' ,
        '9':'จนท.อาภรณ์' ,
        '10':'จนท.บก.ร้อย'}
    ,
    
    'MenuPermission': {
        '0':[0,1,2,3,4,5,6],
        '1':[0,1,2,3,4,5,6],
        '2':[0],
        '3':[],
        '4':[],
        '5':[2],
        '6':[],
        '7':[],
        '8':[],
        '9':[],
        '10':[],
    },

    'ThaiMonth': {
        '1':'ม.ค.',
        '2':'ก.พ.',
        '3':'มี.ค.',
        '4':'เม.ย.',
        '5':'พ.ค.',
        '6':'มิ.ย.',
        '7':'ก.ค.',
        '8':'ส.ค.',
        '9':'ก.ย.',
        '10':'ต.ค.',
        '11':'พ.ย.',
        '12':'ธ.ค.'
    }
    
});