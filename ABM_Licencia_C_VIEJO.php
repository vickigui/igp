<?php
include "database.php";

function altaLicencia ($dbConn) {
  $T_EMPR = $_SESSION['T_EMPR'];
  $T_SUCU = $_SESSION['T_SUCU'];
  $T_LEGA = $_GET['T_LEGA'];

  $F_INIC = $_POST['F_INIC'] > 0 ? $_POST['F_INIC'] : null;
  $F_FINA = $_POST['F_FINA']  > 0 ? $_POST['F_FINA'] : null;

  $statement = $dbConn->prepare("INSERT INTO eegepe007 VALUES(:T_EMPR, :T_SUCU, :T_LEGA, :T_ESTA, :F_INIC, :F_FINA, :id, :ART_ID)");

  $statement->bindValue(":T_EMPR", $T_EMPR);
  $statement->bindValue(":T_SUCU", $T_SUCU);
  $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);
  $statement->bindValue(":T_ESTA", $_POST['T_ESTA']);
  $statement->bindValue(":F_INIC", $F_INIC);
  $statement->bindValue(":F_FINA", $F_FINA);
  $statement->bindValue(":id", null);
  $statement->bindValue(":ART_ID", $_POST['ART_ID']);

  $statement->execute();

  //Traer lista de cargos en el establecimiento con la fecha de inicio más lejana
  $statement = $dbConn->prepare("SELECT * FROM eegepe003 WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA");
  $statement->bindValue(":T_EMPR", $T_EMPR);
  $statement->bindValue(":T_SUCU", $T_SUCU);
  $statement->bindValue(":T_LEGA", $T_LEGA);
  $statement->execute();
  $cargos = $statement->fetchAll(PDO::FETCH_ASSOC);

  //Traer lista de cargos en otros establecimientos con la fecha de inicio más lejana
  $statement = $dbConn->prepare("SELECT * FROM eegepe009 WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA");
  $statement->bindValue(":T_EMPR", $T_EMPR);
  $statement->bindValue(":T_SUCU", $T_SUCU);
  $statement->bindValue(":T_LEGA", $T_LEGA);
  $statement->execute();
  $cargosFuera = $statement->fetchAll(PDO::FETCH_ASSOC);

  $cuentaCargo = 0;

  foreach ($cargos as $cargo) {
    if ($_POST['F_INIC'] >= $cargo['F_INIC_CARG'] && $_POST['F_FINA'] <= $cargo['F_FINI_CARG']) {
      $cuentaCargo = $cuentaCargo + 1;
    }
  }

  foreach ($cargosFuera as $cargoFuera) {
    if ($_POST['F_INIC'] >= $cargoFuera['F_INIC'] && $_POST['F_FINA'] <= $cargoFuera['F_FINA']) {
      $cuentaCargo = $cuentaCargo + 1;
    }
  }

  if ($cuentaCargo == 1) {
    $statement = $dbConn->prepare("INSERT INTO eegepe004 VALUES(:id, :T_EMPR, :T_SUCU, :T_LEGA, :F_INIC, :F_FINA, :T_LICE)");

    $statement->bindValue(":id", null);
    $statement->bindValue(":T_EMPR", $T_EMPR);
    $statement->bindValue(":T_SUCU", $T_SUCU);
    $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);
    $statement->bindValue(":F_INIC", $F_INIC);
    $statement->bindValue(":F_FINA", $F_FINA);
    $statement->bindValue(":T_LICE", true);

    $statement->execute();
  }
}


function bajaLicencia ($dbConn) {
  $statement = $dbConn->prepare("DELETE FROM eegepe007 WHERE id = :id");
  $statement->bindValue(":id", $_POST['id']);

  $statement->execute();
}

function actualizaLicencia ($dbConn) {
  $statement = $dbConn->prepare("UPDATE eegepe007 SET F_INIC = :F_INIC, F_FINA = :F_FINA, T_ESTA = :T_ESTA, ART_ID = :ART_ID WHERE id = :id");
  $statement->bindValue(":T_ESTA", $_POST['T_ESTA']);
  $statement->bindValue(":F_INIC", $_POST['F_INIC']);
  $statement->bindValue(":F_FINA", $_POST['F_FINA']);
  $statement->bindValue(":ART_ID", $_POST['ART_ID']);
  $statement->bindValue(":id", $_POST['id']);

  $statement->execute();

}

function consLicenciaLegajo ($dbConn) {
  $T_EMPR = $_SESSION['T_EMPR'];
  $T_SUCU = $_SESSION['T_SUCU'];

  $statement = $dbConn->prepare("SELECT eegepe007.id, eegepe007.T_LEGA, eegepe007.T_EMPR, eegepe007.T_SUCU, eegepe007.T_ESTA, eegepe007.F_INIC, eegepe007.F_FINA, eegetm013.T_DESC FROM eegepe007 LEFT JOIN eegetm013 ON eegepe007.ART_ID = eegetm013.id WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA");
  $statement->bindValue(":T_EMPR", $T_EMPR);
  $statement->bindValue(":T_SUCU", $T_SUCU);
  $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);

  $statement->execute();
  return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function consArticulos ($dbConn) {
  $statement = $dbConn->prepare("SELECT * FROM eegetm013");
  $statement->execute();
  return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function consCargosLegajo ($dbConn) {
  $T_EMPR = $_SESSION['T_EMPR'];
  $T_SUCU = $_SESSION['T_SUCU'];
  $T_LEGA = $_GET['T_LEGA'];

  $statement = $dbConn->prepare("SELECT eegepe003.id, eegepe003.T_LEGA, eegepe003.T_EMPR, eegepe003.T_SUCU, eegetm011.T_DESC_LARG FROM eegepe003 LEFT JOIN eegetm011 ON eegepe003.N_CARG = eegetm011.id WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA");
  $statement->bindValue(":T_EMPR", $T_EMPR);
  $statement->bindValue(":T_SUCU", $T_SUCU);
  $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);

  $statement->execute();
  return $statement->fetchAll(PDO::FETCH_ASSOC);
}
?>
