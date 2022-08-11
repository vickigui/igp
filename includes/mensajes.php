<?php

include "database.php";

function consTitulos($dbConn) {
  $statement = $dbConn->prepare("SELECT T_DESC FROM ingnma003 WHERE N_IDIO = 1");
  $statement->execute();
  return $statement->fetchAll(PDO::FETCH_ASSOC);
}

$msgList = consTitulos($dbConn);
$mensaje = array();
foreach ($msgList as $msg) {
  $mensaje[] = $msg['T_DESC'];
}
// 
// print_r($mensaje);

function consIdiomas($dbConn) {
  $statement = $dbConn->prepare("SELECT * FROM ingnma002");
  $statement->execute();
  return $statement->fetchAll(PDO::FETCH_ASSOC);
}

 ?>
