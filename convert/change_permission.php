<?
$CO = array("พ.ท.","พ.ต.");
$NCO = array("จ.ส.อ.","จ.ส.ท.","จ.ส.ต.","ส.ต.","ส.ท.","ส.ต.");
$Pri = array("พลทหาร","พลฯ");

include ("../php/db_connect.php");
$conn = new mysqli($host, $username, $password, $database);

$result = $conn->query("SET NAMES UTF8");

$sql = "SELECT * FROM personal ORDER BY PersonalID ASC";
$result = $conn->query($sql);

$i = 0;
while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    if(in_array($rs["TitleName"],$CO)){
        $NewPermission = "3";
    }
    if(in_array($rs["TitleName"],$NCO)){
        $NewPermission = "4";
    }
    if(in_array($rs["TitleName"],$Pri)){
        $NewPermission = "5";
    }

	$sql2 = "UPDATE personal SET Permission='".$NewPermission."' WHERE PersonalID='".$rs["PersonalID"]."'";
	$change = $conn->query($sql2);
    echo $rs["TitleName"]." ".$rs["FirstName"]." ".$rs["LastName"]." = [ ".$NewPermission." ]<br>";
    $i++;
}

?>