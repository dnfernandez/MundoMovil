<?php
require_once(__DIR__ . "/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$usuarioActual = $view->getVariable("usuarioActual");
$mensaje = $view->getVariable("mensaje");
?>

<div class="container content">
    <div class="row">
        <div class="col-md-12 ">
            <div class="page-header encabezado">
                Panel de Control de Usuario
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="list-group">
                        <a href="usuario/general" class="list-group-item">P&aacute;gina general</a>
                        <a href="usuario/perfil" class="list-group-item">Perfil</a>
                        <a href="mensaje/enviados" class="list-group-item activo">Mensajes Enviados</a>
                        <a href="mensaje/recibidos" class="list-group-item">Mensajes Recibidos</a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                Ver mensaje enviado
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="row vistaMensaje">
                                <div class="col-md-2">
                                    <div class="row">
                                        <a href="usuario/ver?id=<?php echo $mensaje["receptor"]; ?>">
                                            <?php echo htmlentities($mensaje["nom_usuario"]); ?>
                                        </a>
                                    </div>
                                    <div class="row">
                                        <?php
                                        $date = date_create($mensaje["fecha"]);
                                        echo date_format($date, 'd.m.Y H:i');
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-10 texto">
                                    <?php echo htmlentities($mensaje["texto"]); ?>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer footerMensajes">
                            <button type="button"
                                    onclick="window.location.href = 'mensaje/enviados';"
                                    class="btn btn-default">Volver
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>