<?
session_start();
ob_start();

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
// if($_POST["Mode"] == "USER"){

//	$outp = "[{'Title':'ข้อมูลกำลังพล','Data':[]}]";

//	$result = $conn->query("SELECT * FROM personal WHERE Company='ร้อย.สสก.'");
	$result = $conn->query("SELECT Institution , Count(PersonalID) as count_id FROM personal GROUP BY Institution");
	$Num = $result->num_rows;
	while($rs = $result->fetch_array(MYSQLI_ASSOC)){
		echo $rs["Institution"]."---".$rs["count_id"]."<br>";
	}


	// $Num = $result->num_rows;
	// $rs = $result->fetch_array(MYSQLI_ASSOC);
	//  for($i=0;$i<$outp.length;$i++){
	//  	$outp.= $outp[$i]."<br>";
	//  }

// }else{
// 		$outp = '{"result":false,"message":"ไม่พบข้อมูลบุคคล"}';
// }

//-----------------------------------------------------------------------------------------------------------

$conn->close();

echo($outp);
echo "--|--";
echo($Num);
?>