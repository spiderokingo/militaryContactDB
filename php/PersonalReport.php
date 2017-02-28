<?
session_start();
ob_start();

if(isset($_POST["Mode"]) == false){
    $_POST = json_decode(file_get_contents('php://input'), true);
}

$time = date("Y-m-d H:i:s");
$today = date("Y-m-d");

$outp = "";

include ("db_connect.php");
$conn = new mysqli($host, $username, $password, $database);
$result = $conn->query("SET NAMES UTF8");

//-----  Personal  Details  -----------------------------------------------------------------------------------
if($_POST["Mode"] == "VIEW"){

	$result = $conn->query("SELECT * FROM personal_report WHERE Date='".$today."' and Company='".$_POST["Company"]."'");
	$Num_Rows = $result->num_rows;

	$result2 = $conn->query("SELECT * FROM personal_total WHERE Company='".$_POST["Company"]."'");
	$rs2 = $result2->fetch_array(MYSQLI_ASSOC);
	
	if($Num_Rows==0){
			$outp = '{"ID":""';
			$outp .= ',"COTotal":"'.$rs2["CO"].'"';
			$outp .= ',"NCOTotal":"'.$rs2["NCO"].'"';
			$outp .= ',"PrivateTotal":"'.$rs2["Private"].'"';
			$outp .= ',"DistributionList":[
				{"id":"A","Title":"ราชการ ร้อย.คทร.","Value":"0"},
				{"id":"B","Title":"ราชการ ร้อย.รส.","Value":"0"},
				{"id":"C","Title":"รวป.ค่าย","Value":"0"},
				{"id":"D","Title":"ชรก","Value":"0"},
				{"id":"E","Title":"นักกีฬา","Value":"0"},
				{"id":"F","Title":"บริการ","Value":"0"},
				{"id":"G","Title":"ลา","Value":"0"},
				{"id":"H","Title":"ขาด","Value":"0"},
				{"id":"I","Title":"โทษ","Value":"0"},
				{"id":"J","Title":"อื่นๆ","Value":"0"}
				]';
			$outp .= '}';
		$Status = "false";
	}else if($Num_Rows==1){
		$rs = $result->fetch_array(MYSQLI_ASSOC);

			$DistributionList='{"id":"A","Title":"ราชการ ร้อย.คทร.","Value":"'.$rs["A"].'"},{"id":"B","Title":"ราชการ ร้อย.รส.","Value":"'.$rs["B"].'"},{"id":"C","Title":"รวป.ค่าย","Value":"'.$rs["C"].'"},{"id":"D","Title":"ชรก","Value":"'.$rs["D"].'"},{"id":"E","Title":"นักกีฬา","Value":"'.$rs["E"].'"},{"id":"F","Title":"บริการ","Value":"'.$rs["F"].'"},{"id":"G","Title":"ลา","Value":"'.$rs["G"].'"},{"id":"H","Title":"ขาด","Value":"'.$rs["H"].'"},{"id":"I","Title":"โทษ","Value":"'.$rs["I"].'"},{"id":"J","Title":"อื่นๆ","Value":"'.$rs["J"].'"}';

			$outp = '{"ID":"'.$rs["ID"].'"';
			$outp .= ',"PrivateTotal":"'.$rs["PrivateTotal"].'"';
			$outp .= ',"DistributionList":['.$DistributionList.']';
			$outp .= ',"UserReport":"'.$rs["UserReport"].'"';
			$outp .= ',"DateTime":"'.$rs["DateTime"].'"';
			$outp .= '}';
		$Status = "success";
	}else{
		$Status = "";
	}

$outp ='{"Status":"'.$Status.'","PersonalReport":'.$outp.'}';
}

//----- Personal  Update  -----------------------------------------------------------------------------------
if($_POST["Mode"] == "UPDATE"){

	$sql = "UPDATE personal_report SET ";
	$sql .= "PrivateTotal='".$_POST["PrivateTotal"]."'";
	$sql .= ", DistributionList=''";
	$sql .= ", A='".$_POST["DistributionList"][0]["Value"]."'";
	$sql .= ", B='".$_POST["DistributionList"][1]["Value"]."'";
	$sql .= ", C='".$_POST["DistributionList"][2]["Value"]."'";
	$sql .= ", D='".$_POST["DistributionList"][3]["Value"]."'";
	$sql .= ", E='".$_POST["DistributionList"][4]["Value"]."'";
	$sql .= ", F='".$_POST["DistributionList"][5]["Value"]."'";
	$sql .= ", G='".$_POST["DistributionList"][6]["Value"]."'";
	$sql .= ", H='".$_POST["DistributionList"][7]["Value"]."'";
	$sql .= ", I='".$_POST["DistributionList"][8]["Value"]."'";
	$sql .= ", J='".$_POST["DistributionList"][9]["Value"]."'";
	$sql .= ", UserReport='".$_POST["UserReport"]."'";
	$sql .= ", DateTime='".$time."'";
	$sql .= " WHERE ID='".$_POST["ID"]."'";

	$Status = ($conn->query($sql) === TRUE? "Success":"False");

$outp ='{"Status":"'.$Status.'"}';
}

//----- Personal  Update  -----------------------------------------------------------------------------------
if($_POST["Mode"] == "INSERT"){

	$sql = "INSERT INTO personal_report ";
	$sql .= "(Date, Company, COTotal, NCOTotal, PrivateTotal, A, B, C, D, E, F, G, H, I, J, UserReport, DateTime)";
	$sql .= " VALUES";
	$sql .= " ('".$today."','".$_POST["Company"]."','".$_POST["COTotal"]."','".$_POST["NCOTotal"]."','".$_POST["PrivateTotal"]."','".$_POST["DistributionList"][0]["Value"]."','".$_POST["DistributionList"][1]["Value"]."','".$_POST["DistributionList"][2]["Value"]."','".$_POST["DistributionList"][3]["Value"]."','".$_POST["DistributionList"][4]["Value"]."','".$_POST["DistributionList"][5]["Value"]."','".$_POST["DistributionList"][6]["Value"]."','".$_POST["DistributionList"][7]["Value"]."','".$_POST["DistributionList"][8]["Value"]."','".$_POST["DistributionList"][9]["Value"]."','".$_POST["UserReport"]."','".$time."')";

	$Status = ($conn->query($sql) === TRUE? "Success":"False");
	$ID = $conn->insert_id;

$outp ='{"Status":"'.$Status.'","ID":"'.$ID.'"}';
}
//-----------------------------------------------------------------------------------------------------------

$conn->close();

echo($outp);
?>