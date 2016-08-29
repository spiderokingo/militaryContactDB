<?
include ("db_connect.php");
$conn = new mysqli($host, $username, $password, $database);
$result = $conn->query("SET NAMES UTF8");

if($Mode=="INSERT"){
    $ImgAll[]="AAA";
	$sql_Add = "UPDATE personal SET ";
	$sql_Add .= "ImageAll='{AAA,BBB,CCC}'";
	$sql_Add .= " WHERE PersonalID='00001'";
$conn->query($sql_Add);
}

//	$resultImg = $conn->query("SELECT * FROM personal WHERE PersonalID='".$rs["PersonalID"]."'");
	$resultImg = $conn->query("SELECT * FROM personal WHERE PersonalID='00001'");
	$rsImg = $resultImg->fetch_array(MYSQLI_ASSOC);
	$ImageAll = $rsImg["ImageAll"];
    $len = count($ImageAll);
    echo $len;


$conn->close();
?>