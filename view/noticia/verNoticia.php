<?php
require_once(__DIR__ . "/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$usuarioActual = $view->getVariable("usuarioActual");
$noticia = $view->getVariable("noticia");
$comentarios = $view->getVariable("comentarios");
$total = $view->getVariable("num_comentarios");
if (isset($_GET["pag"])) {
    if (is_numeric($_GET["pag"])) {
        $pag = $_GET["pag"];
    }
}
$id_comentarios = $view->getVariable("id_comentarios");
$recargar = $view->getVariable("recarga");
if(isset($recargar) && $recargar=="true"){
    header("Refresh:2");
}
?>

<div class="container content">
    <div class="row vistaNoticia">
        <div class="col-md-12">
            <!--Desde aqui-->
            <?php if (isset($usuarioActual)):
                if (($usuarioActual->getRol() == "administrador") || ($usuarioActual->getRol() == "moderador" && $usuarioActual->getIdUsuario() == $noticia["id_usuario"])): ?>
                    <form id="formPanelMos" method="post" action="usuario/preferencias">
                        <span id="panelAdminMos">
                            <button type="submit" onclick="mostrar_panel_creacion()"
                                    class="btn-panel glyphicon glyphicon-menu-down"></button>
                        </span>
                        <input type="hidden" name="visibilidad" value="mostrar">
                    </form>
                    <div id="panelAdmin" class="row botonesNoticia">
                        <div class="col-md-12">
                            <h4>Panel de administraci&oacute;n de noticia</h4>

                            <a href="noticia/modificar?id=<?php echo $noticia["id_noticia"]; ?>"
                               class="btn btn-default">Modificar</a>

                            <form id="borrarNot" method="post" action="noticia/eliminar">
                                <a
                                    onclick="javascript:alertify.confirm('¿Est\u00E1 seguro de querer eliminar la noticia?').autoCancel(10).set('title','MundoMovil')
                                        .set('onok', function(closeEvent){ document.getElementById('borrarNot').submit();} );"
                                    class="btn btn-default">Eliminar
                                </a>
                                <input type="hidden" name="id_noticia"
                                       value="<?php echo $noticia["id_noticia"]; ?>">
                                <input type="hidden" name="id_usuario_not"
                                       value="<?php echo $noticia["id_usuario"]; ?>">
                            </form>
                            <form id="formPanelOcul" method="post" action="usuario/preferencias">
                                <button type="submit" class="close" data-dismiss="modal" aria-hidden="true"
                                        onclick="ocultar_panel_creacion()">&times;</button>
                                <input type="hidden" name="visibilidad" value="ocultar">
                            </form>
                        </div>
                    </div>
                    <?php
                    if (isset($_SESSION["__sesion__herramienta__"]["__visibilidad_panel__"]) && ($_SESSION["__sesion__herramienta__"]["__visibilidad_panel__"]) == "ocultar") {
                        echo '<script type="text/javascript">
                            document.getElementById("panelAdmin").style.display = "none";
                            document.getElementById("panelAdminMos").style.display = "block";
                        </script>';
                    } else {
                        echo '<script type="text/javascript">
                            document.getElementById("panelAdmin").style.display = "block";
                            document.getElementById("panelAdminMos").style.display = "none";
                        </script>';
                    }
                    ?>
                <?php endif;
            endif; ?>
            <!--Mostrar hasta aqui si es creador-->
            <div class="row cuerpo-not">
                <div class="row">
                    <div class="col-md 12 tituloNot2">
                        <h2>
                            <?php echo htmlentities($noticia["titulo"]); ?>
                        </h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="imgNot">
                            <img src="<?php echo $noticia["rutaImagen"]; ?>" alt="imagen noticia"
                                 class="img-responsive">
                        </div>
                        <div class="datosNot">
                            <div class="row">
                                <div class="col-md-6 fec">
                                    Fecha: <?php
                                    $date = date_create($noticia["fecha"]);
                                    echo date_format($date, 'd.m.Y | H:i');
                                    ?>
                                </div>
                                <div class="col-md-6 aut">
                                    Autor:
                                    <?php if (!isset($usuarioActual)) {
                                        echo '<a class="enlaces-sinHref" onclick="javascript:alertify.message(\'Debes estar identificado en el sistema\')">' . htmlentities($noticia["nom_usuario"]) . '</a>';
                                    } else {
                                        echo '<a href="usuario/ver?id=' . $noticia["id_usuario"] . '">
                                            ' . htmlentities($noticia["nom_usuario"]) . '
                                        </a>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="resNot">
                            <?php echo htmlentities($noticia["resumen"]); ?>
                        </div>
                        <div class="clavNot text-muted">
                            <?php
                            $claves = explode(" ", $noticia["pal_clave"]);
                            foreach ($claves as $c) {
                                echo '
                                    <form class="form_filtrado" id=id' . $c . ' method="post" action="noticia/filtro">
                                        <a onclick="document.getElementById(\'id' . $c . '\').submit();">
                                        ' . htmlentities($c) . '
                                        </a>
                                        <input type="hidden" name="opciones" value="noticia">
                                        <input type="hidden" name="texto" value="' . $c . '">
                                        <input type="hidden" name="tipo_filtro" value="palabras">
                                    </form>
                                ';
                            }
                            ?>
                        </div>
                        <div class="textoNoticia">
                            <?php echo $noticia["texto"]; ?>
                        </div>
                        <div id="up" class="comentariosNoticia">
                            <div class="titComNot">
                                <h3><?php echo $total; ?> COMENTARIOS</h3>
                            </div>
                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="col-md-8">
                                    <div class="panelCom">
                                        <h4>Deja tu comentario</h4>
                                        <?php if (!isset($usuarioActual)) {
                                            echo '<a class="btn btn-warning" onclick="javascript:alertify.message(\'Debes estar identificado en el sistema\')">';
                                        } else {
                                            echo '<a href="javascript:mostrar_comentario_noticia(\'null\')" onclick="recarga2()" class="btn btn-warning">';
                                        }
                                        ?>
                                        Comentar</a>
                                    </div>
                                    <div class="coment">
                                        <!--Bucle-->
                                        <?php foreach ($comentarios as $comentario): ?>
                                            <div class="comentario">
                                                <div class="row cabeceraCom">
                                                    <div class="col-md-7 aut">
                                                        <?php
                                                        echo "#" . $id_comentarios[$comentario["id_com_noticia"]] . " ";

                                                        if (!isset($usuarioActual)) {
                                                            echo '<a class="enlaces-sinHref" onclick="javascript:alertify.message(\'Debes estar identificado en el sistema\')">' . htmlentities($comentario["nom_usuario"]) . '</a>';
                                                        } else {
                                                            echo '<a href="usuario/ver?id=' . $comentario["id_usuario"] . '">
                                                                 ' . htmlentities($comentario["nom_usuario"]) . '
                                                                 </a>';
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="col-md-5 fec">
                                                        <?php
                                                        $date = date_create($comentario["fecha"]);
                                                        echo date_format($date, 'd.m.Y | H:i');
                                                        if (isset($usuarioActual) && ($usuarioActual->getRol() == "administrador" || $usuarioActual->getRol() == "moderador")) {
                                                            echo ' <a onclick="javascript:recarga()" href="noticia/eliminar_comentario?id=' . $comentario["id_com_noticia"] . '" class="glyphicon glyphicon-trash"></a>';
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="row cuerpoCom">
                                                    <div class="col-md-12">
                                                        <?php if (!empty($comentario["id_com_respondido"])): ?>
                                                            <?php if (isset($id_comentarios[$comentario["id_com_respondido"]])): ?>
                                                                <a onmouseover="mostrar_respuesta_comentario('<?php echo $comentario["id_com_noticia"]; ?>')"
                                                                   onmouseout="ocultar_respuesta_comentario('<?php echo $comentario["id_com_noticia"]; ?>')">
                                                                    <?php echo "#" . $id_comentarios[$comentario["id_com_respondido"]]; ?>
                                                                </a>
                                                                <div
                                                                    id="<?php echo "comentarioRespuesta" . $comentario["id_com_noticia"]; ?>"
                                                                    class="comentarioRespuesta well">
                                                                    <?php echo htmlentities($comentario[0]); ?>
                                                                </div>
                                                            <?php else: ?>
                                                                <a onmouseover="mostrar_respuesta_comentario('<?php echo $comentario["id_com_noticia"]; ?>')"
                                                                   onmouseout="ocultar_respuesta_comentario('<?php echo $comentario["id_com_noticia"]; ?>')">
                                                                    <?php echo "#" ?>
                                                                </a>
                                                                <div
                                                                    id="<?php echo "comentarioRespuesta" . $comentario["id_com_noticia"]; ?>"
                                                                    class="comentarioRespuesta well">
                                                                    <?php echo "Se ha eliminado el comentario por no cumplir con las reglas de participaci&oacute;n" ?>
                                                                </div>
                                                            <?php endif;
                                                        endif;
                                                        echo htmlentities($comentario["texto"]) . "<br>"; ?>
                                                    </div>
                                                </div>
                                                <div class="row pieCom">
                                                    <div class="col-md-12">
                                                        <?php if (!isset($usuarioActual)) {
                                                            echo '<a class="btn btn-default" onclick="javascript:alertify.message(\'Debes estar identificado en el sistema\')">';
                                                        } else {
                                                            echo '<a href="javascript:mostrar_comentario_noticia(\'' . $comentario["id_com_noticia"] . '\')"
                                                                        class="btn btn-default" onclick="recarga2()">';
                                                        }
                                                        ?>

                                                        Responder
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                        <!--Tope-->
                                        <div class="pieComNot"></div>
                                    </div>
                                </div>
                                <div class="col-md-2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <nav class="paginacion">
                <ul class="pagination">
                    <li>
                        <?php if (isset($pag) && $pag > 1) {
                            echo '<a href = "noticia/ver?id=' . $noticia["id_noticia"] . '&pag=' . ($pag - 1) . '" aria-label = "Anterior" onclick="javascript:recarga()">
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
                                echo '<li class="active"><a href="noticia/ver?id=' . $noticia["id_noticia"] . '&pag=' . $i . '" onclick="javascript:recarga()">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="noticia/ver?id=' . $noticia["id_noticia"] . '&pag=' . $i . '" onclick="javascript:recarga()">' . $i . '</a></li>';
                            }
                        }
                    } elseif (isset($pag) && $pag == 2) {
                        for ($i = 1; $i <= $num_pag && $i <= 5; $i++) {
                            if ($i == 2) {
                                echo '<li class="active"><a href="noticia/ver?id=' . $noticia["id_noticia"] . '&pag=' . $i . '" onclick="javascript:recarga()">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="noticia/ver?id=' . $noticia["id_noticia"] . '&pag=' . $i . '" onclick="javascript:recarga()">' . $i . '</a></li>';
                            }
                        }
                    } elseif (isset($pag) && $pag > 2 && $pag <= $num_pag - 2) {
                        for ($i = $pag - 2; $i <= $num_pag && $i <= $pag + 2; $i++) {
                            if ($i <= 0) {

                            } elseif ($i == $pag) {
                                echo '<li class="active"><a href="noticia/ver?id=' . $noticia["id_noticia"] . '&pag=' . $i . '" onclick="javascript:recarga()">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="noticia/ver?id=' . $noticia["id_noticia"] . '&pag=' . $i . '" onclick="javascript:recarga()">' . $i . '</a></li>';
                            }
                        }
                    } elseif (isset($pag) && $pag == $num_pag - 1) {
                        for ($i = $pag - 3; $i <= $num_pag && $i <= $pag + 1; $i++) {
                            if ($i <= 0) {

                            } elseif ($i == $pag) {
                                echo '<li class="active"><a href="noticia/ver?id=' . $noticia["id_noticia"] . '&pag=' . $i . '" onclick="javascript:recarga()">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="noticia/ver?id=' . $noticia["id_noticia"] . '&pag=' . $i . '" onclick="javascript:recarga()">' . $i . '</a></li>';
                            }
                        }
                    } elseif (isset($pag) && $pag == $num_pag) {
                        for ($i = $pag - 4; $i <= $num_pag && $i <= $pag; $i++) {
                            if ($i <= 0) {

                            } elseif ($i == $pag) {
                                echo '<li class="active"><a href="noticia/ver?id=' . $noticia["id_noticia"] . '&pag=' . $i . '" onclick="javascript:recarga()">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="noticia/ver?id=' . $noticia["id_noticia"] . '&pag=' . $i . '" onclick="javascript:recarga()">' . $i . '</a></li>';
                            }
                        }
                    }
                    ?>
                    <li>
                        <?php if (isset($pag) && $pag < $num_pag) {
                            echo '<a href="noticia/ver?id=' . $noticia["id_noticia"] . '&pag=' . ($pag + 1) . '" aria-label="Siguiente" onclick="javascript:recarga()">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>';
                        } elseif (!isset($pag) && $num_pag > 1) {
                            echo '<a href="noticia/ver?id=' . $noticia["id_noticia"] . '&pag=' . 2 . '" aria-label="Siguiente" onclick="javascript:recarga()">
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

<!--- Formularios enviar comentario -->

<div id="formComentNoticia" class="formulario-js">
    <div class="panel panel-default" data-lightbox="busqueda">
        <div class="panel-heading">
            <h3 class="panel-title">
                Comentar noticia
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        onclick="ocultar_comentario_noticia()">&times;</button>
            </h3>
        </div>
        <form id="idformBusq" method="POST" action="noticia/comentar">
            <div class="panel-body">
                Introduzca el contenido del comentario y pulse Enviar
                <div></div>
                <label class="lbl-dest">Comentario:</label>
                <textarea type="text" class="form-control" name="texto_com" placeholder="..."></textarea>
            </div>
            <div class="panel-footer btn-form">
                <button type="reset" class="btn btn-default" onclick="ocultar_comentario_noticia()">Cancelar</button>
                <button type="submit" class="btn btn-primary">Enviar</button>
            </div>
            <input type="hidden" name="id_noticia" value="<?php echo $noticia["id_noticia"]; ?>">

            <div id="idRespComNot"></div>
        </form>
    </div>
</div>
