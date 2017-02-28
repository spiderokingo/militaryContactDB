// JavaScript Document

app.controller('tabController', function($scope, $http, $uibModal, $log, $state, localStorageService, $rootScope) {
	$scope.tabclicked = function(index){
			$rootScope.tabactive = index;
		}

	$scope.mb_menu_active = false;

	// variable of Menu Title and url link
	$scope.db_tabs = [
    {id:0, title:'หน้าแรก', url: 'views/content_home.html'},
    {id:1, title:'ข้อมูลบุคคล', url: 'views/content_personal_details.html'},
    {id:2, title:'ปริ้น QR Code', url: 'views/content_qr_code_printing.html'},
    {id:2, title:'ข้อมูลอาวุธ'},
    {id:3, title:'สป 2-4' },
    {id:4, title:'ข้อมูลเบอร์ติดต่อ', url: 'views/content_personal_contact.html' },
    {id:5, title:'หน่วยฝึกทหารใหม่'},
    {id:6, title:'Logout'}
  ];

	$scope.mb_menu_clicked = function(index,state){

		if(index != undefined){
			// LOGOUT !
			if($scope.db_tabs[index].id == 6){
				$state.go('login');
				localStorageService.remove('userObj');
			}

			// Assign selected tab to active
			$rootScope.tabactive = index;

			// Check show/hide state of menu in mobile views\
			$scope.mb_menu_active = state;
		}else
			$scope.mb_menu_active = !$scope.mb_menu_active;

	}

});