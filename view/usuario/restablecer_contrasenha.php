<?php
require_once(__DIR__ . "/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$usuario = $view->getVariable("usuario");
?>

<!--Formulario login-->
<div class="form-especial">
    <a rel="home" href="noticia/index" title="Mundo Movil">
        <img class="logo2 img-responsive" src="images/letra.png">
    </a>

    <div class="panel panel-default" data-lightbox="busqueda">
        <div class="panel-heading">
            <h3 class="panel-title">
                Restablecer contrase&ntilde;a en MundoMovil
            </h3>
        </div>
        <form id="idformRestCon" method="POST" action="usuario/modificar_contrasenha">
            <div class="panel-body">
                <label>Introduzca contrase&ntilde;a</label>

                <div id="div-passResCon" class="form-group">
                    <input type="password" class="form-control inp-log" name="contrasenha" id="passResCon"
                           onblur="valida_contrasenha(this.id)">

                    <div id="help-passResCon" class="help-block"></div>
                </div>
                <label>Repita contrase&ntilde;a</label>

                <div id="div-passResCon2" class="form-group">
                    <input type="password" class="form-control inp-log" name="contrasenha2" id="passResCon2"
                           onblur="valida_contrasenha(this.id)">

                    <div id="help-passResCon2" class="help-block"></div>
                </div>
            </div>
            <?php
            if (!empty($usuario)) {
                $tmp = serialize($usuario);
                $tmp = urlencode($tmp);
                echo '<input type="hidden" name="usuario" value="' . $tmp . '">';
            }
            ?>
            <input type="hidden" name="codigo" value="<?php echo $_GET["cod_act"]; ?>">

            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-12 btn-form">
                        <button id="btnResCon" type="button" onclick="validaRestablecerContrasenha([this.form.id,'passResCon','passResCon2',this.id])" class="btn btn-primary">Enviar</button>
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
