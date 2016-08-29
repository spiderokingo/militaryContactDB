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
if($_POST["Mode"] == "WEAPON"){

$result = $conn->query("SELECT * FROM withdraw WHERE ReturnPers='' and WeaponNumber='".$_POST["WeaponNumber"]."'");

	if ($result->num_rows == 1) {

	$outp = "";
	$rs = $result->fetch_array(MYSQLI_ASSOC);
		$outp .= '{"WithdrawID":"'.$rs["WithdrawID"].'"';
		$outp .= ',"WithdrawTime":"'.$rs["WithdrawTime"].'"';
		$outp .= ',"PersonalID":"'.$rs["PersonalID"].'"';
		//------------ Personal Data -----------------------------------------
			$resultPer = $conn->query("SELECT * FROM personal WHERE PersonalID='".$rs["PersonalID"]."'");
			$rsPer = $resultPer->fetch_array(MYSQLI_ASSOC);
			$outp .= ',"PersonalName":"'.$rsPer["TitleName"].' '.$rsPer["FirstName"].'   '.$rsPer["LastName"].'"';
	// 		$outp .= ',"PersonalImageFullPath":"'.$ImagePath.$rsPer["ImageName"].'"';
	// 		$outp .= ',"Company":"'.$rsPer["Company"].'"';

	// //------------ Mititary Data -----------------------------------------
	// 		$resultMilitary = $conn->query("SELECT * FROM personal_military WHERE PersonalID='".$rs["PersonalID"]."'");
	// 		$rsMilitary = $resultMilitary->fetch_array(MYSQLI_ASSOC);
	// 		$outp .= ',"Position":"'. $rsMilitary["Position"].'"';
	// 		$outp .= ',"Company":"'. $rsMilitary["Company"].'"';

		$outp .= ',"WeaponID":"'.$rs["WeaponID"].'"';
		//------------ Weapon Data -----------------------------------------
			$resultWeapon = $conn->query("SELECT * FROM Weapon WHERE WeaponID='".$rs["WeaponID"]."'");
			$rsWeapon = $resultWeapon->fetch_array(MYSQLI_ASSOC);
			$outp .= ',"WeaponNumber":"'.$rsWeapon["WeaponNumber"].'"';
			$outp .= ',"WeaponType":"'.$rsWeapon["WeaponType"].'"';
			$outp .= ',"WeaponCompany":"'.$rsWeapon["WeaponCompany"].'"';
			$ImageType=$rsWeapon["WeaponType"];
			include "imageWeapon.php";
			$outp .= ',"ImageFullPath":"'.$ImagePath.$ImageName.'"';

		$outp .= ',"WithdrawDetails":"'.$rs["WithdrawDetails"].'"';
		$outp .= ',"result":true';
		$outp .= '}';
	}else{
		$outp = '{"result":false,"message":"ไม่พบข้อมูลอาวุธที่จะส่งคืน"}';
	}		
}

//----- Deposit  Update  -----------------------------------------------------------------------------------
if($_POST["Mode"] == "DEPOSIT"){

	$sql = "UPDATE withdraw SET ";
	$sql .= "ReturnTime='".$time."'";
	$sql .= ", ReturnPers='".$_POST["PersonalID"]."'";
	$sql .= ", ReturnOperator='".$_POST["ReturnOperator"]."'";
	$sql .= " WHERE WithdrawID='".$_POST["WithdrawID"]."'";

	$sql_Wp = "UPDATE weapon SET ";
	$sql_Wp .= "WeaponStatus=''";
	$sql_Wp .= " WHERE WeaponID='".$_POST["WeaponID"]."'";

	if ($conn->query($sql) === TRUE and $conn->query($sql_Wp) === TRUE){
    	$outp = '{"result":true,"message":"บันทึกการส่งคืนอาวุธ เรียบร้อย"}';
	}else{
    	$outp = '{"result":false,"message":"บันทึกไม่สำเร็จ"}';
	}

}

//-----------------------------------------------------------------------------------------------------------

$conn->close();

echo($outp);
?>