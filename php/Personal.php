<?
session_start();
ob_start();

//if($_COOKIE["UsernameInfantry43"] == ""){
//	exit();
//}

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$postData = file_get_contents("php://input");
$data = json_decode($postData);

//----- Path เก็บรูป -----------------------------------
$ImagePath = "personal_image/";

$outp = "";

include ("db_connect.php");
$conn = new mysqli($host, $username, $password, $database);

$result = $conn->query("SET NAMES UTF8");

//-----  List Record  -----------------------------------------------------------------------------------
if($data->Mode == "LIST"){
	$Amount = $data->Amount;
	$Start = ($Amount*$data->Page)-$Amount;

	$sql = "SELECT * FROM personal ";

	//---- ตรวจสอบถ้า SearchText ไม่เท่ากับค่าว่าง ให้ทำการ Search -----------------
	if($data->SearchText!=""){
		$sql .= "WHERE Name LIKE '%".$data->SearchText."%' or Sername LIKE '%".$data->SearchText."%'";
	}
	$result = $conn->query($sql);
	$Num_Rows = $result->num_rows;

	$sql .= "ORDER BY PersonalID ASC LIMIT $Start , $Amount";
	$result = $conn->query($sql);

	while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    	if ($outp != "") {$outp .= ",";}
    	$outp .= '{"ID":"'.$rs["PersonalID"].'"';
		$outp .= ',"ImageFullPath":"'.$ImagePath.$rs["Picture"].'"';
		$outp .= ',"TitleName":"'. $rs["TitleName"].'"';
		$outp .= ',"FirstName":"'. $rs["Name"].'"';
		$outp .= ',"LastName":"'. $rs["Sername"].'"';
		$outp .= ',"Company":"'. $rs["Company"].'"';
		$outp .= '}';
	}

$outp ='{"PersonalRecord":['.$outp.'],"TotalItems":"'.$Num_Rows.'"}';
}

//-----  Personal  Details  -----------------------------------------------------------------------------------
if($data->Mode == "VIEW"){

$result = $conn->query("SELECT * FROM personal WHERE PersonalID='".$data->ID."'");
$outp = "";

$rs = $result->fetch_array(MYSQLI_ASSOC);
    $outp .= '{"ID":"'.$rs["PersonalID"].'"';
	$outp .= ',"ImageFullPath":"'.$ImagePath.$rs["Picture"].'"';
	$outp .= ',"ImageName":"'.$rs["Picture"].'"';
	$outp .= ',"CitizenID":"'.$rs["CitizenID"].'"';
	$outp .= ',"TitleName":"'. $rs["TitleName"].'"';
	$outp .= ',"FirstName":"'. $rs["Name"].'"';
	$outp .= ',"LastName":"'. $rs["Sername"].'"';
	$outp .= ',"NickName":"'. $rs["NickName"].'"';
	$outp .= ',"BirthDay":"'. $rs["BirthDay"].'"';
	$outp .= ',"BloodGroup":"'. $rs["BloodGroup"].'"';

	//------------ Mititary Data -----------------------------------------
		$resultMilitary = $conn->query("SELECT * FROM personal_army WHERE PersonalID='".$rs["PersonalID"]."'");
		$outp3 = "";
		$rsMilitary = $resultMilitary->fetch_array(MYSQLI_ASSOC);
		
		$outp .= ',"TbArmyID":"'. $rsMilitary["ID"].'"';
		$outp .= ',"MilitaryID":"'. $rsMilitary["MilitaryID"].'"';
		$outp .= ',"Position":"'. $rsMilitary["Position"].'"';
		$outp .= ',"Company":"'. $rsMilitary["Company"].'"';
		
	//------------ Phone Number List -----------------------------------------
		$result2 = $conn->query("SELECT * FROM personal_phone WHERE PersonalID='".$rs["PersonalID"]."'");
		$outp2 = "";
		while($rs2 = $result2->fetch_array(MYSQLI_ASSOC)) {
			if ($outp2 != "") {$outp2 .= ",";}
			$outp2 .= '{"ID":"'.$rs2["ID"].'","PhoneNumber":"'.$rs2["PhoneNumber"].'","PhoneProvider":"'.$rs2["PhoneProvider"].'"}';
		}
	$outp .= ',"PhoneNumberList":['.$outp2.']';

		
	//------------ Address List -----------------------------------------
		$result3 = $conn->query("SELECT * FROM personal_address WHERE PersonalID='".$rs["PersonalID"]."'");
		$outp3 = "";
		while($rs3 = $result3->fetch_array(MYSQLI_ASSOC)) {
			if ($outp3 != "") {$outp3 .= ",";}
			$outp3 .= '{"Category":"'.$rs3["ID"].'"';
			$outp3 .= ',"HouseNumber":"'.$rs3["HouseNumber"].'"';
			$outp3 .= ',"Moo":"'.$rs3["Moo"].'"';
			$outp3 .= ',"Lane":"'.$rs3["Lane"].'"';
			$outp3 .= ',"Road":"'.$rs3["Road"].'"';
			$outp3 .= ',"SubDistrict":"'.$rs3["SubDistrict"].'"';
			$outp3 .= ',"District":"'.$rs3["District"].'"';
			$outp3 .= ',"Province":"'.$rs3["Province"].'"';
			$outp3 .= ',"PostCode":"'.$rs3["PostCode"].'"}';
		}
	$outp .= ',"Address":['.$outp3.']';

	$outp .= '}';
}

//----- Personal  Update  -----------------------------------------------------------------------------------
if($data->Mode == "UPDATE"){

	$sql = "UPDATE personal SET ";
	$sql .= "Picture='".$data->obj->ImageName."'";
	$sql .= ", TitleName='".$data->obj->TitleName."'";
	$sql .= ", Name='".$data->obj->FirstName."'";
	$sql .= ", Sername='".$data->obj->LastName."'";
	$sql .= ", NickName='".$data->obj->NickName."'";
	$sql .= ", BirthDay='".$data->obj->BirthDay."'";
	$sql .= ", BloodGroup='".$data->obj->BloodGroup."'";
	$sql .= ", Company='".$data->obj->Company."'";
	$sql .= " WHERE PersonalID='".$data->obj->ID."'";

if ($conn->query($sql) === TRUE) {
    $outp = '{"result":"success","message":"updated successfully"}';
} else {
    $outp = '{"result":"False","message":"Error updating record '. $conn->error.'"';
}

	//------- PhoneNumber Update ------------------------
	$PhoneNumberList = $data->PhoneNumberList;
	


}

//-----------------------------------------------------------------------------------------------------------

$conn->close();

echo($outp);
?>