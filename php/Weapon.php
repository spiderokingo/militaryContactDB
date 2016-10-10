<?
session_start();
ob_start();

//if($_COOKIE["UsernameInfantry43"] == ""){
//	exit();
//}

// header("Access-Control-Allow-Origin: *");
// header("Content-Type: application/json; charset=UTF-8");

if(isset($_POST["Mode"]) == false){
    $_POST = json_decode(file_get_contents('php://input'), true);
}

//----- Path เก็บรูป -----------------------------------
$ImagePath = "images/";
$time = date("Y-m-d H:i:s");

$outp = "";

include ("db_connect.php");
$conn = new mysqli($host, $username, $password, $database);

$result = $conn->query("SET NAMES UTF8");

//-----  List Record  -----------------------------------------------------------------------------------
if($_POST["Mode"] == "LIST"){
	$Amount = $_POST["Amount"];
	$Start = ($Amount*$_POST["Page"])-$Amount;

	$sql = "SELECT * FROM weapon WHERE WeaponNumber LIKE '%".$_POST["SearchText"]."%' and WeaponType LIKE '%".$_POST["WeaponType"]."%' and WeaponCompany LIKE '%".$_POST["WeaponCompany"]."%'";

	$result = $conn->query($sql);
	$Num_Rows = $result->num_rows;

	$sql .= "ORDER BY WeaponID ASC LIMIT $Start , $Amount";
	$result = $conn->query($sql);

	while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    	if ($outp != "") {$outp .= ",";}
    	$outp .= '{"ID":"'.$rs["WeaponID"].'"';
		$outp .= ',"ImageFullPath":"'.$ImagePath.$rs["WeaponType"].".png".'"';
		$outp .= ',"WeaponNumber":"'.$rs["WeaponNumber"].'"';
		$outp .= ',"WeaponType":"'.$rs["WeaponType"].'"';
		$outp .= ',"WeaponCompany":"'. $rs["WeaponCompany"].'"';
		$outp .= ',"WeaponStatus":"'. $rs["WeaponStatus"].'"';
		$outp .= '}';
	}

$outp ='{"WeaponRecord":['.$outp.'],"TotalItems":"'.$Num_Rows.'"}';
}

//-----  Personal  Details  -----------------------------------------------------------------------------------
if($_POST["Mode"] == "VIEW"){

$result = $conn->query("SELECT * FROM weapon WHERE WeaponID='".$_POST["ID"]."'");
$outp = "";

$rs = $result->fetch_array(MYSQLI_ASSOC);
    $outp .= '{"ID":"'.$rs["WeaponID"].'"';
	$outp .= ',"ImageFullPath":"'.$ImagePath.$rs["WeaponType"].".png".'"';
	$outp .= ',"WeaponNumber":"'.$rs["WeaponNumber"].'"';
	$outp .= ',"WeaponType":"'.$rs["WeaponType"].'"';
	$outp .= ',"WeaponCompany":"'. $rs["WeaponCompany"].'"';
	$outp .= ',"WeaponDetails":"'. $rs["WeaponDetails"].'"';
	$outp .= ',"WeaponStatus":"'. $rs["WeaponStatus"].'"';

	//------------ Mititary Data -----------------------------------------
		$resultWithdraw = $conn->query("SELECT * FROM withdraw WHERE WeaponID='".$rs["WeaponID"]."' ORDER BY WithdrawID ASC");
		$outp2 = "";
		while($rs2 = $resultWithdraw->fetch_array(MYSQLI_ASSOC)) {
			if ($outp2 != "") {$outp2 .= ",";}
			$outp2 .= '{"ID":"'.$rs2["WithdrawID"].'"';
			$outp2 .= ',"WithdrawTime":"'.$rs2["WithdrawTime"].'"';
				$resultWP = $conn->query("SELECT * FROM personal WHERE PersonalID='".$rs2["PersonalID"]."'");
				$rsWP = $resultWP->fetch_array(MYSQLI_ASSOC);
				$WithdrawPersonal=$rsWP["TitleName"]." ".$rsWP["FirstName"]."  ".$rsWP["LastName"];
			$outp2 .= ',"WithdrawPersonal":"'.$WithdrawPersonal.'"';
			$outp2 .= ',"Mission":"'.$rs2["Mission"].'"';
			$outp2 .= ',"ReturnTime":"'.$rs2["ReturnTime"].'"}';
		}
	$outp .= ',"WithdrawList":['.$outp2.']';

	$outp .= '}';
}

//----- Personal  Update  -----------------------------------------------------------------------------------
if($_POST["Mode"] == "UPDATE"){
	$obj = $_POST["obj"];

	$sql = "UPDATE personal SET ";
	$sql .= "IdentityID='".$obj["IdentityID"]."'";
	$sql .= ", ImageName='".$obj["ImageName"]."'";
	$sql .= ", TitleName='".$obj["TitleName"]."'";
	$sql .= ", FirstName='".$obj["FirstName"]."'";
	$sql .= ", LastName='".$obj["LastName"]."'";
	$sql .= ", NickName='".$obj["NickName"]."'";
	$sql .= ", BirthDay='".$obj["BirthDay"]."'";
	$sql .= ", BloodGroup='".$obj["BloodGroup"]."'";
	$sql .= " WHERE PersonalID='".$obj["ID"]."'";

	$sql2 = "UPDATE personal_military SET ";
	$sql2 .= "MilitaryID='".$obj["MilitaryID"]."'";
	$sql2 .= ",Position='".$obj["Position"]."'";
	$sql2 .= ",Company='".$obj["Company"]."'";
	$sql2 .= " WHERE ID='".$obj["TbArmyID"]."'";
	$statusMilitary = ($conn->query($sql2) === TRUE? "Success":"False");

	//------- Address Update -------//
	$AddressList = $obj["Address"];
	$Address_length = count($AddressList);

	for($i=0;$i<$Address_length;$i++){
		switch($AddressList[$i]["Mode"]){
			case "EDIT" :
				$sql_Add = "UPDATE personal_address SET ";
				$sql_Add .= "HouseNumber='".$AddressList[$i]["HouseNumber"]."'";
				$sql_Add .= ",Moo='".$AddressList[$i]["Moo"]."'";
				$sql_Add .= ",Lane='".$AddressList[$i]["Lane"]."'";
				$sql_Add .= ",Road='".$AddressList[$i]["Road"]."'";
				$sql_Add .= ",SubDistrict='".$AddressList[$i]["SubDistrict"]."'";
				$sql_Add .= ",District='".$AddressList[$i]["District"]."'";
				$sql_Add .= ",Province='".$AddressList[$i]["Province"]."'";
				$sql_Add .= ",PostCode='".$AddressList[$i]["PostCode"]."'";
				$sql_Add .= ",DateTime='".$time."'";
				$sql_Add .= " WHERE ID='".$AddressList[$i]["ID"]."'";
				$statusAddress = "Edit ".($conn->query($sql_Add) === TRUE? "Success":"False");
				break;
			case "INS" :
				if(empty($AddressList[$i]["HouseNumber"]) and empty($AddressList[$i]["Moo"]) and empty($AddressList[$i]["Lane"]) and empty($AddressList[$i]["Road"]) and empty($AddressList[$i]["SubDistrict"]) and empty($AddressList[$i]["District"]) and empty($AddressList[$i]["Province"]) and empty($AddressList[$i]["PostCode"])){
					$statusAddress="none";
				}else{
					$sql_Add = "INSERT INTO personal_address ";
					$sql_Add .= "(PersonalID,HouseNumber,Moo,Lane,Road,SubDistrict,District,Province,PostCode,DateTime) ";
					$sql_Add .= "VALUES ";
					$sql_Add .= "('".$obj["ID"]."','".$AddressList[$i]["HouseNumber"]."'";
					$sql_Add .= ",'".$AddressList[$i]["Moo"]."','".$AddressList[$i]["Lane"]."'";
					$sql_Add .= ",'".$AddressList[$i]["Road"]."','".$AddressList[$i]["SubDistrict"]."'";
					$sql_Add .= ",'".$AddressList[$i]["District"]."','".$AddressList[$i]["Province"]."'";
					$sql_Add .= ",'".$AddressList[$i]["PostCode"]."','".$time."')";
					$statusAddress = "Insert ".($conn->query($sql_Add) === TRUE? "Success":"False");
				}
				break;
			case "DEL" :
				$sql_Add = "DELETE FROM personal_address WHERE ID='".$AddressList[$i]["ID"]."'";
				$statusAddress = "Delete ".($conn->query($sql_Add) === TRUE? "Success":"False");
				break;
		}
	}

	//------- PhoneNumber Update -------//
	$PhoneNumberList = $obj["PhoneNumberList"];
	$PNL_length = count($PhoneNumberList);

	for($i=0;$i<$PNL_length;$i++){
		switch($PhoneNumberList[$i]["Mode"]){
			case "EDIT" :
				$sql_PNL = "UPDATE personal_phone SET ";
				$sql_PNL .= "PhoneNumber='".$PhoneNumberList[$i]["PhoneNumber"]."'";
				$sql_PNL .= ", PhoneProvider='".$PhoneNumberList[$i]["PhoneProvider"]."'";
				$sql_PNL .= ", DateTime='".$time."'";
				$sql_PNL .= " WHERE ID='".$PhoneNumberList[$i]["ID"]."'";
				$statusPhoneNumber = "Edit ".($conn->query($sql_PNL) === TRUE? "Success":"False");
				break;
			case "INS" :
				if($PhoneNumberList[$i]["PhoneNumber"]!=""){
				$sql_PNL = "INSERT INTO personal_phone ";
				$sql_PNL .= "(PersonalID,PhoneNumber,PhoneProvider,DateTime) ";
				$sql_PNL .= "VALUES ";
				$sql_PNL .= "('".$obj["ID"]."','".$PhoneNumberList[$i]["PhoneNumber"]."','".$PhoneNumberList[$i]["PhoneProvider"]."','".$time."')";
				$statusPhoneNumber = "Insert ".($conn->query($sql_PNL) === TRUE? "Success":"False");
				}else{$statusPhoneNumber="none";}
				break;
			case "DEL" :
				$sql_PNL = "DELETE FROM personal_phone WHERE ID='".$PhoneNumberList[$i]["ID"]."'";
				$statusPhoneNumber = "Delete ".($conn->query($sql_PNL) === TRUE? "Success":"False");
				break;
		}
	}

	//------- Images Update -------//
//		$resultImg = $conn->query("SELECT * FROM personal WHERE PersonalID='".$rs["PersonalID"]."'");
//		$rsImg = $resultImg->fetch_array(MYSQLI_ASSOC);
//		$ImageAll = $rsImg["ImageAll"];



	if ($conn->query($sql) === TRUE){
    	$outp = '{"result":"success","message":"updated successfully","statusUpdate":[{"Mititary":"'.$statusMititary.'","Address":"'.$statusAddress.'","PhoneNumber":"'.$statusPhoneNumber.'"}]}';
	} else {
    	$outp = '{"result":"False","message":"Error updating record '. $conn->error.'"';
	}

}

//-----------------------------------------------------------------------------------------------------------

$conn->close();

echo($outp);
?>