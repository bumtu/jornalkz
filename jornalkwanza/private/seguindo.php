<?php
require_once 'functions.php';

if(isset($_GET['url'])){
    $fallw = sanitizeString($_GET['url']);
    $j     = sanitizeString($_GET['j']);

    $f = queryMysql("SELECT * FROM seguidores WHERE user='$fallw' AND jornalist='$j'");

    if($f->rowCount()){
        queryMysql("DELETE FROM seguidores WHERE user='$fallw' AND jornalist='$j'");
        echo "Seguir";
    }else{
        queryMysql("INSERT INTO seguidores(user, jornalist) VALUES('$fallw','$j')");
        echo "Seguindo";
    }
    
}
?>