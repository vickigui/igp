<?php
include "database.php";

function altaAntiguedad ($dbConn) {
  $T_EMPR = $_SESSION['T_EMPR'];
  $T_SUCU = $_SESSION['T_SUCU'];

  $statement = $dbConn->prepare("SELECT * FROM eegepe003 WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA ORDER BY N_SECU DESC LIMIT 1");
  $statement->bindValue(":T_EMPR", $T_EMPR);
  $statement->bindValue(":T_SUCU", $T_SUCU);
  $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);

  $statement->execute();
  $resultCargo = $statement->fetch(PDO::FETCH_ASSOC);

//Pregunta si tiene un cargo
  if (isset($resultCargo)) {
    //Ya lo agrega a la tabla de antigüedad de otros establecimientos
    $F_INIC = $_POST['F_INIC'] > 0 ? $_POST['F_INIC'] : null;
    $F_FINA = $_POST['F_FINA']  > 0 ? $_POST['F_FINA'] : null;

    $statement = $dbConn->prepare("INSERT INTO eegepe009 VALUES(:id, :T_EMPR, :T_SUCU, :T_LEGA, :T_ESTA, :F_INIC, :F_FINA)");

    $statement->bindValue(":id", null);
    $statement->bindValue(":T_EMPR", $T_EMPR);
    $statement->bindValue(":T_SUCU", $T_SUCU);
    $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);
    $statement->bindValue(":T_ESTA", $_POST['T_ESTA']);
    $statement->bindValue(":F_INIC", $F_INIC);
    $statement->bindValue(":F_FINA", $F_FINA);

    $statement->execute();

    //Después sigue el proceso para agregarla a antigüedad del personal - Selecciona la fecha de inicio más lejana
    $statement = $dbConn->prepare("SELECT * FROM eegepe004 WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA ORDER BY F_INIC LIMIT 1");
    $statement->bindValue(":T_EMPR", $T_EMPR);
    $statement->bindValue(":T_SUCU", $T_SUCU);
    $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);

    $statement->execute();
    $antiguedadInicio = $statement->fetch(PDO::FETCH_ASSOC);

    //Selecciona la fecha de finalización más cercana
    $statement = $dbConn->prepare("SELECT * FROM eegepe004 WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA ORDER BY F_FINA DESC LIMIT 1");
    $statement->bindValue(":T_EMPR", $T_EMPR);
    $statement->bindValue(":T_SUCU", $T_SUCU);
    $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);

    $statement->execute();
    $antiguedadFin = $statement->fetch(PDO::FETCH_ASSOC);

    //Pregunta si esa fecha de inicio es mayor a la fecha de finalización del nuevo cargo o si la fecha de finalización es anterior a la fecha de inicio del nuevo cargo
    if ($F_INIC >= $antiguedadFin['F_FINA'] || $antiguedadInicio['F_INIC'] >= $F_FINA) {
      //Agrega el cargo porque es anterior e independiente de los que ya están
      if (isset($F_INIC)) {
        $inicio = new DateTime($F_INIC);
        $final = new Datetime($F_FINA);
        $diff = $final->diff($inicio);
        $N_ANOS = $diff->y;
        $N_MESES = $diff->m;
        $N_DIAS = $diff->d;
      }

      $statement = $dbConn->prepare("INSERT INTO eegepe004 VALUES(:id, :T_EMPR, :T_SUCU, :T_LEGA, :F_INIC, :F_FINA, :T_LICE)");

      $statement->bindValue(":id", null);
      $statement->bindValue(":T_EMPR", $T_EMPR);
      $statement->bindValue(":T_SUCU", $T_SUCU);
      $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);
      $statement->bindValue(":F_INIC", $F_INIC);
      $statement->bindValue(":F_FINA", $F_FINA);
      $statement->bindValue(":T_LICE", null);

      $statement->execute();
      //Pregunta si el nuevo cargo empieza después de la fecha de finalización de la antigüedad histórica
    } else {
      //Selecciona todos los registros de antigüedad para ir comparando
      $statement = $dbConn->prepare("SELECT * FROM eegepe004 WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA");
      $statement->bindValue(":T_EMPR", $T_EMPR);
      $statement->bindValue(":T_SUCU", $T_SUCU);
      $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);

      $statement->execute();
      $antiguedadRegistros = $statement->fetchAll(PDO::FETCH_ASSOC);

      foreach ($antiguedadRegistros as $antiguedad) {
        if ($antiguedad['F_INIC'] >= $F_INIC && $antiguedad['F_INIC'] < $F_FINA && $antiguedad['F_FINA'] >= $F_FINA) {
          $statement = $dbConn->prepare("UPDATE eegepe004 SET F_INIC = :F_INIC WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA AND id = :id");
          $statement->bindValue(":T_EMPR", $T_EMPR);
          $statement->bindValue(":T_SUCU", $T_SUCU);
          $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);
          $statement->bindValue(":F_INIC", $F_INIC);
          $statement->bindValue(":id", $antiguedad['id']);

          $statement->execute();
        } else if ($F_INIC >= $antiguedad['F_INIC'] && $F_INIC < $antiguedad['F_FINA'] && $F_FINA >= $antiguedad['F_FINA']) {
          $statement = $dbConn->prepare("UPDATE eegepe004 SET F_FINA = :F_FINA WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA  AND id = :id");
          $statement->bindValue(":T_EMPR", $T_EMPR);
          $statement->bindValue(":T_SUCU", $T_SUCU);
          $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);
          $statement->bindValue(":F_FINA", $F_FINA);
          $statement->bindValue(":id", $antiguedad['id']);

          $statement->execute();
        }
      }
    }
    //Una vez que cargó la antigüedad de este nuevo cargo va a ir revisando las fechas de ingreso de cada registro y comparando con las siguientes
    $statement = $dbConn->prepare("SELECT * FROM eegepe004 WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA ORDER BY id");
    $statement->bindValue(":T_EMPR", $T_EMPR);
    $statement->bindValue(":T_SUCU", $T_SUCU);
    $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);

    $statement->execute();
    $antiguedadRevision = $statement->fetchAll(PDO::FETCH_ASSOC);



    foreach ($antiguedadRevision as $revision) {
      foreach ($antiguedadRevision as $comparacion) {
        if ($revision['F_INIC'] < $comparacion['F_INIC'] && $revision['F_FINA'] > $comparacion['F_INIC'] && $revision['F_FINA'] < $comparacion['F_FINA']) {
          $statement = $dbConn->prepare("UPDATE eegepe004 SET F_FINA = :F_FINA WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA  AND id = :id");
          $statement->bindValue(":T_EMPR", $T_EMPR);
          $statement->bindValue(":T_SUCU", $T_SUCU);
          $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);
          $statement->bindValue(":F_FINA", $comparacion['F_FINA']);
          $statement->bindValue(":id", $revision['id']);
          if ($statement->execute()) {
            $statement = $dbConn->prepare("DELETE FROM eegepe004 WHERE id = :id");
            $statement->bindValue(":id", $comparacion['id']);
            if ($statement->execute()) {
              $statement = $dbConn->prepare("SELECT * FROM eegepe004 WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA ORDER BY id");
              $statement->bindValue(":T_EMPR", $T_EMPR);
              $statement->bindValue(":T_SUCU", $T_SUCU);
              $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);

              $statement->execute();
              $antiguedadRevision = $statement->fetchAll(PDO::FETCH_ASSOC);
            }
          }
        } else if ($revision['F_INIC'] > $comparacion['F_INIC'] && $revision['F_INIC'] < $comparacion['F_FINA'] && $revision['F_FINA'] > $comparacion['F_FINA']) {
          $statement = $dbConn->prepare("UPDATE eegepe004 SET F_INIC = :F_INIC WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA AND id = :id");
          $statement->bindValue(":T_EMPR", $T_EMPR);
          $statement->bindValue(":T_SUCU", $T_SUCU);
          $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);
          $statement->bindValue(":F_INIC", $comparacion['F_INIC']);
          $statement->bindValue(":id", $revision['id']);

          if ($statement->execute()) {
            $statement = $dbConn->prepare("DELETE FROM eegepe004 WHERE id = :id");
            $statement->bindValue(":id", $comparacion['id']);
            if ($statement->execute()) {
              $statement = $dbConn->prepare("SELECT * FROM eegepe004 WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA ORDER BY id");
              $statement->bindValue(":T_EMPR", $T_EMPR);
              $statement->bindValue(":T_SUCU", $T_SUCU);
              $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);

              $statement->execute();
              $antiguedadRevision = $statement->fetchAll(PDO::FETCH_ASSOC);
            }
          }
        } else if ($revision['F_INIC'] < $comparacion['F_INIC'] && $revision['F_FINA'] > $comparacion['F_FINA'] && $comparacion['F_FINA'] != null) {
          $statement = $dbConn->prepare("DELETE FROM eegepe004 WHERE id = :id");
          $statement->bindValue(":id", $comparacion['id']);
          $statement->execute();
        } else if ($revision['F_INIC'] < $comparacion['F_INIC'] && $revision['F_FINA'] > $comparacion['F_INIC'] && $comparacion['F_FINA'] == null) {
          $statement = $dbConn->prepare("UPDATE eegepe004 SET F_FINA = :F_FINA WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA  AND id = :id");
          $statement->bindValue(":T_EMPR", $T_EMPR);
          $statement->bindValue(":T_SUCU", $T_SUCU);
          $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);
          $statement->bindValue(":F_FINA", null);
          $statement->bindValue(":id", $revision['id']);
          if ($statement->execute()) {
            $statement = $dbConn->prepare("DELETE FROM eegepe004 WHERE id = :id");
            $statement->bindValue(":id", $comparacion['id']);
            if ($statement->execute()) {
              $statement = $dbConn->prepare("SELECT * FROM eegepe004 WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA ORDER BY id");
              $statement->bindValue(":T_EMPR", $T_EMPR);
              $statement->bindValue(":T_SUCU", $T_SUCU);
              $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);

              $statement->execute();
              $antiguedadRevision = $statement->fetchAll(PDO::FETCH_ASSOC);
            }
          }
        } else if ($revision['F_FINA'] == null && $comparacion['F_FINA'] == null && $revision['F_INIC'] < $comparacion['F_INIC']) {
          $statement = $dbConn->prepare("DELETE FROM eegepe004 WHERE id = :id");
          $statement->bindValue(":id", $comparacion['id']);
          if ($statement->execute()) {
            $statement = $dbConn->prepare("SELECT * FROM eegepe004 WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA ORDER BY id");
            $statement->bindValue(":T_EMPR", $T_EMPR);
            $statement->bindValue(":T_SUCU", $T_SUCU);
            $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);

            $statement->execute();
            $antiguedadRevision = $statement->fetchAll(PDO::FETCH_ASSOC);
          }
        } else if ($revision['F_INIC'] < $comparacion['F_INIC'] && $revision['F_FINA'] == null && $comparacion['F_FINA'] != null) {
          $statement = $dbConn->prepare("DELETE FROM eegepe004 WHERE id = :id");
          $statement->bindValue(":id", $comparacion['id']);
          $statement->execute();
        }
      }
    }
  }
}

function bajaAntiguedad ($dbConn) {
  $statement = $dbConn->prepare("DELETE FROM eegepe009 WHERE id = :id");

  $statement->bindValue(":id", $_POST['id']);

  $statement->execute();
}

function actualizaAntiguedad ($dbConn) {
  $statement = $dbConn->prepare("UPDATE eegepe009 SET F_INIC = :F_INIC, F_FINA = :F_FINA, T_ESTA = :T_ESTA WHERE id = :id");
  $statement->bindValue(":T_ESTA", $_POST['T_ESTA']);
  $statement->bindValue(":F_INIC", $_POST['F_INIC']);
  $statement->bindValue(":F_FINA", $_POST['F_FINA']);
  $statement->bindValue(":id", $_POST['id']);

  $statement->execute();

}

function consAntiguedadLegajo ($dbConn) {
  $T_EMPR = $_SESSION['T_EMPR'];
  $T_SUCU = $_SESSION['T_SUCU'];

  $statement = $dbConn->prepare("SELECT * FROM eegepe009 WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA");
  $statement->bindValue(":T_EMPR", $T_EMPR);
  $statement->bindValue(":T_SUCU", $T_SUCU);
  $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);

  $statement->execute();
  return $statement->fetchAll(PDO::FETCH_ASSOC);
}



 ?>
