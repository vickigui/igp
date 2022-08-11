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

  //Selección de Licencias para saber si son con goce de sueldo o no
  $statement = $dbConn->prepare("SELECT * FROM eegetm013 WHERE id = :id");
  $statement->bindValue(":id", $_POST['ART_ID']);
  $statement->execute();
  $licenciaSeleccionada = $statement->fetch(PDO::FETCH_ASSOC);

  //Selección de antigüedad para comparar las fechas
  $statement = $dbConn->prepare("SELECT * FROM eegepe004 WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA AND T_LICE is null");
  $statement->bindValue(":T_EMPR", $T_EMPR);
  $statement->bindValue(":T_SUCU", $T_SUCU);
  $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);
  $statement->execute();
  $antiguedades = $statement->fetchAll(PDO::FETCH_ASSOC);

  if ($licenciaSeleccionada['P_GOCE_SUEL'] == 1) {
    foreach ($antiguedades as $antiguedad) {
      if ($antiguedad['F_INIC'] <= $F_INIC && $antiguedad['F_FINA'] >= $F_FINA) {
        $statement = $dbConn->prepare("INSERT INTO eegepe004 VALUES(:id, :T_EMPR, :T_SUCU, :T_LEGA, :F_INIC, :F_FINA, :T_LICE)");

        $statement->bindValue(":id", null);
        $statement->bindValue(":T_EMPR", $T_EMPR);
        $statement->bindValue(":T_SUCU", $T_SUCU);
        $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);
        $statement->bindValue(":F_INIC", $F_INIC);
        $statement->bindValue(":F_FINA", $F_FINA);
        $statement->bindValue(":T_LICE", 1);

        $statement->execute();
      }
    }
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
