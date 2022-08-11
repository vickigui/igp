<?php
include "exportpdf.php";
include "includes/lateral.php";
$pageTitle = $mensaje[929];
include "includes/header.php";
include "ABM_MaestroPersonal_C.php";
include "includes/funciones.php";

$cons = consMaestroPersonal($dbConn);
$consBaja = consMaestroPersonalBaja($dbConn);
$consTodo = consMaestroPersonalTodo($dbConn);
$consPaises = consPaises($dbConn);
$consProvincias = consProvincias($dbConn);
$consLocalidades = consLocalidades($dbConn);
$consObrasSociales = consObrasSociales($dbConn);


$T_LEGA = isset($_POST['T_LEGA']) ? $_POST['T_LEGA'] : "";
$id = isset($_POST['id']) ? $_POST['id'] : "";

if (isset($_POST['button-alta'])) {
  $alta = generarLegajo($dbConn, $_POST, $_SESSION);
  echo("<script>location.href = 'ABM_MaestroPersonal_D.php'</script>");
} else if (isset($_POST['confirma-modi'])) {
  $modi = modiMaestroPersonal($dbConn);
  echo("<script>location.href = 'ABM_MaestroPersonal_D.php'</script>");
} else if (isset($_POST['confirma-baja'])) {
  $modi = bajaMaestroPersonal($dbConn, $_POST);
  echo("<script>location.href = 'ABM_MaestroPersonal_D.php'</script>");
};
?>

<script type="text/javascript">
function revisarDatos(id, lega, apel, nomb, call, piso, depa, nacf, tele, mail, foto, codi, alta, baja, cuil, loca, prov, pais, locanaci, provnaci, paisnaci) {

    var id = id;
    var lega = lega;
    var apel = apel;
    var nomb = nomb;
    var call = call;
    var piso = piso;
    var depa = depa;
    var nacf = nacf;
    var tele = tele;
    var mail = mail;
    var foto = foto;
    var codi = codi;
    var alta = alta;
    var baja = baja;
    var cuil = cuil;
    var cuilSepa = cuil.split("-");
    var loca = loca;
    var prov = prov;
    var pais = pais;
    var locanaci = locanaci;
    var provnaci = provnaci;
    var paisnaci = paisnaci;

    document.getElementById("id-elegido").value = id;
    document.getElementById("lega-elegido").innerHTML = lega;
    document.getElementById("apel-elegido").value = apel;
    document.getElementById("nomb-elegido").value = nomb;
    document.getElementById("call-elegido").value = call;
    document.getElementById("piso-elegido").value = piso;
    document.getElementById("depa-elegido").value = depa;
    document.getElementById("nacf-elegido").value = nacf;
    document.getElementById("tele-elegido").value = tele;
    document.getElementById("mail-elegido").value = mail;
    document.getElementById("foto-elegido").src = 'images/' + foto;
    document.getElementById("codi-elegido").value = codi;
    document.getElementById("altf-elegido").value = alta;
    document.getElementById("bajf-elegido").value = baja;
    document.getElementById("cuitPre-elegido").value = cuilSepa[0];
    document.getElementById("cuit-elegido").value = cuilSepa[1];
    document.getElementById("cuitPost-elegido").value = cuilSepa[2];
    document.getElementById("loca-elegido").value = loca;
    document.getElementById("prov-elegido").value = prov;
    document.getElementById("pais-elegido").value = pais;
    document.getElementById("locanaci-elegido").value = locanaci;
    document.getElementById("provnaci-elegido").value = provnaci;
    document.getElementById("paisnaci-elegido").value = paisnaci;
};

function revisarDatosDos(id, lega) {
    var id = id;
    var lega = lega;

    document.getElementById("id-elegido-2").value = id;
    document.getElementById("lega-elegido-2").value = lega;
};

function autocompleteData() {
  var pais = document.getElementById("Country").value;
  var prov = document.getElementById("State").value;
  var loca = document.getElementById("City").value;

  document.getElementById("N_PAIS_NACI").value = pais;
  document.getElementById("N_PROV_NACI").value = prov;
  document.getElementById("N_LOCA_NACI").value = loca;
};

$(document).on("keydown", ":input:not(textarea)", function(event) {
    return event.key != "Enter";
});
</script>

<div>


  <!-- Consulta usuarios dados de baja -->
  <div class="card mb-4">
    <div class="card-header card-header-primary">
      <h2 class="card-title"><?php echo $mensaje[929]; ?></h2>
    </div>
    <div class="card-body">
      <div id="dvContainer">
        <table id="datatablesSimple">
          <thead>
            <tr>
              <th class="tituloGrilla"><?php echo $mensaje[606];?></th>
              <th class="tituloGrilla"><?php echo $mensaje[312];?></th>
              <th class="tituloGrilla"><?php echo $mensaje[53];?></th>
              <th class="tituloGrilla"><?php echo $mensaje[1437];?></th>
            </tr>
          </thead>
          <tbody>
            <?php if (isset ($_POST['button-usuariosBaja'])) {
              foreach ($consBaja as $con): ?>
              <tr>
                <td><?php echo $con['T_LEGA']; ?></td>
                <td><?php echo $con['T_APEL']; ?></td>
                <td><?php echo $con['T_NOMB']; ?></td>
                <td class="col-2">
                  <form action="" method="post">
                    <input type="hidden" name="id" value="<?php echo $con['id']; ?>">
                    <input type="hidden" name="T_LEGA" value="<?php echo $con['T_LEGA']; ?>">

                    <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modi-js" onclick="javascript:revisarDatos('<?php echo $con['id']?>', '<?php echo $con['T_LEGA']?>', '<?php echo $con['T_APEL']?>', '<?php echo $con['T_NOMB']?>', '<?php echo $con['T_CALL']?>', '<?php echo $con['T_PISO']?>', '<?php echo $con['T_DEPA']?>', '<?php echo $con['F_NACI']?>', '<?php echo $con['T_TELE']?>', '<?php echo $con['T_MAIL']?>', '<?php echo $con['T_FOTO']?>', '<?php echo $con['T_CODI_POST']?>', '<?php echo $con['F_ALTA']?>', '<?php echo $con['F_BAJA']?>', '<?php echo $con['N_CUIL']?>', '<?php echo $con['N_LOCA']?>', '<?php echo $con['N_PROV']?>', '<?php echo $con['N_PAIS']?>', '<?php echo $con['N_LOCA_NACI']?>', '<?php echo $con['N_PROV_NACI']?>', '<?php echo $con['N_PAIS_NACI']?>');"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#baja-js" onclick="javascript:revisarDatosDos('<?php echo $con['id']?>', '<?php echo $con['T_LEGA']?>');"><i class="fas fa-trash-alt"></i></button>
                  </form>
                </td>
              </tr>
            <?php endforeach;
            } else if (isset ($_POST['button-usuariosCompleto'])){
              foreach ($consTodo as $con): ?>
              <tr>
                <td><?php echo $con['T_LEGA']; ?></td>
                <td><?php echo $con['T_APEL']; ?></td>
                <td><?php echo $con['T_NOMB']; ?></td>
                <td class="col-2">
                  <form action="" method="post">
                    <input type="hidden" name="id" value="<?php echo $con['id']; ?>">
                    <input type="hidden" name="T_LEGA" value="<?php echo $con['T_LEGA']; ?>">

                    <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modi-js" onclick="javascript:revisarDatos('<?php echo $con['id']?>', '<?php echo $con['T_LEGA']?>', '<?php echo $con['T_APEL']?>', '<?php echo $con['T_NOMB']?>', '<?php echo $con['T_CALL']?>', '<?php echo $con['T_PISO']?>', '<?php echo $con['T_DEPA']?>', '<?php echo $con['F_NACI']?>', '<?php echo $con['T_TELE']?>', '<?php echo $con['T_MAIL']?>', '<?php echo $con['T_FOTO']?>', '<?php echo $con['T_CODI_POST']?>', '<?php echo $con['F_ALTA']?>', '<?php echo $con['F_BAJA']?>', '<?php echo $con['N_CUIL']?>', '<?php echo $con['N_LOCA']?>', '<?php echo $con['N_PROV']?>', '<?php echo $con['N_PAIS']?>', '<?php echo $con['N_LOCA_NACI']?>', '<?php echo $con['N_PROV_NACI']?>', '<?php echo $con['N_PAIS_NACI']?>');"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#baja-js" onclick="javascript:revisarDatosDos('<?php echo $con['id']?>', '<?php echo $con['T_LEGA']?>');"><i class="fas fa-trash-alt"></i></button>
                  </form>
                </td>
              </tr>
            <?php endforeach;
            } else {
              foreach ($cons as $con): ?>
              <tr>
                <td><?php echo $con['T_LEGA']; ?></td>
                <td><?php echo $con['T_APEL']; ?></td>
                <td><?php echo $con['T_NOMB']; ?></td>
                <td class="col-2">
                  <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modi-js" onclick="javascript:revisarDatos('<?php echo $con['id']?>', '<?php echo $con['T_LEGA']?>', '<?php echo $con['T_APEL']?>', '<?php echo $con['T_NOMB']?>', '<?php echo $con['T_CALL']?>', '<?php echo $con['T_PISO']?>', '<?php echo $con['T_DEPA']?>', '<?php echo $con['F_NACI']?>', '<?php echo $con['T_TELE']?>', '<?php echo $con['T_MAIL']?>', '<?php echo $con['T_FOTO']?>', '<?php echo $con['T_CODI_POST']?>', '<?php echo $con['F_ALTA']?>', '<?php echo $con['F_BAJA']?>', '<?php echo $con['N_CUIL']?>', '<?php echo $con['N_LOCA']?>', '<?php echo $con['N_PROV']?>', '<?php echo $con['N_PAIS']?>', '<?php echo $con['N_LOCA_NACI']?>', '<?php echo $con['N_PROV_NACI']?>', '<?php echo $con['N_PAIS_NACI']?>');"><i class="fas fa-edit"></i></button>
                  <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#baja-js" onclick="javascript:revisarDatosDos('<?php echo $con['id']?>', '<?php echo $con['T_LEGA']?>');"><i class="fas fa-trash-alt"></i></button>
                </td>
              </tr>
            <?php endforeach; }?>
          </tbody>
        </table>
      </div>
      <div class="d-inline-flex">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal1"><i class="fas fa-plus-circle"></i></button>
        <button type="button" class="btn btn-primary"><i class="far fa-file-pdf" value="Print Div Contents" id="btnPrint"></i></button>
        <div class="dropdown">
          <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa-eye"></i></button>
          <div class="dropdown-menu">
            <form action="" method="post">
              <?php if (isset ($_POST['button-usuariosCompleto'])) {?>
                <button class="dropdown-item" type="submit" name="button-usuarios">Ver usuarios activos</button>
                <button class="dropdown-item" type="submit" name="button-usuariosBaja">Ver usuarios dados de baja</button>
              <?php } else if (isset ($_POST['button-usuariosBaja'])) {?>
                <button class="dropdown-item" type="submit" name="button-usuarios">Ver usuarios activos</button>
                <button class="dropdown-item" type="submit" name="button-usuariosCompleto">Ver todos los usuarios</button>
              <?php } else {?>
                <button class="dropdown-item" type="submit" name="button-usuariosCompleto">Ver todos los usuarios</button>
                <button class="dropdown-item" type="submit" name="button-usuariosBaja">Ver usuarios dados de baja</button>
            <?php };?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>



  <!-- modal alta-->
  <div class="modal fade" style="margin-top:3rem" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
     <div class="modal-dialog">
        <div class="modal-content modalAltaUser">
           <div class="modal-header">
               <h5 class="modal-title" id="myModalLabel"><?php echo $mensaje[17]; ?></h5>
                <button type="button" class="close btn-close-form" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
             </div>
             <form role="form" action="" method="post" class="registration-form" enctype="multipart/form-data" onkeydown="return event.key != 'Enter';">
               <div class="modal-body">
                   <fieldset style="display: block;">
                      <div class="form-top">
                         <div class="form-top-left">
                            <h3>Paso 1 / 4</h3>
                         </div>
                      </div>
                      <div class="form-bottom">
                        <div class='form-group'>
                          <label for=""><?php echo $mensaje[312]; ?></label>
                          <input type='text' name='T_APEL' class='form-control' value=''>
                        </div>
                        <div class='form-group'>
                          <label for=""><?php echo $mensaje[53]; ?></label>
                          <input type='text' name='T_NOMB' class='form-control' value=''>
                        </div>
                        <div class='form-group'>
                          <label for=""><?php echo $mensaje[77]; ?></label><br>
                          <div class="row">
                            <div class="col-2">
                              <input type='text' name='N_CUIT_PRE' class='form-control' value=''>
                            </div>
                            <div class="col-6">
                              <input type='text' name='N_CUIT' class='form-control' value=''>
                            </div>
                            <div class="col-2">
                              <input type='text' name='N_CUIT_POST' class='form-control' value=''>
                            </div>
                          </div>
                        </div>
                        <label for="">Foto</label><br>
                        <input name="archivo" id="archivo" type="file"/>
                      </div>
                   </fieldset>
             </div>
             <div class="modal-footer">
                <button type="button" data-dismiss="modal" data-toggle="modal" data-target="#myModal2" class="btn btn-default btn-next"><?php echo $mensaje[1506]; ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $mensaje[154]; ?></button>
             </div>
          </div>
       </div>
    </div>

    <div class="modal fade modal-map"  style="margin-top:3rem" id="myModal2" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
     <div class="modal-dialog">
        <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="myModalLabel"><?php echo $mensaje[17]; ?></h5>
                <button type="button" class="close btn-close-form" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
             </div>
             <div class="modal-body">
                <div class="form-top">
                   <div class="form-top-left">
                      <h3>Paso 2 / 4</h3>
                   </div>
                </div>
                <fieldset class="address">
                  <div class="form-group">
                    <div class="row">
                      <label class="control-label col-12">
                        Dirección
                      </label>
                      <div class="col-12">
                        <input class="form-control places-autocomplete" type="text" id="Street" name="Street" placeholder="" value="" autocomplete="address-line1">
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="row">
                      <label class="control-label col-4">
                        Piso
                      </label>
                      <label class="control-label col-4">
                        Departamento
                      </label>
                      <label class="control-label col-4">
                        Código Postal
                      </label>
                    </div>
                    <div class="row">
                      <div class="col-4">
                        <input class="form-control" type="text" id="Street2" name="T_PISO" value="" autocomplete="address-line2">
                      </div>
                      <div class="col-4">
                        <input class="form-control" type="text" id="Street2" name="T_DEPA" value="" autocomplete="address-line2">
                      </div>
                      <div class="col-4">
                        <input class="form-control places-autocomplete" type="text" id="PostalCode" name="PostalCode" placeholder="" value="" autocomplete="postal-code">
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="row">
                      <label class="control-label col-12">
                        Ciudad
                      </label>
                      <div class="col-12">
                        <input class="form-control" type="text" id="City" name="City" value="" autocomplete="address-level2">
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="row">
                      <label class="control-label col-12">
                        Provincia
                      </label>
                      <div class="col-12">
                        <input class="form-control" type="text" id="State" name="State" value="" autocomplete="address-level1">
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="row">
                      <label class="control-label col-12">
                        País
                      </label>
                      <div class="col-12">
                        <input class="form-control" type="text" id="Country" name="Country" value="" autocomplete="country">
                      </div>
                    </div>
                  </div>
              </fieldset>
             </div>
             <div class="modal-footer">
               <button type="button" data-dismiss="modal" data-toggle="modal" data-target="#myModal1" class="btn btn-default btn-prev"><?php echo $mensaje[1520]; ?></button>
               <button type="button" data-dismiss="modal" data-toggle="modal" data-target="#myModal3" class="btn btn-default btn-next" onclick="javascript:autocompleteData()"><?php echo $mensaje[1506]; ?></button>
               <button type="button" class="btn btn-default btn-close-form"><?php echo $mensaje[154]; ?></button>
             </div>
          </div>
       </div>
    </div>

    <div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
       <div class="modal-dialog">
          <div class="modal-content">
             <div class="modal-header">
               <h5 class="modal-title" id="myModalLabel"><?php echo $mensaje[17]; ?></h5>
                <button type="button" class="close btn-close-form" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
             </div>
             <div class="modal-body">
                <fieldset>
                      <div class="form-top">
                         <div class="form-top-left">
                            <h3>Paso 3 / 4</h3>
                         </div>
                      </div>
                      <div class="form-bottom">
                        <div class='form-group'>
                          <label for=""><?php echo $mensaje[417]; ?></label>
                          <input type="text"  class='form-control' name="N_PAIS_NACI" id="N_PAIS_NACI" value="">
                        </div>
                        <div class="form-group">
                          <div class="row">
                            <label class="control-label col-12">
                              Provincia
                            </label>
                            <div class="col-12">
                              <input type="text"  class='form-control' name="N_PROV_NACI" id="N_PROV_NACI" value="">
                            </div>
                          </div>
                        </div>
                        <div class='form-group'>
                          <label for=""><?php echo $mensaje[415]; ?></label>
                          <input type="text"  class='form-control' name="N_LOCA_NACI" id="N_LOCA_NACI" value="">
                        </div>
                        <div class='form-group'>
                          <label for=""><?php echo $mensaje[418]; ?></label>
                          <input type='date' name='F_NACI' class='form-control' value=''>
                        </div>
                      </div>
                   </fieldset>
             </div>
             <div class="modal-footer">
               <button type="button" data-dismiss="modal" data-toggle="modal" data-target="#myModal2" class="btn btn-default btn-prev"><?php echo $mensaje[1520]; ?></button>
               <button type="button" data-dismiss="modal" data-toggle="modal" data-target="#myModal4" class="btn btn-default btn-next"><?php echo $mensaje[1506]; ?></button>
               <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $mensaje[154]; ?></button>
             </div>
          </div>
       </div>
    </div>

    <div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
       <div class="modal-dialog">
          <div class="modal-content">
             <div class="modal-header">
               <h5 class="modal-title" id="myModalLabel"><?php echo $mensaje[17]; ?></h5>
                <button type="button" class="close btn-close-form" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
             </div>
             <div class="modal-body">
                <fieldset>
                      <div class="form-top">
                         <div class="form-top-left">
                            <h3>Paso 4 / 4</h3>
                         </div>
                      </div>
                      <div class="form-bottom">
                        <div class="row">
                          <div class='form-group col-6'>
                            <label for=""><?php echo $mensaje[419]; ?></label>
                            <select class="form-select" name="P_ESTA_CIVI" id="P_ESTA_CIVI">
                              <option value="<?php echo "S"; ?>"><?php echo $mensaje[430]; ?></option>
                              <option value="<?php echo "C"; ?>"><?php echo $mensaje[429]; ?></option>
                              <option value="<?php echo "D"; ?>"><?php echo $mensaje[431]; ?></option>
                              <option value="<?php echo "V"; ?>"><?php echo $mensaje[432]; ?></option>
                            </select>
                          </div>
                          <div class='form-group col-6'>
                            <label for=""><?php echo $mensaje[78]; ?></label>
                            <input type='tel' name='T_TELE' class='form-control' value=''>
                          </div>
                        </div>
                        <div class='form-group'>
                          <label for=""><?php echo $mensaje[80]; ?></label>
                          <input type='email' name='T_MAIL' class='form-control' value=''>
                        </div>
                        <div class='form-group'>
                          <label for=""><?php echo $mensaje[1546]; ?></label>
                          <select class="form-select" name="N_OBRA_SOCI" id="N_OBRA_SOCI">
                          <?php foreach ($consObrasSociales as $con): ?>
                            <option value="<?php echo $con["id"] ?>"><?php echo $con["T_DESC_LARG"] ?></option>
                          <?php endforeach; ?>
                          </select>
                        </div>
                        <div class='form-group'>
                          <label for=""><?php echo $mensaje[427]; ?></label>
                          <input type='date' name='F_ALTA' class='form-control' value=''>
                        </div>
                        <div class='form-group'>
                          <label for=""><?php echo $mensaje[428]; ?></label>
                          <input type='date' name='F_BAJA' class='form-control' value=''>
                        </div>
                        <!-- <div class='form-group'>
                          <label for=""><?php echo $mensaje[1048]; ?></label>
                          <input type='date' name='F_INIC_ACTI' class='form-control' value=''>
                        </div> -->
                   </fieldset>

             </div>
             <div class="modal-footer">
               <button type="button" data-dismiss="modal" data-toggle="modal" data-target="#myModal3" class="btn btn-default btn-prev"><?php echo $mensaje[1520]; ?></button>
               <button type='submit' class='btn btn-primary' name='button-alta'><?php echo $mensaje[57]; ?></button>
               <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $mensaje[154]; ?></button>
             </div>
             </form>
          </div>
       </div>
    </div>
    </div>

  <!--modal edición-->
  <div class="modal fade" id="modi-js" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog">
        <div class="modal-content">
           <div class="modal-header">
             <h5 class="modal-title">Legajo: <span id="lega-elegido"></span> </h5>
              <button type="button" class="close btn-close-form" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
           </div>
           <form role="form" action="" method="post" class="registration-form" enctype="multipart/form-data" onkeydown="return event.key != 'Enter';">
             <input type="hidden" name="id" id="id-elegido" value="">
             <div class="modal-body">
                 <fieldset style="display: block;">
                    <div class="form-bottom">
                      <div class='form-group'>
                        <label for=""><?php echo $mensaje[312]; ?></label>
                        <input type='text' name='T_APEL' class='form-control' id="apel-elegido" value=''>
                      </div>
                      <div class='form-group'>
                        <label for=""><?php echo $mensaje[53]; ?></label>
                        <input type='text' name='T_NOMB' class='form-control' id="nomb-elegido" value=''>
                      </div>
                      <div class='form-group'>
                        <label for=""><?php echo $mensaje[77]; ?></label><br>
                        <div class="row">
                          <div class="col-2">
                            <input type='text' name='N_CUIT_PRE' class='form-control' id="cuitPre-elegido" value=''>
                          </div>
                          <div class="col-6">
                            <input type='text' name='N_CUIT' class='form-control' id="cuit-elegido" value=''>
                          </div>
                          <div class="col-2">
                            <input type='text' name='N_CUIT_POST' class='form-control' id="cuitPost-elegido" value=''>
                          </div>
                        </div>
                      </div>
                      <div class="form-group-img">
                        <img id="foto-elegido" src="" alt="">
                      </div>
                      <label for="">Foto</label><br>
                      <input name="archivo" id="archivo" type="file"/>
                      <div class='form-group'>
                        <label for=""><?php echo $mensaje[411]; ?></label>
                        <input type='text' name='T_CALL' class='form-control' id="call-elegido" value=''>
                      </div>
                      <div class="row">
                        <div class='form-group col-3'>
                          <label for=""><?php echo $mensaje[412]; ?></label>
                          <input type='text' name='N_NUME' class='form-control' id="nume-elegido" value=''>
                        </div>
                        <div class='form-group col-3'>
                          <label for=""><?php echo $mensaje[413]; ?></label>
                          <input type='text' name='T_PISO' class='form-control' id="piso-elegido" value=''>
                        </div>
                        <div class='form-group col-3'>
                          <label for=""><?php echo $mensaje[1504]; ?></label>
                          <input type='text' name='T_DEPA' class='form-control' id="depa-elegido" value=''>
                        </div>
                        <div class='form-group col-3'>
                          <label for=""><?php echo $mensaje[76]; ?></label>
                          <input type='text' name='T_CODI_POST' class='form-control' id="codi-elegido" value=''>
                        </div>
                      </div>
                      <div class='form-group'>
                        <label for=""><?php echo $mensaje[75]; ?></label>
                        <input type="text" class="form-control" name="N_PAIS" id="pais-elegido" value="">
                        <!-- <select class="form-select" name="N_PAIS" id="N_PAIS">
                        <?php foreach ($consPaises as $con): ?>
                          <option value="<?php echo $con["N_PAIS"] ?>"><?php echo $con["T_DESC"] ?></option>
                        <?php endforeach; ?>
                        </select> -->
                      </div>
                      <div class='form-group'>
                        <label for=""><?php echo $mensaje[74]; ?></label>
                        <input type="text" class="form-control" name="N_PROV" id="prov-elegido" value="">
                        <!-- <select class="form-select" name="N_PROV" id="N_PROV">
                        <?php foreach ($consProvincias as $con): ?>
                          <option value="<?php echo $con["N_PROV"] ?>"><?php echo $con["T_DESC"] ?></option>
                        <?php endforeach; ?>
                        </select> -->
                      </div>
                      <div class='form-group'>
                        <label for=""><?php echo $mensaje[73]; ?></label>
                        <input type="text" class="form-control" name="N_LOCA" id="loca-elegido" value="">
                        <!-- <select class="form-select" name="N_LOCA" id="N_LOCA">
                        <?php foreach ($consLocalidades as $con): ?>
                          <option value="<?php echo $con["N_LOCA"] ?>"><?php echo $con["T_DESC"] ?></option>
                        <?php endforeach; ?>
                        </select> -->
                      </div>
                      <div class='form-group'>
                        <label for=""><?php echo $mensaje[417]; ?></label>
                        <input type="text" class="form-control" name="N_PAIS_NACI" id="paisnaci-elegido" value="">
                        <!-- <select class="form-select" name="N_PAIS_NACI" id="N_PAIS_NACI">
                        <?php foreach ($consPaises as $con): ?>
                          <option value="<?php echo $con["N_PAIS"] ?>"><?php echo $con["T_DESC"] ?></option>
                        <?php endforeach; ?>
                        </select> -->
                      </div>
                      <div class='form-group'>
                        <label for=""><?php echo $mensaje[416]; ?></label>
                        <input type="text" class="form-control" name="N_PROV_NACI" id="provnaci-elegido" value="">
                        <!-- <select class="form-select" name="N_PROV_NACI" id="N_PROV_NACI">
                        <?php foreach ($consProvincias as $con): ?>
                          <option value="<?php echo $con["N_PROV"] ?>"><?php echo $con["T_DESC"] ?></option>
                        <?php endforeach; ?>
                        </select> -->
                      </div>
                      <div class='form-group'>
                        <label for=""><?php echo $mensaje[415]; ?></label>
                        <input type="text" class="form-control" name="N_LOCA_NACI" id="locanaci-elegido" value="">
                        <!-- <select class="form-select" name="N_LOCA_NACI" id="N_LOCA_NACI">
                        <?php foreach ($consLocalidades as $con): ?>
                          <option value="<?php echo $con["N_LOCA"] ?>"><?php echo $con["T_DESC"] ?></option>
                        <?php endforeach; ?>
                        </select> -->
                      </div>
                      <div class='form-group'>
                        <label for=""><?php echo $mensaje[418]; ?></label>
                        <input type='date' name='F_NACI' class='form-control' id="nacf-elegido" value=''>
                      </div>
                      <div class="row">
                        <div class='form-group col-6'>
                          <label for=""><?php echo $mensaje[419]; ?></label>
                          <select class="form-select" name="P_ESTA_CIVI" id="P_ESTA_CIVI">
                            <option value="<?php echo "S"; ?>"><?php echo $mensaje[430]; ?></option>
                            <option value="<?php echo "C"; ?>"><?php echo $mensaje[429]; ?></option>
                            <option value="<?php echo "D"; ?>"><?php echo $mensaje[431]; ?></option>
                            <option value="<?php echo "V"; ?>"><?php echo $mensaje[432]; ?></option>
                          </select>
                        </div>
                        <div class='form-group col-6'>
                          <label for=""><?php echo $mensaje[78]; ?></label>
                          <input type='tel' name='T_TELE' class='form-control' id="tele-elegido" value=''>
                        </div>
                      </div>
                      <div class='form-group'>
                        <label for=""><?php echo $mensaje[80]; ?></label>
                        <input type='email' name='T_MAIL' class='form-control' id="mail-elegido" value=''>
                      </div>
                      <div class='form-group'>
                        <label for=""><?php echo $mensaje[427]; ?></label>
                        <input type='date' name='F_ALTA' class='form-control' id="altf-elegido" value=''>
                      </div>
                      <div class='form-group'>
                        <label for=""><?php echo $mensaje[428]; ?></label>
                        <input type='date' name='F_NACI' class='form-control' id="bajf-elegido" value=''>
                      </div>
                 </fieldset>
           </div>
           <div class="modal-footer">
             <button type='submit' class='btn btn-primary' name='confirma-modi'>Guardar cambios</button>
             <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
           </div>
           </form>
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
          <p>¿Está seguro de borrar el maestro de personal seleccionado?</p>
            <form method='post' action=''>
              <input type="hidden" name="T_LEGA" class='form-control' id="lega-elegido-2" value="">
              <input type="hidden" name="id" class='form-control' id="id-elegido-2" value="">
              <!-- <div class="form-group">
                <input type="date" name="F_BAJA" value="">
              </div> -->
              <div class='form-group'>
                  <button type='submit' class='btn btn-primary' name='confirma-baja'>Sí</button>
                  <button type="button" class='btn btn-default btn-close-form' data-dismiss='modal' name="button">Cancelar</button>
              </div>
            </form>
        </div>
      </div>
    </div>
  </div>


<?php include "includes/footer.php" ?>
