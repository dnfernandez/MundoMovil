<?php
require_once(__DIR__ . "/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$error = $view->popFlash();
$datos = $view->getVariable("datos");
$mensajeRegistro = $view->getVariable("mensajeRegistro2");
$mensajeSucces = $view->getVariable("mensajeSucces2");
$mensajeError = $view->getVariable("mensajeError2");
?>

<!--Formulario login-->
<div class="form-especial">
    <a rel="home" href="noticia/index" title="Mundo Movil">
        <img class="logo2 img-responsive" src="images/letra.png">
    </a>

    <div id="error">
        <?php if (isset($error) && $error != null):
            echo '<script>
                    document.getElementById("error").style.display = "block";
                  </script>';
            echo $error;
        endif; ?>
    </div>
    <div class="panel panel-default" data-lightbox="busqueda">
        <div class="panel-heading">
            <h3 class="panel-title">
                Restablecer contrase&ntilde;a en MundoMovil
            </h3>
        </div>
        <form id="idformEmailCont" method="POST" action="usuario/mail_contrasenha">
            <div class="panel-body">
                <div>
                    Introduce tu email y te enviaremos a tu correo electr&oacute;nico un enlace para que puedas
                    restablecer tu contrase&ntilde;a
                </div>
                <div id="div-emailCont" class="form-group">
                    <label></label>
                    <input type="text" class="form-control inp-log" name="email" placeholder="test@example.com"
                           value="<?php echo $datos['email']; ?>" id="emailCont"
                           onblur="valida_email(this.id)">
                    <div id="help-emailCont" class="help-block"></div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-12 btn-form">
                        <button id="btnRecCon" type="button" onclick="validaRecuperarContrasenha([this.form.id,'emailCont',this.id])" class="btn btn-primary">Enviar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="volver">
        <a href="noticia/index">
            << Volver a MundoMovil
        </a>
    </div>
</div>
