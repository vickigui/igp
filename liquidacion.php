<?php
include "exportpdf.php";
include "includes/lateral.php";
$pageTitle = "Liquidación";
include "includes/header.php";
include "ABM_Personal_C.php";

$cons = consPersonal($dbConn);
?>

<div class="card mb-4">
  <div class="card-header card-header-primary d-flex justify-content-between align-items-center">
    <div>
      <h2 class="card-title">Liquidación</h2>
    </div>
  </div>
  <div class="card-body">
    <div ="dvContainer" class="cienvh">
      <table id="datatablesSimple">
        <thead>
          <tr>
            <th class="tituloGrilla"><?php echo $mensaje[606];?></th>
            <th class="tituloGrilla"><?php echo $mensaje[312];?></th>
            <th class="tituloGrilla"><?php echo $mensaje[53];?></th>
            <th class="tituloGrilla"><?php echo $mensaje[1437]; ?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($cons as $con): ?>
              <tr class="row-hover">
                <td><?php echo $con['T_LEGA']; ?></td>
                <td><?php echo $con['T_APEL']; ?></td>
                <td><?php echo $con['T_NOMB']; ?></td>
                <td>
                  <form class="" action="calculos-sueldo.php" method="get">
                    <input type="hidden" name="T_LEGA" value="<?php echo $con['T_LEGA']; ?>">
                    <input type="hidden" name="T_APEL" value="<?php echo $con['T_APEL']; ?>">
                    <input type="hidden" name="T_NOMB" value="<?php echo $con['T_NOMB']; ?>">
                    <button class="btn btn-primary" type="submit">Liquidar</i></button>
                  </form>
                </td>
              </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include "includes/footer.php" ?>
