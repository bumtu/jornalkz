<?php
require_once 'functions.php';

if(isset($_GET['g']))
{
    $ghost = sanitizeString($_GET['g']);
    $id = sanitizeString($_GET['idg']);

    $res = queryMysql("SELECT * FROM noticias WHERE id='$id'");

    if(!$res->rowCount()){
        queryMysql("INSERT INTO noticias(anonimo) VALUES('$ghost') WHERE id='$id'");
    }else{
        queryMysql("UPDATE noticias SET anonimo='$ghost' WHERE id='$id'");
    }
    echo"
        <img src='../view/img/ghost.png'>
    ";
}
?>