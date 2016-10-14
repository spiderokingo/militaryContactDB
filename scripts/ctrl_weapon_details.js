app.controller('weaponController', function($scope, $http, $uibModal, $log, $rootScope, $state, localStorageService, $timeout) {

	var allCompany = "ทุกกองร้อย"
	var allType = "ทุกประเภท"
	$scope.init = function(){

		$scope.currentPage = 1;
		$scope.amount = 25;
		$scope.search = "";
		$scope.maxSize = 7;
		$scope.option = { 
			companyOption: allCompany,
			weaponOption: allType
		};

		$scope.filterText = "ตัวกรอง";

        $scope.getWeaponList();
		$scope.getCompanyList();
		$scope.getWeaponTypeList();
	};

	$scope.getWeaponList = function(){
		var request = $http({
			method: "POST",
			url: "php/Weapon.php",
			data: { 'Mode': "LIST", 'Page': $scope.currentPage, 'Amount': $scope.amount, 'SearchText': $scope.search,
					'WeaponCompany': $scope.option.companyOption == allCompany ? "": $scope.option.companyOption,
					 'WeaponType': $scope.option.weaponOption== allType ? "": $scope.option.weaponOption}
		});

		request.success(function (res) {
			$scope.WeaponRecord = res.WeaponRecord;
			$scope.TotalItems = res.TotalItems;

			console.log(res);
		});
	}

	$scope.onSearch = function(){
		$scope.getWeaponList();
	}

	$scope.onWeaponDetail = function(obj){
		var modalInstance = $uibModal.open({
			animation: true,
			templateUrl: 'WeaponDetailModalTemplate.html',
			controller: 'WeaponDetailModalCtrl',
			resolve: {
				weaponObj: obj
			}
			});
			modalInstance.result.then(function (modalFilter) {
				console.log(modalFilter);
			});
	}

	$scope.getCompanyList = function () {
		var request =	$http({
			method: "POST",
			url: "php/CompanyList.php"
		})
		request.success(function (res) {
			console.log(res);
			res.CompanyList.push({
				ID: 0,
				CompanyShort: allCompany
			});
			$scope.CompanyList = res.CompanyList;
		});
	}

	$scope.getWeaponTypeList = function () {
		var request =	$http({
			method: "POST",
			url: "php/WeaponTypeList.php"
		})
		request.success(function (res) {
			console.log(res);
			res.WeaponTypeList.push({
				WeaponType: allType
			});
			$scope.WeaponTypeList = res.WeaponTypeList;
		});
	}

	$scope.onCompanyChange = function(item){
		$scope.option.companyOption = item;
        $scope.getWeaponList();
	}

	$scope.onWeaponChange = function(item){
		$scope.option.weaponOption = item;
        $scope.getWeaponList();
	}
    
	$scope.init();

});


// 
// START Weapon Detail Modal Controller
// 
app.controller('WeaponDetailModalCtrl', function ($scope, $uibModalInstance, $http, weaponObj) {

	$scope.init = function () {
		$scope.getWeaponDetail();
	}

	$scope.getWeaponDetail = function () {
		var request =	$http({
			method: "POST",
			url: "php/Weapon.php",
			data: { 'Mode': "VIEW" , 'ID': weaponObj.ID}
		})
		request.success(function (res) {
			$scope.weaponObj = res;
			console.log(res);
		});
	}

	$scope.save = function(){
		$uibModalInstance.close();
  	};

	$scope.init();
});

