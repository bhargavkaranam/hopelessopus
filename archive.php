<?php
session_start();
$username=$_SESSION['username'];
require_once("dbconnection.php");
$sql="select * from users where Username='$username'";
$result=mysqli_query($con,$sql);
$row=mysqli_fetch_array($result,MYSQL_ASSOC);
$log=unserialize($row['Log']);
$len=count($log);
for($i=0;$i<$len;$i++)
{
	$sql="select * from questions where Id='$log[$i]'";
	$result=mysqli_query($con,$sql);
	$row=mysqli_fetch_array($result,MYSQL_ASSOC);
	$s=$row['Story'];
	echo($s."<br>");
}
?>