<?php
include "exportpdf.php";
include "includes/lateral.php";
$pageTitle = $mensaje[1331];
include "includes/header.php";
include "ABM_Identificaciones_C.php";


$cons = consIdentificaciones($dbConn, $_SESSION, $_POST);
$consIdentificaciones = grillaIdentificaciones($dbConn);

$N_IDEN = isset($_POST['N_IDEN']) ? $_POST['N_IDEN'] : "";
$T_DESC_CORT = isset($_POST['T_DESC_CORT']) ? $_POST['T_DESC_CORT'] : "";
$T_NUME_IDEN = isset($_POST["T_NUME_IDEN"]) ? $_POST["T_NUME_IDEN"] : "";

if (isset($_POST['button-alta'])) {
  $alta = altaIdentificaciones($dbConn);
  echo("<script>location.href = 'ABM_Identificaciones_D.php?T_LEGA=". $_GET['T_LEGA'] . "&T_APEL=" . $_GET['T_APEL'] . "&T_NOMB=" . $_GET['T_NOMB'] . "'</script>");
} else if (isset($_POST['confirma-baja'])) {
  $baja = bajaIdentificaciones($dbConn);
  echo("<script>location.href = 'ABM_Identificaciones_D.php?T_LEGA=". $_GET['T_LEGA'] . "&T_APEL=" . $_GET['T_APEL'] . "&T_NOMB=" . $_GET['T_NOMB'] . "'</script>");
} else if (isset($_POST['confirma-modi'])) {
  $modi = modiIdentificaciones($dbConn);
  echo("<script>location.href = 'ABM_Identificaciones_D.php?T_LEGA=". $_GET['T_LEGA'] . "&T_APEL=" . $_GET['T_APEL'] . "&T_NOMB=" . $_GET['T_NOMB'] . "'</script>");
}


?>

<script type="text/javascript">
    function revisarDatos(id, iden, nume, desc) {
      var id = id;
      var iden = iden;
      var nume = nume;
      var desc = desc;

      document.getElementById("id-elegido").value = id;
      document.getElementById("iden-elegido").value = iden;
      document.getElementById("iden-elegido").innerHTML = desc;
      document.getElementById("nume-elegido").value = nume;
      document.getElementById("id-elegido-2").value = id;
      // document.getElementById("desc-elegido").innerHTML = desc;
  };
</script>



<div style="margin-top: 4rem">
  <div class="card mb-4">
    <div class="card-header card-header-primary d-flex justify-content-between align-items-center">
      <div>
        <h2 class="card-title"><?php echo $mensaje[1331]; ?></h2>
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
              <th class="tituloGrilla"><?php echo $mensaje[523];?></th>
              <th class="tituloGrilla"><?php echo $mensaje[524];?></th>
              <th class="tituloGrilla"><?php echo $mensaje[1437];?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($cons as $con): ?>
              <tr>
                <td><?php echo $con['T_DESC_CORT']; ?></td>
                <td><?php echo $con['T_NUME_IDEN']; ?></td>
                <td>
                  <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modi-js" onclick="javascript:revisarDatos('<?php echo $con['id']?>', '<?php echo $con['N_IDEN']?>', '<?php echo $con['T_NUME_IDEN']?>', '<?php echo $con['T_DESC_CORT']?>');"><i class="fas fa-edit"></i></button>
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
              <label for=""><?php echo $mensaje[523]; ?></label>
              <select class="form-select" name="N_IDEN" id="N_IDEN">
              <?php foreach ($consIdentificaciones as $consIdentificacion): ?>
                <option value="<?php echo $consIdentificacion["N_IDEN"] ?>"><?php echo $consIdentificacion["T_DESC_CORT"] ?></option>
              <?php endforeach; ?>
              </select>
            </div>
            <div class='form-group'>
              <label for=""><?php echo $mensaje[524]; ?></label>
              <input type='text' name='T_NUME_IDEN' class='form-control' value=''>
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
            <p id="desc-elegido"></p>
            <div class='form-group'>
              <label for=""><?php echo $mensaje[523]; ?></label>
              <select class="form-select" name="N_IDEN">
                <option id="iden-elegido" value=""><?php echo $consIdentificacion["T_DESC_CORT"] ?></option>
              <?php foreach ($consIdentificaciones as $consIdentificacion): ?>
                <option value="<?php echo $consIdentificacion["N_IDEN"] ?>"><?php echo $consIdentificacion["T_DESC_CORT"] ?></option>
              <?php endforeach; ?>
              </select>
            </div>
            <div class='form-group'>
              <label for=""><?php echo $mensaje[524]; ?></label>
              <input type='text' name='T_NUME_IDEN' id="nume-elegido" class='form-control' value=''>
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
          <h5 class='modal-title'>Eliminar identificación</h5>
          <button type='button' class='close' data-dismiss='modal'>&times;</button>
      </div>
      <div class='modal-body'>
        <p>¿Está seguro de borrar esta identificación?</p>
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
