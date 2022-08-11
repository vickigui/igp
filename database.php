<?php

$dsn = 'mysql:host=190.228.29.62;dbname=gestionarigp;charset=utf8';
$user = 'victoriagui';
$pass = 'hsaw5hCqz9J7';


$dbConn = new PDO($dsn, $user, $pass);

try {
  $dbConn = new PDO($dsn, $user, $pass);
  $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
  echo $exception->getMessage();
}

 ?>
