// JavaScript Document

app.controller('returnCtrl', function($scope, focus, $rootScope, $http, toaster, $uibModal) {

	$scope.init = function () {
		$scope.isEditMode = false;
		$scope.initailObj = {
			isWeaponInput: true,
			isWeaponShow: false,
			isIdentityInput: false,
			isIdentityShow: false,
			iden: "",
			weapon: "",
		};
		$scope.withdrawList = [angular.copy($scope.initailObj)];
	}

	$scope.WeaponSubmit = function(item){

		//POST to server, request weapon information
		var request = $http({
			method: "POST",
			url: "php/getDepositDetails.php",
			data: { 'Mode': "WEAPON", 'WeaponNumber': item.weapon}
		});

		request.success(function (res) {
			console.log(res);
			if(res.result){
				//in order to prevent Same Attribute of "PersonalID"
				res.withdrawPersonID = res.PersonalID;
				angular.merge(item, res);
				item.itemImage = res.ImageFullPath;
				item.isWeaponInput = false;
				item.isWeaponShow = true;
				
				if(item.isIdentityShow){
					$scope.postReturnToServer(item);
				}else{
					item.isIdentityInput = true;
				}
				
			}else{
				//Cannot get Weapon details
				toaster.pop("error",res.message);
			}
		});
	}

	$scope.IndentitySubmit = function(item){
		var request = $http({
			method: "POST",
			url: "php/getQR.php",
			data: { 'Mode': "USER", 'IdentityID': item.iden}
		});

		request.success(function (res) {
			if(res.result){
				if(item.withdrawPersonID != res.PersonalID){
					angular.merge(item, res);
					item.userImage = res.ImageFullPath;
					item.isIdentityInput = false;
					item.isIdentityShow = true;

					var message = "อาวุธหมายนี้ถูกเบิกออกโดย" + item.PersonalName + "และจะส่งคืนโดย"
                                + res.TitleName + " " + res.FirstName + "  " + res.LastName;
					var modalInstance = $uibModal.open({
					animation: true,
					templateUrl: 'ReturnWarningModalTemplate.html',
					controller: 'ReturnModalCtrl',
					resolve: {
						obj:{
							PersonalName: item.PersonalName,
							res: res
						}
					}
					});
					modalInstance.result.then(function (goodResult) {
						if(goodResult)
							$scope.postReturnToServer(item);
					});
				}else{
					$scope.postReturnToServer(item);
				}

			}else{
				toaster.pop("error",res.message);
			}
		});
	}

	$scope.postReturnToServer = function(item){
		console.log(item)
		//POST to server, withdraw an item
		var withdrawRequest = $http({
			method: "POST",
			url: "php/getDepositDetails.php",
			data: { 'Mode': "DEPOSIT", 	
					'ReturnOperator': $rootScope.user.PersonalID,
					'PersonalID': item.PersonalID,
					'WeaponID': item.WeaponID,
					'WithdrawID': item.WithdrawID,}
		});
		withdrawRequest.success(function (withDrawRes) {
			console.log(withDrawRes);
			if(withDrawRes.result){
				angular.merge(item, withDrawRes);
				toaster.pop("success",withDrawRes.message);
				//Do not create another one if just edit
				if(!$scope.isEditMode)
					$scope.withdrawList.push(angular.copy($scope.initailObj));
			}else{
				//Fails to withdraw an item
				toaster.pop("error",withDrawRes.message);
			}
		});
	}

	//Edit user
	$scope.onEdit = function(obj, type){
		//POST to server, delete the record
		var withdrawRequest = $http({
			method: "POST",
			url: "php/getQR.php",
			data: { 'Mode': "DELETE", 
					'WithdrawID': obj.WithdrawID,
					'PersonalID': obj.PersonalID,
					'WeaponID': obj.WeaponID,}
		});
		withdrawRequest.success(function (deleteRes) {
			console.log("Delete success");
			$scope.isEditMode = true;

			switch (type) {
				case 'IDENTITY':
					
					obj.isIdentityInput = true;
					obj.isIdentityShow = false;
					break;
				case 'WEAPON':
					obj.isWeaponInput = true;
					obj.isWeaponShow = false;
					
					//Hide text field
					if(obj.isWeaponInput)
						obj.isWeaponInput = false;
					break;
			}
		});

	}

	$scope.init();

});

// 
// START Weapon Detail Modal Controller
// 
app.controller('ReturnModalCtrl', function ($scope, $uibModalInstance, $http, obj) {

	$scope.init = function () {
		$scope.obj = obj;
	}

	$scope.save = function(){
		$uibModalInstance.close(true);
  	};

	$scope.init();
});

