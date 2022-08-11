<?php
include "exportpdf.php";
include "includes/lateral.php";
$pageTitle = $mensaje[1138];
include "includes/header.php";
include "ABM_CargosAsignados_C.php";

$consCargo = consCargo($dbConn);
$consCargoLegajo = consCargoLegajo($dbConn);
$consBajaCargo = consCargoLegajoBaja($dbConn);
$consTodoCargo = consCargoLegajoTodo($dbConn);
$consHoras = consHoras($dbConn);
$id = isset($_POST['id']) ? $_POST['id'] : "";

if (isset($_POST['button-alta'])) {
  $alta = altaCargo($dbConn);
  echo("<script>location.href = 'ABM_CargosAsignados_D.php?T_LEGA=". $_GET['T_LEGA'] . "&T_APEL=" . $_GET['T_APEL'] . "&T_NOMB=" . $_GET['T_NOMB'] . "'</script>");
} else if (isset($_POST['confirma-baja'])) {
  $baja = bajaCargo($dbConn);
  echo("<script>location.href = 'ABM_CargosAsignados_D.php?T_LEGA=". $_GET['T_LEGA'] . "&T_APEL=" . $_GET['T_APEL'] . "&T_NOMB=" . $_GET['T_NOMB'] . "'</script>");
} else if (isset($_POST['confirma-modi'])) {
  $actualizacion = actualizaCargo($dbConn);
  echo("<script>location.href = 'ABM_CargosAsignados_D.php?T_LEGA=". $_GET['T_LEGA'] . "&T_APEL=" . $_GET['T_APEL'] . "&T_NOMB=" . $_GET['T_NOMB'] . "'</script>");
}
?>

<script type="text/javascript">
  function siNoBoni() {
    if(document.getElementById('N').checked) {
      document.getElementById('noBoni').style.display = 'block';
    } else {
      document.getElementById('noBoni').style.display = 'none';
    }
  };

  function horasSemanales () {
    $("#N_CARG").change( function () {
      var hora = $(this).find(':selected').data('hora');
      if (hora == 'S') {
        document.getElementById('horasSemanales').style.display = 'block';
      } else {
        document.getElementById('horasSemanales').style.display = 'none';
      }
    }
  )};

  function editSiNoBoni() {
    if(document.getElementById('no').checked) {
      document.getElementById('editNoBoni').style.display = 'block';
    } else {
      document.getElementById('editNoBoni').style.display = 'none';
    }
  };

  function revisarDatos(id, inic, fina, cant, boni) {
      var id = id;
      var inic = inic;
      var fina = fina;
      var cant = cant;
      var boni = boni;

      console.log(inic);

      document.getElementById("id-elegido").value = id;
      document.getElementById("inic-elegido").value = inic;
      document.getElementById("fina-elegido").value = fina;
      document.getElementById("boni-elegido").value = boni;
      document.getElementById("id-elegido-2").value = id;

      if (cant > 0) {
        document.getElementById("edithorassemanales").style.display = 'block';
        document.getElementById("cant-elegido").value = cant;
      };

  };
</script>


<div class="card mb-4">
  <div class="card-header card-header-primary d-flex justify-content-between align-items-center">
    <div>
      <h2 class="card-title"><?php echo $mensaje[1138]; ?></h2>
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
            <th class="tituloGrilla"><?php echo $mensaje[611];?></th>
            <th class="tituloGrilla"><?php echo $mensaje[448];?></th>
            <?php if (isset ($_POST['button-cargosBaja']) || isset($_POST['button-cargosCompleto'])) {?>
            <th class="tituloGrilla"><?php echo $mensaje[449];?></th>
            <?php } ?>
            <th class="tituloGrilla"><?php echo $mensaje[1437];?></th>
          </tr>
        </thead>
        <tbody>
          <?php if (isset ($_POST['button-cargosBaja'])) {
            foreach ($consBajaCargo as $con): ?>
            <tr>
              <td><?php echo $con['T_DESC_LARG']; ?></td>
              <td><?php echo $con['F_INIC_CARG']; ?></td>
              <td><?php echo $con['F_FINI_CARG']; ?></td>
              <td>
                <form action="" method="post">
                  <input type="hidden" name="id" value="<?php echo $con['id']; ?>">

                  <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modi-js" onclick="javascript:revisarDatos(<?php echo $con['id']?> , '<?php echo $con['F_INIC_CARG']?>', '<?php echo $con['F_FINI_CARG']?>', '<?php echo $con['N_CANT_HORA']?>', '<?php echo $con['N_CON_BONI']?>');"><i class="fas fa-edit"></i></button>
                  <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#baja-js" onclick="javascript:revisarDatos(<?php echo $con['id']?>);"><i class="fas fa-trash-alt"></i></button>
                </form>
              </td>
            </tr>
          <?php endforeach;
          } else if (isset ($_POST['button-cargosCompleto'])){
            foreach ($consTodoCargo as $con): ?>
              <tr>
                <td><?php echo $con['T_DESC_LARG']; ?></td>
                <td><?php echo $con['F_INIC_CARG']; ?></td>
                <td><?php echo $con['F_FINI_CARG']; ?></td>
                <td>
                  <form action="" method="post">
                    <input type="hidden" name="id" value="<?php echo $con['id']; ?>">

                    <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modi-js" onclick="javascript:revisarDatos(<?php echo $con['id']?> , '<?php echo $con['F_INIC_CARG']?>', '<?php echo $con['F_FINI_CARG']?>', '<?php echo $con['N_CANT_HORA']?>', '<?php echo $con['N_CON_BONI']?>');"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#baja-js" onclick="javascript:revisarDatos(<?php echo $con['id']?>);"><i class="fas fa-trash-alt"></i></button>
                  </form>
                </td>
              </tr>
            <?php endforeach;
            } else {
              foreach ($consCargoLegajo as $con): ?>
                <tr>
                  <td><?php echo $con['T_DESC_LARG']; ?></td>
                  <td><?php echo $con['F_INIC_CARG']; ?></td>
                  <td>
                    <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modi-js" onclick="javascript:revisarDatos(<?php echo $con['id']?> , '<?php echo $con['F_INIC_CARG']?>', '<?php echo $con['F_FINI_CARG']?>', '<?php echo $con['N_CANT_HORA']?>', '<?php echo $con['N_CON_BONI']?>');"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#baja-js" onclick="javascript:revisarDatos(<?php echo $con['id']?>);"><i class="fas fa-trash-alt"></i></button>
                  </td>
                </tr>
              <?php endforeach; }?>
        </tbody>
      </table>
      <div class="d-inline-flex">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#alta"><i class="fas fa-plus-circle"></i></button>
        <button type="button" class="btn btn-primary"><i class="far fa-file-pdf" value="Print Div Contents" id="btnPrint"></i></button>
        <button type="button" class="btn btn-primary" onclick="ExportToExcel('xlsx')"><i class="far fa-file-excel"></i></button>
        <button type="button" class="btn btn-primary" onclick="ExportHTML()"><i class="far fa-file-word"></i></button>
        <button type="button" class="btn btn-primary" onclick="history.back()"><i class="fas fa-arrow-left"></i></button>
        <div class="dropdown">
          <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa-eye"></i></button>
          <div class="dropdown-menu">
            <form action="" method="post">
              <?php if (isset ($_POST['button-cargosCompleto'])) {?>
                <button class="dropdown-item" type="submit" name="button-usuarios">Ver cargos activos</button>
                <button class="dropdown-item" type="submit" name="button-cargosBaja">Ver cargos dados de baja</button>
              <?php } else if (isset ($_POST['button-cargosBaja'])) {?>
                <button class="dropdown-item" type="submit" name="button-cargos">Ver cargos activos</button>
                <button class="dropdown-item" type="submit" name="button-cargosCompleto">Ver todos los cargos</button>
              <?php } else {?>
                <button class="dropdown-item" type="submit" name="button-cargosCompleto">Ver todos los cargos</button>
                <button class="dropdown-item" type="submit" name="button-cargosBaja">Ver cargos dados de baja</button>
            <?php };?>
            </form>
          </div>
        </div>
      </div>
  </div>
</div>

<!--modal alta-->
<div class="modal fade" id="alta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class='modal-header'>
          <h5 class='modal-title'><?php echo $mensaje[1524]; ?></h5>
          <button type='button' class='close' data-dismiss='modal'>&times;</button>
      </div>
      <div class='modal-body'>
          <form method='post' action=''>
            <input type="hidden" name="T_LEGA" value="<?php echo $_GET['T_LEGA']; ?>">
            <div class='form-group'>
              <label for=""><?php echo $mensaje[611]; ?></label>
              <select class="form-select" name="N_CARG" id="N_CARG" onclick="javascript:horasSemanales();">
              <?php foreach ($consCargo as $con): ?>
                <option value="<?php echo $con['id']?>" data-hora="<?php echo $con['P_HORA']?>"><?php echo $con["T_DESC_LARG"]?></option>
              <?php endforeach; ?>
              </select>
            </div>
            <div class='form-group'>
              <label for="">Condición</label>
              <select class="form-select" name="N_TITU" id="N_TITU">
                <option value="1">Titular</option>
                <option value="2">Suplente</option>
                <option value="3">Provisorio</option>
              </select>
            </div>
            <div class='form-group'>
              <label for=""><?php echo $mensaje[448]; ?></label>
              <input type='date' name='F_INIC_CARG' class='form-control' value=''>
            </div>
            <div class='form-group'>
              <label for=""><?php echo $mensaje[449]; ?></label>
              <input type='date' name='F_FINI_CARG' class='form-control' value=''>
            </div>
            <div id="horasSemanales" style="display:none">
              <div class='form-group'>
                <label for=""><?php echo $mensaje[601]; ?></label>
                <input type='number' name='N_CANT_HORA' class='form-control' value=''>
              </div>
            </div>
            <div class="form-group">
              <label for="">¿Cobra premio?</label>
              <input type="radio" name="N_PREM" value="1" checked id="S">
              <label for="S">Si</label>
              <input type="radio" name="N_PREM" value="0" id="N">
              <label for="N">No</label>
            </div>
            <div class="form-group">
              <label for=""><?php echo $mensaje[1540]; ?></label>
              <input type="radio" name="P_BONI" value="S" checked id="S" onclick="javascript:siNoBoni();">
              <label for="S">Si</label>
              <input type="radio" name="P_BONI" value="N" id="N" onclick="javascript:siNoBoni();">
              <label for="N">No</label>
            </div>
            <div id="noBoni" style="display: none">
              <div class='form-group'>
                <label for=""><?php echo $mensaje[1538]; ?></label>
                <input type='number' name='N_CON_BONI' class='form-control' value=''>
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
        <p id="lega-elegido"></p>
          <form method='post' action=''>
            <input type="hidden" name="id" id="id-elegido" value="">
            <div class='form-group'>
              <label for="">Condición</label>
              <select class="form-select" name="N_TITU" id="titu-elegido">
                <option value="1">Titular</option>
                <option value="2">Suplente</option>
                <option value="3">Provisorio</option>
              </select>
            </div>
            <div class='form-group'>
              <label for=""><?php echo $mensaje[448]; ?></label>
              <input type='date' name='F_INIC_CARG' id="inic-elegido" class='form-control' value=''>
            </div>
            <div class='form-group'>
              <label for=""><?php echo $mensaje[449]; ?></label>
              <input type='date' name='F_FINI_CARG' id="fina-elegido" class='form-control' value=''>
            </div>
              <div id="edithorassemanales" style="display:none">
                <div class='form-group'>
                  <label for=""><?php echo $mensaje[601]; ?></label>
                  <input type='number' name='N_CANT_HORA' class='form-control' id="cant-elegido" value=''>
                </div>
              </div>
              <div class="form-group">
                <label for="">¿Cobra premio?</label>
                <input type="radio" name="N_PREM" value="1" checked id="S">
                <label for="S">Si</label>
                <input type="radio" name="N_PREM" value="0" id="N">
                <label for="N">No</label>
              </div>
              <div class="form-group">
                <label for=""><?php echo $mensaje[1540]; ?></label>
                <input type="radio" name="P_BONI" value="S" checked id="S" onclick="javascript:editSiNoBoni();">
                <label for="S">Si</label>
                <input type="radio" name="P_BONI" value="N" id="no" onclick="javascript:editSiNoBoni();">
                <label for="N">No</label>
              </div>
              <div id="editNoBoni" style="display: none">
                <div class='form-group'>
                  <label for=""><?php echo $mensaje[1538]; ?></label>
                  <input type='number' name='N_CON_BONI' class='form-control' id="boni-elegido" value=''>
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
        <p>¿Está seguro de borrar este cargo?</p>
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
