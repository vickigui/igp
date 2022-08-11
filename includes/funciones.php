<?php
function consPaises($dbConn) {
  $statement = $dbConn->prepare("SELECT * FROM ingnma006 ORDER BY T_DESC ASC");
  $statement->execute();
  return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function consProvincias($dbConn) {
  $statement = $dbConn->prepare("SELECT * FROM ingnma007 ORDER BY T_DESC ASC");
  $statement->execute();
  return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function consLocalidades($dbConn) {
  $statement = $dbConn->prepare("SELECT * FROM ingnma008 ORDER BY T_DESC ASC");
  $statement->execute();
  return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function consObrasSociales($dbConn) {
  $statement = $dbConn->prepare("SELECT * FROM eegetm014");
  $statement->execute();
  return $statement->fetchAll(PDO::FETCH_ASSOC);
}


 ?>
