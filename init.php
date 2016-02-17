<?php
require_once("dbconnection.php");
session_start();
$username=$_SESSION['username'];
$sql="select * from users where Username='$username'";
	$result=mysqli_query($con,$sql);
	$count=mysqli_num_rows($result);
	if($count==0)
	{
		$sql="insert into users(Username,QNo,CurrentX,CurrentY,CurrentState,Log,Freeze,LastAnswered,Timestamp) values('$username','1','903','652','-1','0','0','0','0')";
		$result=mysqli_query($con,$sql);
	}
?>