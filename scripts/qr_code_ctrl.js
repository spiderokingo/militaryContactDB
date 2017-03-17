app.controller('qrCodeController', function($scope, $http, $uibModal, $log, $rootScope, $state, localStorageService, $timeout) {

	$scope.init = function(){

		$scope.tel = "tel:"

		$scope.currentPage = 1;
		$scope.amount = 25;
		$scope.search = "";
		$scope.maxSize = 7;

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
		});
	}

    $scope.onSelected = function(item){
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

    $scope.isActiveCheck = function(item){
        var isExist = false;
        angular.forEach($scope.selectedItem, function(person, pos) {
            if(person.ID == item.ID){
                isExist = true;
            }
        });
        return isExist;
    }

	$scope.onSearch = function(){
		$scope.getPersonalList();
	}

	$scope.init();

});