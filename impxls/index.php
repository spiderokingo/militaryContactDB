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
	$OpenFile = "personal4003.xls";
	//*** Create Exce.Application ***//
	$xlApp = new COM("Excel.Application");
	$xlBook = $xlApp->Workbooks->Open($strPath."/".$OpenFile);
	//$xlBook = $xlApp->Workbooks->Open(realpath($OpenFile));

	$xlSheet1 = $xlBook->Worksheets(1);	

	//*** Insert to MySQL Database ***//
	$objConnect = mysql_connect("localhost","infantry43_db","Infantry43") or die("Error Connect to Database");
	$objDB = mysql_select_db("infantry43_db");
	mysql_query("SET NAMES UTF8");

	$j=0;
	for($i=$_GET["St"];$i<=$_GET["Sp"];$i++){
		If(trim($xlSheet1->Cells->Item($i,1)) != "")
		{
			$strSQL = "";
			$strSQL .= "INSERT INTO personal ";
			$strSQL .= "(PersonalID2,Category,CitizenID,Password,Permission,TitleName,Name,Sername,NickName,BirthDay,BloodGroup,MilitaryID,Corps,Institution,Generation,RegisMilitary,DateReassign,Unit,Position,Company,PositionNumber,Salary,PhoneNumber,HouseNum,Moo,Road,Tambon,Amper,Province,Father,Mother,Education,TimeArmy,DateTime) ";
			$strSQL .= "VALUES ";
			$strSQL .= "('".$xlSheet1->Cells->Item($i,1)."' ";
			$strSQL .= ",'".iconv('TIS-620', 'UTF-8',$xlSheet1->Cells->Item($i,2))."' ";
			$strSQL .= ",'".$xlSheet1->Cells->Item($i,3)."' ";
			$strSQL .= ",'".$xlSheet1->Cells->Item($i,4)."' ";
			$strSQL .= ",'".iconv('TIS-620', 'UTF-8',$xlSheet1->Cells->Item($i,5))."' ";
			$strSQL .= ",'".iconv('TIS-620', 'UTF-8',$xlSheet1->Cells->Item($i,6))."' ";
			$strSQL .= ",'".iconv('TIS-620', 'UTF-8',$xlSheet1->Cells->Item($i,7))."' ";
			$strSQL .= ",'".iconv('TIS-620', 'UTF-8',$xlSheet1->Cells->Item($i,8))."' ";
			$strSQL .= ",'".iconv('TIS-620', 'UTF-8',$xlSheet1->Cells->Item($i,9))."' ";
			$strSQL .= ",'".$xlSheet1->Cells->Item($i,10)."' ";
			$strSQL .= ",'".$xlSheet1->Cells->Item($i,11)."' ";
			$strSQL .= ",'".$xlSheet1->Cells->Item($i,12)."' ";
			$strSQL .= ",'".iconv('TIS-620', 'UTF-8',$xlSheet1->Cells->Item($i,13))."' ";
			$strSQL .= ",'".iconv('TIS-620', 'UTF-8',$xlSheet1->Cells->Item($i,14))."' ";
			$strSQL .= ",'".$xlSheet1->Cells->Item($i,15)."' ";
			$strSQL .= ",'".$xlSheet1->Cells->Item($i,16)."' ";
			$strSQL .= ",'".$xlSheet1->Cells->Item($i,15)."' ";
			$strSQL .= ",'".iconv('TIS-620', 'UTF-8',$xlSheet1->Cells->Item($i,18))."' ";
			$strSQL .= ",'".iconv('TIS-620', 'UTF-8',$xlSheet1->Cells->Item($i,19))."' ";
			$strSQL .= ",'".iconv('TIS-620', 'UTF-8',$xlSheet1->Cells->Item($i,20))."' ";
			$strSQL .= ",'".$xlSheet1->Cells->Item($i,21)."' ";
			$strSQL .= ",'".iconv('TIS-620', 'UTF-8',$xlSheet1->Cells->Item($i,22))."' ";
			$strSQL .= ",'".iconv('TIS-620', 'UTF-8',$xlSheet1->Cells->Item($i,23))."' ";
			$strSQL .= ",'".$xlSheet1->Cells->Item($i,24)."' ";
			$strSQL .= ",'".$xlSheet1->Cells->Item($i,25)."' ";
			$strSQL .= ",'".iconv('TIS-620', 'UTF-8',$xlSheet1->Cells->Item($i,26))."' ";
			$strSQL .= ",'".iconv('TIS-620', 'UTF-8',$xlSheet1->Cells->Item($i,27))."' ";
			$strSQL .= ",'".iconv('TIS-620', 'UTF-8',$xlSheet1->Cells->Item($i,28))."' ";
			$strSQL .= ",'".iconv('TIS-620', 'UTF-8',$xlSheet1->Cells->Item($i,29))."' ";
			$strSQL .= ",'".iconv('TIS-620', 'UTF-8',$xlSheet1->Cells->Item($i,30))."' ";
			$strSQL .= ",'".iconv('TIS-620', 'UTF-8',$xlSheet1->Cells->Item($i,31))."' ";
			$strSQL .= ",'".iconv('TIS-620', 'UTF-8',$xlSheet1->Cells->Item($i,32))."' ";
			$strSQL .= ",'".iconv('TIS-620', 'UTF-8',$xlSheet1->Cells->Item($i,33))."' ";
			$strSQL .= ",'".date("Y-m-d H:i:s")."'";
			$strSQL .= ")";
			mysql_query($strSQL);
		}
		$j++;
	}
	
	//*** Close MySQL ***//
	@mysql_close($objConnect);

	//*** Close & Quit ***//
	$xlApp->Application->Quit();
	$xlApp = null;
	$xlBook = null;
	$xlSheet1 = null;
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