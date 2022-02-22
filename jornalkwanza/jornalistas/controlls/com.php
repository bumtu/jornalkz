<?php
require_once 'head.php';

$id = sanitizeString($_GET['url']);

$allComents = queryMysql("SELECT * FROM comments WHERE postID='$id' ORDER BY id DESC");

if($allComents->rowCount()){
    $row = $allComents->fetch();
    $usr = $row['user'];
    $comment = $row['comment'];
}

//while ($row=$allComents->fetch()) {
    echo "
        <div class='cada-um'>
            <b>$usr</b>
            <p style='color: gray;'>$comment</p>
        </div>
    ";
//}
?>