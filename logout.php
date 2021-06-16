


<?php
include("includes/db.php");
include_once("includes/navbar.php");

session_destroy();
unset($_SESSION['user_id']);
unset($_SESSION['username']);
unset($_SESSION['role']);
header("location: login.php");
