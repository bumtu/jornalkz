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

if(!$loggedin){
    echo <<<_BANNER
    <div class='head-banner'>
        <div class='head-content'>
            <div class='app-head'><h1>$appname</h1></div>

        <div class='menu-session-app'>
            <a href='../index.php'><div class='linkHd'>Voltar</div></a>
            <a href='signup.php'><div class='btn-login'>Entrar</div></a>
            <a href='#'><div class='menu-login'><img src='img/pull_down.png'></div></a>
        </div>

        </div>
    </div>

    <div class='menu-device'>
        <div class='dev-items'><a href='../index.php'>Home</a></div>
        <div class='dev-items' id='enter'><a href='signup.php'>Entrar</a></div>
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
            <a href='../index.php'><div class='linkHd'>Voltar</div></a>
            <a href='../private/session.php'><div class='btn-login'>Sair</div></a>
            <a href='#'><div class='menu-login'><img src='img/pull_down.png'></div></a>
        </div>

        </div>
    </div>

    <div class='menu-device'>
        <div class='dev-items'><a href='#'>Home</a></div>
        <div class='dev-items' id='enter'><a href='private/session.php'>Sair</a></div>
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
}


$valor = sanitizeString($_GET['id']);


$copyright = RollingCopyrigh($appname);
$result = queryMysql("SELECT * FROM noticias WHERE id='$valor'");
$num = $result->rowCount();


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

                        <a href='seguindo.php'><span class='tdasTxt'>Seguindo</span>
                        <div class='tdas-newsIcon' id='id-seg'>
                            <img src='img/fallowers.png' alt='#'>
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


echo"
            <div class='center-content'>
            ";

                    while($row = $result->fetch())
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



                            //apresentar o numero de comentarios
                            $numCmments = queryMysql("SELECT * FROM comments WHERE 
                            postID='$idnotic'");

                            while($row = $numCmments->fetch()){
                                $usrComents = $row['user'];
                                $tComents = $row['comment'];
                            }

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

                            //query Anonimo
                            $anonimo = queryMysql("SELECT id,anonimo FROM noticias WHERE id='$idnotic' AND anonimo='ghost'");

                            if($anonimo->rowCount() == ""){
                                $foto = '../jornalistas/view/' . $foto;
                                $nome = $nome;
                            }else{
                                $foto = 'img/ghost.png';
                                $nome = 'Fantasminha';
                            }

                        }




                        if($imgNot != 'anexos/'){

                        echo"  
                            <div class='content-index'>
                                <div class='btn-seguir' id='seguir_$i' onclick='logeddin($i)'>$seguidor</div>
                                <div class='id-jornalist'>
                                        <span class='jrNome'>$nome</span><br>
                                        <span class='ntData'>$data</span>
                                    </div>
                                    <div class='pic-jornalist'>
                                        <img src='$foto' alt='$nome'>
                                    </div>
                                </div>  

                                <div class='img-noticia imageCm'>
                                    <img src='../jornalistas/view/$imgNot' alt='#'>
                                </div>
                                

                                <div class='noticia-content'>
                                    <div class='votes'>
                                        <a href='javascript:void(0)' onclick='logeddin($idnotic)' id='vote_$idnotic'><div class='up-vote' id='up-icon'> 
                                            <img id='img$idnotic' src='$imageUp' title='Vote Up se gostou da noticia'>
                                        </div></a>
                                        <a href='javascript:void(0)' id='comment_$idnotic'><div class='up-vote'>
                                            <img src='img/comments.png' title='Comentar a notica'>
                                        </div></a>

                                    </div>
                                    <div class='like-num' id='num-up$idnotic'>$ttlVotes Ups</div>
                                    ". $text;



                                    echo"<div class='cmmt' id='cmntrs'>
                                         <p>$ncmmts</p>
                                     </div>




                                    <div class='cmt-list' id='c-list'>";

                                    $numCmments = queryMysql("SELECT * FROM comments WHERE 
                                    postID='$idnotic' ORDER BY id DESC");
        
                                    while($row = $numCmments->fetch()){
                                        $usrComents = $row['user'];
                                        $tComents = $row['comment'];

                                        echo " <div class='boilTxt'>
                                        <b style='color:black;'>$usrComents</b>
                                        <p>$tComents<p>
                                        </div><br>";
                                    }

                                echo"</div>


                                    <div class='frm-post' style='display: none;'>
                                    <form method='POST' id='my-form'>
                                        <input type='text' name='comment' 
                                        placeholder='Deixe o seu comentário...' class='comment-field' id='c_$idnotic' />
                                        <button class='btnSend' id='btnSend'><img src='img/send.png'></button>
                                    </form>
                                    </div>


                                </div> ";
                    }
                    else{
                        echo"  
                        <div class='content-index'>
                            <div class='btn-seguir' id='seguir_$i' onclick='logeddin($i)'>$seguidor</div>
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
                                <a href='javascript:void(0)' onclick='logeddin($idnotic)' id='vote_$idnotic'><div class='up-vote' id='up-icon'> 
                                        <img id='img$idnotic' src='$imageUp' title='Vote Up se gostou da noticia'>
                                    </div></a>
                                    <a href='javascript:void(0)' id='comment_$idnotic'><div class='up-vote'>
                                        <img src='img/comments.png' title='Comente a noticia'>
                                    </div></a>

                                </div>
                                <div class='like-num' id='num-up$idnotic'>$ttlVotes Ups</div>
                                "; echo TextTruncate($text, 100, " ...");



                               echo"<div class='cmmt' id='cmntrs'>
                                    <p>$ncmmts</p>
                                </div>



                                <div class='cmt-list' id='c-list'>";

                                    $numCmments = queryMysql("SELECT * FROM comments WHERE 
                                    postID='$idnotic' ORDER BY id DESC");
        
                                    while($row = $numCmments->fetch()){
                                        $usrComents = $row['user'];
                                        $tComents = $row['comment'];

                                        echo " <div class='boilTxt'>
                                        <b style='color:black;'>$usrComents</b>
                                        <p>$tComents<p>
                                        </div>";
                                    }

                                echo"</div>



                                <div class='frm-post'>
                                    <form method='POST' id='my-form'>
                                        <input type='text' name='comment' 
                                        placeholder='Deixe o seu comentário...' class='comment-field' id='c_$idnotic'>
                                        <button class='btnSend' id='btnSend'><img src='img/send.png'></button>
                                    </form>
                                </div>


                            </div> "; 
                    }
                //}
    echo"</div>";


    //Inserindo comentarios as apostagens
    if(isset($_POST['comment'])){
        $usrC = sanitizeString($_POST['comment']);

        if($usrC != ""){
            queryMysql("INSERT INTO comments(user, postID, comment) VALUES('$user', '$idnotic', '$usrC')");
        }
    }



    echo <<<_SCRIPT
        <script>

        function ref()
        {
            PostAjaxRequest(callback, '../private/cntexe.php', 'post=$idnotic')

            function callback(){
                document.getElementById('c-list').innerHTML = this
            }
        }
        
        setTimeout(ref(), 1000);


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


    </script>
_SCRIPT;



?>


<script>
    function logeddin(userId){
        alert('É necessário cadastrar-se ou fazer login para ter acesso a todas funcionalidades.');
    }

</script>

</div>
</body></html>


<!--
$('#submit').click(function(e){

//call ajax

e.preventDefault();
})
-->