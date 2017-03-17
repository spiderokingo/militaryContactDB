// JavaScript Document

app.controller('homeCtrl', function($scope, focus, $rootScope, $http, toaster, myConfig) {

	$scope.init = function () {
    $scope.isDetails = false;
    $scope.CompanyNumberInitial();
    $scope.PrivateDetailsInitial();
    $scope.getReportList();

    $scope.StatusList = [];

    var dateObj = new Date();
    var month = dateObj.getUTCMonth() + 1; //months from 1-12
    var day = dateObj.getUTCDate();
    var year = dateObj.getUTCFullYear()+543; //convert to พ.ศ.
    
    $scope.currentDate = day + " " + myConfig.ThaiMonth[month] + " " + year;

    $scope.isAdmin = myConfig.Permission[$rootScope.user.Permission] == "Administrator";

	}

  $scope.getReportList = function(){
    var request = $http({
			method: "POST",
			url: "php/NewsFeed.php",
			data: { 'Mode': "LIST"}
		})
		request.success(function (res) {
      console.log("Post NewsFeed.php");
      console.log(res);
      $scope.res = res;

      $scope.setupPersonRecord();
      $scope.calculatePersonAmountTotal();

      $scope.setupPrivateDetails();
		});
  }

  $scope.setupPersonRecord = function(){
    angular.forEach($scope.res.PersonalReport, function(resVal){
      //แยกแยะกองร้อยต่างๆ
      var tempCompany = $scope.getCompanyVar(resVal.Company);

      $scope.companyNumberList[0][tempCompany] = parseInt(resVal.COTotal);
      $scope.companyNumberList[1][tempCompany] = parseInt(resVal.NCOTotal);
      $scope.companyNumberList[2][tempCompany] = parseInt(resVal.PrivateTotal);
      
    });
  }

  $scope.setupPrivateDetails = function(){
    angular.forEach($scope.res.PersonalReport, function(perVal){
      //แยกแยะกองร้อยต่างๆ
      var tempCompany = $scope.getCompanyVar(perVal.Company);

      //ใส่ค่าที่ยอดเดิมของแต่ละกองร้อย
      $scope.PrivateList[0][tempCompany] = parseInt(perVal.PrivateTotal);

      //ใส่ยอดจำหน่ายตามหัวข้อ
      angular.forEach(perVal.DistributionList, function(disVal, ind){
          $scope.PrivateList[ind+1][tempCompany] = parseInt(disVal.Value);
      });
    });

    //คำนวนยอด "คงเหลือ"
    $scope.calculateDetailsTotalLeft();
  }

  $scope.getCompanyVar = function(companyInThai){
    var tempCompany = "";
      switch (companyInThai) {
        case "ร้อย.สสก.":
          tempCompany = "CompanyAssist";
          break;
        case "ร้อย.สสช.":
          tempCompany = "CompanySupport";
          break;
        case "ร้อย.อวบ.ที่ 1":
          tempCompany = "Company1";
          break;
        case "ร้อย.อวบ.ที่ 2":
          tempCompany = "Company2";
          break;
        case "ร้อย.อวบ.ที่ 3":
          tempCompany = "Company3";
          break;
    }
    return tempCompany;
  }

  //คำนวนยอดจำหน่าย และ คงเหลือ หน้า details
  $scope.calculateDetailsTotalLeft = function(){
    //คำนวนยอดจำหน่ายรวม
    var contibuteAssist = 0, contibuteSupport = 0, contibuteCompany1 = 0, contibuteCompany2 = 0, contibuteCompany3 = 0;
    angular.forEach($scope.PrivateList, function(val, ind){
      //ยกเว้นช่อง ยอดเดิม นอกนั้นเอามาบวกรวมทั้งหมด
      if(ind > 0){
        contibuteAssist += val.CompanyAssist;
        contibuteSupport += val.CompanySupport;
        contibuteCompany1 += val.Company1;
        contibuteCompany2 += val.Company2;
        contibuteCompany3 += val.Company3;
      }
    });
    $scope.PrivateList.push({
        ContributeTitle: "ยอดจำหน่าย",
        CompanyAssist: contibuteAssist, 
        CompanySupport: contibuteSupport,
        Company1: contibuteCompany1,
        Company2: contibuteCompany2,
        Company3: contibuteCompany3
      });

    //คำนวนยอดคงเหลือจาก ยอดเดิม - ยอดจำหน่าย
    var obj = angular.copy($scope.PrivateList[0]);
    $scope.PrivateList.push({
      ContributeTitle: "คงเหลือ",
        CompanyAssist: $scope.PrivateList[0].CompanyAssist - contibuteAssist, 
        CompanySupport: $scope.PrivateList[0].CompanySupport - contibuteSupport,
        Company1: $scope.PrivateList[0].Company1 - contibuteCompany1,
        Company2: $scope.PrivateList[0].Company2 - contibuteCompany2,
        Company3: $scope.PrivateList[0].Company3 - contibuteCompany3
    });
  }

  //คำนวนยอดจำนวนทหารทั้งหมด
  $scope.calculatePersonAmountTotal = function(){
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
      
  //กำหนด onClick เพื่อเข้าไปดูรายละเอียดการจำหน่ายของแต่ละประเภท
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

  //ตั้งค่าตารางของพลทหาร
  $scope.PrivateDetailsInitial = function(){
    $scope.PrivateList = [];
    var contributionTitle = ["ยอดเดิม", "ราชการ ร้อย.คทร.", "ราชการ ร้อย.รส.",
          "รวป.ค่าย","ชรก","นักกีฬา","บริการ", "ลา", "ขาด", "โทษ", "อื่นๆ"];
    angular.forEach(contributionTitle, function(val, ind){
      $scope.PrivateList.push(
        {
          ContributeTitle: contributionTitle[ind],
          CompanyAssist: 0, 
          CompanySupport: 0,
          Company1: 0,
          Company2: 0,
          Company3: 0
        }
      );
    });
  }

  //ตั้งค่าตั้งต้นสำหรับ obj ชื่อนำหน้า
  $scope.CompanyNumberInitial = function(){
    $scope.companyNumberList = [];
    var nameTitle = ["น.", "ส.", "พล"];
    angular.forEach(nameTitle, function(val, ind){
      $scope.companyNumberList.push(
        {
          TitleName: nameTitle[ind],
          CompanyAssist: 0, 
          CompanySupport: 0,
          Company1: 0,
          Company2: 0,
          Company3: 0
        }
      );
    });
  }

	$scope.init();

});