<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Download File</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Tahoma;
	font-size: 14px;
}
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-image: url(../images/bg.gif);
}
a:link {
	text-decoration: none;
	color: #000099;
}
a:visited {
	text-decoration: none;
	color: #000099;
}
a:hover {
	text-decoration: underline;
	color: #000099;
}
a:active {
	text-decoration: none;
	color: #000099;
}
.style2 {color: #F0FFFF}
.style99 {font-family: "MS Sans Serif"; font-size: 14px; }
.borderimage{border:1px solid white; }
-->
</style>
</head>
<body >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="top"><table width="950" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td height="250" align="center" valign="top" bgcolor="#FFFFFF"><br>
          ดาวโหลดไฟล์<br>
          <br>
          <table width="600" border="0" cellspacing="1" cellpadding="0" bgcolor="#EAEAEA">
            <tr class="style2">
              <td width="396" height="30" align="center" valign="bottom" bgcolor="#0066AA">ชื่อไฟล์</td>
              <td width="125" height="30" align="center" valign="bottom" bgcolor="#0066AA">ขนาดไฟล์</td>
              <td width="75" height="30" align="center" valign="bottom" bgcolor="#0066AA">&nbsp;</td>
            </tr>
            <?
$objOpen = opendir(".");
while (($file = readdir($objOpen)) !== false)
{
	$filetype=filetype($file);
	$filesize=filesize($file);
if($filesize >1024000 ){
	$filesize=round($filesize/1024000,2)." Mb";
}else if($filesize >1024 ){
	$filesize=round($filesize/1024,2)." Kb";
}else{
	$filesize=$filesize." Byte";
}
?>
            <tr>
              <td height="25" align="left" bgcolor="#FFFFFF">&nbsp;
                <? if($filetype == "dir"){echo "Folder";}else{echo "File";}?>
                :
                <?=$file?></td>
              <td height="25" align="right" bgcolor="#FFFFFF"><? if($filetype != "dir"){echo "$filesize";}?>
                &nbsp;</td>
              <td height="25" align="center" bgcolor="#FFFFFF"><? if($filetype != "dir"){?>
                <a href="<?=$file?>" target="_blank">ดาวโหลด</a>
                <?}?></td>
            </tr>
            <?
}
?>
          </table></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>