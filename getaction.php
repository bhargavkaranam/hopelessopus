<?php
session_start();
$session=$_SESSION['action'];
unset($_SESSION['action']);
echo $session;
?>