<?php
include "exportpdf.php";
include "includes/lateral.php";
$pageTitle = "Asignaciones familiares";
include "includes/header.php";
include "ABM_AsignacionesFamiliares_C.php";

$consAsignacionLegajo = consAsignacionLegajo($dbConn);
$consSalarioFamiliar = consSalarioFamiliar($dbConn);

$id = isset($_POST['id']) ? $_POST['id'] : "";

if (isset($_POST['button-alta'])) {
  $alta = altaAsignacion($dbConn);
  echo("<script>location.href = 'ABM_AsignacionesFamiliares_D.php?T_LEGA=". $_GET['T_LEGA'] . "&T_APEL=" . $_GET['T_APEL'] . "&T_NOMB=" . $_GET['T_NOMB'] . "'</script>");
} else if (isset($_POST['confirma-baja'])) {
  $baja = bajaAsignacion($dbConn);
  echo("<script>location.href = 'ABM_AsignacionesFamiliares_D.php?T_LEGA=". $_GET['T_LEGA'] . "&T_APEL=" . $_GET['T_APEL'] . "&T_NOMB=" . $_GET['T_NOMB'] . "'</script>");
} else if (isset($_POST['confirma-modi'])) {
  $actualizacion = actualizaAsignacion($dbConn);
  echo("<script>location.href = 'ABM_AsignacionesFamiliares_D.php?T_LEGA=". $_GET['T_LEGA'] . "&T_APEL=" . $_GET['T_APEL'] . "&T_NOMB=" . $_GET['T_NOMB'] . "'</script>");
}

if (isset($_POST['button-salario'])) {
  if (isset($consSalarioFamiliar['id'])) {
    $actualizaSalario = actualizaSalarioFamiliar($dbConn);
    echo("<script>location.href = 'ABM_AsignacionesFamiliares_D.php?T_LEGA=". $_GET['T_LEGA'] . "&T_APEL=" . $_GET['T_APEL'] . "&T_NOMB=" . $_GET['T_NOMB'] . "'</script>");
  } else {
    $altaSalario = altaSalarioFamiliar($dbConn);
    echo("<script>location.href = 'ABM_AsignacionesFamiliares_D.php?T_LEGA=". $_GET['T_LEGA'] . "&T_APEL=" . $_GET['T_APEL'] . "&T_NOMB=" . $_GET['T_NOMB'] . "'</script>");
  }
}
?>


<script type="text/javascript">
function revisarDatos(id, nomb, apel, naci, docu) {
    var id = id;
    var nomb = nomb;
    var apel = apel;
    var naci = naci;
    var docu = docu;
    document.getElementById("id-elegido").value = id;
    document.getElementById("nomb-elegido").value = nomb;
    document.getElementById("apel-elegido").value = apel;
    document.getElementById("naci-elegido").value = naci;
    document.getElementById("docu-elegido").value = docu;
};

function revisarId(id) {
    var id = id;
    document.getElementById("id-elegido-borrar").value = id;
};
</script>

<div class="card mb-4">
  <div class="card-header card-header-primary d-flex justify-content-between align-items-center">
    <div>
      <h2 class="card-title">Asignaciones familiares</h2>
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
            <th class="tituloGrilla">Nombre</th>
            <th class="tituloGrilla">Apellido</th>
            <th class="tituloGrilla">Fecha de nacimiento</th>
            <th class="tituloGrilla"><?php echo $mensaje[1437]; ?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($consAsignacionLegajo as $con): ?>
            <tr>
              <td><?php echo $con['T_NOMB']; ?></td>
              <td><?php echo $con['T_APEL']; ?></td>
              <td><?php echo $con['F_NACI']; ?></td>
              <td>
                <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modi-js" onclick="javascript:revisarDatos(<?php echo $con['id']?> , '<?php echo $con['T_NOMB']?>', '<?php echo $con['T_APEL']?>', '<?php echo $con['F_NACI']?>', '<?php echo $con['N_DOCU']?>');"><i class="fas fa-edit"></i></button>
                <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#baja-js" onclick="javascript:revisarId(<?php echo $con['id']?>);"><i class="fas fa-trash-alt"></i></button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="d-inline-flex">
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#salariogrupo">Salario del Grupo Familiar</button>
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#alta"><i class="fas fa-plus-circle"></i></button>
      <button type="button" class="btn btn-primary"><i class="far fa-file-pdf" value="Print Div Contents" id="btnPrint"></i></button>
      <button type="button" class="btn btn-primary" onclick="ExportToExcel('xlsx')"><i class="far fa-file-excel"></i></button>
      <button type="button" class="btn btn-primary" onclick="ExportHTML()"><i class="far fa-file-word"></i></button>
      <button type="button" class="btn btn-primary" onclick="history.back()"><i class="fas fa-arrow-left"></i></button>
    </div>
  </div>
</div>

<!--modal salario familiar-->
<div class="modal fade" id="salariogrupo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class='modal-header'>
          <h5 class='modal-title'>Salario del grupo familiar</h5>
          <button type='button' class='close' data-dismiss='modal'>&times;</button>
      </div>
      <div class='modal-body'>
          <form method='post' action=''>
            <div class='form-group'>
              <label for="">Salario de otros miembros de la familia</label>
              <input type='number' name='N_SALA_FAMI' class='form-control' value='<?php if (isset($consSalarioFamiliar['id'])) { echo $consSalarioFamiliar['N_SALA_FAMI'];} ?>'>
            </div>
            <div class='form-group'>
                <button type='submit' class='btn btn-primary' name='button-salario'>Aceptar</button>
            </div>
          </form>
      </div>
    </div>
  </div>
</div>

<!--modal alta-->
<div class="modal fade" id="alta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class='modal-header'>
          <h5 class='modal-title'>Agregar hijo</h5>
          <button type='button' class='close' data-dismiss='modal'>&times;</button>
      </div>
      <div class='modal-body'>
          <form method='post' action=''>
            <input type="hidden" name="T_LEGA" value="<?php echo $_GET['T_LEGA']; ?>">
            <div class='form-group'>
              <label for="">Nombre</label>
              <input type='text' name='T_NOMB' class='form-control' value=''>
            </div>
            <div>
              <div class='form-group'>
                <label for="">Apellido</label>
                <input type='text' name='T_APEL' class='form-control' value=''>
              </div>
              <div class='form-group'>
                <label for="">Fecha de nacimiento</label>
                <input type='date' name='F_NACI' class='form-control' value=''>
              </div>
              <div class='form-group'>
                <label for="">Número de documento</label>
                <input type='text' name='N_DOCU' class='form-control' value=''>
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
          <h5 class='modal-title'>Editar hijo</h5>
          <button type='button' class='close' data-dismiss='modal'>&times;</button>
      </div>
      <div class='modal-body'>
          <form method='post' action=''>
            <input type="hidden" name="id" id="id-elegido" value="">
            <div>
              <div class='form-group'>
                <label for="">Nombre</label>
                <input type='text' name='T_NOMB' class='form-control' id="nomb-elegido" value=''>
              </div>
              <div class='form-group'>
                <label for="">Apellido</label>
                <input type='text' name='T_APEL' class='form-control' id="apel-elegido" value=''>
              </div>
              <div class='form-group'>
                <label for="">Fecha de nacimiento</label>
                <input type='date' name='F_NACI' class='form-control' id="naci-elegido" value=''>
              </div>
              <div class='form-group'>
                <label for="">Número de documento</label>
                <input type='text' name='N_DOCU' class='form-control' id="docu-elegido" value=''>
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
        <p>¿Está seguro de borrar esta antigüedad?</p>
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
          <th class="tituloGrilla"><?php echo $mensaje[1544]; ?></th>
          <th class="tituloGrilla"><?php echo $mensaje[448]; ?></th>
          <th class="tituloGrilla"><?php echo $mensaje[449]; ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($consAntiguedadLegajo as $con): ?>
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
