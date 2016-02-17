<?php
session_start();
if(!isset($_SESSION['username']))
{
header("location:http://onlineevents.techtatva.in");
}
require_once("dbconnection.php");
$username=$_SESSION['username'];
$sql="select * from users where Username='$username'";
$result=mysqli_query($con,$sql);
$row=mysqli_fetch_array($result,MYSQL_ASSOC);
$x=$row['CurrentX'];
$y=$row['CurrentY'];
$count=count($x);
$send=array("x"=>$x,
	"y"=>$y,);
echo json_encode($send);
?>