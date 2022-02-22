<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database setting</title>
</head>
<body>
    <h1>Setting Database...</h1>
    <?php
    require_once 'functions.php';

    createTable('noticias',
    'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    topico VARCHAR(200),
    jorid INT UNSIGNED,
    imagem VARCHAR(300),
    jornalist VARCHAR(100),
    categ VARCHAR(30),
    time INT UNSIGNED,
    zona VARCHAR(100),
    anonimo VARCHAR(11),
    desenvolvimento VARCHAR(5700),
    INDEX(topico(200))');

    createTable('jornalista',
    'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(30),
    sobrenom VARCHAR(30),
    emissora VARCHAR(50),
    codigovalido VARCHAR(20),
    contacto VARCHAR(50),
    password VARCHAR(8),
    imagem VARCHAR(300),
    localizacao VARCHAR(300),
    INDEX(codigovalido(20))');

    createTable('users',
    'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(25),
    nome VARCHAR(150),
    contacto VARCHAR(50),
    passw VARCHAR(12),
    localizacao VARCHAR(300),
    INDEX(nome(30))');

    createTable('pagamentos',
    'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(25),
    tipo VARCHAR(10),
    datavalidade INT UNSIGNED,
    INDEX(username(25))');

    createTable('vote',
    'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    contentID VARCHAR(11),
    tipo VARCHAR(10),
    INDEX(contentID(11))');

    createTable('seguidores',
    'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user VARCHAR(25),
    jornalist VARCHAR(100)');

    createTable('comments',
    'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user VARCHAR(30),
    postID INT UNSIGNED,
    comment VARCHAR(4090)');

    createTable('saveNoticias',
    'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    notId INT UNSIGNED,
    username VARCHAR(25),
    INDEX(username(25))');


    ?>
    <br>...done.
</body>
</html>