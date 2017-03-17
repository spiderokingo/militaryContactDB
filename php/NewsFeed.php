<?
session_start();
ob_start();

if(isset($_POST["Mode"]) == false){
    $_POST = json_decode(file_get_contents('php://input'), true);
}

$time = date("Y-m-d H:i:s");
$today = date("Y-m-d");

$PersonalReport = "";

include ("func.php");
include ("db_connect.php");
$conn = new mysqli($host, $username, $password, $database);
$result = $conn->query("SET NAMES UTF8");

//-----  List Record  -----------------------------------------------------------------------------------

$sql = "SELECT * FROM personal_report WHERE Date = '".$today."'";

	$result = $conn->query($sql);
	$Num_Rows = $result->num_rows;

	while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    	if ($PersonalReport != "") {$PersonalReport .= ",";}

		$DistributionList='{"id":"A","Title":"ราชการ ร้อย.คทร.","Value":"'.$rs["A"].'"},{"id":"B","Title":"ราชการ ร้อย.รส.","Value":"'.$rs["B"].'"},{"id":"C","Title":"รวป.ค่าย","Value":"'.$rs["C"].'"},{"id":"D","Title":"ชรก","Value":"'.$rs["D"].'"},{"id":"E","Title":"นักกีฬา","Value":"'.$rs["E"].'"},{"id":"F","Title":"บริการ","Value":"'.$rs["F"].'"},{"id":"G","Title":"ลา","Value":"'.$rs["G"].'"},{"id":"H","Title":"ขาด","Value":"'.$rs["H"].'"},{"id":"I","Title":"โทษ","Value":"'.$rs["I"].'"},{"id":"J","Title":"อื่นๆ","Value":"'.$rs["J"].'"}';

    	$PersonalReport .= '{"ID":"'.$rs["ID"].'"';
		$PersonalReport .= ',"Company":"'.$rs["Company"].'"';
		$PersonalReport .= ',"COTotal":"'.$rs["COTotal"].'"';
		$PersonalReport .= ',"NCOTotal":"'.$rs["NCOTotal"].'"';
		$PersonalReport .= ',"PrivateTotal":"'.$rs["PrivateTotal"].'"';
		$PersonalReport .= ',"DistributionList":['.$DistributionList.']';
		$PersonalReport .= ',"UserReport":"'.$rs["UserReport"].'"';
		$PersonalReport .= ',"DateTime":"'.$rs["DateTime"].'"';
		$PersonalReport .= '}';
	}

//-----  List Record  -----------------------------------------------------------------------------------
function Company($Company){
	switch($Company){
		case "ร้อย.สสก." : $Company = "สสก.";
		break;
		case "ร้อย.สสช." : $Company = "สสช.";
		break;
		case "ร้อย.อวบ.ที่ 1" : $Company = "ร้อย.1";
		break;
		case "ร้อย.อวบ.ที่ 2" : $Company = "ร้อย.2";
		break;
		case "ร้อย.อวบ.ที่ 3" : $Company = "ร้อย.3";
		break;
	}
	return $Company;
}

$LastActive = "";
$sql_LA = "SELECT * FROM personal ORDER BY LastActive DESC LIMIT 0 , 10";
	$result_LA = $conn->query($sql_LA);
	while($rs = $result_LA->fetch_array(MYSQLI_ASSOC)){
    	if ($LastActive != "") {$LastActive .= ",";}

    	$LastActive .= '{"PersonalID":"'.$rs["PersonalID"].'"';
		$LastActive .= ',"PersonalName":"'.$rs["TitleName"].' '.$rs["FirstName"].'  '.$rs["LastName"].'"';
			//-----  Search Company  --------------------------------------------------------------------
			$result_company = $conn->query("SELECT * FROM personal_military WHERE PersonalID='".$rs["PersonalID"]."'");
			$rs_company = $result_company->fetch_array(MYSQLI_ASSOC);
		
		$LastActive .= ',"Company":"'.Company($rs_company["Company"]).'"';
		$LastActive .= ',"ActiveCounter":"'.$rs["ActiveCounter"].'"';
		$LastActive .= ',"DateTime":"'.DateTime_TH_Short("t",$rs["LastActive"]).'"';
		$LastActive .= '}';
	}

//-----  End  -----------------------------------------------------------------------------------
$outp ='{"Status":"success","PersonalReport":['.$PersonalReport.'],"LastActive":['.$LastActive.']}';
//-----------------------------------------------------------------------------------------------------------

$conn->close();

echo($outp);
?>