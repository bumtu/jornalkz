<?php
require_once 'head.php';

if(isset($_SESSION['username']))
{
    destroySession();
    header("Location:../index.php");
}
?>