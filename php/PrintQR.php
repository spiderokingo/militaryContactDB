<?
session_start();
ob_start();

//if($_COOKIE["UsernameInfantry43"] == ""){
//	exit();
//}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
.QRpers {
	position:relative;
	float:left;
	margin: 3px;
	width:145px;
	height:125px;
	border:1px solid #CCC;
	border-radius:4px;
	text-align:center;
	font-size:11px;
}
.imgQR {
	margin: 5px;
}
</style>
<script src="../jquery/jquery-1.12.4.min.js" type="text/javascript"></script>
<script>
function GenQR(id,data,level,size){
//	alert("555");
	$.post("phpqrcode/index.php", {data:data,level:level,size:size}, function(result){
		$("#"+id).html(result);
	});
}
</script>
</head>

<body>
<?
//----- Path เก็บรูป -----------------------------------
$ImagePath = "personal_image/";
$time = date("Y-m-d H:i:s");

include ("db_connect.php");
$conn = new mysqli($host, $username, $password, $database);

$result = $conn->query("SET NAMES UTF8");


//-----  อ่านข้อมูล ตาม Category ที่เลือก  ----------
switch($_POST["Mode"]){
	//---------------- จาก ID ถึง ID -----------
	case "TO" :
		$result = $conn->query("SELECT * FROM personal LIMIT 10 OFFSET 10");
		while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    		$outp["PersonalID"] = $rs["PersonalID"];
			$outp["IdentityID"]= $rs["IdentityID"];
			$outp["TitleName"]= $rs["TitleName"];
			$outp["FirstName"]= $rs["FirstName"];
			$outp["LastName"]= $rs["LastName"];
			$Val[] = $outp;
		}
		break;
	//---------------- จาก ID ถึง ID -----------
	case "SELECT" :
		$lengthKey = count($_POST["Key"]);
		$Key = $_POST["Key"];
		for($i=0;$i<$lengthKey;$i++){
			$result = $conn->query("SELECT * FROM personal WHERE PersonalID='".$Key[$i]."'");
			$rs = $result->fetch_array(MYSQLI_ASSOC);
				$outp["PersonalID"] = $rs["PersonalID"];
				$outp["IdentityID"]= $rs["IdentityID"];
				$outp["TitleName"]= $rs["TitleName"];
				$outp["FirstName"]= $rs["FirstName"];
				$outp["LastName"]= $rs["LastName"];
				$Val[] = $outp;
		}
		break;
}

		$result = $conn->query("SELECT * FROM personal LIMIT 10 OFFSET 10");
		while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    		$outp["PersonalID"] = $rs["PersonalID"];
			$outp["IdentityID"]= $rs["IdentityID"];
			$outp["TitleName"]= $rs["TitleName"];
			$outp["FirstName"]= $rs["FirstName"];
			$outp["LastName"]= $rs["LastName"];
			$Val[] = $outp;
		}


$conn->close();

//------------------------------ ส่วนแสดงผล --------------------------------------------------------------
$lengthID = count($Val);
for($i=0;$i<$lengthID;$i++){
	$rs = $Val[$i];

	echo "<div class='QRpers'><div class='imgQR' id='".$rs["PersonalID"]."'><script>GenQR('".$rs["PersonalID"]."','".$rs["IdentityID"]."','L','1');</script></div>".$rs["IdentityID"]."<br>".$rs["TitleName"]." ". $rs["FirstName"]." ". $rs["LastName"]."</div>";

}
?>
</body>
</html>