<?php
include "database.php";

function altaIdentificaciones ($dbConn) {
  $statement = $dbConn->prepare("INSERT INTO eegepe002 VALUES (0, :T_EMPR, :T_SUCU, :T_LEGA, :P_TIPO_USUA, :N_IDEN, :T_NUME_IDEN)");

  $statement->bindValue(":T_EMPR", $_SESSION["T_EMPR"]);
  $statement->bindValue(":T_SUCU", $_SESSION["T_SUCU"]);
  $statement->bindValue(":T_LEGA", $_POST["T_LEGA"]);
  $statement->bindValue(":P_TIPO_USUA", "P");
  $statement->bindValue(":N_IDEN", $_POST["N_IDEN"]);
  $statement->bindValue(":T_NUME_IDEN", $_POST["T_NUME_IDEN"]);

  $statement->execute();
}

function bajaIdentificaciones ($dbConn) {
  $statement = $dbConn->prepare("DELETE FROM eegepe002 WHERE id = :id");
  $statement->bindValue(":id", $_POST['id']);
  $statement->execute();
}

function modiIdentificaciones($dbConn) {
  $statement = $dbConn->prepare("UPDATE eegepe002 SET T_NUME_IDEN = :T_NUME_IDEN, N_IDEN = :N_IDEN WHERE id = :id");

  $statement->bindValue(":id", $_POST["id"]);
  $statement->bindValue(":N_IDEN", $_POST["N_IDEN"]);
  $statement->bindValue(":T_NUME_IDEN", $_POST["T_NUME_IDEN"]);

  $statement->execute();
}

function consIdentificaciones($dbConn) {
  $T_EMPR = $_SESSION["T_EMPR"];
  $T_SUCU = $_SESSION["T_SUCU"];
  $T_LEGA = $_GET["T_LEGA"];

  $statement = $dbConn->prepare("SELECT *, eegetm005.T_DESC_CORT FROM eegepe002 LEFT JOIN eegetm005 ON eegepe002.N_IDEN = eegetm005.N_IDEN WHERE eegepe002.T_EMPR = :T_EMPR AND eegepe002.T_SUCU = :T_SUCU AND eegepe002.T_LEGA = :T_LEGA ");

  $statement->bindValue(":T_EMPR", $T_EMPR);
  $statement->bindValue(":T_SUCU", $T_SUCU);
  $statement->bindValue(":T_LEGA", $T_LEGA);

  $statement->execute();
  return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function grillaIdentificaciones($dbConn) {
  $T_LEGA = $_GET['T_LEGA'];

  $statement = $dbConn->prepare("SELECT * FROM eegetm005 WHERE N_IDEN NOT IN (SELECT N_IDEN FROM eegepe002 WHERE eegepe002.T_LEGA = :T_LEGA)");

  $statement->bindValue(":T_LEGA", $T_LEGA);

  $statement->execute();
  return $statement->fetchAll(PDO::FETCH_ASSOC);
}


 ?>
