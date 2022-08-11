<?php
include "database.php";

function altaPersonal($dbConn, $data) {
  $statement = $dbConn->prepare("INSERT INTO eegetm002 VALUES (:T_EMPR, :T_SUCU, :T_DESC_CORT, :T_DESC_LARG, :N_CICL_ESCO)");

  $statement->bindValue(":T_EMPR", $_POST["T_EMPR"]);
  $statement->bindValue(":T_SUCU", $_POST["T_SUCU"]);
  $statement->bindValue(":T_DESC_CORT", $_POST["T_DESC_CORT"]);
  $statement->bindValue(":T_DESC_LARG", $_POST["T_DESC_LARG"]);
  $statement->bindValue(":N_CICL_ESCO", $_POST["N_CICL_ESCO"]);

  $statement->execute();
}

function bajaPersonal($dbConn, $data) {
  $N_CICL_ESCO = $_POST['N_CICL_ESCO'];
  $statement = $dbConn->prepare("DELETE FROM eegetm002 WHERE N_CICL_ESCO = $N_CICL_ESCO");
  $statement->execute();
}

function modiPersonal($dbConn, $data) {
  $statement = $dbConn->prepare("UPDATE eegetm002 SET T_DESC_CORT = :T_DESC_CORT, T_DESC_LARG = :T_DESC_LARG WHERE N_CICL_ESCO = :N_CICL_ESCO");

  $statement->bindValue(":T_DESC_CORT", $_POST["T_DESC_CORT"]);
  $statement->bindValue(":T_DESC_LARG", $_POST["T_DESC_LARG"]);
  $statement->bindValue(":N_CICL_ESCO", $_POST["N_CICL_ESCO"]);

  $statement->execute();
}

function consPersonal($dbConn) {
  $statement = $dbConn->prepare("SELECT * FROM eegepe001");
  $statement->execute();
  return $statement->fetchAll(PDO::FETCH_ASSOC);
}
 ?>
