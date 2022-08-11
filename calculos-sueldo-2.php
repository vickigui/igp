<?php
include "exportpdf.php";
include "includes/lateral.php";
$pageTitle = $mensaje[929];
include "includes/header.php";
include "ABM_MaestroPersonal_C.php";
include "ABM_AsignacionesFamiliares_C.php";
include "includes/funciones.php";

?>
<div class="card mb-4">
  <div class="card-header card-header-primary d-flex justify-content-between align-items-center">
    <div>
      <h2 class="card-title">Liquidación - <?php echo $_GET['T_LEGA'] . " - " . $_GET['T_NOMB'] . " " . $_GET['T_APEL']?></h2>
    </div>
  </div>
  <div class="card-body">
    <div ="dvContainer" class="cienvh">
      <?php
        $conceptoBR1 = 0;
        $conceptoBoniSec = 0;
        $conceptoBR2 = 0;
        $conceptoBoniBR3 = 0;
        $conceptoBoniBR4 = 0;
        $conceptoBoniEGB = 0;
        $conceptoBoniNoJerar = 0;
        $conceptoBR5 = 0;
        $conceptoBNR1 = 0;
        $conceptoBNR2 = 0;
        $conceptoAsignacionHijo = 0;

    //Select tabla de personal y cargos
      $T_EMPR = $_SESSION['T_EMPR'];
      $T_SUCU = $_SESSION['T_SUCU'];
      $T_LEGA = $_GET['T_LEGA'];

      $statement = $dbConn->prepare("SELECT * FROM eegepe003 WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA AND F_FINI_CARG = 0");
      $statement->bindValue(":T_EMPR", $T_EMPR);
      $statement->bindValue(":T_SUCU", $T_SUCU);
      $statement->bindValue(":T_LEGA", $T_LEGA);
      $statement->execute();
      $legajoSeleccionado = $statement->fetchAll(PDO::FETCH_ASSOC);

      //Select Montos
      $statement = $dbConn->prepare("SELECT * FROM eegetm018");
      $statement->execute();
      $montos = $statement->fetchAll(PDO::FETCH_ASSOC);

      $arrayMontos = [];

      foreach ($montos as $monto) {
        $arrayMontos[$monto['T_DESC_CORT']] = $monto['N_MONT'];
      }

      $BC = $arrayMontos['BC'];
      $BR1 = $arrayMontos['BR1'];
      $BR2 = $arrayMontos['BR2'];
      $BR3 = $arrayMontos['BR3'];
      $BR4 = $arrayMontos['BR4'];
      $BR5 = $arrayMontos['BR5'];
      $BNR1 =	$arrayMontos['BNR1'];
      $BNR2	= $arrayMontos['BNR2'];

      foreach ($legajoSeleccionado as $legajo) {
        $idCargo = $legajo['N_CARG'];

        //Select tabla de cargos
        $statement = $dbConn->prepare("SELECT * FROM eegetm011 WHERE id = :id");
        $statement->bindValue(":id", $idCargo);
        $statement->execute();
        $cargo = $statement->fetch(PDO::FETCH_ASSOC);

        //Select conceptos para ese cargo
        $statement = $dbConn->prepare("SELECT * FROM eegetm016");
        $statement->execute();
        $conceptosCargo = $statement->fetchAll(PDO::FETCH_ASSOC);

        //ArrayB donde voy a ir guardando los resultados de las distintas fórmulas
        $arraySueldo = [];

        foreach ($conceptosCargo as $concepto) {
          //Fórmula concepto Nominal
          if ($concepto['id'] == 1) {
            if ($cargo['P_HORA'] == 'N') {
              $arraySueldo[] = ['Descripcion' => $concepto['T_DESC'], 'Valor' => $BC * $cargo['N_FACT']];
            } else {
              $arraySueldo[] = ['Descripcion' => $concepto['T_DESC'], 'Valor' => $BC / 15 * $legajo['N_CANT_HORA']];
            }
          //Fórmula Antigüedad
          } else if ($concepto['id'] == 2) {
            //Select Antigüedad que no tiene fecha de finalización
            $T_EMPR = $_SESSION['T_EMPR'];
            $T_SUCU = $_SESSION['T_SUCU'];
            $T_LEGA = $_GET['T_LEGA'];

            $statement = $dbConn->prepare("SELECT * FROM eegepe004 WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA AND F_FINA IS null AND T_LICE IS null");
            $statement->bindValue(":T_EMPR", $T_EMPR);
            $statement->bindValue(":T_SUCU", $T_SUCU);
            $statement->bindValue(":T_LEGA", $T_LEGA);
            $statement->execute();
            $antiguedadActual = $statement->fetch(PDO::FETCH_ASSOC);

            $inicAntiguedadActual = new DateTime($antiguedadActual['F_INIC']);
            $fechaActual = new DateTime(date("Y-m-d"));

            $diff = $fechaActual->diff($inicAntiguedadActual);
            $ANOS_ANTI_ACTU = $diff->y;
            $MES_ANTI_ACTU = $diff->m;
            $DIAS_ANTI_ACTU = $diff->d;

            // echo 'Años antigüedad actual: ' . $ANOS_ANTI_ACTU . '<br>';
            // echo 'Meses antigüedad actual: ' . $MES_ANTI_ACTU . '<br>';
            // echo 'Días antigüedad actual: ' . $DIAS_ANTI_ACTU . '<br>';


            //Select Antigüedad que sí tienen fecha de finalización y sumarlas
            $T_EMPR = $_SESSION['T_EMPR'];
            $T_SUCU = $_SESSION['T_SUCU'];
            $T_LEGA = $_GET['T_LEGA'];

            $statement = $dbConn->prepare("SELECT * FROM eegepe004 WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA AND F_FINA IS not null AND T_LICE IS null");
            $statement->bindValue(":T_EMPR", $T_EMPR);
            $statement->bindValue(":T_SUCU", $T_SUCU);
            $statement->bindValue(":T_LEGA", $T_LEGA);
            $statement->execute();
            $antiguedadSeleccionada = $statement->fetchAll(PDO::FETCH_ASSOC);

            $ANOS_ANTI = 0;
            $MES_ANTI = 0;
            $DIAS_ANTI = 0;

            foreach ($antiguedadSeleccionada  as $antiguedad) {
              $finaAntiguedad = new DateTime($antiguedad['F_FINA']);
              $inicAntiguedad = new DateTime($antiguedad['F_INIC']);

              $diff = $finaAntiguedad->diff($inicAntiguedad);

              $ANOS_ANTI = $ANOS_ANTI + $diff->y;
              $MES_ANTI = $MES_ANTI + $diff->m;
              $DIAS_ANTI = $DIAS_ANTI + $diff->d;

              // echo 'Años antigüedad viejo: ' . $diff->y . '<br>';
              // echo 'Meses antigüedad viejo: ' . $diff->m . '<br>';
              // echo 'Días antigüedad viejo : ' . $diff->d . '<br>';
            }

            $diasAMeses = ($DIAS_ANTI + $DIAS_ANTI_ACTU) / 30;
            $mesesAAnos = ($MES_ANTI + $MES_ANTI_ACTU + $diasAMeses) / 12;
            $antiguedadSinLicencia = $ANOS_ANTI + $ANOS_ANTI_ACTU + $mesesAAnos;
            //


            //Select Licencias y sumarlas
            $statement = $dbConn->prepare("SELECT * FROM eegepe007 WHERE T_EMPR = :T_EMPR AND T_SUCU = :T_SUCU AND T_LEGA = :T_LEGA");
            $statement->bindValue(":T_EMPR", $T_EMPR);
            $statement->bindValue(":T_SUCU", $T_SUCU);
            $statement->bindValue(":T_LEGA", $T_LEGA);
            $statement->execute();
            $licenciaSeleccionada = $statement->fetchAll(PDO::FETCH_ASSOC);

            $ANOS_LICE = 0;
            $MES_LICE = 0;
            $DIAS_LICE = 0;

            foreach ($licenciaSeleccionada  as $licencia) {
              $finaLicencia = new DateTime($licencia['F_FINA']);
              $inicLicencia = new DateTime($licencia['F_INIC']);

              $diff = $finaLicencia->diff($inicLicencia);

              $ANOS_LICE = $ANOS_LICE + $diff->y;
              $MES_LICE = $MES_LICE + $diff->m;
              $DIAS_LICE = $DIAS_LICE + $diff->d;

              // echo "<br>";
              // echo 'Años licencia viejo: ' . $diff->y . '<br>';
              // echo 'Meses licencia viejo: ' . $diff->m . '<br>';
              // echo 'Días licencia viejo : ' . $diff->d . '<br>';
            }

            $diasAMesesLicencia = $DIAS_LICE / 30;
            $mesesAAnosLicencia = ($MES_LICE + $diasAMesesLicencia) / 12;
            $antiguedadLicencia = $ANOS_LICE + $mesesAAnosLicencia;

            $antiguedad = $antiguedadSinLicencia - $antiguedadLicencia;


            if ($antiguedad <= 1) {
              $arraySueldo[] = ['Descripcion' => $concepto['T_DESC'] . " (" . floor($antiguedad) . " años)", 'Valor' => $arraySueldo[0]['Valor'] * 21 / 100];
            } else if ($antiguedad <= 3) {
              $arraySueldo[] = ['Descripcion' => $concepto['T_DESC'] . " (" . floor($antiguedad) . " años)", 'Valor' => $arraySueldo[0]['Valor'] * 24 / 100];
            } else if ($antiguedad <= 6) {
              $arraySueldo[] = ['Descripcion' => $concepto['T_DESC'] . " (" . floor($antiguedad) . " años)", 'Valor' => $arraySueldo[0]['Valor'] * 33 / 100];
            } else if ($antiguedad <= 9) {
              $arraySueldo[] = ['Descripcion' => $concepto['T_DESC'] . " (" . floor($antiguedad) . " años)", 'Valor' => $arraySueldo[0]['Valor'] * 43 / 100];
            } else if ($antiguedad <= 11) {
              $arraySueldo[] = ['Descripcion' => $concepto['T_DESC'] . " (" . floor($antiguedad) . " años)", 'Valor' => $arraySueldo[0]['Valor'] * 54 / 100];
            } else if ($antiguedad <= 14) {
              $arraySueldo[] = ['Descripcion' => $concepto['T_DESC'] . " (" . floor($antiguedad) . " años)", 'Valor' => $arraySueldo[0]['Valor'] * 64 / 100];
            } else if ($antiguedad <= 16) {
              $arraySueldo[] = ['Descripcion' => $concepto['T_DESC'] . " (" . floor($antiguedad) . " años)", 'Valor' => $arraySueldo[0]['Valor'] * 74 / 100];
            } else if ($antiguedad <= 19) {
              $arraySueldo[] = ['Descripcion' => $concepto['T_DESC'] . " (" . floor($antiguedad) . " años)", 'Valor' => $arraySueldo[0]['Valor'] * 84 / 100];
            } else if ($antiguedad <= 21) {
              $arraySueldo[] = ['Descripcion' => $concepto['T_DESC'] . "(" . floor($antiguedad) . " años)", 'Valor' => $arraySueldo[0]['Valor'] * 105 / 100];
            } else if ($antiguedad <= 23) {
              $arraySueldo[] = ['Descripcion' => $concepto['T_DESC'] . " (" . floor($antiguedad) . " años)", 'Valor' => $arraySueldo[0]['Valor'] * 115 / 100];
            } else if ($antiguedad > 23) {
              $arraySueldo[] = ['Descripcion' => $concepto['T_DESC'] . " (" . floor($antiguedad) . " años)", 'Valor' => $arraySueldo[0]['Valor'] * 125 / 100];
            }
        } else if ($concepto['id'] == 3) {
          if ($cargo['N_FACT'] == '1.1') {
            if ($conceptoBR1 < 2) {
              $arraySueldo[] = ['Descripcion' => $concepto['T_DESC'], 'Valor' => $BR1];
              $conceptoBR1 = $conceptoBR1 + 1;
            }
          }
        } else if ($concepto['id'] == 4) {
          if ($cargo['N_CONC_4'] == 1) {
            if ($conceptoBoniSec < 2) {
              $arraySueldo[] = ['Descripcion' => $concepto['T_DESC'], 'Valor' => $BC * 37 / 100];
              $conceptoBoniSec = $conceptoBoniSec + 1;
            }
          }
        } else if ($concepto['id'] == 5) {
          if ($conceptoBR2 < 2) {
            if ($cargo['P_HORA'] == 'N') {
              $arraySueldo[] = ['Descripcion' => $concepto['T_DESC'], 'Valor' => $BR2];
              $conceptoBR2 = $conceptoBR2 + 1;
            } else {
              $arraySueldo[] = ['Descripcion' => $concepto['T_DESC'], 'Valor' => $BR2 / 15 * $legajo['N_CANT_HORA']];
              $conceptoBR2 = $conceptoBR2 + 1;
            }
          }
        } else if ($concepto['id'] == 6) {
          if ($cargo['N_CONC_6'] == 1) {
            if ($conceptoBoniBR3 < 2) {
              $arraySueldo[] = ['Descripcion' => $concepto['T_DESC'], 'Valor' => $BR3 * $cargo['N_FACT']];
              $conceptoBoniBR3 = $conceptoBoniBR3 + 1;
            }
          }
        } else if ($concepto['id'] == 7) {
          if ($cargo['N_CONC_7'] == 1) {
            if ($conceptoBR4 < 2) {
              $arraySueldo[] = ['Descripcion' => $concepto['T_DESC'], 'Valor' => $BR4];
              $conceptoBR4 = $conceptoBR4 + 1;
            }
          }
        } else if ($concepto['id'] == 8) {
          if ($cargo['P_HORA'] == 'N') {
            if ($conceptoBoniEGB < 2) {
              $arraySueldo[] = ['Descripcion' => $concepto['T_DESC'], 'Valor' => $BC * 67 / 100];
              $conceptoBoniEGB = $conceptoBoniEGB + 1;
            }
          }
        } else if ($concepto['id'] == 9) {
          if ($cargo['P_HORA'] == 'S') {
            if ($conceptoBoniNoJerar < 2) {
              $arraySueldo[] = ['Descripcion' => $concepto['T_DESC'], 'Valor' => $BC * 3 / 15 * 32 / 100];
              $conceptoBoniNoJerar = $conceptoBoniNoJerar + 1;
            }
          }
        } else if ($concepto['id'] == 10) {
          if ($cargo['N_CONC_10'] == 1) {
            if ($conceptoBR5 < 2) {
              $arraySueldo[] = ['Descripcion' => $concepto['T_DESC'], 'Valor' => $BR5 * $cargo['N_FACT']];
              $conceptoBR5 = $conceptoBR5 + 1;
            }
          }
        } else if ($concepto['id'] == 11) {
          $arraySueldo[] = ['Descripcion' => $concepto['T_DESC'], 'Valor' => ($arraySueldo[0]['Valor'] + $arraySueldo[1]['Valor']) * 10 / 100];
        } else if ($concepto['id'] == 12) {
          $totalParcial = 0;
          foreach ($arraySueldo as $array) {
            if ($array['Descripcion'] != 'Premio') {
              $totalParcial = $totalParcial + $array['Valor'];
            }
          }
          $arraySueldo[] = ['Descripcion' => $concepto['T_DESC'], 'Valor' => $totalParcial * 0.0637 / 0.81];
        } else if ($concepto['id'] == 13) {
          if ($conceptoBNR1 < 2) {
            if ($cargo['P_HORA'] == 'N') {
              $arraySueldo[] = ['Descripcion' => $concepto['T_DESC'], 'Valor' => $BNR1];
              $conceptoBNR1 = $conceptoBNR1 + 1;
            } else {
              $arraySueldo[] = ['Descripcion' => $concepto['T_DESC'], 'Valor' => $BNR1 * $cargo['N_FACT']];
              $conceptoBNR1 = $conceptoBNR1 + 1;
            }
          }
        } else if ($concepto['id'] == 14) {
          if ($conceptoBNR2 < 2) {
            if ($cargo['P_HORA'] == 'N') {
              $arraySueldo[] = ['Descripcion' => $concepto['T_DESC'], 'Valor' => $BNR2];
              $conceptoBNR2 = $conceptoBNR2 + 1;
            } else {
              $arraySueldo[] = ['Descripcion' => $concepto['T_DESC'], 'Valor' => $BNR2 * $cargo['N_FACT']];
              $conceptoBNR2 = $conceptoBNR2 + 1;
            }
          }
        } else if ($concepto['id'] == 15) {
          if ($conceptoAsignacionHijo < 1) {
            $salarioFamiliar = consSalarioFamiliar ($dbConn);
            if (isset($salarioFamiliar['id'])) {
              $totalParcial = 0;
              foreach ($arraySueldo as $array) {
                $totalParcial = $totalParcial + $array['Valor'];
              }
              $salarioFamiliarTotal = $totalParcial + $salarioFamiliar['N_SALA_FAMI'];

              $escalasAsignaciones = escalasAsignaciones ($dbConn);
              $consHijos = consHijos($dbConn);
              foreach ($escalasAsignaciones as $escala) {
                if ($salarioFamiliarTotal > $escala['N_INGR_MINI'] && $salarioFamiliarTotal < $escala['N_INGR_FAMI']) {
                  $arraySueldo[] = ['Descripcion' => $concepto['T_DESC'], 'Valor' => (int)$consHijos['hijo'] * $escala['N_ZONA_NORM']];
                  $conceptoAsignacionHijo = $conceptoAsignacionHijo + 1;
                }
              }
            }
          }
        }
      }
    ?>
    <!-- <h2><?php echo $cargo['T_DESC_LARG'] ?></h2><?php
    $totalFinal = 0;
    foreach ($arraySueldo as $array) { ?>
      <p><?php echo $array['Descripcion']?>: <?php echo $array['Valor'];?></p>
    <?php
    $totalFinal = $totalFinal + $array['Valor'];
  }
    ?> -->
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0"><?php echo $cargo['T_DESC_LARG'] ?></h6>
            <!-- <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                    aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Dropdown Header:</div>
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </div> -->
        </div>
        <!-- Card Body -->
        <div class="card-body">
          <?php
          $totalFinal = 0;
          foreach ($arraySueldo as $array) { ?>
            <p><?php echo $array['Descripcion']?>: <?php echo $array['Valor'];?></p>
          <?php
          $totalFinal = $totalFinal + $array['Valor'];
        }
          ?>
          <h5>Total: <?php echo $totalFinal ?></h5>

        </div>
    </div>

    <hr>
    <?php
  }
  ?>
      </div>
    </div>
  </div>

  <?php

  include "includes/footer.php";
 ?>
