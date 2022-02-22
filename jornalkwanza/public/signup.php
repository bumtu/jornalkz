<?php
require_once '../private/head.php';

echo <<<_HEAD
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>$appname</title>
    <link href="css/styles.css" rel="stylesheet">
    <link href="css/media.css" rel="stylesheet">
    <script src="js/app.js"></script>
</head>
<body>
_HEAD;



echo"<div id='root'>";

echo <<<_BANNER
    <div class='head-banner'>
        <div class='head-content'>
            <div class='app-head'><a href='../index.php'><div class='vlt'><img src='img/back.png'></div></a>
            <h1>$appname</h1></div>

        <div class='menu-session-app'>
            <a href='../index.php'><div class='linkHd'>Home</div></a>
        </div>

        </div>
    </div>
_BANNER;

//Sessao de cadastro.....
$copyright = RollingCopyrigh($appname);
$error = $username = $nome = $cont = $password = $location = "";

if(isset($_SESSION['username']))
destroySession();


if(isset($_POST['username']))
{   
    $username = sanitizeString($_POST['username']);
    $nome = sanitizeString($_POST['nome']);
    $cont = sanitizeString($_POST['contacto']);
    $password = sanitizeString($_POST['password']);
    $location = sanitizeString($_POST['location']);

    if($username == "" || $nome == "" || $cont == "" || $password == "" || $location == "")
    $error = "<span class='infoerror'>Existem campos vazios.</span><br>";
    else
    {
        $rs = queryMysql("SELECT * FROM users WHERE username='$username'");

        if($rs->rowCount())
        $error = "<span class='infoerror'>O nome de usuário ja existente.</span><br>";


        else{
        queryMysql("INSERT INTO users(username, nome, contacto, passw, localizacao) 
        VALUES('$username', '$nome', '$cont', '$password', '$location')");
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        die("<div class='sg-benvindo'>
            <b>Cadastro feito com sucesso clique <a href='../timeline.php'>aqui</a>
            para entrar.</b>
        </div></body></html>");
        }
    }

}





//Sessao de login.....
$errlg = $user = $pss = "";
if(isset($_POST['user'])){
    $user = sanitizeString($_POST['user']);
    $pss = sanitizeString($_POST['pss']);

    if($user == "" || $pss == "")
    $errlg = "<span class='infoerror'>Campos vazios.</span><br>";
    else{
        $rs = queryMysql("SELECT username,passw FROM users WHERE username='$user' AND 
        passw='$pss'");

        if($rs->rowCount() == 0){
            $errlg = "<span class='infoerror'>User ou palavra-passe invlidos.<span><br>";
        }else{
            $_SESSION['username'] = $user;
            $_SESSION['pss']  = $pss;
            die("<div class='sg-benvindo'>
                <b>Log in feito com sucesso clique <a href='../timeline.php'>aqui</a>
                para entrar.</b>
            </div></body></html>");
        }

    }
}

echo <<<_SIGNUP
    <div class='sign-users'>

        <div class='sign-descricao'>
            <p>Use os seus dados para se cadastrar ou fazer log in.</p>
        </div>


        <div class='sign-date'>
            <form method='POST' action='signup.php'>$error
                <input type='text' name='username' placeholder='Nome de usuário' class='sign-user' /><br>
                <input type='text' name='nome' placeholder='Nome Completo' class='sign-user' /><br>
                <input type='text' name='contacto' placeholder='E-mail ou Telefone' class='sign-user' /><br>
                <input type='text' name='password' placeholder='Palavra-passe' class='sign-user' maxlength='12'/><br>
                <input type='text' name='location' placeholder='Provincia' class='sign-user' /><br>
                <input type='submit' value='Cadastrar' class='btn-logar' id='btn-sg'>
            </form>
        </div>


        <div class='login-date'>
            <form method='POST' action='signup.php'>$errlg
                <input type='text' name='user' placeholder='Nome de usuário' class='sign-user' /><br>
                <input type='password' name='pss' placeholder='Palavra-passe' class='sign-user' /><br>
                <input type='submit' value='Entrar' class='btn-logar' id='btn-lg'>
            </form>
        </div>
        <div class='copyright-signup'>$copyright</div>
    </div>
_SIGNUP;


?>
</div></body></html>