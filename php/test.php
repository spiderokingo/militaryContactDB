<?
include ("db_connect.php");

// Create connection
$conn = new mysqli($host, $username, $password, $database);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$conn->query("SET NAMES UTF8");

$sql = "INSERT INTO personal (CitizenID, Password, Permission, TitleName, Name, Sername, BirthDay,DateTime)
VALUES ('112233445566774', '1234', 'USER','นาย', 'สมชาย', 'จรดปลายเท้า', '".date("Y-m-d")."', '".date("Y-m-d H:i:s")."')";

if ($conn->query($sql) === TRUE) {
    $last_id = (string)$conn->insert_id;
    echo "New record created successfully. Last inserted ID is: " . $last_id;
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>