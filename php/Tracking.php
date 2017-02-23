<?
session_start();
ob_start();

if(isset($_POST["Mode"]) == false){
    $_POST = json_decode(file_get_contents('php://input'), true);
}

include ("db_connect.php");
$conn = new mysqli($host, $username, $password, $database);

$result = $conn->query("SET NAMES UTF8");

//----- Personal  Update  -----------------------------------------------------------------------------------
if($_POST["Mode"] == "INSERT"){
	$sql = "INSERT INTO tracking ";
	$sql .= "(PersonalID,DateTime,Position) ";
	$sql .= "VALUES ";
	$sql .= "('".$_POST["PersonalID"]."','".$_POST["DateTime"]."','".$_POST["Position"]."')";


	if ($conn->query($sql) === TRUE){
    	$outp = '{"result":"true","message":"Insert successfully"}';
	} else {
    	$outp = '{"result":"false","message":"Error updating record '.$conn->error.'"}';
	}

}

//-----------------------------------------------------------------------------------------------------------

$conn->close();

echo($outp);
?>