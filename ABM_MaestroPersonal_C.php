<?php
include "database.php";


function generarLegajo ($dbConn, $data1, $data2) {
  $T_EMPR = $_SESSION['T_EMPR'];
  $T_SUCU = $_SESSION['T_SUCU'];
  $statement = $dbConn->prepare("SELECT * FROM eegepe001 WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU ORDER BY T_LEGA DESC LIMIT 1");
  $statement->bindValue(":T_EMPR", $T_EMPR);
  $statement->bindValue(":T_SUCU", $T_SUCU);
  $statement->execute();

  $result = $statement->fetch(PDO::FETCH_ASSOC);
  if (isset($result['T_LEGA'])) {
    $numero = (int) $result['T_LEGA'];
  } else {
    $numero = 0;
  }
  $num = ++$numero;
  $numFinal = str_pad($num,5,'0',STR_PAD_LEFT);

  $archivo = $_FILES['archivo']['name'];
   //Si el archivo contiene algo y es diferente de vacio
   if (isset($archivo) && $archivo != "") {
      //Obtenemos algunos datos necesarios sobre el archivo
      $tipo = $_FILES['archivo']['type'];
      $tamano = $_FILES['archivo']['size'];
      $temp = explode(".", $_FILES['archivo']['name']);

      //Se comprueba si el archivo a cargar es correcto observando su extensión y tamaño
     if (!((strpos($tipo, "gif") || strpos($tipo, "jpeg") || strpos($tipo, "jpg") || strpos($tipo, "png")) && ($tamano < 2000000))) {
        echo '<div><b>Error. La extensión o el tamaño de los archivos no es correcta.<br/>
        - Se permiten archivos .gif, .jpg, .png. y de 200 kb como máximo.</b></div>';
     }
     else {
       $ruta_indexphp = dirname(realpath(__FILE__));
       $newfilename = $numFinal . '.' . end($temp);
        //Si la imagen es correcta en tamaño y tipo
        //Se intenta subir al servidor
        if (move_uploaded_file($_FILES["archivo"]["tmp_name"], $ruta_indexphp."/images/" . $newfilename)) {
            //Cambiamos los permisos del archivo a 777 para poder modificarlo posteriormente
            chmod('images/'.$newfilename, 0777);
        }
        else {
           //Si no se ha podido subir la imagen, mostramos un mensaje de error
           echo '<div><b>Ocurrió algún error al subir el fichero. No pudo guardarse.</b></div>';
        }
      }
   }

   $newfilename = isset($newfilename) ? $newfilename : '';

   $CUIT = $_POST['N_CUIT_PRE'] . "-" . $_POST['N_CUIT'] . "-" . $_POST['N_CUIT_POST'];

  $statement = $dbConn->prepare("INSERT INTO eegepe001 VALUES (:T_EMPR, :T_SUCU, :T_LEGA, :P_TIPO_USUA, :T_APEL, :T_NOMB, :N_CUIT, :T_CALL, :T_PISO, :T_DEPA, :N_LOCA, :N_PROV, :N_PAIS, :N_LOCA_NACI, :F_NACI, :P_ESTA_CIVI, :T_TELE, :T_MAIL, :F_ALTA, :F_BAJA, :F_TELE_RENU, :F_INIC_ACTI, :T_FOTO, :N_PROV_NACI, :N_PAIS_NACI, :T_CODI_POST, '', 0, :N_OBRA_SOCI, :N_SIND)");

  $statement->bindValue(":T_EMPR", $_SESSION["T_EMPR"]);
  $statement->bindValue(":T_SUCU", $_SESSION["T_SUCU"]);
  $statement->bindValue(":T_LEGA", $numFinal);
  $statement->bindValue(":P_TIPO_USUA", "P");
  $statement->bindValue(":T_APEL", $_POST["T_APEL"]);
  $statement->bindValue(":T_NOMB", $_POST["T_NOMB"]);
  $statement->bindValue(":N_CUIT", $CUIT);
  $statement->bindValue(":T_CALL", $_POST["Street"]);
  // $statement->bindValue(":N_NUME", $_POST["N_NUME"]);
  $statement->bindValue(":T_PISO", $_POST["T_PISO"]);
  $statement->bindValue(":T_DEPA", $_POST["T_DEPA"]);
  $statement->bindValue(":N_LOCA", $_POST["City"]);
  $statement->bindValue(":N_PROV", $_POST["State"]);
  $statement->bindValue(":N_PAIS", $_POST["Country"]);
  $statement->bindValue(":N_LOCA_NACI", $_POST["N_LOCA_NACI"]);
  $statement->bindValue(":F_NACI", $_POST["F_NACI"]);
  $statement->bindValue(":P_ESTA_CIVI", $_POST["P_ESTA_CIVI"]);
  $statement->bindValue(":T_TELE", $_POST["T_TELE"]);
  $statement->bindValue(":T_MAIL", $_POST["T_MAIL"]);
  $statement->bindValue(":F_ALTA", $_POST["F_ALTA"]);
  $statement->bindValue(":F_BAJA", $_POST["F_BAJA"]);
  $statement->bindValue(":F_TELE_RENU", null);
  $statement->bindValue(":F_INIC_ACTI", null);
  $statement->bindValue(":T_FOTO", $newfilename);
  $statement->bindValue(":N_PROV_NACI", $_POST["N_PROV_NACI"]);
  $statement->bindValue(":N_PAIS_NACI", $_POST["N_PAIS_NACI"]);
  $statement->bindValue(":T_CODI_POST", $_POST["PostalCode"]);
  $statement->bindValue(":N_OBRA_SOCI", $_POST["N_OBRA_SOCI"]);
  $statement->bindValue(":N_SIND", 0);

  $statement->execute();

  $statement = $dbConn->prepare("INSERT INTO eegepe002 VALUES (0, :T_EMPR, :T_SUCU, :T_LEGA, :P_TIPO_USUA, :N_IDEN, :T_NUME_IDEN)");

  $statement->bindValue(":T_EMPR", $_SESSION["T_EMPR"]);
  $statement->bindValue(":T_SUCU", $_SESSION["T_SUCU"]);
  $statement->bindValue(":T_LEGA", $numFinal);
  $statement->bindValue(":P_TIPO_USUA", "P");
  $statement->bindValue(":N_IDEN", 1);
  $statement->bindValue(":T_NUME_IDEN", $_POST['N_CUIT']);

  $statement->execute();

  $statement = $dbConn->prepare("INSERT INTO eegepe002 VALUES (0, :T_EMPR, :T_SUCU, :T_LEGA, :P_TIPO_USUA, :N_IDEN, :T_NUME_IDEN)");

  $statement->bindValue(":T_EMPR", $_SESSION["T_EMPR"]);
  $statement->bindValue(":T_SUCU", $_SESSION["T_SUCU"]);
  $statement->bindValue(":T_LEGA", $numFinal);
  $statement->bindValue(":P_TIPO_USUA", "P");
  $statement->bindValue(":N_IDEN", 7);
  $statement->bindValue(":T_NUME_IDEN", $CUIT);

  $statement->execute();

  $statement = $dbConn->prepare("INSERT INTO eegepe004 VALUES (0, :T_EMPR, :T_SUCU, :T_LEGA, :F_INIC, :F_FINA, :T_LICE)");

  $statement->bindValue(":T_EMPR", $_SESSION["T_EMPR"]);
  $statement->bindValue(":T_SUCU", $_SESSION["T_SUCU"]);
  $statement->bindValue(":T_LEGA", $numFinal);
  $statement->bindValue(":F_INIC", $_POST["F_ALTA"]);
  $statement->bindValue(":F_FINA", null);
  $statement->bindValue(":T_LICE", null);

  $statement->execute();
}

function modiMaestroPersonal($dbConn) {
  $T_APEL = isset($_POST['T_APEL']) ? $_POST['T_APEL'] : "";
  $T_NOMB = isset($_POST['T_NOMB']) ? $_POST['T_NOMB'] : "";
  $T_CALL = isset($_POST['T_CALL']) ? $_POST['T_CALL'] : "";
  $T_PISO = isset($_POST['T_PISO']) ? $_POST['T_PISO'] : "";
  $T_DEPA = isset($_POST['T_DEPA']) ? $_POST['T_DEPA'] : "";
  $N_LOCA = isset($_POST['N_LOCA']) ? $_POST['N_LOCA'] : "";
  $N_PROV = isset($_POST['N_PROV']) ? $_POST['N_PROV'] : "";
  $N_PAIS = isset($_POST['N_PAIS']) ? $_POST['N_PAIS'] : "";
  $N_LOCA_NACI = isset($_POST['N_LOCA_NACI']) ? $_POST['N_LOCA_NACI'] : "";
  $F_NACI = isset($_POST['F_NACI']) ? $_POST['F_NACI'] : "";
  $P_ESTA_CIVI = isset($_POST['P_ESTA_CIVI']) ? $_POST['P_ESTA_CIVI'] : "";
  $T_TELE = isset($_POST['T_TELE']) ? $_POST['T_TELE'] : "";
  $T_MAIL = isset($_POST['T_MAIL']) ? $_POST['T_MAIL'] : "";
  $F_ALTA = isset($_POST['F_ALTA']) ? $_POST['F_ALTA'] : "";
  $F_BAJA = isset($_POST['F_BAJA']) ? $_POST['F_BAJA'] : "";
  $F_INIC_ACTI = isset($_POST['F_INIC_ACTI']) ? $_POST['F_INIC_ACTI'] : "";
  $T_FOTO = isset($_POST['T_FOTO']) ? $_POST['T_FOTO'] : "";
  $N_PROV_NACI = isset($_POST['N_PROV_NACI']) ? $_POST['N_PROV_NACI'] : "";
  $N_PAIS_NACI = isset($_POST['N_PAIS_NACI']) ? $_POST['N_PAIS_NACI'] : "";
  $T_CODI_POST = isset($_POST['T_CODI_POST']) ? $_POST['T_CODI_POST'] : "";
  $T_INTE = isset($_POST['T_INTE']) ? $_POST['T_INTE'] : "";


  $CUIT = $_POST['N_CUIT_PRE'] . "-" . $_POST['N_CUIT'] . "-" . $_POST['N_CUIT_POST'];

  $statement = $dbConn->prepare("UPDATE eegepe001 SET T_APEL = :T_APEL, T_NOMB = :T_NOMB, N_CUIL = :N_CUIL, T_CALL = :T_CALL, T_PISO = :T_PISO, T_DEPA = :T_DEPA, N_LOCA = :N_LOCA, N_PROV = :N_PROV, N_PAIS = :N_PAIS, N_LOCA_NACI = :N_LOCA_NACI, F_NACI = :F_NACI, P_ESTA_CIVI = :P_ESTA_CIVI, T_TELE = :T_TELE, T_MAIL = :T_MAIL, F_ALTA = :F_ALTA, F_BAJA = :F_BAJA, F_INIC_ACTI = :F_INIC_ACTI, T_FOTO = :T_FOTO, N_PROV_NACI = :N_PROV_NACI, N_PAIS_NACI = :N_PAIS_NACI, T_CODI_POST = :T_CODI_POST, T_INTE = :T_INTE WHERE id = :ID");

  $statement->bindValue(":T_APEL", $T_APEL);
  $statement->bindValue(":T_NOMB", $T_NOMB);
  $statement->bindValue(":N_CUIL", $CUIT);
  $statement->bindValue(":T_CALL", $T_CALL);
  $statement->bindValue(":T_PISO", $T_PISO);
  $statement->bindValue(":T_DEPA", $T_DEPA);
  $statement->bindValue(":N_LOCA", $N_LOCA);
  $statement->bindValue(":N_PROV", $N_PROV);
  $statement->bindValue(":N_PAIS", $N_PAIS);
  $statement->bindValue(":N_LOCA_NACI", $N_LOCA_NACI);
  $statement->bindValue(":F_NACI", $F_NACI);
  $statement->bindValue(":P_ESTA_CIVI", $P_ESTA_CIVI);
  $statement->bindValue(":T_TELE", $T_TELE);
  $statement->bindValue(":T_MAIL", $T_MAIL);
  $statement->bindValue(":F_ALTA", $F_ALTA);
  $statement->bindValue(":F_BAJA", $F_BAJA);
  $statement->bindValue(":F_INIC_ACTI", $F_INIC_ACTI);
  $statement->bindValue(":T_FOTO", $T_FOTO);
  $statement->bindValue(":N_PROV_NACI", $N_PROV_NACI);
  $statement->bindValue(":N_PAIS_NACI", $N_PAIS_NACI);
  $statement->bindValue(":T_CODI_POST", $T_CODI_POST);
  $statement->bindValue(":T_INTE", $T_INTE);
  $statement->bindValue(":ID", $_POST["id"]);

  $statement->execute();
}

function bajaMaestroPersonal ($dbConn, $data) {
  $T_EMPR = $_SESSION['T_EMPR'];
  $T_SUCU = $_SESSION['T_SUCU'];

  $statement = $dbConn->prepare("DELETE FROM eegepe002 WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA");

  $statement->bindValue(":T_EMPR", $T_EMPR);
  $statement->bindValue(":T_SUCU", $T_SUCU);
  $statement->bindValue(":T_LEGA", $_POST['T_LEGA']);
  $statement->execute();



  $statement = $dbConn->prepare("DELETE FROM eegepe001 WHERE id = :ID");

  $statement->bindValue(":ID", $_POST["id"]);

  $statement->execute();
}

function consMaestroPersonal($dbConn) {
  $statement = $dbConn->prepare("SELECT * FROM eegepe001 WHERE F_BAJA is null OR F_BAJA = 0");
  $statement->execute();
  return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function consMaestroPersonalBaja($dbConn) {
  $statement = $dbConn->prepare("SELECT * FROM eegepe001 WHERE F_BAJA is not null");
  $statement->execute();
  return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function consMaestroPersonalTodo($dbConn) {
  $statement = $dbConn->prepare("SELECT * FROM eegepe001");
  $statement->execute();
  return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function seleMaestroPersonal($dbConn, $data1) {
  $statement = $dbConn->prepare("SELECT * FROM eegepe001 WHERE id = :ID");
  $statement->bindValue(":ID", $_POST["id"]);
  $statement->execute();
  return $statement->fetch(PDO::FETCH_ASSOC);
}


 ?>
