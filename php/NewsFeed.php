<?
session_start();
ob_start();

if(isset($_POST["Mode"]) == false){
    $_POST = json_decode(file_get_contents('php://input'), true);
}

$time = date("Y-m-d H:i:s");
$today = date("Y-m-d");

$outp = "";
$DistributionList = "";
$lastActiveUser = "";

include ("db_connect.php");
$conn = new mysqli($host, $username, $password, $database);
$result = $conn->query("SET NAMES UTF8");

//-----  Personal Report  -----------------------------------------------------------------------------------

$sql = "SELECT * FROM personal_report WHERE Date = '".$today."'";

	$result = $conn->query($sql);
	$Num_Rows = $result->num_rows;

	while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    	if ($PersonalList != "") {$PersonalList .= ",";}

			$DistributionList ='{"id":"A","Title":"ราชการ ร้อย.คทร.","Value":"'.$rs["A"].'"}';
			$DistributionList .=',{"id":"B","Title":"ราชการ ร้อย.รส.","Value":"'.$rs["B"].'"}';
			$DistributionList .=',{"id":"C","Title":"รวป.ค่าย","Value":"'.$rs["C"].'"}';
			$DistributionList .=',{"id":"D","Title":"ชรก","Value":"'.$rs["D"].'"}';
			$DistributionList .=',{"id":"E","Title":"นักกีฬา","Value":"'.$rs["E"].'"}';
			$DistributionList .=',{"id":"F","Title":"บริการ","Value":"'.$rs["F"].'"}';
			$DistributionList .=',{"id":"G","Title":"ลา","Value":"'.$rs["G"].'"}';
			$DistributionList .=',{"id":"H","Title":"ขาด","Value":"'.$rs["H"].'"}';
			$DistributionList .=',{"id":"I","Title":"โทษ","Value":"'.$rs["I"].'"}';
			$DistributionList .=',{"id":"J","Title":"อื่นๆ","Value":"'.$rs["J"].'"}';

    	$PersonalList .= '{"ID":"'.$rs["ID"].'"';
		$PersonalList .= ',"Company":"'.$rs["Company"].'"';
		$PersonalList .= ',"CO":{"COTotal":"'.$rs["COTotal"].'"}';
		$PersonalList .= ',"NCO":{"NCOTotal":"'.$rs["NCOTotal"].'"}';
		$PersonalList .= ',"Private":{"PrivateTotal":"'.$rs["PrivateTotal"].'","ContributionList":['.$DistributionList.']}';
		$PersonalList .= ',"UserReport":"'.$rs["UserReport"].'"';
		$PersonalList .= ',"DateTime":"'.$rs["DateTime"].'"';
		$PersonalList .= '}';
	}
	

//-----  Personal Last Active  -----------------------------------------------------------------------------------

$sql = "SELECT * FROM personal ORDER BY LastActive DESC LIMIT 0, 10";
$result = $conn->query($sql);

	while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    	if($lastActiveUser != ""){$lastActiveUser .= ",";}

		$lastActiveUser .= '{"name":"'.$rs["TitleName"].' '.$rs["FirstName"].'  '.$rs["LastName"].'"';
		$lastActiveUser .= ',"date":"'.date('Y-m-d',$rs["LastActive"]).'"';
		$lastActiveUser .= ',"time":"'.date('H:i:s',$rs["LastActive"]).'"';
		$lastActiveUser .= ',"ActiveCounter":"'.$rs["ActiveCounter"].'"}';

	}

//-----------------------------------------------------------------------------------------------------------

$outp ='{"Status":"success","PersonalList":['.$PersonalList.'],"lastActiveUser":['.$lastActiveUser.']}';

//-----------------------------------------------------------------------------------------------------------

$conn->close();

echo($outp);
?>