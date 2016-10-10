// JavaScript Document

app.controller('tabController', function($scope, $http, $uibModal, $log, $state, localStorageService, $rootScope) {
	$scope.tabclicked = function(index){
			$rootScope.tabactive = index;
		}

	// variable of Menu Title and url link
	$scope.database_tabs = [
		{id:0, title:'หน้าแรก', url: 'views/content_home.html'},
		{id:1, title:'ข้อมูล', 
			subList:[
				{id:0, title:'บุคคล', url: 'views/content_personal_details.html'},
				{id:1, title:'อาวุธ', url: 'views/content_weapon_details.html'},
				{id:2, title:'สป 2-4'},
				{id:3, title:'ปริ้น Bar Code', url: 'views/content_code_printing.html'},
			]
		},
		{id:2, title:'ข้อมูลเบอร์ติดต่อ', url: 'views/content_personal_contact.html' },
		{id:3, title:'เบิก/คืน สป.',
			subList:[
					{id:0, title:'เบิกอาวุธ', url: 'views/content_withdraw.html'},
					{id:1, title:'คืนอาวุธ', url: 'views/content_return.html'},
				] 
		},
		{id:4, title:'หน่วยฝึกทหารใหม่'},
		{id:5, title:'Logout'},
	];

	$scope.init = function () {

		if($rootScope.user.Permission == 'USER')
			$scope.sourceUrl = $scope.database_tabs[2].url;
		else{
			// $scope.sourceUrl = $scope.database_tabs[2].url;
			$scope.sourceUrl = $scope.database_tabs[1].subList[0].url;
		}

	}

	$scope.mb_menu_clicked = function(index){

		if($scope.database_tabs[index].subList){
			return;
		}

		// LOGOUT !
		if($scope.database_tabs[index].title == 'Logout'){
			$state.go('login');
			localStorageService.remove('userObj');
		}

		// Assign selected tab to active
		$rootScope.tabactive = index;

		$scope.sourceUrl = $scope.database_tabs[index].url;


	}

	$scope.sub_menu_clicked = function(index, subIndex){
		$rootScope.tabactive = index;
		$scope.sourceUrl = $scope.database_tabs[index].subList[subIndex].url;
	}

	$scope.init();

});