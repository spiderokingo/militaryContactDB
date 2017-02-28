// JavaScript Document

app.controller('reportCtrl', function($scope, $http, $rootScope, toaster, myConfig) {

	$scope.init = function () {
    $scope.report = {};
    $scope.reportList = [];

    $scope.reporter = $rootScope.user.FirstName + " " + $rootScope.user.LastName;
    $scope.Company = $rootScope.user.Company;
    $scope.PrivateTotal = 250;
    $scope.PrivateTotalLeft = $scope.PrivateTotal;
    $scope.AbsentTotal = 0;

    $scope.dateSetup();
    $scope.getReportList();
	}

  $scope.getReportList = function(){
    var request = $http({
			method: "POST",
			url: "php/PersonalReport.php",
			data: { 'Mode': "VIEW", 'Company': $scope.Company }
		})
		request.success(function (res) {
      console.log(res);
      $scope.res = res;
      $scope.obj = res.PersonalReport;
      $scope.reportList = res.PersonalReport.DistributionList;
      
      $scope.PrivateTotal = parseInt($scope.obj.PrivateTotal);
      $scope.calculateTotal();
		});
  }

  $scope.dateSetup = function(){
    var dateObj = new Date();
    var month = dateObj.getMonth() + 1; //months from 1-12
    var day = dateObj.getDate();
    var year = dateObj.getFullYear()+543; //convert to พ.ศ.
    
    $scope.currentDate = day + " " + myConfig.ThaiMonth[month] + " " + year;
  }

  $scope.calculateTotal = function(){
    $scope.AbsentTotal = 0;
    angular.forEach($scope.reportList, function(val){
      if(val.Value != null) {
        $scope.AbsentTotal += parseInt(val.Value, 10);

      }
    });
    $scope.PrivateTotalLeft = $scope.obj.PrivateTotal - $scope.AbsentTotal;
  }

  $scope.save = function(){
    if($scope.PrivateTotalLeft < 0) {
      toaster.pop("error","กรุณาตรวจสอบตัวเลขใหม่อีกครั้ง");
      return;
    }
    var mode = $scope.res.Status == "success" ? "UPDATE": "INSERT";
    $http({
			method: "POST",
			url: "php/PersonalReport.php",
			data: { 
        'Mode': mode, 
        'ID': $scope.obj.ID,
        'COTotal': $scope.obj.COTotal,
        'NCOTotal': $scope.obj.NCOTotal,
        'PrivateTotal': $scope.obj.PrivateTotal,
        'DistributionList': $scope.obj.DistributionList,
        'UserReport':$rootScope.user.PersonalID,
        'Company': $scope.Company
      },
			headers: { 'Content-Type': 'application/json' }
		}).then(function (res) {
      console.log(res);
      if(res.statusText == "OK"){
			  toaster.pop("success","บันทึกสำเร็จ");
        $scope.res.Status = "success";
        $scope.obj.ID = res.ID;
      }
      else
        toaster.pop("error","ไม่สามารถบันทึกได้");
		});
  }

	$scope.init();
});