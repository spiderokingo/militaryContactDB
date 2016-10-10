<?
$conn = new mysqli("localhost", "infantry43_db", "Infantry43", "infantry43_db");

$result = $conn->query("SET NAMES UTF8");

$result = $conn->query("SELECT * FROM personal ORDER BY PersonalID ASC");

$i = 0;

while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
	$i++;

	$c = trim($rs["Password"]);

	$sql = "UPDATE personal SET Password='".$c."' WHERE PersonalID='".$rs["PersonalID"]."'";

	if($conn->query($sql) === TRUE){;

		echo "Record ".$i." [".$rs["Password"]."] NEW [".$c."] Success Full<br>";
	}else{
		echo "UPDATE False<br>";
	}

}
$conn->close();
?>
