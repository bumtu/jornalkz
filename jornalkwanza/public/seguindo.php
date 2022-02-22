<?php
require_once '../private/head.php';
//if(!$location) header("Location:../index.php");

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

$copyright = RollingCopyrigh($appname);
echo"<div id='root'>";

if(!$loggedin){
    echo <<<_BANNER
    <div class='head-banner'>
        <div class='head-content'>
            <div class='app-head'><h1>$appname</h1></div>

        <div class='menu-session-app'>
            <a href='timeline.php'><div class='linkHd'>Carregar</div></a>
            <a href='public/signup.php'><div class='btn-login'>Entrar</div></a>
        </div>

        </div>
    </div>
_BANNER;
}else{
    $result = queryMysql("SELECT * FROM users WHERE username='$username'");
    while($row = $result->fetch()){
    $user = $row['username'];
    $location = $row['localizacao'];
    }
    echo <<<_BANNER
    <div class='head-banner'>
        <div class='head-content'>
            <div class='app-head'><h1>$appname</h1></div>

        <div class='menu-session-app'>
            <a href='../timeline.php'><div class='linkHd'>$user</div></a>
            <a href='../private/session.php'><div class='btn-login'>Sair</div></a>
            <a href='#'><div class='menu-login'><img src='img/pull_down.png'></div></a>
            </div>
    
            </div>
        </div>
    
        <div class='menu-device'>
            <div class='dev-items'><a href='../timeline.php'>Home</a></div>
            <div class='dev-items' id='enter'><a href='../private/session.php'>Sair</a></div>
            <div class='dev-items'><a href='local.php'>Local</a></div>
            <div class='dev-items'><a href='seguindo.php'>Seguindo</a></div>
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




if(!$loggedin){
    echo"
    <div class='all-content'>

        <div class='left-content'>


            <div class='for-you'>

                <div class='atuais-seg'>

                    <a href='#'><span class='tdasTxt'>Local</span>
                    <div class='tdas-newsIcon'>
                        <img src='img/b-location.png' alt='#'>
                    </div></a>

                    <a href='#'><span class='tdasTxt'>Seguindo</span>
                    <div class='tdas-newsIcon' id='id-seg'>
                        <img src='img/fallowers.png' alt='#'>
                    </div></a>


                </div>
            </div>

                <div class='desc-btn'>
                    <p>
                        Faça o login para seguires jornalistas, comentares e curtires 
                        noticias.
                    </p>
                    <a href='public/signup.php'><div class='btn-Entrar'>Entrar</div></a>
                </div>

                <div class='copyright-index'>
                    $copyright
                </div>

        </div>";

}else{
    echo"
    <div class='all-content'>

        <div class='left-content'>


            <div class='for-you'>

                <div class='atuais-seg'>

                    <a href='local.php'><span class='tdasTxt'>Local</span>
                    <div class='tdas-newsIcon'>
                        <img src='img/b-location.png' alt='#'>
                    </div></a>

                    <a href='seguindo.php'><span class='tdasTxt' style='color: #950101; text-decoration: underline;'>Seguindo</span>
                    <div class='tdas-newsIcon' id='id-seg'>
                        <img src='img/r-fallowers.png' alt='#'>
                    </div></a>


                </div>
            </div>

                <div class='desc-btn'>
                    <p>
                        Podes acompanhar noticias da tua região, país ou do mundo.
                        Seguir jornalistas, comentar noticias e dar a tua curtida.
                    </p>
                    <a href='../private/session.php'><div class='btn-Entrar'>Sair</div></a>
                </div>

                <div class='copyright-index'>
                    $copyright
                </div>

        </div>";
}

/*$se = queryMysql("SELECT * FROM seguidores WHERE user='$username'");

while ($row=$se->fetch()) {
    $f = $row['jornalist'];
}*/

$nov = queryMysql("SELECT pi.user, pi.jornalist, n.id, n.jorid, n.imagem,
n.jornalist, n.time, n.zona, n.anonimo, n.desenvolvimento
 FROM seguidores as pi
LEFT JOIN noticias as n ON pi.jornalist = n.jorid AND pi.user='$username' ORDER BY n.id DESC");



 $num = $nov->rowCount();



 
echo "
            <div class='center-content'>
            ";



            if($num == 0){
                echo "
                    <div class='center-content'>
                        <div class='content-index'>
                        <h2>Não estas a seguir ninguem!</h2>
                    </div>
                ";

            }else{

/*echo "
            <div class='center-content'>
            ";*/

                    while($row=$nov->fetch())
                    {
                        $idnotic = $row['id'];
                        $imgNot = $row['imagem'];
                        $keyJorn = $row['jornalist'];
                        $time = $row['time'];
                        $text = $row['desenvolvimento'];

                        $idJornalist = queryMysql("SELECT * FROM jornalista WHERE contacto='$keyJorn'");
                        while($row = $idJornalist->fetch())
                        {   
                            $i = $row['id'];
                            $nome = $row['nome'].' '.$row['sobrenom'];
                            $foto = $row['imagem'];
                        }

                        $vote = queryMysql("SELECT * FROM vote WHERE contentID='$idnotic'");
                        $ttlVotes = $vote->rowCount();

                        
                        $data = date('M jS \'y G:i', $time);

                        //toggle da imagem up
                        $click = queryMysql("SELECT * FROM vote WHERE contentID='$idnotic' AND user='$user'");
                        if(!$click->rowCount()){
                            $imageUp = 'img/empty_up.png';
                        }else{
                            $imageUp = 'img/sort_up.png';
                        }

                        //query Anonimos
                        $anonimo = queryMysql("SELECT id,anonimo FROM noticias WHERE id='$idnotic' AND anonimo='ghost'");

                        if($anonimo->rowCount() == ""){
                            $foto = '../jornalistas/view/' . $foto;
                            $nome = $nome;
                        }else{
                            $foto = 'img/ghost.png';
                            $nome = 'Fantasminha';
                        }





                            //apresentar o numero de comentarios
                            $numCmments = queryMysql("SELECT * FROM comments WHERE 
                            postID='$idnotic'");

                            if(!$numCmments->rowCount()){
                                $ncmmts = "Sem comentários ainda.";
                            }else{
                                $ncmmts = "ver todos " . $numCmments->rowCount() . " comentários...";
                            }


                            //verificando os seguidores
                            $f = queryMysql("SELECT * FROM seguidores WHERE user='$user' AND jornalist='$i'");
                            if($f->rowCount()){
                                $seguidor = "Seguindo";
                            }else{
                                $seguidor = "Seguir";   
                            }

                            
                        if($imgNot != 'anexos/'){ 

                        echo"  
                            <div class='content-index'>
                                <div class='btn-seguir' id='seguir_$i' onclick='fallw($i)'>$seguidor</div>
                                <div class='id-jornalist'>
                                        <span class='jrNome'>$nome</span><br>
                                        <span class='ntData'>$data</span>
                                    </div>
                                    <div class='pic-jornalist'>
                                        <img src='$foto' alt='$nome'>
                                    </div>
                                </div>  

                                <div class='img-noticia'>
                                    <img src='../jornalistas/view/$imgNot' alt='#'>
                                </div>
                                

                                <div class='noticia-content'>
                                    <div class='votes'>
                                        <a href='javascript:void(0)' onclick='voteUp($idnotic)' id='vote_$idnotic'><div class='up-vote' id='up-icon'> 
                                            <img id='img$idnotic' src='$imageUp' title='Vote Up se gostou da noticia'>
                                        </div></a>
                                        <a href='javascript:void(0)' id='comment_$idnotic'><div class='up-vote'>
                                            <img src='img/comments.png' title='Comentar a notica'>
                                        </div></a>

                                    </div>
                                    <div class='like-num' id='num-up$idnotic'>$ttlVotes Ups</div>
                                    $text


                                    <a href='comente.php?id=$idnotic'><div class='cmmt' id='cmntrs'>
                                        <p>$ncmmts</p>
                                    </div></a>


                                    <div class='frm-post'>
                                    <form method='POST' action='timeline.php'>
                                        <div class='btnSend'><img src='img/send.png'></div>
                                        <input type='hidden' name='commtky' value='$idnotic'>
                                        <input type='text' name='comment' 
                                        placeholder='Deixe o seu comentário...' class='comment-field' id='c_$idnotic' onclick='p($idnotic)'>
                                    </form>
                                    </div>


                                </div> ";
                    }
                    else{
                        echo"  
                        <div class='content-index'>
                            <div class='btn-seguir' id='seguir_$i' onclick='fallw($i)'>$seguidor</div>
                            <div class='id-jornalist'>
                                    <span class='jrNome'>$nome</span><br>
                                    <span class='ntData'>$data</span>
                                </div>
                                <div class='pic-jornalist'>
                                    <img src='$foto' alt='$nome'>
                                </div>
                            </div>                              

                            <div class='noticia-content'>
                                <div class='votes'>
                                <a href='javascript:void(0)' onclick='voteUp($idnotic)' id='vote_$idnotic'><div class='up-vote' id='up-icon'> 
                                        <img id='img$idnotic' src='$imageUp' title='Vote Up se gostou da noticia'>
                                    </div></a>
                                    <a href='javascript:void(0)' id='comment_$idnotic'><div class='up-vote'>
                                        <img src='img/comments.png' title='Comente a noticia'>
                                    </div></a>

                                </div>
                                <div class='like-num' id='num-up$idnotic'>$ttlVotes Ups</div>
                                $text

                                <a href='comente.php?id=$idnotic'><div class='cmmt' id='cmntrs'>
                                    <p>$ncmmts</p>
                                </div></a>



                                <div class='frm-post'>
                                    <form method='POST' action='timeline.php'>
                                        <div class='btnSend'><img src='img/send.png'></div>
                                        <input type='hidden' name='commtky' value='$idnotic'>
                                        <input type='text' name='comment' 
                                        placeholder='Deixe o seu comentário...' class='comment-field' id='c_$idnotic' onclick='p($idnotic)'/>
                                    </form>
                                </div>


                            </div> "; 
                    }
                }
            }
    
//}

echo"</div>";



//
    echo <<<_SCRIPT
        <script>

        function voteUp(str)
        {
            const voteClick = document.querySelector("#vote_"+str)
            const upIcons = document.querySelector("#up-icon")
            voteClick.addEventListener('click', vota(this.id))

            function vota(id)
            {
                const imgEmpty = document.querySelector("#img"+str).src;
                if(imgEmpty.indexOf('img/empty_up.png') !=-1){
                    document.querySelector("#img"+str).src = 'img/sort_up.png'
                }else{
                    document.querySelector("#img"+str).src = 'img/empty_up.png'
                }

            }
            GetAjaxRequest(callback, '../private/vote.php', 'votar='+ str +'&usr=$user')

            function callback(){
                document.getElementById('num-up'+str).innerHTML = this
            }
        }




        function fallw(e){
            const userFllw = document.getElementById('seguir_'+e)

            GetAjaxRequest(callback, '../private/seguindo.php', 'url=$user&j='+e)

            function callback(){
                userFllw.innerHTML = this
            }
        }


        function p(d){
            window.location.href = 'comente.php?id=' + d
        }
    </script>
_SCRIPT;


?>


</div>
</body></html>