<?php
require_once(__DIR__ . "/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$usuarioActual = $view->getVariable("usuarioActual");
$pregunta = $view->getVariable("pregunta");
$respuestas = $view->getVariable("respuestas", array());
$total = $view->getVariable("total");
if (isset($_GET["pag"])) {
    if (is_numeric($_GET["pag"])) {
        $pag = $_GET["pag"];
    }
}
?>
<div class="container content">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header encabezado">
                <?php echo $pregunta["titulo"]; ?>
            </div>
            <div class="volver2">
                <a href="foro/ver?id=<?php echo $pregunta["id_foro"]; ?>"><< Volver a foro</a>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default panelPregunta">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="autorPregunta">
                                        <img src="<?php echo $pregunta["avatar"]; ?>" class="img-responsive"
                                             alt="ImagenPerfil">
                                        <?php if (!isset($usuarioActual)) {
                                            echo '<a class="enlaces-sinHref" onclick="javascript:alertify.message(\'Debes estar identificado en el sistema\')">' . htmlentities($pregunta["nom_usuario"]) . '</a>';
                                        } else {
                                            echo '<a href="usuario/ver?id=' . $pregunta["id_usuario"] . '">
                                            ' . htmlentities($pregunta["nom_usuario"]) . '
                                        </a>';
                                        }
                                        ?>
                                        <?php if (!isset($usuarioActual)): ?>
                                            <button class="btn btn-default"
                                                    onclick="javascript:alertify.message('Debes estar identificado en el sistema')">
                                                Mensaje privado
                                            </button>
                                        <?php elseif (isset($usuarioActual) && $usuarioActual->getIdUsuario() == $pregunta["id_usuario"]): ?>
                                            <button class="btn btn-default"
                                                    onclick="javascript:alertify.message('No puedes enviarte un mensaje a ti mismo')">
                                                Mensaje privado
                                            </button>
                                        <?php else : ?>
                                            <button type="button" class="btn btn-default"
                                                    onclick="mostrar_enviar_mensaje('<?php echo $pregunta["id_usuario"] ?>','<?php echo $pregunta["nom_usuario"]; ?>')">
                                                Mensaje Privado
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="preguntaDer">
                                        <div class="cabeceraPregunta">
                                            <?php
                                            $date = date_create($pregunta["fecha"]);
                                            echo date_format($date, 'd.m.Y | H:i');
                                            ?>
                                            <?php if (isset($usuarioActual)):
                                                if ($usuarioActual->getRol() == "administrador" || $usuarioActual->getRol() == "moderador" || $usuarioActual->getIdUsuario() == $pregunta["id_usuario"]): ?>
                                                    <span class="botonesAdministracion">
														<a href="pregunta/modificar?id=<?php echo $pregunta['id_pregunta']; ?>">
                                                            Editar
                                                        </a>
														|
                                                        <form class="sin-salto" id="eliminarTema" method="post"
                                                              action="pregunta/eliminarPregunta">
                                                            <a
                                                                onclick="javascript:alertify.confirm('¿Est\u00E1 seguro de querer eliminar a <?php echo $pregunta["titulo"]; ?>?').autoCancel(10).set('title','MundoMovil')
                                                                    .set('onok', function(closeEvent){ document.getElementById('eliminarTema').submit();});"
                                                                class="enlaces-sinHref">Eliminar
                                                            </a>
                                                            <input type="hidden" name="id_pregunta"
                                                                   value="<?php echo $pregunta["id_pregunta"]; ?>">
                                                            <input type="hidden" name="id_pregunta_usuario"
                                                                   value="<?php echo $pregunta["id_usuario"]; ?>">
                                                            <input type="hidden" name="id_foro"
                                                                   value="<?php echo $pregunta["id_foro"]; ?>">
                                                        </form>
													</span>
                                                <?php endif;
                                            endif; ?>
                                        </div>
                                        <div class="cuerpoPregunta">
                                            <?php echo $pregunta["texto"]; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="tagsPregunta">
                                        <?php
                                        $claves = explode(" ", $pregunta["pal_clave"]);
                                        foreach ($claves as $c) {
                                            echo '
                                            <form class="form_filtrado" id=id' . $c . ' method="post" action="foro/filtro">
                                                <a onclick="document.getElementById(\'id' . $c . '\').submit();">
                                                ' . htmlentities($c) . '
                                                </a>
                                                <input type="hidden" name="opciones" value="foro">
                                                <input type="hidden" name="texto" value="' . $c . '">
                                                <input type="hidden" name="tipo_filtro" value="palabras">
                                            </form>
                                        ';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer btn-form">
                            <?php if (!isset($usuarioActual)): ?>
                                <button type="button" class="btn btn-primary"
                                        onclick="javascript:alertify.message('Debes estar identificado en el sistema')">
                                    Responder
                                </button>
                            <?php else: ?>
                                <button type="button" class="btn btn-primary" onclick="mostrar_crear_respuesta('')">
                                    Responder
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!--Respuestas-->
            <?php foreach ($respuestas as $respuesta) : ?>
                <div class="row">
                    <div class="col-md-1 ">
                    </div>
                    <div class="col-md-11">
                        <div class="panel panel-default panelRespuesta">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="autorPregunta">
                                            <img src="<?php echo $respuesta["avatar"]; ?>"
                                                 class="img-responsive"
                                                 alt="ImagenPerfil">
                                            <?php if (!isset($usuarioActual)) {
                                                echo '<a class="enlaces-sinHref" onclick="javascript:alertify.message(\'Debes estar identificado en el sistema\')">' . htmlentities($respuesta["nom_usuario"]) . '</a>';
                                            } else {
                                                echo '<a href="usuario/ver?id=' . $respuesta["id_usuario"] . '">
                                                        ' . htmlentities($respuesta["nom_usuario"]) . '
                                                     </a>';
                                            } ?>
                                            <?php if (!isset($usuarioActual)): ?>
                                                <button class="btn btn-default"
                                                        onclick="javascript:alertify.message('Debes estar identificado en el sistema')">
                                                    Mensaje privado
                                                </button>
                                            <?php elseif (isset($usuarioActual) && $usuarioActual->getIdUsuario() == $respuesta["id_usuario"]): ?>
                                                <button class="btn btn-default"
                                                        onclick="javascript:alertify.message('No puedes enviarte un mensaje a ti mismo')">
                                                    Mensaje privado
                                                </button>
                                            <?php else : ?>
                                                <button type="button" class="btn btn-default"
                                                        onclick="mostrar_enviar_mensaje('<?php echo $respuesta["id_usuario"] ?>','<?php echo $respuesta["nom_usuario"]; ?>')">
                                                    Mensaje Privado
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="preguntaDer">
                                            <div class="cabeceraPregunta">
                                                <?php
                                                $date = date_create($respuesta["fecha"]);
                                                echo date_format($date, 'd.m.Y | H:i');
                                                ?>
                                                <?php if (!isset($usuarioActual)): ?>
                                                    <a class=" botonesAdministracion enlaces-sinHref"
                                                       onclick="javascript:alertify.message('Debes estar identificado en el sistema')">
                                                        Citar
                                                    </a>
                                                <?php else: ?>
                                                    <span class="botonesAdministracion">
                                                        <a href="javascript:recarga2();mostrar_crear_respuesta('<?php echo htmlentities($respuesta["texto"]); ?>')">
                                                            Citar
                                                        </a>
                                                        <?php
                                                            if ($usuarioActual->getRol() == "administrador" || $usuarioActual->getRol() == "moderador") {
                                                                echo ' - <a onclick="javascript:recarga2()" href="respuesta/eliminar_respuesta?id=' . $respuesta["id_respuesta"] . '" class="glyphicon glyphicon-trash"></a>';
                                                            }
                                                        ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="cuerpoPregunta">
                                                <?php echo $respuesta["texto"]; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="votacion">
                                            <?php if (isset($usuarioActual)): ?>
                                                <span class="votosPos"><?php echo $respuesta["likes_pos"]; ?></span>
                                                <?php if ($respuesta["tipo_votacion"] == null || ($respuesta["tipo_votacion"] == 0)): ?>
                                                    <form class="sin-salto"
                                                          id="votoPos<?php echo $respuesta["id_respuesta"] ?>"
                                                          action="respuesta/votar" method="post">
                                                        <a class="likeUp enlaces-sinHref"
                                                           onclick="javascript: document.getElementById('votoPos<?php echo $respuesta['id_respuesta']; ?>').submit();recarga2();">
                                                            <span class="glyphicon glyphicon-thumbs-up"/>
                                                        </a>
                                                        <input type="hidden" name="id_respuesta"
                                                               value="<?php echo $respuesta['id_respuesta']; ?>">
                                                        <input type="hidden" name="tipo_voto" value="positivo">
                                                    </form>
                                                <?php else: ?>
                                                    <a class="likeUp enlaces-sinHref"
                                                       onclick="javascript:alertify.message('Ya has votado esta respuesta')">
                                                        <span class="glyphicon glyphicon-thumbs-up"/>
                                                    </a>
                                                <?php endif; ?>
                                                <span class="votosNeg"><?php echo $respuesta["likes_neg"]; ?></span>
                                                <?php if (($respuesta["tipo_votacion"] == 1 || $respuesta["tipo_votacion"] == null)): ?>
                                                    <form class="sin-salto"
                                                          id="votoNeg<?php echo $respuesta["id_respuesta"] ?>"
                                                          action="respuesta/votar" method="post">
                                                        <a class="likeUp enlaces-sinHref"
                                                           onclick="javascript: document.getElementById('votoNeg<?php echo $respuesta['id_respuesta']; ?>').submit();recarga2();">
                                                            <span class="glyphicon glyphicon-thumbs-down"/>
                                                        </a>
                                                        <input type="hidden" name="id_respuesta"
                                                               value="<?php echo $respuesta['id_respuesta']; ?>">
                                                        <input type="hidden" name="tipo_voto" value="negativo">
                                                    </form>
                                                <?php else: ?>
                                                    <a class="enlaces-sinHref"
                                                       onclick="javascript:alertify.message('Ya has votado esta respuesta')">
                                                        <span class="glyphicon glyphicon-thumbs-down"/>
                                                    </a>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="votosPos"><?php echo $respuesta["likes_pos"]; ?></span>
                                                <a class=" likeUp enlaces-sinHref"
                                                   onclick="javascript:alertify.message('Debes estar identificado en el sistema')">
                                                    <span class="glyphicon glyphicon-thumbs-up"/>
                                                </a>
                                                <span class="votosNeg"><?php echo $respuesta["likes_neg"]; ?></span>
                                                <a class=" likeUp enlaces-sinHref"
                                                   onclick="javascript:alertify.message('Debes estar identificado en el sistema')">
                                                    <span class="glyphicon glyphicon-thumbs-down"/>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <!----->
        </div>
    </div>
    <div class="row paginas">
        <div class="col-md-12">
            <nav class="paginacion">
                <ul class="pagination">
                    <li>
                        <?php if (isset($pag) && $pag > 1) {
                            echo '<a href = "pregunta/ver?id=' . $pregunta["id_pregunta"] . '&pag=' . ($pag - 1) . '" aria-label = "Anterior" onclick="javascript:recarga()">
                                    <span aria-hidden = "true" >&laquo;</span >
                                </a >';
                        } else {
                            echo '<a aria-label = "Anterior" >
                                    <span aria-hidden = "true" >&laquo;</span >
                                </a >';
                        }
                        ?>
                    </li>
                    <?php
                    $num_pag = ceil($total / 10);
                    if (!isset($pag) || $pag == 1) {
                        for ($i = 1; $i <= $num_pag && $i <= 5; $i++) {
                            if ($i == 1) {
                                echo '<li class="active"><a href="pregunta/ver?id=' . $pregunta["id_pregunta"] . '&pag=' . $i . '" onclick="javascript:recarga()">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="pregunta/ver?id=' . $pregunta["id_pregunta"] . '&pag=' . $i . '" onclick="javascript:recarga()">' . $i . '</a></li>';
                            }
                        }
                    } elseif (isset($pag) && $pag == 2) {
                        for ($i = 1; $i <= $num_pag && $i <= 5; $i++) {
                            if ($i == 2) {
                                echo '<li class="active"><a href="pregunta/ver?id=' . $pregunta["id_pregunta"] . '&pag=' . $i . '" onclick="javascript:recarga()">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="pregunta/ver?id=' . $pregunta["id_pregunta"] . '&pag=' . $i . '" onclick="javascript:recarga()">' . $i . '</a></li>';
                            }
                        }
                    } elseif (isset($pag) && $pag > 2 && $pag <= $num_pag - 2) {
                        for ($i = $pag - 2; $i <= $num_pag && $i <= $pag + 2; $i++) {
                            if ($i <= 0) {

                            } elseif ($i == $pag) {
                                echo '<li class="active"><a href="pregunta/ver?id=' . $pregunta["id_pregunta"] . '&pag=' . $i . '" onclick="javascript:recarga()">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="pregunta/ver?id=' . $pregunta["id_pregunta"] . '&pag=' . $i . '" onclick="javascript:recarga()">' . $i . '</a></li>';
                            }
                        }
                    } elseif (isset($pag) && $pag == $num_pag - 1) {
                        for ($i = $pag - 3; $i <= $num_pag && $i <= $pag + 1; $i++) {
                            if ($i <= 0) {

                            } elseif ($i == $pag) {
                                echo '<li class="active"><a href="pregunta/ver?id=' . $pregunta["id_pregunta"] . '&pag=' . $i . '" onclick="javascript:recarga()">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="pregunta/ver?id=' . $pregunta["id_pregunta"] . '&pag=' . $i . '" onclick="javascript:recarga()">' . $i . '</a></li>';
                            }
                        }
                    } elseif (isset($pag) && $pag == $num_pag) {
                        for ($i = $pag - 4; $i <= $num_pag && $i <= $pag; $i++) {
                            if ($i <= 0) {

                            } elseif ($i == $pag) {
                                echo '<li class="active"><a href="pregunta/ver?id=' . $pregunta["id_pregunta"] . '&pag=' . $i . '" onclick="javascript:recarga()">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="pregunta/ver?id=' . $pregunta["id_pregunta"] . '&pag=' . $i . '" onclick="javascript:recarga()">' . $i . '</a></li>';
                            }
                        }
                    }
                    ?>
                    <li>
                        <?php if (isset($pag) && $pag < $num_pag) {
                            echo '<a href="pregunta/ver?id=' . $pregunta["id_pregunta"] . '&pag=' . ($pag + 1) . '" aria-label="Siguiente" onclick="javascript:recarga()">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>';
                        } elseif (!isset($pag) && $num_pag > 1) {
                            echo '<a href="pregunta/ver?id=' . $pregunta["id_pregunta"] . '&pag=' . 2 . '" aria-label="Siguiente" onclick="javascript:recarga()">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>';
                        } else {
                            echo '<a aria-label="Siguiente">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>';
                        }
                        ?>
                    </li>
                </ul>
            </nav>
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
        <form id="idformEnvMen" method="POST" action="mensaje/enviar">
            <div class="panel-body">
                Introduzca el contenido del mensaje y pulse Enviar
                <div></div>
                <label class="lbl-dest">Destinatario:</label>
                <span id="idDestinatario" class="form-control" disabled="disabled"></span>
                <label class="lbl-dest">Mensaje:</label>

                <div id="div-mensajePriv" class="form-group">
                    <textarea type="text" class="form-control" name="texto" placeholder="..." id="mensajePriv"
                              onblur="valida_texto(this.id)"></textarea>

                    <div id="help-mensajePriv" class="help-block"></div>
                </div>
            </div>
            <div class="panel-footer btn-form">
                <button type="reset" class="btn btn-default"
                        onclick="limpia_form(['mensajePriv']);ocultar_enviar_mensaje()">Cancelar
                </button>
                <button id="btnMenPriv" type="button" onclick="validaComentario([this.form.id,'mensajePriv',this.id])"
                        class="btn btn-primary">Enviar
                </button>
            </div>
            <div id="idDesHid"></div>
        </form>
    </div>
</div>

<!--- Formularios crear respuesta-->

<div id="formCrearRespuesta" class="formulario-js2">
    <div class="panel panel-default" data-lightbox="busqueda">
        <div class="panel-heading">
            <h3 class="panel-title">
                Crear respuesta
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        onclick="ocultar_crear_respuesta()">&times;</button>
            </h3>
        </div>
        <form id="idformCrearRespuesta" method="POST" action="respuesta/crearRespuesta">
            <div class="panel-body">
                Introduzca el contenido de la respuesta y pulse Crear
                <div></div>
                <label class="lbl-dest">Respuesta:</label>

                <div id="div-textareaRespuesta" class="form-group">
                    <textarea id="textareaRespuesta" type="text" class="form-control" name="texto"></textarea>
                    <script type="text/javascript">
                        CKEDITOR.replace('textareaRespuesta');
                        CKEDITOR.config.height = '25em';
                        CKEDITOR.instances['textareaRespuesta'].on('blur', function () {
                            valida_texto('textareaRespuesta', 1);
                        })
                    </script>
                    <div id="help-textareaRespuesta" class="help-block"></div>
                </div>
            </div>
            <input type="hidden" name="id_pregunta" value="<?php echo $pregunta["id_pregunta"] ?>">
            <input type="hidden" name="id_foro" value="<?php echo $pregunta["id_foro"] ?>">

            <div class="panel-footer btn-form">
                <button type="reset" class="btn btn-default"
                        onclick="limpia_form(['textareaRespuesta'],1);ocultar_crear_respuesta()">Cancelar
                </button>
                <button id="btnResPreg" type="button"
                        onclick="validaRespuesta([this.form.id,'textareaRespuesta',this.id])" class="btn btn-primary">
                    Crear
                </button>
            </div>
        </form>
    </div>
</div>
