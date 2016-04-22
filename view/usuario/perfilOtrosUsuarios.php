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
                Ver Perfil de <?php echo $datos["nom_usuario"]; ?>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                Viendo perfil - <?php echo $datos["nom_usuario"]; ?>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-5 imagenReg2">
                                    <div class="form-group img-perfil-per">
                                        <img id="imgPerfil" src="<?php echo $datos["avatar"]; ?>" alt="ImagenPerfil"
                                             class="img-rounded img-responsive">
                                    </div>
                                    <div class="form-group">
                                        <label>Avatar</label>
                                    </div>
                                </div>

                                <div class="col-md-7">
                                    <ul class="list-group listaGeneral">
                                        <li>Nombre de usuario: <?php echo $datos["nom_usuario"]; ?></li>
                                        <li>Rol en el sistema: <?php echo $datos["rol"]; ?></li>
                                        <li>Fecha registro:
                                            <?php
                                            $date = date_create($datos["fecha_reg"]);
                                            echo date_format($date, 'H:i d-m-Y');
                                            ?>
                                        </li>
                                        <li>&Uacute;ltima conexi&oacute;n:
                                            <?php
                                            $date = date_create($datos["fecha_conex"]);
                                            echo date_format($date, 'H:i d-m-Y');
                                            ?>
                                        </li>
                                        <li>Ubicaci&oacute;n: <?php echo $datos["ubicacion"]; ?></li>
                                        <li>
                                            <a class="btn btn-default"
                                               onclick="mostrar_enviar_mensaje('<?php echo $datos["id_usuario"] ?>','<?php echo $datos["nom_usuario"]; ?>')">
                                                Enviar Mensaje Privado
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer btn-form">
                            <form id="formUrlReferer" method="post" action="usuario/evitarReferencias">
                                <div id="div_url_ref"></div>
                            </form>
                            <button type="button"
                                    onclick="window.location.href = '<?php echo $_SESSION["__sesion__herramienta__"]["__url_ref__"]; ?>';"
                                    class="btn btn-primary">Volver
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Estad&iacute;sticas de usuario</h3>
                        </div>
                        <div class="panel-body">
                            <ul class="list-group listaGeneral">
                                <li>N&uacute;mero de noticias: <?php echo $datos["num_not"]; ?></li>
                                <li>N&uacute;mero de tutoriales: <?php echo $datos["num_tut"]; ?></li>
                                <li>N&uacute;mero de preguntas en foro: <?php echo $datos["num_preg"]; ?></li>
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
