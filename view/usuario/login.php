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
                Entrar en MundoMovil
            </h3>
        </div>
        <form id="idformLoginEsp" method="POST" action="usuario/login">
            <input type="hidden" name="url_referer"
                   value="<?php echo $_SESSION["__sesion__herramienta__"]["__url_ref__"]; ?>">

            <div class="panel-body">
                <div class="form-group">
                    <label>Introduce E-mail</label>
                    <input type="text" class="form-control inp-log" name="email" value="<?php echo $datos['email']; ?>">
                </div>
                <div class="form-group">
                    <label>Introduce Contrase&ntilde;a</label>
                    <input type="password" class="form-control inp-log" name="contrasenha">
                </div>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-6">
                        <a class="btn btn-link" href="usuario/registro_error">
                            Reg&iacute;strate ahora
                        </a>
                    </div>
                    <div class="col-md-6 btn-form">
                        <button type="submit" class="btn btn-primary">Entrar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="volver">
        <?php if (empty($mensajeRegistro) && empty($mensajeError) && empty($mensajeSucces)): ?>
            <form id="formUrlReferer" method="post" action="usuario/evitarReferencias">
                <div id="div_url_ref"></div>
                <?php
                if (!empty($error)) {
                    echo '<input type="hidden" name="error" value="' . $error . '">';
                }
                if (!empty($datos)) {
                    $tmp = serialize($datos);
                    $tmp = urlencode($tmp);
                    echo '<input type="hidden" name="datos" value="' . $tmp . '">';
                }
                ?>
            </form>
            <a href="<?php echo $_SESSION["__sesion__herramienta__"]["__url_ref__"]; ?>">
                << Volver a MundoMovil
            </a>
        <?php else: ?>
            <a href="noticia/index">
                << Volver a MundoMovil
            </a>
        <?php endif; ?>
    </div>
</div>
