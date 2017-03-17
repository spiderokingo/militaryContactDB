app.controller('qrCodeController', function($scope, $http, $uibModal,
 $log, $rootScope, $state, localStorageService, $timeout) {

	$scope.init = function(){

		$scope.tel = "tel:"

		$scope.currentPage = 1;
		$scope.amount = 25;
		$scope.search = "";
		$scope.maxSize = 7;

		$scope.isSelectAll = false;
		$scope.selectAllText = "เลือกทั้งหมด";

        $scope.getPersonalList();

        $scope.selectedItem = [];
		
	};

	$scope.getPersonalList = function(){
		var request = $http({
			method: "POST",
			url: "php/Personal.php",
			data: { 'Mode': "LIST", 'Page': $scope.currentPage, 'Amount': $scope.amount, 'SearchText': $scope.search}
		});

		request.success(function (res) {
			$scope.PersonalRecord = res.PersonalRecord;
			$scope.TotalItems = res.TotalItems;
			$scope.setIsActive();
		});
	}

    $scope.onSelected = function(item){
		item.isActive = true;
        $scope.pushInSelectedList(item);
    }

	$scope.pushInSelectedList = function(item){
		var check = {
            isSame: false,
            index: -1
        }
        angular.forEach($scope.selectedItem, function(person, pos) {
            if(person.ID == item.ID){
                check.isSame = true;
                check.index = pos;  
            }
        });
        if(check.isSame){
            $scope.selectedItem.splice(check.index, 1);
        }else{
            $scope.selectedItem.push(item);
        }
	}

	$scope.onSelectAll = function(){
		angular.forEach($scope.PersonalRecord, function(person) {
			person.isActive = true;

			var check = {
				isSame: false,
				index: -1
			}
			angular.forEach($scope.selectedItem, function(item, pos) {
				if(person.ID == item.ID){
					check.isSame = true;
					check.index = pos;  
				}
			});
			if(!check.isSame){
				$scope.selectedItem.push(person);
			}
		});
	}

    $scope.setIsActive = function(){
        angular.forEach($scope.selectedItem, function(selected) {
			angular.forEach($scope.PersonalRecord, function(person) {
				if(selected.ID == person.ID){
					person.isActive = true;
				}
			});
        });
    }

	$scope.onSearch = function(){
		$scope.getPersonalList();
	}

    $scope.onPrint = function(){
		$rootScope.selectedItem = $scope.selectedItem;
		console.log( $scope.selectedItem);
        $state.go('printconfirm');
    }

	$scope.init();

});

app.controller('PrintConfirmCtrl', function ($scope, $http, $rootScope) {

	$scope.init = function(){
		//QR Code Initial
		$scope.version = 1;
		$scope.level = "M";
		$scope.size = 100;
		$scope.data = "1509901148832";
		$scope.selectedList = $rootScope.selectedItem;
	}

	$scope.perslist = function () {
		
		var request = $http({
			method: "post",
			url: "php/Personal.php",
			data: {
				Mode: "LIST",
				Amount: "1",
				Page: "1"
			},
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
		});
		
		request.success(function (data) {
			console.log(data.PersonalRecord);
			$scope.PersonalRecord = data.PersonalRecord;
//			$scope.BtnList = " Print QR Code ";
		});		
	}

	$scope.qrprint = function () {
		var PrintQR = $scope.IDpers;
		alert(PrintQR);
		console.log(PrintQR);
	}

	$scope.init();

});