<?php
session_start();
include "login_C.php";
include "includes/mensajes.php";
$pageTitle = $mensaje[118];
include "includes/header.php";
include "ABM_CargosAsignados_C.php";

if($_POST) {
  login($dbConn, $_POST);
  // idioma($dbConn, $_POST);
  if (isset($_SESSION["T_CODI_USUA"])) {
    echo("<script>location.href = 'gestionar.php'</script>");
  }  else {
    $error = "<div class='alert d-flex justify-content-center' style='background-color: #9c27b0; color: white' role='alert'>" . $mensaje[255] . "</div>";
  }
}

include "includes/header.php";

// $consIdiomas = consIdiomas($dbConn);
?>

<script type="text/javascript">
function validateempr(){
  var empr = document.getElementById("T_EMPR").value;
  // var usua = document.getElementById("T_CODI_USUA").value;

if (empr==null || empr=="") {
      // alert("La empresa no puede quedar en blanco");
      document.getElementById("error-empr").style.display = "block";
      document.getElementById("T_EMPR").style.border = "1px solid red";
      return false;
  } else {
    document.getElementById("error-empr").style.display = "none";
    document.getElementById("T_EMPR").style.border = "none";
    return false;
  }
}

function validatesucu(){
  var sucu = document.getElementById("T_SUCU").value;
  // var usua = document.getElementById("T_CODI_USUA").value;

  if (sucu==null || sucu=="") {
      document.getElementById("error-sucu").style.display = "block";
      document.getElementById("T_SUCU").style.border = "1px solid red";
      return false;
  } else {
    document.getElementById("error-sucu").style.display = "none";
    document.getElementById("T_SUCU").style.border = "none";
    return false;
  }
}

function validateusua(){
  var usua = document.getElementById("T_CODI_USUA").value;

  if (usua==null || usua=="") {
      document.getElementById("error-usua").style.display = "block";
      document.getElementById("T_CODI_USUA").style.border = "1px solid red";
      return false;
  } else {
    document.getElementById("error-usua").style.display = "none";
    document.getElementById("T_CODI_USUA").style.border = "none";
    return false;
  }
}

function validatepass(){
  var pass = document.getElementById("T_PASS").value;
  // var usua = document.getElementById("T_CODI_USUA").value;

  if (pass==null || pass=="") {
      document.getElementById("error-pass").style.display = "block";
      document.getElementById("T_PASS").style.border = "1px solid red";
      return false;
  } else {
    document.getElementById("error-pass").style.display = "none";
    document.getElementById("T_PASS").style.border = "none";
    return false;
  }
}
</script>

    <body class="bg-primary" id="back-login">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4"><?php echo $mensaje[118]; ?></h3></div>
                                    <div class="card-body">
                                        <form action="" method="post" name="myform">
                                            <div class="form-floating mb-3">
                                                <input name="T_EMPR" class="form-control" id="T_EMPR" type="text" placeholder="<?php echo $mensaje[69];?>" onblur="return validateempr()"/>
                                                <label for="T_EMPR"><?php echo $mensaje[69];?></label>
                                                <p id="error-empr" style="display:none; color:red">La empresa no puede quedar en blanco</p>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input name="T_SUCU" class="form-control" id="T_SUCU" type="text" placeholder="<?php echo $mensaje["70"];?>" onblur="return validatesucu()"/>
                                                <label for="T_SUCU"><?php echo $mensaje["70"];?></label>
                                                <p id="error-sucu" style="display:none; color:red">La sucursal no puede quedar en blanco</p>
                                            </div>
                                            <!-- <div class="form-floating mb-3">
                                                <select class="form-select" name="N_IDIO" id="N_IDIO">
                                                <?php foreach ($consIdiomas as $consIdioma): ?>
                                                  <option value="<?php echo $consIdioma["N_IDIO"] ?>"><?php echo $consIdioma["T_DESC"] ?></option>
                                                <?php endforeach; ?>
                                                </select>
                                                <label for="N_IDIO"><?php echo $mensaje[123];?></label>
                                            </div> -->
                                            <div class="form-floating mb-3">
                                                <input name="T_CODI_USUA" class="form-control" id="T_CODI_USUA" type="text" placeholder="<?php echo $mensaje[42];?>" / onblur="return validateusua()">
                                                <label for="T_CODI_USUA"><?php echo $mensaje[42];?></label>
                                                <p id="error-usua" style="display:none; color:red">El usuario no puede quedar en blanco</p>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input name="T_PASS" class="form-control" id="T_PASS" type="password" placeholder="<?php echo $mensaje[319];?>" onblur="return validatepass()"/>
                                                <label for="T_PASS"><?php echo $mensaje[319];?></label>
                                                <p id="error-pass" style="display:none; color:red">La contraseña no puede quedar en blanco</p>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <a class="small" href="password.php">¿Olvidaste tu contraseña?</a>
                                                <!-- <a class="btn btn-primary" href="index.html">Login</a> -->
                                                <button class="btn btn-primary" type="submit" name="button">Iniciar sesión</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center py-3">
                                        <div class="small"><a href="index.php">Crear una cuenta</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>


<?php include "includes/footer.php" ?>
