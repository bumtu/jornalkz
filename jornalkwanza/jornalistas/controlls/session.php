<?php
require_once 'head.php';

if(isset($_SESSION['jornalist'])){
    destroySession();
    header("Location:../view/login.php");
}
?>