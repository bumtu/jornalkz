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


$segidores = queryMysql("SELECT * FROM seguidores WHERE jornalist='$idj'");
if($segidores->rowCount())
    $seg = $segidores->rowCount();
else $seg = $segidores->rowCount();




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
            <div class='each-link'><a href='#'>Seguidores ($seg)</a></div>
            <div class='each-link'><a href='#'>Settings</a></div>
            <div class='each-link' id='each-l'><a href='../controlls/session.php'>Sair</a></div>
    </div>
_MENUBAR;



if(isset($_POST['noticia'])){
    $noticia = sanitizeString($_POST['noticia']);
    $time = time();

    if(isset($_FILES['imgnoti']['name'])){
        $imgSave = 'anexos/' . $_FILES['imgnoti']['name'];
        move_uploaded_file($_FILES['imgnoti']['tmp_name'], $imgSave);
        $typeok = TRUE;

        switch($_FILES['imgnoti']['type']){
            case "image/gif": $src = imagecreatefromgif($imgSave);
                break;
            case "image/jpeg":
            case "image/pjpeg": $src = imagecreatefromjpeg($imgSave);
                break;
            case "image/png": $src = imagecreatefrompng($imgSave);
                break;
            default:          $typeok = FALSE; 
                break;
        }

        if($typeok)
        {
            list($w, $h) = getimagesize($imgSave);

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
            imagejpeg($tmp, $imgSave);
            imagedestroy($tmp);
            imagedestroy($src);
        }

    }

    if($noticia != ""){
        queryMysql("INSERT INTO noticias(imagem, jorid, jornalist, time, zona, desenvolvimento) 
        VALUES('$imgSave', '$idj', '$contact', '$time', '$location', '$noticia')");
    }
}



echo<<<_BODY
    <div class='jr-bodyApp'>
        <div class='jl-session' id='jl-data'>
            <div class='proFoto'><img src='$foto' alt='$ncompleto' title='$ncompleto'></div>
            <div class='fullName'>$ncompleto</div>
            <div class='seguidores'>Seguidores<br>($seg)</div>
            <div class='logout'><a href='../controlls/session.php'>Terminar Sessão</a></div>
        </div>


        <div class='jl-session' id='jl-post'>
        <div class='agrup-formP'>
            <form method='POST' action='home.php' enctype='multipart/form-data'>
            <div class='edt-topico'><h3>Editar Noticia</h3></div>
            <textarea class='txtarea' name='noticia' placeholder='Desenvolva a sua noticia aqui.' cols='55' rows='10'></textarea><br>
            <label for='imgLoad' style='cursor: pointer; font-weight: bold; font-size: 13px;'>Adicionar uma imagem:</label>
            <input type='file' name='imgnoti' size='14' id='imgLoad'><br><br>
            <input type='submit' value='Publicar Noticia' class='pblic-not'>
            </form>
        </div>

_BODY;

$posts = queryMysql("SELECT * FROM noticias WHERE jornalist='$contact' ORDER BY id DESC");
$numP = $posts->rowCount();

if($numP == 0){
    echo "<div class='all-notic-post' id='empty'>
    <div class='all-notic-post'><div class='blocos-Not'>

        <center><h2 style='color:#636363;'>Crie postagens, informe ao mundo.</h2></center>

    </div>
    </div></div>";
}else{

    echo "<div class='all-notic-post' id='all-notic'>";

        while($row = $posts->fetch())
        {
            $id = $row['id'];
            $imagem = $row['imagem'];
            $jornalist = $row['jornalist'];
            $time = $row['time'];
            $desenvlv = $row['desenvolvimento'];

            $horas = date('M jS \'y G:i', $time);


            //numero de likes(up)
            $nLike =queryMysql("SELECT * FROM vote WHERE contentID='$id'");

            if($nLike->rowCount()){
                $numV = $nLike->rowCount();
            }else{
                $numV = $nLike->rowCount();
            }

            //numero de comentarios
            $coment = queryMysql("SELECT * FROM comments  WHERE postID='$id'");

            if($coment->rowCount()){
                $c = $coment->rowCount();
            }else $c = $coment->rowCount();


            //query Anonimos
            $anonimo = queryMysql("SELECT id,anonimo FROM noticias WHERE id='$id' AND anonimo='ghost'");

                if($anonimo->rowCount() == ""){
                    $fotonew = $foto;
                    $ncompletonew = $ncompleto;
                }else{
                    $fotonew = 'img/ghost.png';
                    $ncompletonew = 'Fantasminha';
                }



            if($imagem != "anexos/")
            {
                echo "
                <div class='blocos-Not'>
                <div class='head-post'>
                    <a href='javascript:void(0)' id='clk_$id' onclick='editM($id)'><div class='menu-post'><img src='img/menu_vertical.png'></div></a>
                    <div class='ed-menu' id='m$id' style='display:none;'>
                        <ul>
                        <li><div class='mjr'><img src='img/delete.png'></div><a href='javascript:void(0)' onclick='del($id)'>Apagar</a></li><hr style='margin-bottom: 15px;'>
                        <li><div class='mjr'><img src='img/edit.png'></div><a href='editar.php?k=$id'>Editar</a></li><hr style='margin-bottom: 15px;'>
                        <li><div class='mjr'><img src='img/invisible.png'></div><a href='javascript:void(0)' onclick='hidde("; echo'"ghost", ' . "$id)' >Esconder ID</a></li>";
                       echo"</ul>
                    </div>
                    <div class='id-post'>
                        <div class='bloco-id-user'><div class='id-user-post' id='idJr$id'>$ncompletonew</div>
                        <div class='time'>$horas</div></div>
                        <div class='post-user-foto' id='ftJr$id'><img src='$fotonew' alt='$appname'></div>
                    </div>
                </div>

                <div class='post-img-noti'>
                    <img src='$imagem' alt='$ncompleto'>
                </div>

                <div class='num-like' id='num-l'>
                    
                    <div class='n-l'>
                        <img src='img/sort_up.png'>
                    </div>
                    <span class='txt-l' style='margin-right:20px; margin-left:5px; margin-top:10px;'>$numV Ups</span>
                    



                    <div class='n-l'>
                    <a href='comentarios.php?k=$id' style='margin-top: 10px; color: black; text-decoration: nome;'><img src='img/comments.png'>
                    </div>
                    <span class='txt-l' style='margin-left:5px; margin-top:10px;'>$c Comentarios</span></a>
                </div>

                <div class='post-desenvlvmento'>
                $desenvlv
                </div>
                </div>
                ";
            }else{
                echo "
                <div class='blocos-Not'>
                <div class='head-post' style='border-bottom:none;'>
                    <a href='javascript:void(0)' id='clk_$id' onclick='editM($id)'><div class='menu-post'><img src='img/menu_vertical.png'></div></a>
                    <div class='ed-menu' id='m$id' style='display:none;'>
                        <ul>
                            <li><div class='mjr'><img src='img/delete.png'></div><a href='javascript:void(0)' onclick='del($id)'>Apagar</a></li><hr style='margin-bottom: 15px;'>
                            <li><div class='mjr'><img src='img/edit.png'></div><a href='editar.php?k=$id'>Editar</a></li><hr style='margin-bottom: 15px;'>
                            <li><div class='mjr'><img src='img/invisible.png'></div><a href='javascript:void(0)' onclick='hidde("; echo'"ghost", ' . "$id)' >Esconder ID</a></li>";
                       echo"</ul>
                    </div>
                    <div class='id-post'>
                    <div class='bloco-id-user'><div class='id-user-post' id='idJr$id'>$ncompletonew</div>
                    <div class='time'>$horas</div></div>
                        <div class='post-user-foto' id='ftJr$id'><img src='$fotonew' alt='$appname'></div>
                    </div>
                </div>


                <div class='num-like' id='num-l'>
                    
                    <div class='n-l'>
                        <img src='img/sort_up.png'>
                    </div>
                    <span class='txt-l' style='margin-right:20px; margin-left:5px; margin-top:10px;'>$numV Ups</span>
                    



                    <div class='n-l'>
                    <a href='comentarios.php?k=$id' style='margin-top: 10px; color: black; text-decoration: nome;'><img src='img/comments.png'>
                    </div>
                    <span class='txt-l' style='margin-left:5px; margin-top:10px;'>$c Comentarios</span></a>
                </div>


                <div class='post-desenvlvmento'>
                $desenvlv
                </div>
                </div>
                ";
            }
        }

    }

    echo"</div></div>";

        
    echo"<div class='jl-session' id='jl-extraDt'></div>
    </div>";


?>




<script>
    function editM(m)
    {
        const menuClik = document.getElementById('clk_'+m)
        const menuEd = document.getElementById('m'+m)
        menuClik.addEventListener('click', openM(this.mnu), false)

        function openM(mnu){
            menuEd.style.display = (menuEd.style.display === 'none') ? 'block' : 'none'
        }
    }
</script>



<script>
    function del(k)
    {
        GetAjaxRequest(callback, '../controlls/delete.php', 'url='+k)
        
        function callback(){
            document.getElementById('all-notic').innerHTML = this
        }
        setTimeout('location.reload()', 100)
    }
</script>


<script>
    function hidde(an, id)
    {
        const picG = document.getElementById('ftJr'+id)
        const nomeG = document.getElementById('idJr'+id)
        const esteMenu = document.getElementById('m'+id)

        GetAjaxRequest(callback, '../controlls/anonimo.php', 'g='+an+'&idg='+id)

        function callback(){
            picG.innerHTML = this
            nomeG.innerHTML = "<div class='id-user-post' id='idJr'"+ id +">Fantasminha</div>"
            esteMenu.style.display = 'none'
        }
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


</div>
</body></html>