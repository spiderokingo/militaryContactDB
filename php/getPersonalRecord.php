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
$time = date("Y-m-d H:i:s");

include ("db_connect.php");
$conn = new mysqli($host, $username, $password, $database);

$result = $conn->query("SET NAMES UTF8");

//-----  อ่านข้อมูล ตาม Category ที่เลือก  -----
if($data->Mode =="LIST"){

$result = $conn->query("SELECT * FROM personal");
$outp = "";
while($rs = $result->fetch_array(MYSQLI_ASSOC)) {

	$result2 = $conn->query("SELECT * FROM personal_phone WHERE PersonalID='".$rs["PersonalID"]."'");

	if($result2->num_rows > 0){
    	if ($outp != "") {$outp .= ",";}
    	$outp .= '{"ID":"'.$rs["PersonalID"].'"';
		$outp .= ',"ImageFullPath":"'.$ImagePath.$rs["ImageName"].'"';
		$outp .= ',"TitleName":"'. $rs["TitleName"].'"';
		$outp .= ',"FirstName":"'. $rs["FirstName"].'"';
		$outp .= ',"LastName":"'. $rs["LastName"].'"';
		$outp .= ',"NickName":"'. $rs["NickName"].'"';

		$outp2 = "";
		while($rs2 = $result2->fetch_array(MYSQLI_ASSOC)) {
			if ($outp2 != "") {$outp2 .= ",";}
			$outp2 .= '{"PhoneNumber":"'.$rs2["PhoneNumber"].'","PhoneProvider":"'.$rs2["PhoneProvider"].'"}';
		}
	$outp .= ',"PhoneNumberList":['.$outp2.']';
	$outp .= '}';
	}
}
$outp ='{"PersonalRecord":['.$outp.']}';
}

$conn->close();

echo($outp);
?>