<?php
include "exportpdf.php";
include "includes/lateral.php";
$pageTitle = $mensaje[796];
include "includes/header.php";
include "ABM_Licencia_C.php";

$consLicenciaLegajo = consLicenciaLegajo($dbConn);
$consArticulos = consArticulos($dbConn);
$id = isset($_POST['id']) ? $_POST['id'] : "";
$consCargosLegajo = consCargosLegajo($dbConn);

if (isset($_POST['button-alta'])) {
  $alta = altaLicencia($dbConn);
  // echo("<script>location.href = 'ABM_Licencia_D.php?T_LEGA=". $_GET['T_LEGA'] . "&T_APEL=" . $_GET['T_APEL'] . "&T_NOMB=" . $_GET['T_NOMB'] . "'</script>");
} else if (isset($_POST['confirma-baja'])) {
  $baja = bajaLicencia($dbConn);
  echo("<script>location.href = 'ABM_Licencia_D.php?T_LEGA=". $_GET['T_LEGA'] . "&T_APEL=" . $_GET['T_APEL'] . "&T_NOMB=" . $_GET['T_NOMB'] . "'</script>");
} else if (isset($_POST['confirma-modi'])) {
  $actualizacion = actualizaLicencia($dbConn);
  echo("<script>location.href = 'ABM_Licencia_D.php?T_LEGA=". $_GET['T_LEGA'] . "&T_APEL=" . $_GET['T_APEL'] . "&T_NOMB=" . $_GET['T_NOMB'] . "'</script>");
}
?>


<script type="text/javascript">
function siNoLice() {
  if(document.getElementById('N').checked) {
    document.getElementById('noLice').style.display = 'block';
    document.getElementById('siLice').style.display = 'none';
  } else {
    document.getElementById('noLice').style.display = 'none';
    document.getElementById('siLice').style.display = 'block';
  }
};

function revisarDatos(id, esta, inic, fina) {
    var id = id;
    var esta = esta;
    var inic = inic;
    var fina = fina;
    document.getElementById("id-elegido").value = id;
    document.getElementById("esta-elegido").value = esta;
    document.getElementById("inic-elegido").value = inic;
    document.getElementById("fina-elegido").value = fina;
};

function revisarId(id) {
    var id = id;
    document.getElementById("id-elegido-borrar").value = id;
};
</script>

<div class="card mb-4">
  <div class="card-header card-header-primary d-flex justify-content-between align-items-center">
    <div>
      <h2 class="card-title"><?php echo $mensaje[796]; ?></h2>
    </div>
    <div class="d-flex" id="datos-usuario">
      <span><?php echo $_GET['T_LEGA']; ?> -&nbsp;</span>
      <span><?php echo $_GET['T_NOMB']; ?>&nbsp;</span>
      <span><?php echo $_GET['T_APEL']; ?> </span>
    </div>
  </div>
  <div class="card-body">
    <div ="dvContainer">
      <table id="datatablesSimple">
        <thead>
          <tr>
            <th class="tituloGrilla"><?php echo $mensaje[1544]; ?></th>
            <th class="tituloGrilla"><?php echo $mensaje[448]; ?></th>
            <th class="tituloGrilla"><?php echo $mensaje[449]; ?></th>
            <th class="tituloGrilla">Artículo</th>
            <th class="tituloGrilla"><?php echo $mensaje[1437]; ?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($consLicenciaLegajo as $con): ?>
            <tr>
              <td><?php echo $con['T_ESTA']; ?></td>
              <td><?php echo $con['F_INIC']; ?></td>
              <td><?php echo $con['F_FINA']; ?></td>
              <td><?php echo $con['T_DESC']; ?></td>
              <td>
                <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modi-js" onclick="javascript:revisarDatos(<?php echo $con['id']?> , '<?php echo $con['T_ESTA']?>', '<?php echo $con['F_INIC']?>', '<?php echo $con['F_FINA']?>');"><i class="fas fa-edit"></i></button>
                <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#baja-js" onclick="javascript:revisarId(<?php echo $con['id']?>);"><i class="fas fa-trash-alt"></i></button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="d-inline-flex">
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#alta"><i class="fas fa-plus-circle"></i></button>
      <button type="button" class="btn btn-primary"><i class="far fa-file-pdf" value="Print Div Contents" id="btnPrint"></i></button>
      <button type="button" class="btn btn-primary" onclick="ExportToExcel('xlsx')"><i class="far fa-file-excel"></i></button>
      <button type="button" class="btn btn-primary" onclick="ExportHTML()"><i class="far fa-file-word"></i></button>
      <button type="button" class="btn btn-primary" onclick="history.back()"><i class="fas fa-arrow-left"></i></button>
    </div>
  </div>
</div>

<!--modal alta-->
<script type="text/javascript">
function siNoBoni() {
  if(document.getElementById('N').checked) {
    document.getElementById('noBoni').style.display = 'block';
    document.getElementById('porFechas').style.display = 'none';
  } else {
    document.getElementById('noBoni').style.display = 'none';
    document.getElementById('porFechas').style.display = 'block';
  }
};
</script>

<div class="modal fade" id="alta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class='modal-header'>
          <h5 class='modal-title'>Agregar licencia</h5>
          <button type='button' class='close' data-dismiss='modal'>&times;</button>
      </div>
      <div class='modal-body'>
          <form method='post' action=''>
            <input type="hidden" name="T_LEGA" value="<?php echo $_GET['T_LEGA']; ?>">
            <div class='form-group'>
              <label for="">¿Desea agregar una licencia para su establecimiento?</label>
              <input type="radio" name="LICE_ESTA" value="S" checked id="S" onclick="javascript:siNoLice();">
              <label for="S">Si</label>
              <input type="radio" name="LICE_ESTA" value="N" id="N" onclick="javascript:siNoLice();">
              <label for="N">No</label>
            </div>
            <div id="siLice">
              <div class='form-group'>
                <label for="">Cargo</label>
                <select class="form-select" name="ART_ID">
                <option value="<?php echo $con['id']; ?>">Todos los cargos</option>
                <?php foreach ($consCargosLegajo as $con): ?>
                    <option value="<?php echo $con['id']; ?>"><?php echo $con['T_DESC_LARG']; ?></option>
                <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div id="noLice" style="display: none">
              <div class='form-group'>
                <label for="">Establecimiento</label>
                <label for="">Cargo</label>
                <select class="form-select" name="ART_ID">
                <option value="<?php echo $con['id']; ?>">Todos los cargos</option>
                <?php foreach ($consLicenciaLegajo as $con) {
                  if ($con['T_ESTA'] != null) { ?>
                    <option value="<?php echo $con['id']; ?>"><?php echo $con['T_ESTA'] . " / " . $con['F_INIC'] . " hasta " . $con['F_FINA']?></option>
                  <?php }
                  }?>
                </select>
                <!-- <input type='text' name='T_ESTA' class='form-control' value=''> -->
              </div>
            </div>
            <div class='form-group'>
              <label for="">Artículo</label>
              <select class="form-select" name="ART_ID">
              <?php foreach ($consArticulos as $con): ?>
                  <option value="<?php echo $con['id']; ?>"><?php echo $con['T_DESC']; ?></option>
              <?php endforeach; ?>
              </select>
            </div>
            <div>
              <div class='form-group'>
                <label for=""><?php echo $mensaje[448]; ?></label>
                <input type='date' name='F_INIC' class='form-control' value=''>
              </div>
              <div class='form-group'>
                <label for=""><?php echo $mensaje[449]; ?></label>
                <input type='date' name='F_FINA' class='form-control' value=''>
              </div>
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
          <h5 class='modal-title'><?php echo $mensaje[1543]; ?></h5>
          <button type='button' class='close' data-dismiss='modal'>&times;</button>
      </div>
      <div class='modal-body'>
          <form method='post' action=''>
            <input type="hidden" name="id" id="id-elegido" value="">
            <div class='form-group'>
              <label for="">Establecimiento</label>
              <input type='text' name='T_ESTA' class='form-control' id="esta-elegido" value=''>
            </div>
            <div class='form-group'>
              <label for="">Artículo</label>
              <select class="form-select" name="ART_ID">
              <?php foreach ($consArticulos as $con): ?>
                  <option value="<?php echo $con['id']; ?>"><?php echo $con['T_DESC']; ?></option>
              <?php endforeach; ?>
            </select>
            </div>
            <div>
              <div class='form-group'>
                <label for=""><?php echo $mensaje[448]; ?></label>
                <input type='date' name='F_INIC' class='form-control' id="inic-elegido" value=''>
              </div>
              <div class='form-group'>
                <label for=""><?php echo $mensaje[449]; ?></label>
                <input type='date' name='F_FINA' class='form-control' id="fina-elegido" value=''>
              </div>
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
          <h5 class='modal-title'>Eliminar registro</h5>
          <button type='button' class='close' data-dismiss='modal'>&times;</button>
      </div>
      <div class='modal-body'>
        <p>¿Está seguro de borrar esta licencia?</p>
          <form method='post' action=''>
            <input type="hidden" name="id" id="id-elegido-borrar" value="">
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
        <h2 class="card-title" style="font-family: 'Roboto', 'Helvetica', 'Arial', sans-serif;" id="tituloDocumento"><?php echo $mensaje[796]; ?></h2>
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
          <th class="tituloGrilla"><?php echo $mensaje[1544]; ?></th>
          <th class="tituloGrilla"><?php echo $mensaje[448]; ?></th>
          <th class="tituloGrilla"><?php echo $mensaje[449]; ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($consLicenciaLegajo as $con): ?>
          <tr>
            <td style="font-family: 'Roboto', 'Helvetica', 'Arial', sans-serif;"><?php echo $con['T_ESTA']; ?></td>
            <td style="font-family: 'Roboto', 'Helvetica', 'Arial', sans-serif;"><?php echo $con['F_INIC']; ?></td>
            <td style="font-family: 'Roboto', 'Helvetica', 'Arial', sans-serif;"><?php echo $con['F_FINA']; ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include "includes/footer.php"; ?>
