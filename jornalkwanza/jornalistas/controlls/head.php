<?php
session_start();
require_once 'functions.php';

$randstr = substr(md5(rand()), 0, 7);

if(isset($_SESSION['jornalist'])){
    $contact = $_SESSION['jornalist'];
    $loggedin = TRUE;
} else $loggedin = FALSE;
?>