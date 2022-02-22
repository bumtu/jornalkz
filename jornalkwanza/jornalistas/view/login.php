<?php
require_once '../controlls/head.php';
ob_start();

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

$error = $contact = $pass = "";
if(isset($_POST['contacto'])){
    $contact = sanitizeString($_POST['contacto']);
    $pass = sanitizeString($_POST['pass']);

    if($contact == "" || $pass == "")
    $error = 'Not all fields entered<br>';
    else{
        $result = queryMysql("SELECT contacto, password FROM jornalista WHERE contacto='$contact' AND password='$pass'");

        if($result->rowCount() == 0){
            $error = 'Login Invalido<br>';
        }
        else{
            $_SESSION['jornalist']   = $contact;
            $_SESSION['pass']        = $pass;
            header("Location:home.php");
        }
    }
}

$copyright = RollingCopyrigh("$appname");

echo<<<_LOGIN
<div class='lgview' id='loginZone'>
<div class='frm'>

    <div class='appname'><h1>$appname</h1>
    <h4>Use seus dados para fazer o Log in.</h4>
    </div>

    <form method='POST' action='login.php?r=$randstr'>$error
       
        <input type='text' name='contacto' placeholder='Telefone ou E-mail' class='lgField'><br>

       
        <input type='password' name='pass' placeholder='Password' class='lgField' maxlength='8'><br>


        <input type='submit' value='Entrar' class='sigBtt'>
    </form>
    <div class='lgaqui'>Ainda sem conta? Cadastre-se <a href='../index.php'>aqui</a></div>
    <div class='cpright'>$copyright</div>
</div>
</div>
_LOGIN;

ob_end_flush();
?>
</body></html>