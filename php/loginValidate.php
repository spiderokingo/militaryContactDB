<?
session_start();
ob_start();

//if($_COOKIE["UsernameInfantry43"] == ""){
//	exit();
//}

$outp = "";

// header("Access-Control-Allow-Origin: *");
// header("Content-Type: application/json; charset=UTF-8");

if(isset($_POST["Mode"]) == false){
    $_POST = json_decode(file_get_contents('php://input'), true);
    }

   $Mode = $_POST["Mode"];
   $Username = $_POST["Username"];
   $Password = $_POST["Password"];

include ("db_connect.php");
$conn = new mysqli($host, $username, $password, $database);

$result = $conn->query("SET NAMES UTF8");

//-----  ตรวจสอบ Username Password  -----
if($Mode=="LOGIN"){

$result = $conn->query("SELECT * FROM personal WHERE CitizenID='".$Username."' and Password='".$Password."'");

if ($result->num_rows == 1) {
$rs = $result->fetch_array(MYSQLI_ASSOC);
    $outp = '{"result":true';
    $outp .= ',"message":"Login Successfull"';
    $outp .= ',"Username":"'.$rs["CitizenID"].'"';
    $outp .= ',"MilitaryID":"'.$rs["MilitaryID"].'"';
    $outp .= ',"TitleName":"'.$rs["TitleName"].'"';
    $outp .= ',"FirstName":"'. $rs["FirstName"].'"';
	$outp .= ',"LastName":"'. $rs["LastName"].'"';
	$outp .= ',"Permission":"'. $rs["Permission"].'"';
	$outp .= '}';
}else{
   $outp = '{"result":false,"message":"Login Failed"}';
   }
}

$conn->close();

echo($outp);
?>