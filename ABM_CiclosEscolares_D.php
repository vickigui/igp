<?php
include "exportpdf.php";
include "includes/header.php";
include "ABM_CiclosEscolares_C.php";
include "includes/mensajes.php";
include "includes/lateral.php";

$cons = consCiclosEscolares($dbConn);
$msg422 = consTitulos($dbConn, 422);
$msg423 = consTitulos($dbConn, 423);
$msg424 = consTitulos($dbConn, 424);

$T_DESC_LARG = isset($_POST['T_DESC_LARG']) ? $_POST['T_DESC_LARG'] : "";
$T_DESC_CORT = isset($_POST['T_DESC_CORT']) ? $_POST['T_DESC_CORT'] : "";
$N_CICL_ESCO = isset($_POST["N_CICL_ESCO"]) ? $_POST["N_CICL_ESCO"] : "";

if (isset($_POST['button-baja'])) {
  $baja = bajaCiclosEscolares($dbConn, $_POST);
  header("Refresh:0");
} else if (isset($_POST['button-modi'])) {
echo
  "<script>
    $(document).ready(function(){
        $('#myModal').modal('show');
    });
  </script>
  </head>
  <body>
  <div id='myModal' class='modal fade'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <h5 class='modal-title'>Editar Ciclo</h5>
                <button type='button' class='close' data-dismiss='modal'>&times;</button>
            </div>
            <div class='modal-body'>
            <form method='post' action=''>
                <div class='form-group'>
                    <input type='hidden' name='N_CICL_ESCO' class='form-control' value='" . $N_CICL_ESCO . "'>
                </div>
                <div class='form-group'>
                    <input type='text' name='T_DESC_LARG' class='form-control' value='" . $T_DESC_LARG . "'>
                </div>
                <div class='form-group'>
                    <input type='text' name='T_DESC_CORT' class='form-control' value='" . $T_DESC_CORT . "'>
                </div>
                <div class='form-group'>
                    <button type='submit' class='btn btn-primary' name='confirma-modi'>Modificar</button>
                </div>
            </form>
            </div>
        </div>
    </div>
  </div>";
} else if (isset($_POST['button-alta'])) {
  $alta = altaCiclosEscolares($dbConn, $_POST);
  header("Refresh:0");
};


if (isset($_POST['confirma-modi'])) {
  $modi = modiCiclosEscolares($dbConn, $_POST);
  header("Refresh:0");
};


?>




<div style="margin-top: 4rem">

<div class="modal fade" id="alta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class='modal-header'>
          <h5 class='modal-title'>Agregar Ciclo:</h5>
          <button type='button' class='close' data-dismiss='modal'>&times;</button>
      </div>
      <div class='modal-body'>
          <form method='post' action=''>
            <div class='form-group'>
              <label for="">N_CICL_ESCO</label>
              <input type='text' name='N_CICL_ESCO' class='form-control' value='' >
            </div>
            <div class='form-group'>
              <label for="">T_EMPR</label>
              <input type='text' name='T_EMPR' class='form-control' value='IGP' disabled>
              <input type='hidden' name='T_EMPR' class='form-control' value='IGP'>
            </div>
            <div class='form-group'>
              <label for="">T_SUCU</label>
              <input type='text' name='T_SUCU' class='form-control' value='0001' disabled>
              <input type='hidden' name='T_SUCU' class='form-control' value='0001'>
            </div>
            <div class='form-group'>
              <label for="">Descripción Larga</label>
              <input type='text' name='T_DESC_LARG' class='form-control' value=''>
            </div>
            <div class='form-group'>
              <label for="">Descripción Corta</label>
              <input type='text' name='T_DESC_CORT' class='form-control' value=''>
            </div>
            <div class='form-group'>
                <button type='submit' class='btn btn-primary' name='button-alta'>Agregar</button>
            </div>
          </form>
      </div>
    </div>
  </div>
</div>

  <div class="card mb-4">
    <div class="card-header card-header-primary">
      <h2 class="card-title">Ciclos Escolares</h2>
    </div>
    <div class="card-body">
      <div id="dvContainer">
        <table id="datatablesSimple">
          <thead>
            <tr>
              <th class="tituloGrilla"><?php echo $msg422["T_DESC"];?></th>
              <th class="tituloGrilla"><?php echo $msg423["T_DESC"];?></th>
              <th class="tituloGrilla"><?php echo $msg424["T_DESC"];?></th>
              <th class="tituloGrilla">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($cons as $con): ?>
              <tr>
                <td><?php echo $con['N_CICL_ESCO']; ?></td>
                <td><?php echo $con['T_DESC_LARG']; ?></td>
                <td><?php echo $con['T_DESC_CORT']; ?></td>
                <td>
                  <form action="" method="post">
                    <input type="hidden" name="N_CICL_ESCO" value="<?php echo $con['N_CICL_ESCO']; ?>">
                    <input type="hidden" name="T_DESC_LARG" value="<?php echo $con['T_DESC_LARG']; ?>">
                    <input type="hidden" name="T_DESC_CORT" value="<?php echo $con['T_DESC_CORT']; ?>">

                    <button class="btn btn-primary" type="submit" name="button-baja"><i class="fas fa-trash-alt"></i></button>
                    <button class="btn btn-primary" type="submit" name="button-modi" data-toggle="modal" data-target="#edit"><i class="fas fa-edit"></i></button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#alta"><i class="fas fa-plus-circle"></i></button>
      <button type="button" class="btn btn-primary"><i class="far fa-file-pdf" value="Print Div Contents" id="btnPrint"></i></i></button>
    </div>
  </div>
</div>
</div>

<?php include "includes/footer.php" ?>
