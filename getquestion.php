<?php
session_start();
require_once("dbconnection.php");
$username=$_SESSION['username'];
$sql="select * from users where Username='$username'";
	$result=mysqli_query($con,$sql);
	$row=mysqli_fetch_array($result,MYSQL_ASSOC);
	$qno=$row['QNo'];
	$sql1="select * from questions where Id='$qno'";
	$result1=mysqli_query($con,$sql1);
	$row1=mysqli_fetch_array($result1,MYSQL_ASSOC);
	$story=$row1['Story'];
	$question=$row1['Question'];
?>
