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
$ImagePath = "personal_image/";
$time = date("Y-m-d H:i:s");

$outp = "";

include ("db_connect.php");
$conn = new mysqli($host, $username, $password, $database);

$result = $conn->query("SET NAMES UTF8");

//-----  Personal  Details  -----------------------------------------------------------------------------------
if($_POST["Mode"] == "USER"){

$result = $conn->query("SELECT * FROM personal WHERE IdentityID='".$_POST["IdentityID"]."'");

	if ($result->num_rows == 1) {

	$outp = "";
	$rs = $result->fetch_array(MYSQLI_ASSOC);
		$outp .= '{"PersonalID":"'.$rs["PersonalID"].'"';
		$outp .= ',"ImageFullPath":"'.$ImagePath.$rs["ImageName"].'"';
		$outp .= ',"ImageName":"'.$rs["ImageName"].'"';
		$outp .= ',"IdentityID":"'.$rs["IdentityID"].'"';
		$outp .= ',"TitleName":"'. $rs["TitleName"].'"';
		$outp .= ',"FirstName":"'. $rs["FirstName"].'"';
		$outp .= ',"LastName":"'. $rs["LastName"].'"';

		//------------ Mititary Data -----------------------------------------
			$resultMilitary = $conn->query("SELECT * FROM personal_military WHERE PersonalID='".$rs["PersonalID"]."'");
			$outp3 = "";
			$rsMilitary = $resultMilitary->fetch_array(MYSQLI_ASSOC);
			
			$outp .= ',"Position":"'. $rsMilitary["Position"].'"';
			$outp .= ',"Company":"'. $rsMilitary["Company"].'"';
			$outp .= ',"result":true';
			
		$outp .= '}';
	}else{
		$outp = '{"result":false,"message":"ไม่พบข้อมูลบุคคล"}';
	}		
}

//-----  Weapon  Details  -----------------------------------------------------------------------------------
if($_POST["Mode"] == "WEAPON"){

$result = $conn->query("SELECT * FROM weapon WHERE WeaponNumber='".$_POST["WeaponNumber"]."'");

	if ($result->num_rows == 1) {
		$rs = $result->fetch_array(MYSQLI_ASSOC);

		if($rs["WeaponStatus"] != ""){
			$PerWD = $conn->query("SELECT * FROM personal WHERE PersonalID='".$rs["WeaponStatus"]."'");
			$rsWD = $PerWD->fetch_array(MYSQLI_ASSOC);
			$NameWD = $rsWD["TitleName"]." ".$rsWD["FirstName"]."   ".$rsWD["LastName"];
			$outp = '{"result":false,"message":"อาวุธหมายเลขนี้ถูกเบิกไปแล้ว โดย\n'.$NameWD.'"}';		
		}else{
			$outp = "";
			$ImageType=$rs["WeaponType"];
			include "imageWeapon.php";
			$outp .= '{"WeaponID":"'.$rs["WeaponID"].'"';
			$outp .= ',"WeaponNumber":"'.$rs["WeaponNumber"].'"';
			$outp .= ',"ImageFullPath":"'.$ImagePath.$ImageName.'"';
			$outp .= ',"WeaponType":"'.$rs["WeaponType"].'"';
			$outp .= ',"WeaponCompany":"'.$rs["WeaponCompany"].'"';
			$outp .= ',"result":true';
			$outp .= '}';
		}
	}else{
		$outp = '{"result":false,"message":"ไม่พบข้อมูลอาวุธ"}';
	}
}

//----- WithDraw  Update  -----------------------------------------------------------------------------------
if($_POST["Mode"] == "WITHDRAW"){

	$sql = "INSERT INTO withdraw ";
	$sql .= "(WithdrawTime,PersonalID,IdentityID,WeaponID,WeaponNumber,WithdrawDetails,WithdrawOperator) ";
	$sql .= "VALUES ";
	$sql .= "('".$time."','".$_POST["PersonalID"]."','".$_POST["IdentityID"]."'";
	$sql .= ",'".$_POST["WeaponID"]."','".$_POST["WeaponNumber"]."'";
	$sql .= ",'".$_POST["WithdrawDetails"]."','".$_POST["WithdrawOperator"]."'";
	$sql .= ")";

	$sql_Wp = "UPDATE weapon SET ";
	$sql_Wp .= "WeaponStatus='".$_POST["PersonalID"]."'";
	$sql_Wp .= " WHERE WeaponID='".$_POST["WeaponID"]."'";

	if ($conn->query($sql) === TRUE and $conn->query($sql_Wp) === TRUE){
    	$outp = '{"result":true,"message":"บันทึกการเบิกอาวุธ เรียบร้อย"}';
	}else{
    	$outp = '{"result":false,"message":"บันทึกไม่สำเร็จ"';
	}

}

//-----------------------------------------------------------------------------------------------------------

$conn->close();

echo($outp);
?>