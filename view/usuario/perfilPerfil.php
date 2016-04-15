<?php
require_once(__DIR__ . "/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$usuarioActual = $view->getVariable("usuarioActual");
$errores = $view->getVariable("errores");
?>

<div class="container content">
    <div class="row">
        <div class="col-md-12 ">
            <div class="page-header encabezado">
                Panel de Control de <?php echo $usuarioActual->getNomUsuario(); ?>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="list-group">
                        <a href="usuario/general" class="list-group-item">P&aacute;gina general</a>
                        <a href="usuario/perfil" class="list-group-item activo">Perfil</a>
                        <a href="mensaje/enviados" class="list-group-item">Mensajes Enviados</a>
                        <a href="mensaje/recibidos" class="list-group-item">Mensajes Recibidos</a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                Perfil
                            </h3>
                        </div>
                        <form id="formPerfil" method="POST" action="usuario/modificar" enctype="multipart/form-data">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label>Nombre de usuario</label>
                                            <span class="form-control inp-log"
                                                  disabled="disabled"> <?php echo $usuarioActual->getNomUsuario(); ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label>Correo electr&oacute;nico</label>
                                            <span class="form-control inp-log"
                                                  disabled="disabled"> <?php echo $usuarioActual->getEmail(); ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label>Ubicaci&oacute;n</label>
                                            <input type="text" class="form-control inp-log" name="ubicacion"
                                                   value="<?php echo $usuarioActual->getUbicacion(); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Contrase&ntilde;a</label>
                                            <input type="password" class="form-control inp-log" name="contrasenha"
                                                   placeholder="Contrase&ntilde;a">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control inp-log" name="contrasenha2"
                                                   placeholder="Repite contrase&ntilde;a">
                                            <span id="errorE" class="error-especial2"/>
                                                <?php if (isset($errores["contrasenha"]) && !empty($errores["contrasenha"])):
                                                    echo '<script>
                                                        var elemento = document.getElementById("errorE");
                                                        elemento.innerHTML ="'.$errores["contrasenha"].'";
                                                        elemento.style.display = "block !important";
                                                      </script>';
                                                endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-5 imagenReg2">
                                        <div class="form-group img-perfil-per">
                                            <img id="imgPerfil" src="<?php echo $usuarioActual->getAvatar(); ?>"
                                                 alt="ImagenPerfil"
                                                 class="img-rounded img-responsive">
                                        </div>
                                        <div class="form-group">
                                            <label>Avatar</label>

                                            <div class="inp-file2">
                                                <input type="file" id="files" name="img_perfil">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer btn-form">
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
