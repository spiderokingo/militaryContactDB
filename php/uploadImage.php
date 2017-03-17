<?
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//$postData = file_get_contents("php://input");
//$data = json_decode($postData);

if(isset($_FILES['fileupload'])){

    $file_name = $_FILES['fileupload']['name'];
    $file_size = $_FILES['fileupload']['size'];
    $file_tmp = $_FILES['fileupload']['tmp_name'];
    $file_type = $_FILES['fileupload']['type'];

    $file_name = $_POST["ID"]."_".date("YmdHis")."_".$file_name;
             
    move_uploaded_file($file_tmp,"../personal_image/".$file_name);

    $outp = '{"result":"success","ImageFullPath":"personal_image/'.$file_name.'","ImageName":"'.$file_name.'"}';

}else{
    $outp = '{"result";"false"}';
}

echo($outp);
?>