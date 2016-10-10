// JavaScript Document

app.controller('withdrawCtrl', function($scope, focus, $rootScope, $http, toaster) {

	$scope.init = function () {
		$scope.isEditMode = false;
		$scope.initailObj = {
			isIdentityInput: true,
			isIdentityShow: false,
			isWeaponInput: false,
			isWeaponShow: false,
			iden: "",
			weapon: "",
		};
		$scope.withdrawList = [angular.copy($scope.initailObj)];
	}

	$scope.IndentitySubmit = function(item){
		var request = $http({
			method: "POST",
			url: "php/getQR.php",
			data: { 'Mode': "USER", 'IdentityID': item.iden}
		});

		request.success(function (res) {
			if(res.result){
				angular.merge(item, res);
				item.userImage = res.ImageFullPath;
				item.isIdentityInput = false;
				item.isIdentityShow = true;
				if(!item.isWeaponShow){
					item.isWeaponInput = true;
				}else{
					$scope.postWithdrawToServer(item);
				}
					
			}else{
				toaster.pop("error",res.message);
			}
		});
	}

	$scope.WeaponSubmit = function(item){

		//POST to server, request weapon information
		var request = $http({
			method: "POST",
			url: "php/getQR.php",
			data: { 'Mode': "WEAPON", 'WeaponNumber': item.weapon}
		});

		request.success(function (res) {
			if(res.result){
				angular.merge(item, res);
				item.itemImage = res.ImageFullPath;
				item.isWeaponInput = false;
				item.isWeaponShow = true;
				
				$scope.postWithdrawToServer(item);
				
			}else{
				//Cannot get Weapon details
				toaster.pop("error",res.message);
			}
		});
	}

	$scope.postWithdrawToServer = function(item){
		//POST to server, withdraw an item
		var withdrawRequest = $http({
			method: "POST",
			url: "php/getQR.php",
			data: { 'Mode': "WITHDRAW", 
					'WithdrawOperator': $rootScope.user.PersonalID,
					'PersonalID': item.PersonalID,
					'IdentityID': item.IdentityID,
					'WeaponID': item.WeaponID,
					'WeaponNumber': item.WeaponNumber,}
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
					
					//Hide text field
					if(!obj.isWeaponShow)
						obj.isWeaponInput = false;
					break;
				case 'WEAPON':
					obj.isWeaponInput = true;
					obj.isWeaponShow = false;
					break;
			}
		});

	}

	$scope.init();

});