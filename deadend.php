<?php
require_once("dbconnection.php");
session_start();
if(!isset($_SESSION['username']))
{
header("location:http://onlineevents.techtatva.in");
}
$username=$_SESSION['username'];
$time=time();
$sql="select * from users where Username='$username'";
$result=mysqli_query($con,$sql);
$row=mysqli_fetch_array($result,MYSQL_ASSOC);
$timestamp=$row['Timestamp'];
//TIMEOUT CHANGE
if((($time-$timestamp)/60)>=4)
{
	$qno=$row['QNo'];
	$sql="select * from questions where Id='$qno'";
$result=mysqli_query($con,$sql);
$row=mysqli_fetch_array($result,MYSQL_ASSOC);
$title=$row['Title'];
$story=utf8_encode($row['Story']);
	$q=utf8_encode($row['Question']);
	$image=$row['Image'];
	$sql="update users set Freeze='0', Timestamp='0',LastAnswered='0' where Username='$username'";
	$result=mysqli_query($con,$sql);
$send=array(
"status"=>"1",
"x"=>$x,
"y"=>$y,
"title"=>$title,
"story"=>$story,
"q"=>$q,
"image"=>$image,);
echo json_encode($send);	
}
else
{
$send=array("status"=>"0");
echo json_encode($send);
}
?>