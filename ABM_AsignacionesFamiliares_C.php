<?php
include "database.php";

function altaAsignacion ($dbConn) {
  $T_EMPR = $_SESSION['T_EMPR'];
  $T_SUCU = $_SESSION['T_SUCU'];

  $statement = $dbConn->prepare("INSERT INTO eegepe008 VALUES(:id, :T_EMPR, :T_SUCU, :T_LEGA, :T_NOMB, :T_APEL, :F_NACI, :N_DOCU)");

  $statement->bindValue(":id", null);
  $statement->bindValue(":T_EMPR", $T_EMPR);
  $statement->bindValue(":T_SUCU", $T_SUCU);
  $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);
  $statement->bindValue(":T_NOMB", $_POST['T_NOMB']);
  $statement->bindValue(":T_APEL", $_POST['T_APEL']);
  $statement->bindValue(":F_NACI", $_POST['F_NACI']);
  $statement->bindValue(":N_DOCU", $_POST['N_DOCU']);


  $statement->execute();
}

function bajaAsignacion ($dbConn) {
  $statement = $dbConn->prepare("DELETE FROM eegepe007 WHERE id = :id");

  $statement->bindValue(":id", $_POST['id']);

  $statement->execute();
}

function actualizaAsignacion ($dbConn) {
  $statement = $dbConn->prepare("UPDATE eegepe008 SET T_NOMB = :T_NOMB, T_APEL = :T_APEL, F_NACI = :F_NACI, N_DOCU = :N_DOCU WHERE id = :id");
  $statement->bindValue(":T_NOMB", $_POST['T_NOMB']);
  $statement->bindValue(":T_APEL", $_POST['T_APEL']);
  $statement->bindValue(":F_NACI", $_POST['F_NACI']);
  $statement->bindValue(":N_DOCU", $_POST['N_DOCU']);
  $statement->bindValue(":id", $_POST['id']);

  $statement->execute();

}

function consAsignacionLegajo ($dbConn) {
  $T_EMPR = $_SESSION['T_EMPR'];
  $T_SUCU = $_SESSION['T_SUCU'];

  $statement = $dbConn->prepare("SELECT * FROM eegepe008 WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA");
  $statement->bindValue(":T_EMPR", $T_EMPR);
  $statement->bindValue(":T_SUCU", $T_SUCU);
  $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);

  $statement->execute();
  return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function altaSalarioFamiliar ($dbConn) {
  $T_EMPR = $_SESSION['T_EMPR'];
  $T_SUCU = $_SESSION['T_SUCU'];

  $statement = $dbConn->prepare("INSERT INTO eegepe010 VALUES(:id, :T_EMPR, :T_SUCU, :T_LEGA, :N_SALA_FAMI)");

  $statement->bindValue(":id", null);
  $statement->bindValue(":T_EMPR", $T_EMPR);
  $statement->bindValue(":T_SUCU", $T_SUCU);
  $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);
  $statement->bindValue(":N_SALA_FAMI", $_POST['N_SALA_FAMI']);

  $statement->execute();
}

function consSalarioFamiliar ($dbConn) {
  $T_EMPR = $_SESSION['T_EMPR'];
  $T_SUCU = $_SESSION['T_SUCU'];

  $statement = $dbConn->prepare("SELECT * FROM eegepe010 WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA");
  $statement->bindValue(":T_EMPR", $T_EMPR);
  $statement->bindValue(":T_SUCU", $T_SUCU);
  $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);

  $statement->execute();
  return $statement->fetch(PDO::FETCH_ASSOC);
}

function actualizaSalarioFamiliar ($dbConn) {
  $T_EMPR = $_SESSION['T_EMPR'];
  $T_SUCU = $_SESSION['T_SUCU'];

  $statement = $dbConn->prepare("UPDATE eegepe010 SET N_SALA_FAMI = :N_SALA_FAMI WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA");
  $statement->bindValue(":T_EMPR", $T_EMPR);
  $statement->bindValue(":T_SUCU", $T_SUCU);
  $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);
  $statement->bindValue(":N_SALA_FAMI", $_POST['N_SALA_FAMI']);

  $statement->execute();

}

function escalasAsignaciones ($dbConn) {
  $statement = $dbConn->prepare("SELECT * FROM eegetm015");
  $statement->execute();
  return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function consHijos ($dbConn) {
  $T_EMPR = $_SESSION['T_EMPR'];
  $T_SUCU = $_SESSION['T_SUCU'];

  $statement = $dbConn->prepare("SELECT count(*) AS hijo FROM eegepe008 WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA");
  $statement->bindValue(":T_EMPR", $T_EMPR);
  $statement->bindValue(":T_SUCU", $T_SUCU);
  $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);

  $statement->execute();
  return $statement->fetch(PDO::FETCH_ASSOC);
}
 ?>
