<?
$Time = time();
echo $Time."<br><br>";
$Date = date('Y-m-d H:i:s',$Time);
echo $Date."<br><br>";
$Time2 = time($Date);
echo $Time2;
?>