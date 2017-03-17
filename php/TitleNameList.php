<?
session_start();
ob_start();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include ("db_connect.php");
$conn = new mysqli($host, $username, $password, $database);

$result = $conn->query("SET NAMES UTF8");

$result = $conn->query("SELECT * FROM titlename ORDER BY ID ASC");
$outp = "";
while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($outp != "") {$outp .= ",";}
    $outp .= '{"TitleShortName":"'.$rs["TitleShortName"].'"';
	$outp .= ',"TitleName":"'.$rs["TitleName"].'"';
	$outp .= '}';
}
$outp ='{"TitleNameList":['.$outp.']}';

$conn->close();

echo($outp);
?>