// JavaScript Document

app.controller('reportCtrl', function($scope, $http, $rootScope, toaster, myConfig) {

	$scope.init = function () {
    $scope.report = {};
    $scope.reportList = [];

    $scope.reporter = $rootScope.user.FirstName + " " + $rootScope.user.LastName;
    $scope.Company = $rootScope.user.Company;
    $scope.GrandTotal = 250;
    $scope.GrandTotalLeft = $scope.GrandTotal;
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
      
      $scope.GrandTotal = parseInt($scope.obj.GrandTotal);
      $scope.calculateTotal();
		});
  }

  $scope.dateSetup = function(){
    var dateObj = new Date();
    var month = dateObj.getUTCMonth() + 1; //months from 1-12
    var day = dateObj.getUTCDate();
    var year = dateObj.getUTCFullYear()+543; //convert to พ.ศ.
    
    $scope.currentDate = day + " " + myConfig.ThaiMonth[month] + " " + year;
  }

  $scope.calculateTotal = function(){
    $scope.AbsentTotal = 0;
    angular.forEach($scope.reportList, function(val){
      if(val.Value != null) {
        $scope.AbsentTotal += parseInt(val.Value, 10);

      }
    });
    $scope.GrandTotalLeft = $scope.obj.GrandTotal - $scope.AbsentTotal;
  }

  $scope.save = function(){
    if($scope.GrandTotalLeft < 0) {
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
        'GrandTotal': $scope.obj.GrandTotal,
        'DistributionList': $scope.obj.DistributionList,
        'UserReport':$rootScope.user.PersonalID,
        'Company': $scope.Company
      },
			headers: { 'Content-Type': 'application/json' }
		}).then(function (res) {
      console.log(res);
      if(res.statusText == "OK")
			  toaster.pop("success","บันทึกสำเร็จ");
      else
        toaster.pop("error","ไม่สามารถบันทึกได้");
		});
  }

	$scope.init();
});