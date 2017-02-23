// JavaScript Document

app.controller('homeCtrl', function($scope, focus, $rootScope, $http, toaster, myConfig) {

	$scope.init = function () {
    $scope.isDetails = false;
    $scope.getReportList();
    $scope.CompanyNumberPrepare();
    $scope.PrivateDetailsPrepare();

    $scope.StatusList = [];

    var dateObj = new Date();
    var month = dateObj.getUTCMonth() + 1; //months from 1-12
    var day = dateObj.getUTCDate();
    var year = dateObj.getUTCFullYear()+543; //convert to พ.ศ.
    
    $scope.currentDate = day + " " + myConfig.ThaiMonth[month] + " " + year;

	}

  $scope.getReportList = function(){
    var request = $http({
			method: "POST",
			url: "php/PersonalReport.php",
			data: { 'Mode': "LIST"}
		})
		request.success(function (res) {
      console.log(res);
      $scope.res = res.PersonalReport;

      angular.forEach($scope.res, function(resVal){
        switch (resVal.Company) {
          case "ร้อย.สสช.":
              var number = 0;
              angular.forEach(resVal.DistributionList, function(disVal){
                number += disVal.Value;
              });
            $scope.companyNumberList[2].CompanyAssist = number;
            break;
          default:
            break;
        }
      });
		});
  }

  $scope.detailsOnClicked = function(type){
    $scope.isDetails = true;

    switch(type){
      case 2:
        $scope.DetailsHeaderName = "พลทหาร" ;
        $scope.StatusList = $scope.PrivateList;
      break;
      default:
        $scope.isDetails = false;
      break;
    }
  }

  $scope.exitDetails = function(){
    $scope.isDetails = false;
  }

  $scope.PrivateDetailsPrepare = function(){
    $scope.PrivateList = [
      {
        Company: "ลา",
        GrandTotal: 0, 
        Leave: 0,
        Preset: 0,
        ServeCommander: 0,
        Others: 0
      },
      {
        Company: "ขาด",
        GrandTotal: 0, 
        Leave: 0,
        Preset: 0,
        ServeCommander: 0,
        Others: 0
      },
      {
        Company: "ป่วย",
        GrandTotal: 0, 
        Leave: 0,
        Preset: 0,
        ServeCommander: 0,
        Others: 0
      },
      {
        Company: "คทร.",
        GrandTotal: 0, 
        Leave: 0,
        Preset: 0,
        ServeCommander: 0,
        Others: 0
      },
      {
        Company: "รส.",
        GrandTotal: 0, 
        Leave: 0,
        Preset: 0,
        ServeCommander: 0,
        Others: 0
      }
    ];
  }

  $scope.CompanyNumberPrepare = function(){
    $scope.companyNumberList = [
      {
        TitleName: "น",
        CompanyAssist: 0,
        CompanySupport: 0,
        Company1: 0,
        Company2: 0,
        Company3: 0
      },
      {
        TitleName: "ส.",
        CompanyAssist: 0,
        CompanySupport: 0,
        Company1: 0,
        Company2: 0,
        Company3: 0
      },
      {
        TitleName: "พล",
        CompanyAssist: 0,
        CompanySupport: 0,
        Company1: 0,
        Company2: 0,
        Company3: 0
      }
    ];

    $scope.calculateTotal();
  }

  $scope.calculateTotal = function(){
    
    gtCompanyAssist = 0; gtCompanySupport = 0; gtCompany1 = 0; gtCompany2 = 0; gtCompany3 = 0; grandTotal = 0;

    angular.forEach($scope.companyNumberList, function(number, index) {
      number.GrandTotal = number.CompanyAssist + number.CompanySupport + number.Company1 + number.Company2 + number.Company3;
      gtCompanyAssist += number.CompanyAssist;
      gtCompanySupport += number.CompanySupport;
      gtCompany1 += number.Company1;
      gtCompany2 += number.Company2;
      gtCompany3 += number.Company3;
      grandTotal += number.GrandTotal;
    });

    $scope.companyNumberList.push({
       TitleName: "รวม",
        CompanyAssist: gtCompanyAssist,
        CompanySupport: gtCompanySupport,
        Company1: gtCompany1,
        Company2: gtCompany2,
        Company3: gtCompany3,
        GrandTotal: grandTotal
    })
  }
	$scope.init();

});