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

    <body class="bg-primary" id="back-login">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
              <main>
                  <div class="container">
                      <div class="row justify-content-center">
                          <div class="col-lg-5">
                              <div class="card shadow-lg border-0 rounded-lg mt-5">
                                  <div class="card-header"><h3 class="text-center font-weight-light my-4">Recuperar contraseña</h3></div>
                                  <div class="card-body">
                                      <div class="small mb-3 text-muted">Ingresa tu correo y te enviaremos un link para recuperar la contraseña.</div>
                                      <form>
                                          <div class="form-floating mb-3">
                                              <input class="form-control" id="inputEmail" type="email" placeholder="name@example.com" />
                                              <label for="inputEmail">Correo electrónico</label>
                                          </div>
                                          <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                              <a class="small" href="login.html">Regresar a Inicio de sesión</a>
                                              <a class="btn btn-primary" href="login.html">Recuperar contraseña</a>
                                          </div>
                                      </form>
                                  </div>
                                  <div class="card-footer text-center py-3">
                                      <div class="small"><a href="register.html">¿Necesitas una cuenta? ¡Contáctanos!</a></div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </main>
            </div>


<?php include "includes/footer.php" ?>
