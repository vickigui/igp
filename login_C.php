<?php
include "database.php";

function login ($dbConn, $data) {
  session_start();
  $statement = $dbConn->prepare("SELECT * FROM ingncf004 WHERE T_CODI_USUA = :T_CODI_USUA AND T_PASS = :T_PASS AND T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU");
  $statement->bindValue(":T_CODI_USUA", $_POST['T_CODI_USUA']);
  $statement->bindValue(":T_PASS", $_POST['T_PASS']);
  $statement->bindValue(":T_EMPR", $_POST['T_EMPR']);
  $statement->bindValue(":T_SUCU", $_POST['T_SUCU']);
  $statement->execute();
  $usuario = $statement->fetch(PDO::FETCH_ASSOC);


  if ($usuario != false) {
    $_SESSION["T_CODI_USUA"] = $usuario["T_CODI_USUA"];
    $_SESSION["T_EMPR"] = $usuario["T_EMPR"];
    $_SESSION["T_SUCU"] = $usuario["T_SUCU"];
  }

}

function idioma ($dbConn, $data) {
  $statement = $dbConn->prepare("SELECT * FROM ingnma002 WHERE N_IDIO = :N_IDIO");
  $statement->bindValue(":N_IDIO", $_POST['N_IDIO']);
  $statement->execute();
  $idioma = $statement->fetch(PDO::FETCH_ASSOC);

  $_SESSION["N_IDIO"] = $idioma["N_IDIO"];
}


 ?>
