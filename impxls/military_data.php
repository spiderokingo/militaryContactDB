<html>
<head>
<title>ThaiCreate.Com PHP(COM) Excel.Application Tutorial</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><table width="500" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="50" align="center" valign="middle">
<?
if($_GET["St"]!="" and $_GET["Sp"]!=""){
	//*** Get Document Path ***//
	$strPath = realpath(basename(getenv($_SERVER["SCRIPT_NAME"]))); // C:/AppServ/www/myphp
	$OpenFile = "private259-army-data.xlsx";
	//*** Create Exce.Application ***//
	$xlApp = new COM("Excel.Application");
	$xlBook = $xlApp->Workbooks->Open($strPath."/".$OpenFile);
	//$xlBook = $xlApp->Workbooks->Open(realpath($OpenFile));

	$xlSheet1 = $xlBook->Worksheets(1);	

	//*** Insert to MySQL Database ***//
	$objConnect = mysql_connect("localhost","infantry43_db","Infantry43") or die("Error Connect to Database");
	$objDB = mysql_select_db("infantry43_db");
	mysql_query("SET NAMES UTF8");

$conn = new mysqli("localhost","infantry43_db","Infantry43","infantry43_db");
$result = $conn->query("SET NAMES UTF8");

	$j=0;
	$outp = "";
	for($i=$_GET["St"];$i<=$_GET["Sp"];$i++){
		If(trim($xlSheet1->Cells->Item($i,1)) != "")
		{
			$strSQL = "";
			$strSQL .= "INSERT INTO personal_military ";
			$strSQL .= "(PersonalID,MilitaryID,Company,TimeArmy,Generation,Unit,DateTime) ";
			$strSQL .= "VALUES ";
			$strSQL .= "('".$xlSheet1->Cells->Item($i,1)."' ";
			$strSQL .= ",'".iconv('TIS-620', 'UTF-8',$xlSheet1->Cells->Item($i,2))."' ";
			$strSQL .= ",'".iconv('TIS-620', 'UTF-8',$xlSheet1->Cells->Item($i,3))."' ";
			$strSQL .= ",'".iconv('TIS-620', 'UTF-8',$xlSheet1->Cells->Item($i,4))."' ";
			$strSQL .= ",'".iconv('TIS-620', 'UTF-8',$xlSheet1->Cells->Item($i,5))."' ";
			$strSQL .= ",'ร.4 พัน.3' ";
			$strSQL .= ",'".date("Y-m-d H:i:s")."'";
			$strSQL .= ")";
			$Status = ($conn->query($strSQL) === TRUE? "Success":"False");
				if($Status==TRUE){
				$outp .='เพิ่ม Record '.$j.' เรียบร้อย<br>';
				}else{
				$outp .='เพิ่ม Record '.$j.' ไม่สำเร็จ<br>';
				}
		}
		$j++;
	}
	
	//*** Close MySQL ***//
	$conn->close();

	//*** Close & Quit ***//
	$xlApp->Application->Quit();
	$xlApp = null;
	$xlBook = null;
	$xlSheet1 = null;

echo $outp."<br><br>";
echo "ดำเนินการ จำนวน ".$j." รายการ";
}
?>
    </td>
  </tr>
<form action="<?=$_PHPSELF;?>" method="get"><tr>
    <td height="50" align="center" valign="middle">จาก&nbsp;<input name="St" type="text" size="8">&nbsp;ถึง&nbsp;<input name="Sp" type="text" size="8">&nbsp;&nbsp;<input type="submit" value=" เริ่ม "></td>
  </tr></form>
</table>
</td>
  </tr>
</table>
</body>
</html>