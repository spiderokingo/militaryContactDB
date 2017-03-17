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

		switch($rs["WeaponStatus"]){
			case "NoCheck" :
				$outp = '{"result":false,"message":"อาวุธยังไม่ผ่านการตรวจสอบ"}';		
				break;
			case "Repair" :
				$outp = '{"result":false,"message":"อาวุธอยู่ในระหว่างการส่งซ่อม"}';		
				break;
			case "Unavailable" :
				$PerWD = $conn->query("SELECT * FROM personal WHERE PersonalID='".$rs["Borrower"]."'");
				$rsWD = $PerWD->fetch_array(MYSQLI_ASSOC);
				$NameWD = $rsWD["TitleName"]." ".$rsWD["FirstName"]."   ".$rsWD["LastName"];
				$outp = '{"result":false,"message":"อาวุธหมายเลขนี้ถูกเบิกไปแล้ว โดย\n'.$NameWD.'"}';		
				break;
			case "Available" :
				$outp .= '{"WeaponID":"'.$rs["WeaponID"].'"';
				$outp .= ',"WeaponNumber":"'.$rs["WeaponNumber"].'"';
				$outp .= ',"ImageFullPath":"images/'.$rs["WeaponType"].'.png"';
				$outp .= ',"WeaponType":"'.$rs["WeaponType"].'"';
				$outp .= ',"WeaponCompany":"'.$rs["WeaponCompany"].'"';
				$outp .= ',"result":true';
				$outp .= '}';
				break;
		}
	}else{
		$outp = '{"result":false,"message":"ไม่พบข้อมูลอาวุธ"}';
	}
}

//----- WithDraw  Update  -----------------------------------------------------------------------------------
if($_POST["Mode"] == "WITHDRAW"){
	$CheckValue = "";
	if($_POST["PersonalID"]!="" or $_POST["IdentityID"]!="" or $_POST["WeaponID"]!="" or $_POST["WeaponNumber"]!="" or $_POST["WithdrawOperator"]!=""){
		$sql = "INSERT INTO withdraw ";
		$sql .= "(WithdrawTime,PersonalID,IdentityID,WeaponID,WeaponNumber,WithdrawDetails,WithdrawOperator) ";
		$sql .= "VALUES ";
		$sql .= "('".$time."','".$_POST["PersonalID"]."','".$_POST["IdentityID"]."'";
		$sql .= ",'".$_POST["WeaponID"]."','".$_POST["WeaponNumber"]."'";
		$sql .= ",'".$_POST["WithdrawDetails"]."','".$_POST["WithdrawOperator"]."'";
		$sql .= ")";

		$sql_Wp = "UPDATE weapon SET ";
		$sql_Wp .= "WeaponStatus='Unavailable'";
		$sql_Wp .= ", Borrower='".$_POST["PersonalID"]."'";
		$sql_Wp .= " WHERE WeaponID='".$_POST["WeaponID"]."'";

		if ($conn->query($sql_Wp) === TRUE and $conn->query($sql) === TRUE){
			$WithdrawID = $conn->insert_id;
			$outp = '{"result":true,"WithdrawID":"'.$WithdrawID.'","message":"บันทึกการเบิกอาวุธ เรียบร้อย"}';
		}else{
			$outp = '{"result":false,"message":"บันทึกไม่สำเร็จ'.$_POST["WeaponID"].'"}';
		}
	}else{
    	$outp = '{"result":false,"message":"บันทึกไม่สำเร็จ ไม่มีข้อมูลรายละเอียดการเบิก"}';
	}
}

//----- WithDraw  Delete  -----------------------------------------------------------------------------------
if($_POST["Mode"] == "DELETE"){
	if($_POST["WithdrawID"]!="" and $_POST["WeaponID"]!=""){
		$sql = "DELETE FROM withdraw WHERE WithdrawID='".$_POST["WithdrawID"]."'";

		$sql_Wp = "UPDATE weapon SET ";
		$sql_Wp .= "WeaponStatus='Available'";
		$sql_Wp .= ", Borrower=''";
		$sql_Wp .= " WHERE WeaponID='".$_POST["WeaponID"]."'";

		if ($conn->query($sql) === TRUE and $conn->query($sql_Wp) === TRUE){
			$outp = '{"result":true,"message":"ลบรายการเบิกอาวุธ เรียบร้อย"}';
		}else{
			$outp = '{"result":false,"message":"การลบรายการเบิกอาวุธ เกิดปัญหา การดำเนินการไม่สมบูรณ์"}';
		}
	}else{
    	$outp = '{"result":false,"message":"ลบรายการเบิกอาวุธไม่สำเร็จ"}';
	}
}

//-----------------------------------------------------------------------------------------------------------

$conn->close();

echo($outp);
?>