<?php
require_once("dbconnection.php");
$text=mysqli_real_escape_string($con,$_GET['text']);
session_start();
if(!isset($_SESSION['username']))
{
header("location:http://onlineevents.techtatva.in");
}

$username=$_SESSION['username'];
$sql="select * from users where Username='$username'";
$result=mysqli_query($con,$sql);
$row=mysqli_fetch_array($result,MYSQL_ASSOC);
$la=$row['LastAnswered'];
if($la==$text)
{
	$send=array("status"=>"911");
	echo json_encode($send);
	die();
}
$sql="update users set LastAnswered='$text' where Username='$username'";
$result=mysqli_query($con,$sql);
$sql="select * from users where Username='$username'";
$result=mysqli_query($con,$sql);
$row=mysqli_fetch_array($result,MYSQL_ASSOC);
$qno=$row['QNo'];
if($qno==1)
{
	$temp=array("1");
	$store=serialize($temp);
	$sql="update users set Log='$store' where Username='$username'";
	$result=mysqli_query($con,$sql);
}
$sql="select * from users where Username='$username'";
$result=mysqli_query($con,$sql);
$row=mysqli_fetch_array($result,MYSQL_ASSOC);
$store=unserialize($row['Log']);
$sql2="select * from locations where Location='$text' and QNo='$qno'";
$result2=mysqli_query($con,$sql2);
$row2=mysqli_fetch_array($result2,MYSQL_ASSOC);
$stat=$row2['Status'];
$count=mysqli_num_rows($result2);
if($count==0) 
{
	die();
}
$status=$row2['Status'];
$action=$row2['Action'];
$destination=$row2['Destination'];
$x=$row2['X'];
$y=$row2['Y'];
$image=$row2['Image'];
$sql="select * from users where Username='$username'";
$result=mysqli_query($con,$sql);
$row=mysqli_fetch_array($result,MYSQL_ASSOC);
if($status==0)
{
	$time=time();
	$sql="update users set Freeze='1',Timestamp='$time',CurrentX='$x',CurrentY='$y' where Username='$username'";
	$result=mysqli_query($con,$sql);
	$action1=utf8_encode($action);
	$send=array("status"=>"000",
		"story"=>$action1,
		"q"=>"You're Dead. Try again after ...",
		"x"=>$x,
		"y"=>$y,
		"time"=>"4",
		"image"=>$image,);
	echo json_encode($send);
}
if($status==1)
{
$qno++;
array_push($store,$qno);
$temp_store=serialize($store);
$sql="update users set Log='$temp_store',CurrentX='$x',CurrentY='$y',QNo='$qno',CurrentState='0' where Username='$username'";
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
"status"=>"111",
"x"=>$x,
"y"=>$y,
"title"=>$title,
"story"=>$story,
"q"=>$q,
"image"=>$image,);
echo json_encode($send);	
}	
if($status==2)
{
	$sql="select * from users where Username='$username'";
	$result=mysqli_query($con,$sql);
	$row=mysqli_fetch_array($result,MYSQL_ASSOC);
	$currentstate=$row['CurrentState'];
if($currentstate==0)
{
$qno++;
}
if($currentstate==-1)
{
	$qno=$qno+2;
}
array_push($store,$qno);
$temp_store=serialize($store);

$sql="update users set Log='$temp_store',CurrentX='$x',CurrentY='$y',QNo='$qno',CurrentState='-1' where Username='$username'";
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
"status"=>"222",
"x"=>$x,
"y"=>$y,
"title"=>$title,
"story"=>$story,
"q"=>$q,
"image"=>$image,
);
echo json_encode($send);
}
?>
