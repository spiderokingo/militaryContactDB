<?
if(isset($_POST["Mode"]) == false){
    $_POST = json_decode(file_get_contents('php://input'), true);
}

include ("db_connect.php");
$conn = new mysqli($host, $username, $password, $database);
$conn->query("SET NAMES UTF8");

//-----  Mission Personal List  --------------------------------------------------------------------------------------------
if($_POST["Mode"]=="LIST"){
    $result_Mis = $conn->query("SELECT * FROM mission WHERE MissionID='".$_POST["MissionID"]."'");
    $rs_Mis = $result_Mis->fetch_array(MYSQLI_ASSOC);

    $result = $conn->query("SELECT * FROM mission_personal ORDER BY ID ASC");
    $MisPerList = "";
    while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
        if($MisPerList != "") {$MisPerList .= ",";}
        $MisPerList .= '{"ID":"'.$rs["MissionID"].'"';
        $MisPerList .= ',"Position":"'.$rs["Position"].'"';
        $MisPerList .= ',"Personal":"'.$rs["Name"].'"';
        $MisPerList .= ',"Company":"'.$rs["Company"].'"';
        $MisPerList .= ',"Details":"'.$rs["Details"].'"';
        $MisPerList .= '}';
    }
    $outp ='{"Status":"Read Success","troopHeader":"'.$rs_Mis["MissionName"].'","MissionPersonalList":['.$MisPerList.']}';
}

//-----  Mission Personal Insert  --------------------------------------------------------------------------------------------
if($_POST["Mode"]=="INSERT"){

    $result = $conn->query("SELECT * FROM permission ORDER BY ID ASC");
    $MisPerList = "";
    while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
        if($MisPerList != "") {$MisPerList .= ",";}
        $MisPerList .= '{"ID":"'.$rs["MissionID"].'"';
        $MisPerList .= ',"Position":"'.$rs["Position"].'"';
        $MisPerList .= ',"Personal":"'.$rs["Name"].'"';
        $MisPerList .= ',"Company":"'.$rs["Company"].'"';
        $MisPerList .= ',"Details":"'.$rs["Details"].'"';
        $MisPerList .= '}';
    }
    $outp ='{"Status":"Insert Success","troopHeader":"'.$rs_Mis["MissionName"].'","MissionPersonalList":['.$MisPerList.']}';
}

echo($outp);

$conn->close();
?>