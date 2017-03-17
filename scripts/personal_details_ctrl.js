
app.controller('personalController', function($scope, $http, $uibModal, $log, $rootScope, $state, localStorageService, $timeout) {

	$scope.init = function(){

		$scope.tel = "tel:"

		$scope.currentPage = 1;
		$scope.amount = 25;
		$scope.search = "";
		$scope.maxSize = 7;

		if($rootScope.user.Permission == 'ADMIN' && $rootScope.tabactive == 1){
			$scope.getPersonalList();
		}else{
			$scope.getContactList();
		}
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

	$scope.getContactList = function(){
		var request = $http({
			method: "POST",
			url: "php/getPersonalRecord.php",
			data: { 'Mode': "LIST", 'Page': $scope.currentPage, 'Amount': $scope.amount, 'SearchText': $scope.search}
		});

		request.success(function (res) {
			$scope.PersonalRecord = res.PersonalRecord;
			$scope.TotalItems = res.TotalItems;

			angular.forEach($scope.PersonalRecord, function(person) {
  			// person.ImageFullPath = "images/" + person.ImagePath;

				angular.forEach(person.PhoneNumberList, function(phone) {
					phone.noProvider = false;
					switch (phone.PhoneProvider) {
						case "DTAC":
							phone.ProviderLogo = "images/dtac.png"
							break;
						case "AIS":
							phone.ProviderLogo = "images/ais.png"
							break;
						case "TRUEMOVE":
							phone.ProviderLogo = "images/truemove.png"
							break;
						default:
							phone.noProvider = true;
					};
				});
			});
		});
	}

	$scope.personal_detail_modal = function(person, index){
		var currentPersonIndex = index;
		var request = $http({
			method: "POST",
			url: "php/Personal.php",
			data: { 'Mode': "VIEW", 'ID': person.ID }
		})
		request.success(function (res) {
            var modalInstance = $uibModal.open({
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

	$scope.userLogout = function(){
		$state.go('login');
		localStorageService.remove('userObj');
	}

	$scope.onSearch = function(){
		$scope.getPersonalList();
	}

	$scope.init();

});

// 
// START Personal Details Modal Controller
// 
app.controller('PersonalDetailModalCtrl', function ($scope, $uibModalInstance, PersonalDetails, $sce, Upload, $http, toaster) {

	var isEditSaved = false;
	$scope.init = function () {

		$scope.tel = "tel:";
		$scope.isEditPersonalDetails = false;
		$scope.person = angular.copy(PersonalDetails);
		$scope.getTitleNameList();

		console.log($scope.person);

		// $scope.Address = [
		// 	{
		// 		HouseNumber: "1/10003",
		// 		Moo: "1",
		// 		Lane: "",
		// 		Road: "",
		// 		SubDistrict: "บ้านคลอง",
		// 		District: "เมือง",
		// 		Province: "พิษณุโลก",
		// 		PostCode: "65000"
		// 	}
		// ];

		$scope.CompanyList = [
			{ CompanyName: 'บก.พัน' },
			{ CompanyName: 'ร้อย.สสก.' },
			{ CompanyName: 'ร้อย.สสช.' },
			{ CompanyName: 'ร้อย.อวบ.ที่ 1' },
			{ CompanyName: 'ร้อย.อวบ.ที่ 2' },
			{ CompanyName: 'ร้อย.อวบ.ที่ 3' }
		];

		$scope.PhoneProviderList = [
			{ PhoneProvider: 'DTAC', ProviderLogo: "images/dtac.png" },
			{ PhoneProvider: 'AIS', ProviderLogo: "images/ais.png" },
			{ PhoneProvider: 'TRUEMOVE', ProviderLogo: "images/truemove.png" }
		];
	}

	$scope.edit = function(){
		$scope.isEditPersonalDetails = true;
	}

	$scope.save = function(){
		$scope.isEditPersonalDetails = false;
		$http({
			method: "POST",
			url: "php/Personal.php",
			data: { 'Mode': "UPDATE", obj: $scope.person},
			headers: { 'Content-Type': 'application/json' }
		}).then(function (res) {
			toaster.pop("success","Saved Successful");
			isEditSaved = true;
		});
		
		angular.forEach($scope.person.PhoneNumberList, function(phone){
			if(phone.PhoneNumber == ""){
				phone.Mode = "DEL";
			}
		});
	}

	$scope.uploadImage = function (files) {
		if (files && files.length) {
			$scope.uploadPercentage = 0;
			var file = files[0];
			Upload.upload({
				url: 'php/uploadImage.php',
				data: { 'fileupload' : file, 'ID' : $scope.person.ID }
			}).then(function (res) {
				$scope.person.ImageFullPath = res.data.ImageFullPath;
				$scope.person.ImageName = res.data.ImageName;
			}, function (res) {
				console.log('Error status: ' + res.status);
			}, function (evt) {
				$scope.uploadPercentage = parseInt(100.0 * evt.loaded / evt.total);
			});
		}
	};

	$scope.onDropdownAction = function(data, type, event) {
		switch(type){
			case 'TITLE':
				data.TitleName = event.TitleShortName;
				break;
			case 'COMPANY':
				data.Company = event.CompanyName;
				break;
			case 'PHONE':
				angular.extend(data, event);
				break;
		}
		
	}

	$scope.getTitleNameList = function () {
		var request =	$http({
			method: "POST",
			url: "php/TitleNameList.php"
		})
		request.success(function (res) {
			$scope.TitleNameList = res.TitleNameList;
		});
	}

	$scope.addNumberContact = function () {
			$scope.person.PhoneNumberList.push({
				PhoneNumber: "",
				PhoneProvider: "DTAC",
				ProviderLogo: "images/dtac.png",
				Mode: "INS"
			});
	}

	$scope.addAddress = function () {
		$scope.person.Address.push(	{
			HouseNumber: "",
			Moo: "",
			Lane: "",
			Road: "",
			SubDistrict: "",
			District: "",
			Province: "",
			PostCode: "",
			Mode: "INS"
		});
	}

	$scope.deleteItem = function (item) {
		item.Mode = "DEL"
	}

	$scope.close = function(){
		if(isEditSaved)
			$uibModalInstance.close($scope.person);
		else
			$uibModalInstance.close();
  	};

	$scope.init();
});
