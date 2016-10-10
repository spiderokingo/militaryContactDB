app.controller('weaponController', function($scope, $http, $uibModal, $log, $rootScope, $state, localStorageService, $timeout) {

	$scope.init = function(){

		$scope.currentPage = 1;
		$scope.amount = 25;
		$scope.search = "";
		$scope.maxSize = 7;
		$scope.option = { 
			companyOption: "เลือกทั้งหมด",
			weaponOption: "เลือกทั้งหมด"
		};

		$scope.filterText = "ตัวกรอง";

        $scope.getWeaponList();
	};

	$scope.getWeaponList = function(){
		var request = $http({
			method: "POST",
			url: "php/Weapon.php",
			data: { 'Mode': "LIST", 'Page': $scope.currentPage, 'Amount': $scope.amount, 'SearchText': $scope.search,
					'WeaponCompany': $scope.option.companyOption == "เลือกทั้งหมด" ? "": $scope.option.companyOption,
					 'WeaponType': $scope.option.weaponOption== "เลือกทั้งหมด" ? "": $scope.option.weaponOption}
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

	$scope.onOption = function(){
		
		var modalInstance = $uibModal.open({
			animation: true,
			templateUrl: 'WeaponFilterModalTemplate.html',
			controller: 'FilterModalCtrl',
			resolve: {
				option: function() {
					return $scope.option;
				}
			}
			});
			modalInstance.result.then(function (modalFilter) {
				//Check whether the Person Details got edit or not
				$scope.option = modalFilter;
				if($scope.option.weaponOption == "เลือกทั้งหมด" && $scope.option.companyOption == "เลือกทั้งหมด"){
					$scope.filterText = "เลือกตัวกรอง";
				}else if($scope.option.weaponOption == "เลือกทั้งหมด" ){
					$scope.filterText = $scope.option.companyOption;
				}else if($scope.option.companyOption == "เลือกทั้งหมด" ){
					$scope.filterText = $scope.option.weaponOption;
				}else{
					$scope.filterText = $scope.option.companyOption+"/"+$scope.option.weaponOption
				}
				$scope.getWeaponList();
			});
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
    
	$scope.init();

});

// 
// START Weapon Filter Modal Controller
// 
app.controller('FilterModalCtrl', function ($scope, $uibModalInstance, $http, option) {

	$scope.init = function () {
		$scope.option = option;
		$scope.getCompanyList();
		$scope.getWeaponTypeList();
	}

	$scope.getCompanyList = function () {
		var request =	$http({
			method: "POST",
			url: "php/CompanyList.php"
		})
		request.success(function (res) {
			console.log(res);
			res.CompanyList.push({
				CompanyShort: "เลือกทั้งหมด"
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
				WeaponType: "เลือกทั้งหมด"
			});
			$scope.WeaponTypeList = res.WeaponTypeList;
		});
	}

	$scope.onCompanyChange = function(item){
		$scope.option.companyOption = item;
	}

	$scope.onWeaponChange = function(item){
		$scope.option.weaponOption = item;
	}

	$scope.save = function(){
		$uibModalInstance.close($scope.option);
  	};

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

