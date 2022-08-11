<?php
include "database.php";

function altaCargo ($dbConn) {
  $T_EMPR = $_SESSION['T_EMPR'];
  $T_SUCU = $_SESSION['T_SUCU'];

  $statement = $dbConn->prepare("SELECT * FROM eegetm011 WHERE id = :id");
  $statement->bindValue(":id", $_POST["N_CARG"]);
  $statement->execute();
  $cargo = $statement->fetch(PDO::FETCH_ASSOC);

  // $N_TIPO_HORA = $cargo['N_TIPO_HORA'];
  $N_CANT_HORA = $_POST['N_CANT_HORA'] > 0 ? $_POST['N_CANT_HORA'] : $cargo['N_HORA_EQUI'];

  if ($_POST['N_CON_BONI'] >= 0) {
    $N_CON_BONI = (int) $_POST['N_CON_BONI'];
  } else if ($_POST['N_CANT_HORA'] > 0) {
    $N_CON_BONI = $_POST['N_CANT_HORA'];
  } else {
    $N_CON_BONI = $cargo['N_HORA_EQUI'];
  }

  $statement = $dbConn->prepare("SELECT * FROM eegepe003 WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA AND N_CARG = :N_CARG ORDER BY N_SECU DESC LIMIT 1");
  $statement->bindValue(":T_EMPR", $T_EMPR);
  $statement->bindValue(":T_SUCU", $T_SUCU);
  $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);
  $statement->bindValue(":N_CARG", $_POST['N_CARG']);

  $statement->execute();
  $result = $statement->fetch(PDO::FETCH_ASSOC);

  if (isset($result['N_CARG'])) {
    $secuencia = (int) $result['N_SECU'];
  } else {
    $secuencia = 0;
  }

  $num = ++$secuencia;

  $statement = $dbConn->prepare("SELECT * FROM eegepe003 WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA ORDER BY N_SECU DESC LIMIT 1");
  $statement->bindValue(":T_EMPR", $T_EMPR);
  $statement->bindValue(":T_SUCU", $T_SUCU);
  $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);

  $statement->execute();
  $resultCargo = $statement->fetch(PDO::FETCH_ASSOC);


//Pregunta si tiene un cargo
  if (empty($resultCargo)) {
    //Si no tiene lo agrega a la tabla de cargos y a la de antigüedad
    $F_INIC = $_POST['F_INIC_CARG'] > 0 ? $_POST['F_INIC_CARG'] : null;
    $F_FINA = $_POST['F_FINI_CARG']  > 0 ? $_POST['F_FINI_CARG'] : null;

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

    //Si tiene un cargo
  } else {
    $F_INIC = $_POST['F_INIC_CARG'] > 0 ? $_POST['F_INIC_CARG'] : null;
    $F_FINA = $_POST['F_FINI_CARG']  > 0 ? $_POST['F_FINI_CARG'] : null;

    //Selecciona la fecha de inicio más lejana
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

    //Pregunta si esa fecha de inicio es mayor a la fecha de finalización del nuevo cargo
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
      }
    }
  }

  $statement = $dbConn->prepare("INSERT INTO eegepe003 VALUES(:T_EMPR, :T_SUCU, :T_LEGA, :P_TIPO_USUA, :N_CARG, :N_SECU, :F_INIC_CARG, :F_FINI_CARG, :P_BONI, :N_TIPO_HORA, :N_CON_BONI, :N_CANT_HORA, :id, :N_TITU, :N_PREM)");

  $statement->bindValue(":T_EMPR", $T_EMPR);
  $statement->bindValue(":T_SUCU", $T_SUCU);
  $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);
  $statement->bindValue(":P_TIPO_USUA", 'P');
  $statement->bindValue(":N_CARG", $_POST['N_CARG']);
  $statement->bindValue(":N_SECU", $num);
  $statement->bindValue(":F_INIC_CARG", $_POST['F_INIC_CARG']);
  $statement->bindValue(":F_FINI_CARG", $_POST['F_FINI_CARG']);
  $statement->bindValue(":P_BONI", $_POST['P_BONI']);
  $statement->bindValue(":N_TIPO_HORA", 3);
  $statement->bindValue(":N_CON_BONI", $N_CON_BONI);
  $statement->bindValue(":N_CANT_HORA", $N_CANT_HORA);
  $statement->bindValue(":id", null);
  $statement->bindValue(":N_TITU", $_POST['N_TITU']);
  $statement->bindValue(":N_PREM", $_POST['N_PREM']);

  $statement->execute();
}

function bajaCargo ($dbConn) {
  $statement = $dbConn->prepare("DELETE FROM eegepe003 WHERE id = :id");
  $statement->bindValue(":id", $_POST['id']);
  $statement->execute();
}


function actualizaCargo($dbConn) {
  $F_INIC_CARG = isset($_POST['F_INIC_CARG']) ? $_POST['F_INIC_CARG'] : "";
  $F_FINI_CARG = isset($_POST['F_FINI_CARG']) ? $_POST['F_FINI_CARG'] : "";
  $P_BONI = isset($_POST['P_BONI']) ? $_POST['P_BONI'] : "";
  // $N_TIPO_HORA = isset($_POST['N_TIPO_HORA']) ? $_POST['N_TIPO_HORA'] : "";
  $N_CON_BONI = isset($_POST['N_CON_BONI']) ? $_POST['N_CON_BONI'] : "";
  $N_CANT_HORA = isset($_POST['N_CANT_HORA']) ? $_POST['N_CANT_HORA'] : "";
  $N_TITU = isset($_POST['N_TITU']) ? $_POST['N_TITU'] : "";
  $N_PREM = isset($_POST['N_PREM']) ? $_POST['N_PREM'] : "";

  $statement = $dbConn->prepare("UPDATE eegepe003 SET F_INIC_CARG = :F_INIC_CARG, F_FINI_CARG = :F_FINI_CARG, P_BONI = :P_BONI, N_CON_BONI = :N_CON_BONI, N_CANT_HORA = :N_CANT_HORA, N_TITU = :N_TITU, N_PREM = :N_PREM WHERE id = :ID");

  $statement->bindValue(":F_INIC_CARG", $F_INIC_CARG);
  $statement->bindValue(":F_FINI_CARG", $F_FINI_CARG);
  $statement->bindValue(":P_BONI", $P_BONI);
  // $statement->bindValue(":N_TIPO_HORA", $N_TIPO_HORA);
  $statement->bindValue(":N_CON_BONI", $N_CON_BONI);
  $statement->bindValue(":N_CANT_HORA", $N_CANT_HORA);
  $statement->bindValue(":N_TITU", $N_TITU);
  $statement->bindValue(":N_PREM", $N_PREM);
  $statement->bindValue(":ID", $_POST["id"]);

  $statement->execute();
}

function consCargo ($dbConn) {
  $statement = $dbConn->prepare("SELECT * FROM eegetm011 WHERE id < 54 OR id > 59 OR id = 56");
  $statement->execute();
  return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function consCargoLegajo ($dbConn) {
  $T_EMPR = $_SESSION['T_EMPR'];
  $T_SUCU = $_SESSION['T_SUCU'];

  $statement = $dbConn->prepare("SELECT eegepe003.id, eegepe003.T_LEGA, eegepe003.T_EMPR, eegepe003.T_SUCU, eegepe003.F_INIC_CARG, eegepe003.F_FINI_CARG, eegetm011.T_DESC_LARG, eegepe003.N_CANT_HORA, eegepe003.N_CON_BONI FROM eegepe003 LEFT JOIN eegetm011 ON eegepe003.N_CARG = eegetm011.id WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA AND F_FINI_CARG = 0");
  $statement->bindValue(":T_EMPR", $T_EMPR);
  $statement->bindValue(":T_SUCU", $T_SUCU);
  $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);

  $statement->execute();
  return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function consCargoLegajoBaja($dbConn) {
  $T_EMPR = $_SESSION['T_EMPR'];
  $T_SUCU = $_SESSION['T_SUCU'];

  $statement = $dbConn->prepare("SELECT eegepe003.id, eegepe003.T_LEGA, eegepe003.T_EMPR, eegepe003.T_SUCU, eegepe003.F_INIC_CARG, eegepe003.F_FINI_CARG, eegetm011.T_DESC_LARG, eegepe003.N_CANT_HORA, eegepe003.N_CON_BONI FROM eegepe003 LEFT JOIN eegetm011 ON eegepe003.N_CARG = eegetm011.id WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA AND F_FINI_CARG != 0");
  $statement->bindValue(":T_EMPR", $T_EMPR);
  $statement->bindValue(":T_SUCU", $T_SUCU);
  $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);

  $statement->execute();
  return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function consCargoLegajoTodo($dbConn) {
  $T_EMPR = $_SESSION['T_EMPR'];
  $T_SUCU = $_SESSION['T_SUCU'];

  $statement = $dbConn->prepare("SELECT eegepe003.id, eegepe003.T_LEGA, eegepe003.T_EMPR, eegepe003.T_SUCU, eegepe003.F_INIC_CARG, eegepe003.F_FINI_CARG, eegetm011.T_DESC_LARG, eegepe003.N_CANT_HORA, eegepe003.N_CON_BONI FROM eegepe003 LEFT JOIN eegetm011 ON eegepe003.N_CARG = eegetm011.id WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA");

  $statement->bindValue(":T_EMPR", $T_EMPR);
  $statement->bindValue(":T_SUCU", $T_SUCU);
  $statement->bindValue(":T_LEGA", $_GET['T_LEGA']);

  $statement->execute();
  return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function consHoras ($dbConn) {
  $statement = $dbConn->prepare("SELECT * FROM eegetm012");
  $statement->execute();
  return $statement->fetchAll(PDO::FETCH_ASSOC);
}

 ?>
