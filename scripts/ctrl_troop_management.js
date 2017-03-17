// JavaScript Document

app.controller('troopManagementCtrl', function($scope, focus, $rootScope, $http, toaster) {

	$scope.init = function(){
		$scope.troopHeader = "การจัดกำลัง ปฏิบัติภารกิจต่างๆ";
		$scope.MissionPersonalList = '';
		$scope.FormNew = '';
		$scope.txt = '';

		var request = $http({
			method: "POST",
			url: "php/troop_management.php",
			data: { "Mode":"LIST","Filter":"ALL"}
		})
		request.success(function(res){
			console.log(res);
			$scope.troopList = res.troopList;
		});
	}

	$scope.init();

	$scope.missionInsert = function(txt){
		var request = $http({
			method: "POST",
			url: "php/troop_management.php",
			data: { "Mode":"INSERT", "obj" : txt }
		})
		request.success(function (res){
			console.log(res);
			$scope.init();
		});
	}

	$scope.missionPersonal = function(MissionID){
		var request = $http({
			method: "POST",
			url: "php/mission_personal.php",
			data: { "Mode":"LIST","MissionID" : MissionID }
		})
		request.success(function (res) {
			console.log(res);
			$scope.MissionPersonalList = res.MissionPersonalList;
			$scope.troopHeader = res.troopHeader;
			$scope.troopList = '';
		});
	}

	$scope.FormNewShow = function(){
		$scope.FormNew = !$scope.FormNew;
	}

	$scope.mission_detail_modal = function(person, index){
		var currentPersonIndex = index;
		var request = $http({
			method: "POST",
			url: "php/troop_management.php",
			data: { 'Mode': "VIEW", 'ID': person.ID }
		})
		request.success(function (res) {
            var modalInstance = $uibModal.open({
				backdrop: 'static',
				keyboard: false,
				animation: true,
				templateUrl: 'PersonalDetailModalTemplate.html',
				controller: 'PersonalDetailModalCtrl',
				resolve: {
					PersonalDetails: function() {
						return res;
					}
				}
			});
			modalInstance.result.then(function (modal_person) {
				// Check whether the Person Details got edit or not
				if(modal_person != undefined){
					console.log(modal_person);
					angular.extend($scope.PersonalRecord[currentPersonIndex], modal_person);
				}
			});
		});
	}

});