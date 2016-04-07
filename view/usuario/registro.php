<?php
require_once(__DIR__ . "/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$base = $view->getBase();
$error = $view->popFlash();
$errores = $view->getVariable("errores");
$datos = $view->getVariable("datos");
?>
<!--Formulario registro-->
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
                Registrarse en MundoMovil
            </h3>
        </div>
        <form id="idformRegistroEspecial" method="POST" action="usuario/registro" enctype="multipart/form-data">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label>Nombre de usuario</label>
                            <input type="text" class="form-control inp-log" name="nom_usuario"
                                   value="<?php echo $datos['nom_usuario']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" class="form-control inp-log" name="email"
                                   value="<?php echo $datos['email']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Ubicaci&oacute;n</label>
                            <input type="text" class="form-control inp-log" name="ubicacion"
                                   value="<?php echo $datos['ubicacion']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Contrasen&ntilde;a</label>
                            <input type="password" class="form-control inp-log" name="contrasenha"
                                   placeholder="Contrase&ntilde;a">
                        </div>
                        <div class="form-group">
                            <label>Repite contrase&ntilde;a</label>
                            <input type="password" class="form-control inp-log" name="contrasenha2"
                                   placeholder="Repite contrase&ntilde;a">
                        </div>
                    </div>
                    <div class="col-md-5 imagenReg">
                        <div class="form-group img-regis">
                            <img id="imgPerfil"
                                <?php if (isset($datos["img_perfil"]) && $datos["img_perfil"] != ""):
                                    echo 'src="'.$datos["img_perfil"].'" alt="ImagenPerfil"';
                                else:
                                    echo 'src="images/perfil.jpg" alt="ImagenPerfil"';
                                endif; ?>
                                 class="img-rounded img-responsive">
                        </div>
                        <div class="form-group">
                            <label>Selecciona avatar</label>

                            <div class="inp-file3">
                                <input type="file" id="files" name="img_perfil">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer btn-form">
                <button type="submit" class="btn btn-primary">Registrar</button>
            </div>
        </form>
    </div>
    <div class="volver">
        <a href="noticia/index">
            << Volver a MundoMovil
        </a>
    </div>
</div>
