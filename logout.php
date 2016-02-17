<?php
session_start();
unset($_SESSION['username']);
header("location:http://onlineevents.techtatva.in");
?>