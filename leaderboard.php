<?php
session_start();
if(!isset($_SESSION['username']))
header("location:http://onlineevents.techtatva.in");
require_once("dbconnection.php");
$username=$_SESSION['username'];
$sql="select * from users where Username='$username'";
$result=mysqli_query($con,$sql);
$row=mysqli_fetch_array($result,MYSQL_ASSOC);
$q=$row['QNo'];
$sql="select * from users where QNo>'$q'";
$result=mysqli_query($con,$sql);
$ahead=mysqli_num_rows($result);
$sql="select * from users where QNo<'$q'";
$result=mysqli_query($con,$sql);
$behind=mysqli_num_rows($result);
$sql="select * from users where QNo='$q'";
$result=mysqli_query($con,$sql);
$same=mysqli_num_rows($result);
$send=array(
"ahead"=>$ahead,
"behind"=>$behind,
"same"=>$same,
);
echo json_encode($send);
?>
