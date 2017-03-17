<?
$time = time();

if(isset($_POST["Mode"]) == false){
    $_POST = json_decode(file_get_contents('php://input'), true);
}

include ("func.php");
include ("db_connect.php");
$conn = new mysqli($host, $username, $password, $database);
$conn->query("SET NAMES UTF8");

//-----  Mission List  ---------------------------------------------------------------------------------------------
// if($_POST["Mode"]=="LIST"){
    $result = $conn->query("SELECT * FROM mission ORDER BY MissionTimeSt ASC");
    $troopList = "";
    while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
        if ($troopList != "") {$troopList .= ",";}
        $troopList .= '{"MissionID":"'.$rs["MissionID"].'"';
        $troopList .= ',"MissionName":"'.$rs["MissionName"].'"';
        $troopList .= ',"MissionTime":"'.Date_TH_Short("t",$rs["MissionTimeSt"]).' - '.Date_TH_Short("t",$rs["MissionTimeSp"]).'"';
        $troopList .= ',"MissionPersonal":"'.$rs["MissionPersonal"].'"';
        $troopList .= ',"MissionEquipment":"'.$rs["MissionEquipment"].'"';
        $troopList .= ',"MissionLocation":"'.$rs["MissionLocation"].'"';
        $troopList .= ',"MissionDetails":"'.$rs["MissionDetails"].'"';
        $troopList .= '}';
    }
    $outp ='{"Status":"Read Success","troopList":['.$troopList.']}';
// }

//-----  Mission Insert  ---------------------------------------------------------------------------------------------
if($_POST["Mode"]=="INSERT"){

    $sql = "INSERT INTO mission (MissionName,MissionTimeSt,MissionTimeSp,MissionPersonal,MissionEquipment,MissionLocation,MissionDetails,DateTime) VALUES ('".$_POST["obj"]["MissionName"]."','".Date_to_Time($_POST["obj"]["MissionTimeSt"])."','".Date_to_Time($_POST["obj"]["MissionTimeSp"])."','".$_POST["obj"]["MissionPersonal"]."','".$_POST["obj"]["MissionEquipment"]."','".$_POST["obj"]["MissionLocation"]."','".$_POST["obj"]["MissionDetails"]."','".$time."')";

    if($conn->query($sql) === TRUE) {
        $outp ='{"Status":"Insert Success"}';
    }else{
        $outp ='{"Status":"Insert Error"}';
    }

         $outp ='{"Status":"'.$sql.'"}';
}

//------------------------------------------------------------------------------------------------------------------

echo($outp);

$conn->close();
?>