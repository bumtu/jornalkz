<?php
require_once '../controlls/head.php';

if(!$loggedin) header("Location:../index.php");


echo <<<_HEAD
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>$appname</title>
    <link href="css/styles.css" rel="stylesheet">
    <link href="css/q.css" rel="stylesheet">
    <script src="js/app.js"></script>
</head>
<body>
_HEAD;

$result = queryMysql("SELECT * FROM jornalista WHERE contacto='$contact'");
$num = $result->rowCount();

while($row = $result->fetch())
{
    $idj = $row['id'];
    $nome = $row['nome'];
    $apelido = $row['sobrenom'];
    $foto = $row['imagem'];
    $ncompleto = $nome.' '.$apelido;
    $location = $row['localizacao'];
}


echo"<div id='root'>";

echo<<<_MENUBAR
    <div class='menu-bar'>
        <div class='posit-Hder'>
        <div class='appnm'><h2>$appname</h2></div>

        <div class='sttng-menu'>
            <a href='home.php'><div class='home-dr'>
                <img src='img/home.png' alt='$appname' class='hmIcon'>
                <span>Home</span>
            </div></a>

            <a href='#'><div class='home-dr'>
                <img src='img/setting.png' alt='$appname' class='hmIcon'>
                <span>Settings</span>
            </div></a>

            <div class='menu-user'>
                <img src='$foto' alt='$ncompleto' title='$ncompleto'>
            </div>
        </div>
    </div>
    </div>

    <!--<div class='toggle-W'>
            <a href='#'>Perfil</a>
            <a href='#'>Configuração</a>
            <a href='../controlls/session.php'>Sair</a>
    </div>-->
_MENUBAR;




?>