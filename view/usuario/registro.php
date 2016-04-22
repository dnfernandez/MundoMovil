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

                            <div id="errorE1" class="error-especial">
                                <?php if (isset($errores["nomUsuario"]) && !empty($errores["nomUsuario"])):
                                    echo '<script>
                                            document.getElementById("errorE1").style.display = "block !important";
                                          </script>';
                                    echo $errores["nomUsuario"];
                                endif; ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" class="form-control inp-log" name="email"
                                   value="<?php echo $datos['email']; ?>">
                            <div id="errorE2" class="error-especial">
                                <?php if (isset($errores["email"]) && !empty($errores["email"])):
                                    echo '<script>
                                            document.getElementById("errorE2").style.display = "block !important";
                                          </script>';
                                    echo $errores["email"];
                                endif; ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Ubicaci&oacute;n</label>
                            <input type="text" class="form-control inp-log" name="ubicacion"
                                   value="<?php echo $datos['ubicacion']; ?>">
                            <div id="errorE3" class="error-especial">
                                <?php if (isset($errores["ubicacion"]) && !empty($errores["ubicacion"])):
                                    echo '<script>
                                            document.getElementById("errorE3").style.display = "block !important";
                                          </script>';
                                    echo $errores["ubicacion"];
                                endif; ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Contrasen&ntilde;a</label>
                            <input type="password" class="form-control inp-log" name="contrasenha"
                                   placeholder="Contrase&ntilde;a">
                            <div id="errorE4" class="error-especial">
                                <?php if (isset($errores["contrasenha"]) && !empty($errores["contrasenha"])):
                                    echo '<script>
                                            document.getElementById("errorE4").style.display = "block !important";
                                          </script>';
                                    echo $errores["contrasenha"];
                                endif; ?>
                            </div>
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
                                    echo 'src="' . $datos["img_perfil"] . '" alt="ImagenPerfil"';
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
        <form id="formUrlReferer" method="post" action="usuario/evitarReferencias">
            <div id="div_url_ref"></div>
        </form>
        <a href="<?php echo $_SESSION["__sesion__herramienta__"]["__url_ref__"]; ?>">
            << Volver a MundoMovil
        </a>
    </div>
</div>
