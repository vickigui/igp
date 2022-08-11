<?php
include "exportpdf.php";
include "includes/lateral.php";
$pageTitle = "Títulos";
include "includes/header.php";
include "ABM_Titulos_C.php";


$cons = consTitulos2($dbConn);
$consTitulos2 = grillaTitulos($dbConn);

$N_IDEN = isset($_POST['N_IDEN']) ? $_POST['N_IDEN'] : "";
$T_DESC_CORT = isset($_POST['T_DESC_CORT']) ? $_POST['T_DESC_CORT'] : "";
$T_NUME_IDEN = isset($_POST["T_NUME_IDEN"]) ? $_POST["T_NUME_IDEN"] : "";

if (isset($_POST['button-alta'])) {
  $alta = altaTitulos($dbConn);
  echo("<script>location.href = 'ABM_Titulos_D.php?T_LEGA=". $_GET['T_LEGA'] . "&T_APEL=" . $_GET['T_APEL'] . "&T_NOMB=" . $_GET['T_NOMB'] . "'</script>");
} else if (isset($_POST['confirma-baja'])) {
  $baja = bajaTitulos($dbConn);
  echo("<script>location.href = 'ABM_Titulos_D.php?T_LEGA=". $_GET['T_LEGA'] . "&T_APEL=" . $_GET['T_APEL'] . "&T_NOMB=" . $_GET['T_NOMB'] . "'</script>");
} else if (isset($_POST['confirma-modi'])) {
  $modi = modiTitulos($dbConn);
  echo("<script>location.href = 'ABM_Titulos_D.php?T_LEGA=". $_GET['T_LEGA'] . "&T_APEL=" . $_GET['T_APEL'] . "&T_NOMB=" . $_GET['T_NOMB'] . "'</script>");
}


?>

<script type="text/javascript">
    function revisarDatos(id, titu, otor, fech) {
      var id = id;
      var titu = titu;
      var otor = otor;
      var fech = fech;

      document.getElementById("id-elegido").value = id;
      document.getElementById("titu-elegido").value = titu;
      document.getElementById("otor-elegido").value = otor;
      document.getElementById("fech-elegido").value = fech;
      document.getElementById("id-elegido-2").value = id;
  };

//   function revisarDatos2(id) {
//     var id = id;
//
//     document.getElementById("id-elegido").value = id;
// };
</script>



<div style="margin-top: 4rem">
  <div class="card mb-4">
    <div class="card-header card-header-primary d-flex justify-content-between align-items-center">
      <div>
        <h2 class="card-title">Títulos</h2>
      </div>
      <div class="d-flex" id="datos-usuario">
        <span><?php echo $_GET['T_LEGA']; ?> -&nbsp;</span>
        <span><?php echo $_GET['T_NOMB']; ?>&nbsp;</span>
        <span><?php echo $_GET['T_APEL']; ?> </span>
      </div>
    </div>
    <div class="card-body">
        <table id="datatablesSimple">
          <thead>
            <tr>
              <th class="tituloGrilla">Título</th>
              <th class="tituloGrilla">Establecimiento</th>
              <th class="tituloGrilla">Fecha</th>
              <th class="tituloGrilla"><?php echo $mensaje[1437];?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($cons as $con): ?>
              <tr>
                <td><?php echo $con['N_TITU']; ?></td>
                <td><?php echo $con['N_OTOR']; ?></td>
                <td><?php echo $con['F_TITU']; ?></td>
                <td>
                  <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modi-js" onclick="javascript:revisarDatos('<?php echo $con['id']?>', '<?php echo $con['N_TITU']?>', '<?php echo $con['N_OTOR']?>', '<?php echo $con['F_TITU']?>');"><i class="fas fa-edit"></i></button>
                  <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#baja-js" onclick="javascript:revisarDatos(<?php echo $con['id']?>);"><i class="fas fa-trash-alt"></i></button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#alta"><i class="fas fa-plus-circle"></i></button>
      <button type="button" class="btn btn-primary"><i class="far fa-file-pdf" value="Print Div Contents" id="btnPrint"></i></button>
      <button type="button" class="btn btn-primary" onclick="ExportToExcel('xlsx')"><i class="far fa-file-excel"></i></button>
      <button type="button" class="btn btn-primary" onclick="ExportHTML()"><i class="far fa-file-word"></i></button>
      <button type="button" class="btn btn-primary" onclick="history.back()"><i class="fas fa-arrow-left"></i></button>
    </div>
  </div>
</div>


<!--modal alta-->
<div class="modal fade" id="alta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class='modal-header'>
          <h5 class='modal-title'><?php echo $mensaje[1463]; ?></h5>
          <button type='button' class='close' data-dismiss='modal'>&times;</button>
      </div>
      <div class='modal-body'>
          <form method='post' action=''>
            <input type="hidden" name="T_LEGA" value="<?php echo $_GET['T_LEGA']; ?>">
            <div class='form-group'>
              <label for="">Título</label>
              <input type='text' name='N_TITU' class='form-control' value=''>
            </div>
            <div class='form-group'>
              <label for="">Establecimiento</label>
              <input type='text' name='N_OTOR' class='form-control' value=''>
            </div>
            <div class='form-group'>
              <label for="">Fecha</label>
              <input type='date' name='F_TITU' class='form-control' value=''>
            </div>
            <div class='form-group'>
                <button type='submit' class='btn btn-primary' name='button-alta'><?php echo $mensaje[57]; ?></button>
            </div>
          </form>
      </div>
    </div>
  </div>
</div>

<!--modal edición-->
<div class="modal fade" id="modi-js" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class='modal-header'>
          <h5 class='modal-title'><?php echo $mensaje[1521] . " " . $mensaje[1331]; ?></h5>
          <button type='button' class='close' data-dismiss='modal'>&times;</button>
      </div>
      <div class='modal-body'>
        <p id="lega-elegido"></p>
          <form method='post' action=''>
            <input type="hidden" name="id" id="id-elegido" value="">
            <div class='form-group'>
              <label for="">Título</label>
              <input type='text' name='N_TITU' id="titu-elegido" class='form-control' value=''>
            </div>
            <div class='form-group'>
              <label for="">Establecimiento</label>
              <input type='text' name='N_OTOR' id="otor-elegido" class='form-control' value=''>
            </div>
            <div class='form-group'>
              <label for="">Fecha</label>
              <input type='date' name='F_TITU' id="fech-elegido" class='form-control' value=''>
            </div>
            <div class='form-group'>
                <button type='submit' class='btn btn-primary' name='confirma-modi'><?php echo $mensaje[1542]; ?></button>
            </div>
          </form>
      </div>
    </div>
  </div>
</div>

<!--modal baja-->
<div class="modal fade" id="baja-js" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class='modal-header'>
          <h5 class='modal-title'>Eliminar Título</h5>
          <button type='button' class='close' data-dismiss='modal'>&times;</button>
      </div>
      <div class='modal-body'>
        <p>¿Está seguro de borrar este título?</p>
          <form method='post' action=''>
            <input type="hidden" name="id" id="id-elegido-2" value="">
            <div class='form-group'>
                <button type='submit' class='btn btn-primary' name='confirma-baja'>Sí</button>
                <button type="button" class='btn btn-default btn-close-form' data-dismiss='modal' name="button">Cancelar</button>
            </div>
          </form>
      </div>
    </div>
  </div>
</div>

<!--tabla para impresión-->
<div style="display:none">
  <div id="dvContainer">
    <div class="card-header card-header-primary d-flex justify-content-between align-items-center">
      <div>
        <h2 class="card-title" style="font-family: 'Roboto', 'Helvetica', 'Arial', sans-serif;" id="tituloDocumento"><?php echo $mensaje[1139]; ?></h2>
      </div>
      <div class="d-flex" id="datos-usuario">
        <span style="font-family: 'Roboto', 'Helvetica', 'Arial', sans-serif;" id="legajo"><?php echo $_GET['T_LEGA']; ?></span>
        <span style="font-family: 'Roboto', 'Helvetica', 'Arial', sans-serif;" id="nombre"><?php echo $_GET['T_NOMB']; ?></span>
        <span style="font-family: 'Roboto', 'Helvetica', 'Arial', sans-serif;" id="apellido"><?php echo $_GET['T_APEL'];?> </span>
      </div>
    </div>
    <table id="datatablesExport">
      <thead>
        <tr>
          <th class="tituloGrilla"><?php echo $mensaje[611];?></th>
          <th class="tituloGrilla"><?php echo $mensaje[448];?></th>
          <?php if (isset ($_POST['button-cargosBaja']) || isset($_POST['button-cargosCompleto'])) {?>
          <th class="tituloGrilla"><?php echo $mensaje[449];?></th>
          <?php } ?>
        </tr>
      </thead>
      <tbody>
        <?php if (isset ($_POST['button-cargosBaja'])) {
          foreach ($consBajaCargo as $con): ?>
          <tr>
            <td><?php echo $con['T_DESC_LARG']; ?></td>
            <td><?php echo $con['F_INIC_CARG']; ?></td>
            <td><?php echo $con['F_FINI_CARG']; ?></td>
          </tr>
        <?php endforeach;
        } else if (isset ($_POST['button-cargosCompleto'])){
          foreach ($consTodoCargo as $con): ?>
            <tr>
              <td><?php echo $con['T_DESC_LARG']; ?></td>
              <td><?php echo $con['F_INIC_CARG']; ?></td>
              <td><?php echo $con['F_FINI_CARG']; ?></td>
            </tr>
          <?php endforeach;
          } else {
            foreach ($consCargoLegajo as $con): ?>
              <tr>
                <td><?php echo $con['T_DESC_LARG']; ?></td>
                <td><?php echo $con['F_INIC_CARG']; ?></td>
              </tr>
            <?php endforeach; }?>
      </tbody>
    </table>
  </div>
</div>

<?php include "includes/footer.php" ?>
