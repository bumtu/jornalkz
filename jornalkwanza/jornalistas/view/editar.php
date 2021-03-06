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


echo"<div id='root' style='height:100vh;'>";

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

            <div class='menu-user musr'>
                <img src='$foto' alt='$ncompleto' title='$ncompleto'>
            </div>

            <div class='home-dr' id='mnun'>
                <img src='img/menu.png' alt='$appname' class='hmIcon'>
            </div>

        </div>
    </div>
    </div>

    <div class='toggle-W'>
            <div class='each-link'><a href='home.php'>Home</a></div>
            <div class='each-link'><a href='#'>Seguidores</a></div>
            <div class='each-link'><a href='#'>Settings</a></div>
            <div class='each-link' id='each-l'><a href='../controlls/session.php'>Sair</a></div>
    </div>
_MENUBAR;


//Mostrar numero de seguidores
$segidores = queryMysql("SELECT * FROM seguidores WHERE jornalist='$idj'");
if($segidores->rowCount())
    $seg = $segidores->rowCount();
else $seg = $segidores->rowCount();


if(isset($_GET['k'])){

    $i = sanitizeString($_GET['k']);
    $r = queryMysql("SELECT * FROM noticias WHERE id='$i'");

    if($r->rowCount()){
        $row = $r->fetch();
        $notUp = $row['desenvolvimento'];
        $idnUp = $row['id'];
    }

    if(isset($_POST['noticia'])){
        $notUp = sanitizeString($_POST['noticia']);

        if($notUp != ""){
            queryMysql("UPDATE noticias SET desenvolvimento='$notUp' WHERE id='$idnUp'");
        }
    }

    echo<<<_BODY
    <div class='jr-bodyApp'>
        <div class='jl-session' id='jl-data'>
            <div class='proFoto'><img src='$foto' alt='$ncompleto' title='$ncompleto'></div>
            <div class='fullName'>$ncompleto</div>
            <div class='seguidores'>Seguidores<br>$seg</div>
            <div class='logout'><a href='../controlls/session.php'>Terminar Sess??o</a></div>
        </div>


        <div class='jl-session' id='jl-post'>
        <div class='agrup-formP'>
            <form method='POST' action='editar.php?k=$idnUp.php' enctype='multipart/form-data'>
            <div class='edt-topico'><h3>Editar Noticia</h3></div>
            <textarea class='txtarea' name='noticia' placeholder='Desenvolva a sua noticia aqui.' cols='55' rows='10'>$notUp</textarea><br>
            <input type='submit' value='Atualizar Noticia' class='pblic-not' onclick='edtS();'>
            </form>
            <div class='back'><img src='img/arrow-back.png' style='float: left; width:20px; height: 20px;'>
            <a href='home.php'>Voltar a pagina inicial</a></div>
        </div>

_BODY;
}

?>

<script>
    function edtS(){
        alert("Texto atualizado com sucesso.");
    }
</script>

<script>
    const mDev = document.getElementById('mnun');
    const menuDevice = document.querySelector('.toggle-W');

    mDev.addEventListener('click', alerta)

    function alerta(){
        menuDevice.classList.toggle('hidde')
    }
</script>



</div></body></html >