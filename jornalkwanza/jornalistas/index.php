<?php
require_once 'controlls/head.php';

echo <<<_HEAD
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>$appname</title>
    <link href="view/css/styles.css" rel="stylesheet">
    <link href="view/css/q.css" rel="stylesheet">
    <script src="view/js/app.js"></script>
</head>
<body>
_HEAD;

$error = $nome = $apelido = $contact = $pass = $provinc = "";
if(isset($_SESSION['jornalist']))
destroySession();

$codeJornal = ["jk0001-21", "jk0002-21",
"jk0003-21", "jk0004-21", "jk0005-21", "jk0006-21",
"jk0007-21", "jk0008-21", "jk0009-21", "jk00010-21",
"jk00011-21", "jk00012-21", "jk00013-21"
];

if(isset($_POST['code'])){
    $code = sanitizeString($_POST['code']);
    $nome = sanitizeString($_POST['nome']);
    $apelido = sanitizeString($_POST['aplid']);
    $contact = sanitizeString($_POST['contacto']);
    $pass = sanitizeString($_POST['pass']);
    $provinc = sanitizeString($_POST['provincia']);

    if(in_array($code, $codeJornal)){
        $cvalidar = 'pertence';
    }else $cvalidar = 'diferente';

    if($code == "" || $nome == "" || $apelido == "" || $contact == "" || $pass == "" || $provinc == "")
    $error = 'Not all field were entered<br>';
    else{
        $result = queryMysql("SELECT * FROM jornalista WHERE codigovalido='$code'");

        if($result->rowCount())
        $error = 'The code already exists<br>';
        if($cvalidar != 'pertence')
        $error = 'Codigo invalido<br>';
        else{
            queryMysql("INSERT INTO jornalista(codigovalido, nome, sobrenom, contacto, password, imagem, localizacao) 
            VALUES('$code','$nome', '$apelido', '$contact', '$pass', 'jornalists/null.png', '$provinc')");
            $_SESSION['jornalist']   = $contact;
            $_SESSION['pass']        = $pass;

            die('<div class="wellcome"><p>' . $appname . '</p><br>Conta criada com sucesso. Por favor
            <a href="view/upload.php?view='. $nome .'&r='. $randstr  .'">Click aqui</a> para continuar.</div></body></html>');
        }
    }

}

$copyright = RollingCopyrigh("$appname");

echo<<<_CORPO
    <div class='indexBd'>

    
        <div class='fview' id='descApp'>
            <div class='greeting'>
                <p>Impressione seus usuários com as melhores 
                noticias da atualidade, e ganhe mais presença no jornalismo 
                profissional.
                </p>
            </div>
        </div>


        <div class='fview' id='loginArea'>
            <div class='frm'>

                <div class='appname'><h1>$appname</h1>
                <h4>Criar uma conta personalizada.</h4>
                </div>

                <form method='POST' action='index.php'>$error
                   
                    <input type='text' name='code' placeholder='Codigo' class='lgField'><br>

                    
                    <input type='text' name='nome' placeholder='Nome' class='lgField'><br>

                    
                    <input type='text' name='aplid' placeholder='Apelido' class='lgField'><br>

                    
                    <input type='text' name='contacto' placeholder='Telefone ou E-mail' class='lgField'><br>

                   
                    <input type='password' name='pass' placeholder='Password' class='lgField' maxlength='8'><br>

                    
                    <input type='text' name='provincia' placeholder='Provincia' class='lgField'><br>

                    <input type='submit' value='Cadastrar' class='sigBtt'>
                </form>
                <div class='lgaqui'>Já tem uma conta? Entre <a href='view/login.php'>aqui</a></div>
                <div class='cpright'>$copyright</div>
            </div>
        </div>
    </div>
_CORPO;

?>
</body></html>