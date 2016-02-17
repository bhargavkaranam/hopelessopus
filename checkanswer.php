<?php
session_start();
if(!isset($_SESSION['username']))
{
header("location:http://onlineevents.techtatva.in");
}
require_once("dbconnection.php");
$username=$_SESSION['username'];
$answer=strtolower($_GET['answer']);
$answer=mysqli_real_escape_string($con,$answer);
$sql="select * from users where Username='$username'";
$result=mysqli_query($con,$sql);
$row=mysqli_fetch_array($result,MYSQL_ASSOC);
$qno=$row['QNo'];
$freeze=$row['Freeze'];
if($freeze==1)
{
	die();
}
$sql1="select * from questions where Id='$qno'";
$result1=mysqli_query($con,$sql1);
$row1=mysqli_fetch_array($result1,MYSQL_ASSOC);
$check=strtolower($row1['Answer']);
$location1=utf8_encode($row1['Location1']);
$location2=utf8_encode($row1['Location2']);
$location3=utf8_encode($row1['Location3']);
$location=array();
array_push($location,$location1,$location2,$location3);
$loc=$location;
shuffle($location);
similar_text($check, $answer, $percentage);
if (number_format($percentage, 0) > 90){ 
	if($qno==37 || $qno==72)
	{

		$qno++;
		$sql="select * from users where Username='$username'";
$result=mysqli_query($con,$sql);
$row=mysqli_fetch_array($result,MYSQL_ASSOC);
$store=unserialize($row['Log']);
array_push($store,$qno);
$temp_store=serialize($store);
//SET NEXT X AND NEXT Y
$sql="update users set Log='$temp_store',CurrentX='700',CurrentY='700',QNo='$qno',CurrentState='-1' where Username='$username'";
$result=mysqli_query($con,$sql);
$destination=strtolower($destination);

$sql="select * from questions where Id='$qno'";
$result=mysqli_query($con,$sql);
$row=mysqli_fetch_array($result,MYSQL_ASSOC);
$title=$row['Title'];
$story=utf8_encode($row['Story']);
	$q=utf8_encode($row['Question']);
	$image=$row['Image'];
	
$send=array(
"status"=>"900",
"x"=>"700",
"y"=>"800",
"title"=>$title,
"story"=>$story,
"q"=>$q,
"image"=>$image,);
echo json_encode($send);	
	}
	else
	{
	$location_store=serialize($loc);
	$sql="update users set Locations='$location_store' where Username='$username'";
	$result=mysqli_query($con,$sql);
	$return=array(
		"status"=>"1",
		"location1"=>$location[0],
		"location2"=>$location[1],
		"location3"=>$location[2],
		);
	echo json_encode($return);
	die();
}
}
echo 0;
?>