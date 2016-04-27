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
        <form id="idformLoginEsp" method="POST" action="usuario/modificar_contrasenha">
            <div class="panel-body">
                <label>Introduzca contrase&ntilde;a</label>
                <div class="form-group">
                    <input type="password" class="form-control inp-log" name="contrasenha">
                </div>
                <label>Repita contrase&ntilde;a</label>
                <div class="form-group">
                    <input type="password" class="form-control inp-log" name="contrasenha2">
                </div>
            </div>
            <?php
            if (!empty($usuario)) {
                $tmp = serialize($usuario);
                $tmp = urlencode($tmp);
                echo '<input type="hidden" name="usuario" value="' . $tmp . '">';
            }
            ?>
            <input type="hidden" name="codigo" value="<?php echo $_GET["cod_act"];?>">
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-12 btn-form">
                        <button type="submit" class="btn btn-primary">Enviar</button>
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
