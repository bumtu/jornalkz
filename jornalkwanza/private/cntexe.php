<?php
require_once 'functions.php';


    //achando o id de cada postagem e gravando na base de dados.
    if(isset($_POST['post'])){

        $id = sanitizeString($_POST['post']);

        $result = queryMysql("SELECT  * FROM comments WHERE postID='$id' ORDER BY id DESC");

        while($row = $result->fetch()){
            
                if($result->rowCount()){
                    echo"<div class='boilTxt'>
                        <b style='color:black;'>" .$row['user'] . "</b>
                        <p>" . $row['comment'] . "<p>
                    </div>";
                }else{
                echo "<div class='boilTxt'>
                Oops! algo deu errado
                </div>";
            }
        }
    }
?>