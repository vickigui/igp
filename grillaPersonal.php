<?php
include "exportpdf.php";
include "includes/lateral.php";
$pageTitle = $_GET['mensaje'];
include "includes/header.php";
include "ABM_Personal_C.php";

$cons = consPersonal($dbConn);
?>

<div class="card mb-4">
  <div class="card-header card-header-primary">
    <h2 class="card-title"><?php echo $_GET['mensaje']; ?></h2>
  </div>
  <div class="card-body">
    <div id="dvContainer">
      <table id="datatablesSimple">
        <thead>
          <tr>
            <th class="tituloGrilla"><?php echo $mensaje[606];?></th>
            <th class="tituloGrilla"><?php echo $mensaje[312];?></th>
            <th class="tituloGrilla"><?php echo $mensaje[53];?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($cons as $con): ?>
              <tr class="row-hover" onclick="window.location='ABM_<?php echo $_GET['archivo']; ?>_D.php?T_LEGA=<?php echo $con['T_LEGA']; ?>&T_APEL=<?php echo $con['T_APEL']; ?>&T_NOMB=<?php echo $con['T_NOMB']; ?>'">
                  <td><?php echo $con['T_LEGA']; ?></td>
                  <td><?php echo $con['T_APEL']; ?></td>
                  <td><?php echo $con['T_NOMB']; ?></td>
              </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include "includes/footer.php" ?>
