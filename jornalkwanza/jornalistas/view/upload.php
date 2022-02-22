<?php
require_once '../controlls/head.php';
ob_start();

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


echo"<div id='root' style='height:100vh'>";

echo<<<_MENUBAR
    <div class='menu-bar'>
        <div class='posit-Hder'>
        <div class='appnm'><h2>$appname</h2></div>

        <div class='sttng-menu'>
            <a href='home.php' style='visibility:hidden;'><div class='home-dr'>
                <img src='img/home.png' alt='$appname' class='hmIcon'>
                <span>Home</span>
            </div></a>

            <a href='#' style='visibility:hidden;'><div class='home-dr'>
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

    $error = "";
    if(isset($_FILES['foto']['name'])){
        $jFoto = 'jornalists/' . $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], $jFoto);
        $typeok = TRUE;

        switch($_FILES['foto']['type']){
            case "image/gif": $src = imagecreatefromgif($jFoto);
                break;
            case "image/jpeg":
            case "image/pjpeg": $src = imagecreatefromjpeg($jFoto);
                break;
            case "image/png": $src = imagecreatefrompng($jFoto);
                break;
            default:          $typeok = FALSE; 
                break;
        }

        if($typeok)
        {
            list($w, $h) = getimagesize($jFoto);

            $max = 800;
            $tw = $w;
            $th = $h;
            
            if($w > $h && $max < $w)
            {
                $th = $max / $w * $h;
                $tw = $max;
            }
            elseif($h > $w && $max < $h)
            {
                $tw = $max / $h * $w;
                $th = $max;
            }
            elseif($max < $w)
            {
                $tw = $th = $max;
            }

            $tmp = imagecreatetruecolor($tw, $th);
            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
            imageconvolution($tmp, array(array(-1, -1, -1),
            array(-1, 16, -1),
            array(-1, -1, -1)), 8, 0);
            imagejpeg($tmp, $jFoto);
            imagedestroy($tmp);
            imagedestroy($src);
        }


        if($_FILES['foto']['name'] != ""){
            queryMysql("UPDATE jornalista SET imagem='$jFoto' WHERE contacto='$contact'");
            header("Location:home.php");
        }else{
            $error = "<span style='color: red;'>Nenhum ficheiro carregado.</span><br>";
        }

    }




echo<<<_BD
<div class='jr-bodyApp'>
    <div class='jl-session' id='jl-data' style='visibility:hidden'>
         <div class='proFoto'><img src='$foto' alt='$ncompleto' title='$ncompleto'></div>
          <div class='fullName'>$ncompleto</div>
          <div class='seguidores'>Seguidores<br></div>
          <div class='logout'><a href='../controlls/session.php'>Terminar Sessão</a></div>
    </div>



    <div class='jl-session' id='jl-post'>
    <div class='agrup-formP' id='upload-medi'>
        <div class='hd-descrition'>
            <h2 style='margin-bottom: 5px;'>Carregar Fotografia</h2>
            <p style='color: gray;'>Ficheiros permitidos PNG, JPG</p>

            <div class='foto-load' id='message'>
                <img src='img/314245_cloud_upload_icon.png'>
            </div>
        </div>
        <div class='f-upload'>$error
            <form method='POST' action='upload.php' enctype='multipart/form-data'>
                <label for='carregar' style='cursor: pointer;'><b>Escolher imagem</b></label><br><br>
                <input type='file' name='foto' id='carregar'>
                <button class='btn-load-img'>Carregar Imagem</button>
            </form>


            <script>
                function listFiles(e)
                {
                    var files = e.target.files;
                    var fileMessage = '<br><strong>SELECTED FILES</strong></br>';

                    for(var i = 0; i < files.length; i++){
                        var file = files[i];
                        //fileMessage += '<strong>' + file.name + '</strong> ' + file.type + 
                        ' - ' + file.size + ' bytes<br>';

                        if(file.type.match('image.*')){
                            displayImage(file);
                        }
                    }
                    document.getElementById('message').innerHTML = '';
                }


                function displayImage(file){
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        var img = new Image();
                        img.src = e.target.result;
                        img.style.height = '100%';
                        img.style.width = '100%';
                        document.getElementById('message').appendChild(img);
                    };
                    reader.readAsDataURL(file);
                }


                var el = document.getElementById('carregar');
                el.accept = 'image/*';
                document.getElementById('carregar').addEventListener('change', listFiles, false);
            </script>



        </div>
    </div>
</div>
_BD;


ob_end_flush();
?>

<script>
    const mDev = document.getElementById('mnun');
    const menuDevice = document.querySelector('.toggle-W');

    mDev.addEventListener('click', alerta)

    function alerta(){
        menuDevice.classList.toggle('hidde')
    }
</script>

</div>
</body></html>