
// create the controller and inject Angular's $scope
app.controller('loginController', function($scope, $location, localStorageService, toaster, $http, $state, $rootScope) {

    $scope.user = {};
    $scope.isLoginFailed = false;

    $scope.init = function () {
        // Check Login state and user permission
        if($rootScope.user != null){
                $state.go('main');
        }
    }

    $scope.login = function(){

        var request = $http({
			method: "POST",
			url: "php/loginValidate.php",
            data: {'Mode': "LOGIN", 'Username': $scope.user.identityId, 'Password': $scope.user.militaryId},
			headers: { 'Content-Type': 'application/json' }
		}).then(function (res) {
			$scope.res = res.data;
            console.log($scope.res);

            if($scope.res.result){
                $state.go('main');
                $scope.setLocalStorage($scope.res);
                // switch($scope.res.Permission){
                //     case 'ADMIN':
                //         $state.go('main');
                //         $scope.setLocalStorage($scope.res);
                //         break;
                //     case 'USER':
                //         $state.go('personalcontact');
                //         $scope.setLocalStorage($scope.res);
                //         break;
                //     default:
                //         toaster.pop('error', 'No Permission assigned');
                //         break;
                // }
            }else{
                $scope.setLoginFailed(true);
                toaster.pop('error', $scope.res.message);
            }
            
        },function (data) {
            toaster.pop("error", "Connect to the server error");
        });
    }

    $scope.setLoginFailed = function (bool) {
        $scope.isLoginFailed = bool;
    }

    $scope.setLocalStorage = function (data) {
        localStorageService.set('userObj',angular.toJson(data));
    }

    $scope.init();
});