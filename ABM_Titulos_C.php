<?php
include "database.php";

function altaTitulos ($dbConn) {
  $statement = $dbConn->prepare("INSERT INTO eegepe005 VALUES (0, :T_EMPR, :T_SUCU, :T_LEGA, :N_OTOR, :F_TITU, :N_TITU)");

  $statement->bindValue(":T_EMPR", $_SESSION["T_EMPR"]);
  $statement->bindValue(":T_SUCU", $_SESSION["T_SUCU"]);
  $statement->bindValue(":T_LEGA", $_POST["T_LEGA"]);
  $statement->bindValue(":N_OTOR", $_POST["N_OTOR"]);
  $statement->bindValue(":F_TITU", $_POST["F_TITU"]);
  $statement->bindValue(":N_TITU", $_POST["N_TITU"]);

  $statement->execute();
}

function bajaTitulos ($dbConn) {
  $statement = $dbConn->prepare("DELETE FROM eegepe005 WHERE id = :id");
  $statement->bindValue(":id", $_POST['id']);
  $statement->execute();
}

function modiTitulos($dbConn) {
  $statement = $dbConn->prepare("UPDATE eegepe005 SET N_OTOR = :N_OTOR, F_TITU = :F_TITU, N_TITU = :N_TITU WHERE id = :id");

  $statement->bindValue(":id", $_POST["id"]);
  $statement->bindValue(":N_OTOR", $_POST["N_OTOR"]);
  $statement->bindValue(":F_TITU", $_POST["F_TITU"]);
  $statement->bindValue(":N_TITU", $_POST["N_TITU"]);

  $statement->execute();
}

function consTitulos2($dbConn) {
  $T_EMPR = $_SESSION["T_EMPR"];
  $T_SUCU = $_SESSION["T_SUCU"];
  $T_LEGA = $_GET["T_LEGA"];

  $statement = $dbConn->prepare("SELECT * FROM eegepe005 WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA ");

  $statement->bindValue(":T_EMPR", $T_EMPR);
  $statement->bindValue(":T_SUCU", $T_SUCU);
  $statement->bindValue(":T_LEGA", $T_LEGA);

  $statement->execute();
  return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function grillaTitulos($dbConn) {
  $T_LEGA = $_GET['T_LEGA'];

  $statement = $dbConn->prepare("SELECT * FROM eegetm005 WHERE N_IDEN NOT IN (SELECT N_IDEN FROM eegepe002 WHERE eegepe002.T_LEGA = :T_LEGA)");

  $statement->bindValue(":T_LEGA", $T_LEGA);

  $statement->execute();
  return $statement->fetchAll(PDO::FETCH_ASSOC);
}


 ?>
