<?php
require_once 'private/head.php';

echo <<<_HEAD
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>$appname</title>
    <link href="public/css/styles.css" rel="stylesheet">
    <link href="public/css/media.css" rel="stylesheet">
    <script src="public/js/app.js"></script>
</head>
<body>
_HEAD;

echo"<div id='root'>";

if(!$loggedin){
    echo <<<_BANNER
    <div class='head-banner'>
        <div class='head-content'>
            <div class='app-head'><h1>$appname</h1></div>

        <div class='menu-session-app'>
            <a href='timeline.php'><div class='linkHd'>Timeline</div></a>
            <a href='public/signup.php'><div class='btn-login'>Entrar</div></a>
            <a href='#'><div class='menu-login'><img src='public/img/pull_down.png'></div></a>
        </div>

        </div>
    </div>

    <div class='menu-device'>
        <div class='dev-items'><a href='index.php'>Home</a></div>
        <div class='dev-items' id='enter'><a href='public/signup.php'>Entrar</a></div>
        <div class='dev-items'><a href='#'>Local</a></div>
        <div class='dev-items'><a href='#'>Seguindo</a></div>
    </div>


    <script>
        const btnDrop = document.querySelector('.menu-login')
        const menuDrop = document.querySelector('.menu-device')

        btnDrop.addEventListener('click', function(){
            menuDrop.classList.toggle('openDrop')
        })

    </script>
_BANNER;
}else{
    $result = queryMysql("SELECT * FROM users WHERE username='$username'");
    while($row = $result->fetch()){
    $user = $row['username'];
    }
    echo <<<_BANNER
    <div class='head-banner'>
        <div class='head-content'>
            <div class='app-head'><h1>$appname</h1></div>

        <div class='menu-session-app'>
            <a href='timeline.php'><div class='linkHd'>$user</div></a>
            <a href='private/session.php'><div class='btn-login'>Sair</div></a>
        </div>

        </div>
    </div>

    <div class='menu-device'>
        <div class='dev-items'><a href='index.php'>Home</a></div>
        <div class='dev-items' id='enter'><a href='#'>Entrar</a></div>
        <div class='dev-items'><a href='public/local.php'>Local</a></div>
        <div class='dev-items'><a href='public/seguindo.php'>Seguindo</a></div>
    </div>


    <script>
        const btnDrop = document.querySelector('.menu-login')
        const menuDrop = document.querySelector('.menu-device')

        btnDrop.addEventListener('click', function(){
            menuDrop.classList.toggle('openDrop')
        })

    </script>
_BANNER;
}


$copyright = RollingCopyrigh($appname);
$result = queryMysql("SELECT * FROM noticias WHERE id='1'");
$num = $result->rowCount();

while($row=$result->fetch()){
    $ky = $row['id'];
    $imagem = $row['imagem'];
    $text = $row['desenvolvimento'];
}

echo "<div class='all-content'  
style='border-radius: 10px;'>";

    echo "
        <div class='manchete'>
            <div class='manchete-dia'><h1>Manchete do dia.</h1></div>
            <a href='public/destaque.php?id=$ky'><img src='jornalistas/view/$imagem' alt='#'>
            <div class='txt-resumo'>
                "; echo TextTruncate($text, 250, "..."); echo"
            </div></a>


            <div class='footer-items'>
                <div class='call-center'>$copyright | Clique <a href='contactos.php'>aqui</a> e torna-te um jornalista editor</div>
            </div>



        </div>
    ";


echo "</div>"; //fim da div all-contente.

?>
</div><!-- Fim da div root. -->
</body></html>