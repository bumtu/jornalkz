<?php
require_once 'functions.php';

if(isset($_GET['url'])){
    $ky = $_GET['url'];

    queryMysql("DELETE FROM noticias WHERE id='$ky'");
}
?>