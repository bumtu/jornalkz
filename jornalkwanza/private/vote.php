<?php
require_once 'functions.php';

if(isset($_GET['votar']))
{
    $votar = sanitizeString($_GET['votar']);
    $user = sanitizeString($_GET['usr']);

    $rs = queryMysql("SELECT * FROM vote WHERE contentID='$votar' AND user='$user'");
    
    if($rs->rowCount()){
        queryMysql("DELETE FROM vote WHERE contentID='$votar' AND user='$user'");
    }else{
        queryMysql("INSERT INTO vote(contentID, user, tipo) VALUES('$votar', '$user', 'up')");
    }
    $ttlVotes = queryMysql("SELECT * FROM vote WHERE contentID='$votar'");
    echo $ttlVotes->rowCount() . " Ups";
}
?>