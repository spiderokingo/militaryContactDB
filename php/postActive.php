<?
session_start();
ob_start();

if(isset($_POST["Mode"]) == false){
    $_POST = json_decode(file_get_contents('php://input'), true);
}

$now = time();
$outp = "";

include ("db_connect.php");
$conn = new mysqli($host, $username, $password, $database);
$conn->query("SET NAMES UTF8");

//----- Permission List -----------------------------------
$result_menu = $conn->query("SELECT * FROM Permission WHERE ID='".$_POST["Permission"]."'");
$rs_menu = $result_menu->fetch_array(MYSQLI_ASSOC);
    $MenuPermission = $rs_menu["Menu"];

//-----  Personal  Details  -----------------------------------------------------------------------------------
$result = $conn->query("SELECT * FROM personal WHERE PersonalID='".$_POST["PersonalID"]."'");

$rs = $result->fetch_array(MYSQLI_ASSOC);
    $ActiveCounter = $rs["ActiveCounter"];
    $TimeLet = time() - $rs["LastActive"];
    if($TimeLet>3600){
        $ActiveCounter++;
    }

	$sql = "UPDATE personal SET ";
	$sql .= "LastActive='".$now."'";
	$sql .= ", ActiveCounter='".$ActiveCounter."'";
	$sql .= " WHERE PersonalID='".$_POST["PersonalID"]."'";

	if ($conn->query($sql) === TRUE){
    	$outp = '{"Status":"Update Post Action Successfully","menuPermission":'.$MenuPermission.'}';
	} 

//-----------------------------------------------------------------------------------------------------------

$conn->close();

echo($outp);
?>