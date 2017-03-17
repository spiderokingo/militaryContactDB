// JavaScript Document

app.controller('learningCenterCtrl', function($scope, $http, $rootScope, toaster, myConfig) {

	$scope.init = function () {
    $scope.lectureHeader = "Lecture Part";
    
    $scope.examinationHeader = "Examination Part";
  }

	$scope.init();
});