<?php
$host = 'localhost';
$data = 'jornalk';
$user = 'root';
$pass = 'root';
$chrs = 'utf8mb4';
$appname = 'JornalKwanza';
$attr = "mysql:host=$host;dbname=$data;charset=$chrs";
$opts = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try{
    $pdo = new PDO($attr, $user, $pass, $opts);
}
catch(\PDOException $e)
{
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

function createTable($name, $query)
{
    queryMysql("CREATE TABLE IF NOT EXISTS $name($query)");
    echo "Table '$name' create or already exists.<br>";
}

function queryMysql($query)
{
    global $pdo;
    return $pdo->query($query);
}

function destroySession()
{
    $_SESSION=array();

    if(session_id() != "" || 
    isset($_COOKIE[session_name()]))
    setcookie(session_name(),
    '', time()-2592000, '/');
    session_destroy();
}

function sanitizeString($var)
{
    global $pdo;
    $var = strip_tags($var);
    $var = htmlentities($var);
    if(get_magic_quotes_gpc());
    $var = stripslashes($var);
    $result = $pdo->quote($var);

    return str_replace("'", "", $result);
}

function RollingCopyrigh($message)
{
    date_default_timezone_set('UTC');
    return "$message &copy;" . date("Y");
}

function TextTruncate($text, $max, $symbol)
{
    $temp = substr($text, 0, $max);
    $last = strrpos($temp, " ");
    $temp = substr($temp, 0, $last);
    $temp = preg_replace("/([^\w])$/", "", $temp);
    return "$temp$symbol";
}
?>