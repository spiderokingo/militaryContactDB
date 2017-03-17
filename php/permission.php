<?
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include ("db_connect.php");
$conn = new mysqli($host, $username, $password, $database);

$result = $conn->query("SET NAMES UTF8");

$result = $conn->query("SELECT * FROM permission ORDER BY ID ASC");
$outp = "";
while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($outp != "") {$outp .= ",";}
    $outp .= '{"ID":"'.$rs["ID"].'"';
	$outp .= ',"Name":"'.$rs["Name"].'"';
	$outp .= ',"Details":"'.$rs["Details"].'"';
	$outp .= ',"Menu":'.$rs["Menu"];
	$outp .= '}';
}
$outp ='{"Permission":['.$outp.']}';

$conn->close();

echo($outp);
?>