<?php
require_once(__DIR__ . "/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$usuarioActual = $view->getVariable("usuarioActual");
$datos = $view->getVariable("datos");
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
                        <a href="usuario/general" class="list-group-item activo">P&aacute;gina general</a>
                        <a href="usuario/perfil" class="list-group-item">Perfil</a>
                        <a href="mensaje/enviados" class="list-group-item">Mensajes Enviados</a>
                        <a href="mensaje/recibidos" class="list-group-item">Mensajes Recibidos</a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">P&aacute;gina general</h3>
                        </div>
                        <div class="panel-body">
                            Bienvenido al Panel de Control de Usuario. En esta zona puedes editar los datos de tu perfil
                            como por ejemplo el avatar. Tambi&eacute;n podr&aacute;s leer tus mensajes.
                            <h4>Tu actividad</h4>
                            <ul class="list-group listaGeneral">
                                <li>Rol en el sistema:
                                    <span class="
                                        <?php if ($usuarioActual->getRol() == 'administrador') {
                                            echo 'admin-estilo';
                                        } elseif ($usuarioActual->getRol() == 'moderador') {
                                            echo 'moderador-estilo';
                                        } ?>">
                                        <?php echo $usuarioActual->getRol(); ?>
                                    </span>
                                </li>
                                <li>Fecha registro:
                                    <?php
                                    $date = date_create($usuarioActual->getFechaReg());
                                    echo date_format($date, 'H:i - d.m.Y');
                                    ?></li>
                                <li>&Uacute;ltima conexi&oacute;n:
                                    <?php
                                    $date = date_create($usuarioActual->getFechaConex());
                                    echo date_format($date, 'H:i - d.m.Y');
                                    ?></li>
                                <li>N&uacute;mero de noticias: <?php echo $datos["num_not"]; ?></li>
                                </li>
                                <li>N&uacute;mero de tutoriales: <?php echo $datos["num_tut"]; ?></li>
                                <li>N&uacute;mero de preguntas en foro:<?php echo $datos["num_preg"]; ?></li>
                                <li>N&uacute;mero de respuestas en foro: <?php echo $datos["num_res"]; ?></li>
                                <li>Votos positivos: <?php echo $datos["num_pos"]; ?></li>
                                <li>Votos negativos: <?php echo $datos["num_neg"]; ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
