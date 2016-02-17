<?php
require_once("dbconnection.php");
session_start();
$username=$_SESSION['username'];
$sql="update users set QNo='1',Log='0',LastAnswered='0',CurrentState='-1' where Username='$username'";
$result=mysqli_query($con,$sql);
?>