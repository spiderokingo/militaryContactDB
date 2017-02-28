// JavaScript Document

app.controller('homeCtrl', function($scope, focus, $rootScope, $http, toaster, myConfig) {

	$scope.init = function () {
    $scope.isDetails = false;
    $scope.CompanyNumberInitial();
    $scope.PrivateDetailsInitial();
    $scope.getReportList();

    $scope.StatusList = [];

    var dateObj = new Date();
    var month = dateObj.getMonth() + 1; //months from 1-12
    var day = dateObj.getDate();
    var year = dateObj.getFullYear()+543; //convert to พ.ศ.
    
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
      console.log(res);
      $scope.res = res;

      $scope.setupPersonList();
      $scope.calculateTotal();
		});
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

  $scope.setupPersonList = function(){
    angular.forEach($scope.res.PersonalList, function(resVal){
      switch (resVal.Company) {
        case "ร้อย.สสก.":
          $scope.companyNumberList[2].CompanyAssist = parseInt(resVal.Private.PrivateTotal);
          break;
        case "ร้อย.สสช.":
          $scope.companyNumberList[2].CompanySupport = parseInt(resVal.Private.PrivateTotal);
          break;
        case "ร้อย.อวบ.ที่ 1":
          $scope.companyNumberList[2].Company1 = parseInt(resVal.Private.PrivateTotal);
          break;
        case "ร้อย.อวบ.ที่ 2":
          $scope.companyNumberList[2].Company2 = parseInt(resVal.Private.PrivateTotal);
          break;
        case "ร้อย.อวบ.ที่ 3":
          $scope.companyNumberList[2].Company3 = parseInt(resVal.Private.PrivateTotal);
          break;
        default:
          break;
      }
    });
  }

  $scope.setupPrivateDetails = function(){
    angular.forEach($scope.res.PersonalList, function(perVal){
      var tempCompany = "";
        switch (perVal.Company) {
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
          default:
            break;
      }

      //ใส่ค่าที่ยอดเดิมของแต่ละกองร้อย
      $scope.PrivateList[0][tempCompany] = parseInt(perVal.Private.PrivateTotal);

      //ใส่ยอดจำหน่ายตามหัวข้อ
      angular.forEach(perVal.Private.ContributionList, function(conVal, ind){
          $scope.PrivateList[ind+1][tempCompany] = parseInt(conVal.Value);
      });
    });

    $scope.calculateTotalContribution();
    $scope.calculateDetailsTotalLeft();
  }

  $scope.calculateDetailsTotalLeft = function(){
    //คำนวนยอด คงเหลือ
    var obj = angular.copy($scope.PrivateList[0]);
    obj.ContributeTitle = "คงเหลือ"

    //ค่าเริ่มต้นของจำหน่ายแต่ละประเภท
    var itemTotal = 0;
    angular.forEach($scope.PrivateList, function(val, ind){
      
      //รวมจำหน่ายของแต่ละประเภท
      $scope.PrivateList[ind].DistributionItemTotal = val.CompanyAssist + val.CompanySupport + val.Company1 + val.Company2 + val.Company3;

      //ยอดเดิม - ยอดรวมจำหน่าย = ยอดคงเหลือ
      if(val.ContributeTitle == "รวมจำหน่าย"){
        obj.CompanyAssist -= val.CompanyAssist;
        obj.CompanySupport -= val.CompanySupport;
        obj.Company1 -= val.Company1;
        obj.Company2 -= val.Company2;
        obj.Company3 -= val.Company3;
        obj.DistributionItemTotal = obj.CompanyAssist + obj.CompanySupport + obj.Company1 + obj.Company2 + obj.Company3;
      }
    });
    $scope.PrivateList.push(obj);
  }

  $scope.calculateTotalContribution = function(){
    //คำนวนยอด รวมจำหน่าย
    var obj = angular.copy($scope.PrivateList[1]);
    obj.ContributeTitle = "รวมจำหน่าย"
    angular.forEach($scope.PrivateList, function(val, ind){
      if(ind > 1){
        obj.CompanyAssist += val.CompanyAssist;
        obj.CompanySupport += val.CompanySupport;
        obj.Company1 += val.Company1;
        obj.Company2 += val.Company2;
        obj.Company3 += val.Company3;
      }
    });
    $scope.PrivateList.push(obj);
  }
      

  $scope.detailsOnClicked = function(type){
    $scope.isDetails = true;

    switch(type){
      case 2:
        $scope.setupPrivateDetails();
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
          Company3: 0,
          Total: 0
        }
      );
    });
  }

  $scope.CompanyNumberInitial = function(){
    // $scope.companyNumberList = [];
    // var TitleName = ["น.", "ส.", "พล"];
    // angular.forEach(TitleName, function(val, ind){
    //   $scope.companyNumberList.push(
    //     {
    //       TitleName: TitleName[ind],
    //       CompanyAssist: 0,
    //       CompanySupport: 0,
    //       Company1: 0,
    //       Company2: 0,
    //       Company3: 0
    //     }
    //   );
    // });
    $scope.companyNumberList = [
      {
        TitleName: "น",
        CompanyAssist: 11,
        CompanySupport: 0,
        Company1: 4,
        Company2: 4,
        Company3: 2
      },
      {
        TitleName: "ส.",
        CompanyAssist: 79,
        CompanySupport: 29,
        Company1: 36,
        Company2: 32,
        Company3: 32
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
  }
	$scope.init();

});