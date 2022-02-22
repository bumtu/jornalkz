<?php
session_start();
require_once 'functions.php';

$randstr = substr(md5(rand()), 0, 7);

if(isset($_SESSION['username'])){
    $username = $_SESSION['username'];
    $loggedin = TRUE;
} else $loggedin = FALSE;
?>