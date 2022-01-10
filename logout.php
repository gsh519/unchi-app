<?php

session_start();

$_SESSION['user_name'] = false;
$_SESSION['user_id'] = false;

header("Location: ./login.php");
