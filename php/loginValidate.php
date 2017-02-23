<?
session_start();
ob_start();

//if($_COOKIE["UsernameInfantry43"] == ""){
//	exit();
//}

$time = date("Y-m-d H:i:s");
$ImagePath = "personal_image/";
$outp = "";

// header("Access-Control-Allow-Origin: *");
// header("Content-Type: application/json; charset=UTF-8");

if(isset($_POST["Mode"]) == false){
    $_POST = json_decode(file_get_contents('php://input'), true);
}

include ("db_connect.php");
$conn = new mysqli($host, $username, $password, $database);

$result = $conn->query("SET NAMES UTF8");

//-----  ตรวจสอบ Username Password  -----
if($_POST["Mode"]=="LOGIN"){

$result = $conn->query("SELECT * FROM personal WHERE IdentityID='".$_POST["Username"]."' and Password='".$_POST["Password"]."'");

if ($result->num_rows == 1) {
$rs = $result->fetch_array(MYSQLI_ASSOC);
    $outp = '{"result":true';
    $outp .= ',"message":"Login Successfull"';
	$outp .= ',"ImageFullPath":"'.$ImagePath.$rs["ImageName"].'"';
    $outp .= ',"PersonalID":"'.$rs["PersonalID"].'"';
    $outp .= ',"Username":"'.$rs["IdentityID"].'"';
    $outp .= ',"MilitaryID":"'.$rs["MilitaryID"].'"';
    $outp .= ',"TitleName":"'.$rs["TitleName"].'"';
    $outp .= ',"FirstName":"'.$rs["FirstName"].'"';
	$outp .= ',"LastName":"'.$rs["LastName"].'"';
	$outp .= ',"Permission":"'.$rs["Permission"].'"';
	$outp .= ',"Company":"'.$rs["Company"].'"';
	$outp .= '}';

	$conn->query("UPDATE personal SET DateLogin='".$time."' WHERE PersonalID='".$rs["PersonalID"]."'");

}else{
   $outp = '{"result":false,"message":"Login Failed"}';
   }
}

$conn->close();

echo($outp);
?>