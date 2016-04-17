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
                        <a href="mensaje/enviados" class="list-group-item">Mensajes Enviados</a>
                        <a href="mensaje/recibidos" class="list-group-item activo">Mensajes Recibidos</a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                Ver mensaje recibido
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="row vistaMensaje">
                                <div class="col-md-2">
                                    <div class="row">
                                        <a href="usuario/ver?id=<?php echo $mensaje["emisor"]; ?>">
                                            <?php echo htmlentities($mensaje["nom_usuario"]); ?>
                                        </a>
                                    </div>
                                    <div class="row">
                                        <?php
                                        $date = date_create($mensaje["fecha"]);
                                        echo date_format($date, 'H:i d.m.Y');
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-10 texto">
                                    <?php echo htmlentities($mensaje["texto"]); ?>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer footerMensajes">
                            <a onclick="window.location.href=document.referrer" class="btn btn-default">Volver</a>
                            <button type="button" class="btn btn-primary"
                                    onclick="mostrar_enviar_mensaje('<?php echo $mensaje["emisor"] ?>','<?php echo $mensaje["nom_usuario"]; ?>')">
                                Responder
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!--- Formularios enviar mensaje -->

<div id="formMensajeEnv" class="formulario-js">
    <div class="panel panel-default" data-lightbox="busqueda">
        <div class="panel-heading">
            <h3 class="panel-title">
                Enviar mensaje
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        onclick="ocultar_enviar_mensaje()">&times;</button>
            </h3>
        </div>
        <form id="idformBusq" method="POST" action="mensaje/enviar">
            <div class="panel-body">
                Introduzca el contenido del mensaje y pulse Enviar
                <div></div>
                <label class="lbl-dest">Destinatario:</label>
                <span id="idDestinatario" class="form-control" disabled="disabled"></span>
                <label class="lbl-dest">Mensaje:</label>
                <textarea type="text" class="form-control" name="texto" placeholder="..."></textarea>
            </div>
            <div class="panel-footer btn-form">
                <button type="reset" class="btn btn-default" onclick="ocultar_enviar_mensaje()">Cancelar</button>
                <button type="submit" class="btn btn-primary">Enviar</button>
            </div>
            <div id="idDesHid"></div>
        </form>
    </div>
</div>
