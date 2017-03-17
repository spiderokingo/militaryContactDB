<?
include ("../php/db_connect.php");
$conn = new mysqli($host, $username, $password, $database);

$result = $conn->query("SET NAMES UTF8");

$sql = "SELECT * FROM personal ORDER BY PersonalID ASC";
$result = $conn->query($sql);

$i=1;
while($rs = $result->fetch_array(MYSQLI_ASSOC)) {

	$sql2 = "UPDATE personal SET Category='INF4003' WHERE PersonalID='".$rs["PersonalID"]."'";
	$change = $conn->query($sql2);
    echo $rs["TitleName"]." ".$rs["FirstName"]." ".$rs["LastName"]." = [".$i."]<br>";
    $i++;
}

?>