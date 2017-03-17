// JavaScript Document

app.controller('tabController', function($scope, $http, $uibModal, $log, $state, localStorageService, $rootScope, myConfig) {
	$scope.tabclicked = function(index){
			$rootScope.tabactive = index;
		}

	// variable of Menu Title and url link
	/*$scope.database_tabs = [
		{id:0, title:'หน้าแรก', url: 'views/content_home.html'},
		{id:1, title:'ข้อมูล', 
			subList:[
				{id:0, title:'บุคคล', url: 'views/content_personal_details.html'},
				{id:1, title:'อาวุธ', url: 'views/content_weapon_details.html'},
				{id:2, title:'สป 2-4'},
				{id:3, title:'ปริ้น Bar Code', url: 'views/content_code_printing.html'},
			],
			
		},
		{id:2, title:'ข้อมูลเบอร์ติดต่อ', url: 'views/content_personal_contact.html'},
		{id:3, title:'เบิก/คืน สป.' ,
			subList:[
					{id:0, title:'เบิกอาวุธ', url: 'views/content_withdraw.html'},
					{id:1, title:'คืนอาวุธ', url: 'views/content_return.html'},
				] 
		},
		{id:4, title:'หน่วยฝึกทหารใหม่'},
		{id:5, title:'รายงาน', url: 'views/content_report.html'},
		{id:6, title:'ตั้งค่า', url: 'views/content_setting.html'},
		{id:100, title:'Logout'},
	];*/

	$scope.database_tabs = [
		{id:0, title:'หน้าแรก', url: 'views/content_home.html'},
		{id:1, title:'กำลังพล', 
			subList:[
				{id:0, title:'การรายงาน', url: 'views/content_report.html'},
				{id:1, title:'ข้อมูลบุคคล', url: 'views/content_personal_details.html'},
				{id:2, title:'ทดสอบร่างกาย'}
			],
			
		},
		{id:2, title:'ยุทธการ',
			subList:[
				{id:0, title:'การจัดกำลัง', url: 'views/content_troop_management.html'},
				{id:1, title:'.....', url: ''}
			],},
		{id:3, title:'ส่งกำลัง' ,
			subList:[
					{id:0, title:'อาวุธ', url: 'views/content_weapon_details.html'},
					/*{id:1, title:'สป 2-4'},
					{id:2, title:'สื่อสาร'},*/
					{id:3, title:'เบิกอาวุธ', url: 'views/content_withdraw.html'},
					{id:4, title:'คืนอาวุธ', url: 'views/content_return.html'},
					{id:5, title:'ปริ้น Bar Code', url: 'views/content_code_printing.html'},
				] 
		},
		{id:4, title:'หน่วยฝึกทหารใหม่'},
		{id:5, title:'ศูนย์การเรียนรู้',
			subList:[
				{id:0, title:'ตำรา', url: 'views/content_lecture.html'},
				{id:1, title:'ข้อสอบ', url: 'views/content_examination.html'}
			],},
		{id:6, title:'ข้อมูลเบอร์ติดต่อ', url: 'views/content_personal_contact.html'},
		{id:7, title:'ตั้งค่า', url: 'views/content_setting.html'},
		{id:100, title:'Logout'},
	];

	$scope.init = function () {
		switch(myConfig.Permission[$rootScope.user.Permission]){
			case 'Administrator':
			case 'ผู้บังคับบัญชา':
			case 'ผู้ดูแลระบบ':
				$scope.sourceUrl = $scope.database_tabs[0].url;
				// $scope.sourceUrl = $scope.database_tabs[2].url;
			break;
			default:
				$scope.sourceUrl = $scope.database_tabs[2].url;
			break;
		}
		$scope.userActive();
	}

	$scope.userActive = function(){
		var request = $http({
			method: "POST",
			url: "php/postActive.php",
			data: { 'PersonalID': $rootScope.user.PersonalID, 'Permission': $rootScope.user.Permission}
		})
		request.success(function (res) {
			$scope.resUserActive = res;
      		console.log(res);
		});
	}

	//Mainmenu validate function
	$scope.MenuShowHide = function(id){
		if($scope.resUserActive == undefined) return;
		if($scope.resUserActive.menuPermission.mainList.indexOf(-1) > -1 || id == 100){
			//ถ้า menu permission เป็น -1 และ id เป็น Logout menu tab
			return true;
		}
		return $scope.resUserActive.menuPermission.mainList.indexOf(id) > -1;
	}

	//Submenu validate function
	$scope.subMenuShowHide = function(menuId, submenuIndex){
		if($scope.resUserActive == undefined) return;
		//ถ้าไม่มี condition ของ submenu ก็แสดงทั้งหมด
		if($scope.resUserActive.menuPermission.subList == undefined) 
			return true;
		
		//loop เพื่อหาว่าเมนูไหนที่จะให้แสดง
		var isAllow = false;
		angular.forEach($scope.resUserActive.menuPermission.subList.list, function(val){
			if($scope.resUserActive.menuPermission.subList.main == menuId &&
				val == submenuIndex){
				isAllow = true;
			}
		})
		
		return isAllow;
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