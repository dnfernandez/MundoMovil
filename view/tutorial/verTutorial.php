<?php
require_once(__DIR__ . "/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$usuarioActual = $view->getVariable("usuarioActual");
$tutorial = $view->getVariable("tutorial");
$comentarios = $view->getVariable("comentarios");
$total = $view->getVariable("num_comentarios");
if (isset($_GET["pag"])) {
    if (is_numeric($_GET["pag"])) {
        $pag = $_GET["pag"];
    }
}
$id_comentarios = $view->getVariable("id_comentarios");
?>
<div class="container content">
    <div class="row vistaNoticia">
        <div class="col-md-12">
            <!--Desde aqui-->
            <?php if (isset($usuarioActual)):
                if (($usuarioActual->getRol() == "administrador") || ($usuarioActual->getRol() == "moderador") || ($usuarioActual->getIdUsuario() == $tutorial["id_usuario"])): ?>
                    <span id="panelAdminMos">
						<a onclick="mostrar_panel_creacion()" class="btn glyphicon glyphicon-menu-down"></a>
					</span>

                    <div id="panelAdmin" class="row botonesNoticia">
                        <div class="col-md-12">
                            <h4>Panel de administraci&oacute;n de tutorial</h4>

                            <!--<form id="modificarTut" method="post" action="tutorial/modificar">

                                <a onclick="document.getElementById('modificarTut').submit();" class="btn btn-default">Modificar</a>
                                <input type="hidden" name="id_tutorial" value="<?php //echo $tutorial["id_tutorial"]; ?>">
                                <input type="hidden" name="id_usuario_tut"
                                       value="<?php //echo $tutorial["id_usuario"]; ?>">
                            </form>-->

                            <a href="tutorial/modificar?id=<?php echo $tutorial['id_tutorial'];?>" class="btn btn-default">Modificar</a>

                            <form id="borrarTut" method="post" action="tutorial/eliminar">
                                <a
                                    onclick="javascript:alertify.confirm('¿Est\u00E1 seguro de querer eliminar el tutorial?').autoCancel(10).set('title','MundoMovil')
                                        .set('onok', function(closeEvent){ document.getElementById('borrarTut').submit();} );"
                                    class="btn btn-default">Eliminar
                                </a>
                                <input type="hidden" name="id_tutorial" value="<?php echo $tutorial["id_tutorial"]; ?>">
                                <input type="hidden" name="id_usuario_tut"
                                       value="<?php echo $tutorial["id_usuario"]; ?>">
                            </form>

                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                                    onclick="ocultar_panel_creacion()">&times;</button>
                        </div>
                    </div>
                <?php endif;
            endif; ?>
            <!--Mostrar hasta aqui si es creador-->
            <div class="row cuerpo-not">
                <div class="row">
                    <div class="col-md 12 tituloTut2">
                        <div class="datosVerTut">
                            <?php
                            $date = date_create($tutorial["fecha"]);
                            echo date_format($date, 'd.m.Y | H:i');
                            ?>
                            <span>
                                Autor:
                                <?php if (!isset($usuarioActual)) {
                                    echo '<a class="enlaces-sinHref" onclick="javascript:alertify.message(\'Debes estar identificado en el sistema\')">' . htmlentities($tutorial["nom_usuario"]) . '</a>';
                                } else {
                                    echo '<a href="usuario/ver?id=' . $tutorial["id_usuario"] . '">
                                            ' . htmlentities($tutorial["nom_usuario"]) . '
                                        </a>';
                                }
                                ?>
                            </span>
                        </div>
                        <h2>
                            <?php echo htmlentities($tutorial["titulo"]); ?>
                        </h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="clavNot clavTut text-muted">
                            <?php
                            $claves = explode(" ", $tutorial["pal_clave"]);
                            foreach ($claves as $c) {
                                echo '
                                    <form class="form_filtrado" id=id' . $c . ' method="post" action="tutorial/filtro">
                                        <a onclick="document.getElementById(\'id' . $c . '\').submit();">
                                        ' . htmlentities($c) . '
                                        </a>
                                        <input type="hidden" name="opciones" value="tutorial">
                                        <input type="hidden" name="texto" value="' . $c . '">
                                        <input type="hidden" name="tipo_filtro" value="palabras">
                                    </form>
                                ';
                            }
                            ?>
                        </div>
                        <div class="textoNoticia">
                            <?php echo $tutorial["texto"]; ?>
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
                                            echo '<a href="javascript:mostrar_comentario_tutorial(\'null\')" onclick="recarga2()" class="btn btn-warning">';
                                        }
                                        ?>Comentar</a>
                                    </div>
                                    <div class="coment">
                                        <!--Bucle-->
                                        <?php foreach ($comentarios as $comentario): ?>
                                            <div class="comentario">
                                                <div class="row cabeceraCom">
                                                    <div class="col-md-7 aut">
                                                        <?php
                                                        echo "#" . $id_comentarios[$comentario["id_com_tutorial"]] . " ";

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
                                                            echo ' <a onclick="javascript:recarga()" href="tutorial/eliminar_comentario?id=' . $comentario["id_com_tutorial"] . '" class="glyphicon glyphicon-trash"></a>';
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="row cuerpoCom">
                                                    <div class="col-md-12">
                                                        <?php if (!empty($comentario["id_com_respondido"])): ?>
                                                            <?php if (isset($id_comentarios[$comentario["id_com_respondido"]])): ?>
                                                                <a onmouseover="mostrar_respuesta_comentario('<?php echo $comentario["id_com_tutorial"]; ?>')"
                                                                   onmouseout="ocultar_respuesta_comentario('<?php echo $comentario["id_com_tutorial"]; ?>')">
                                                                    <?php echo "#" . $id_comentarios[$comentario["id_com_respondido"]]; ?>
                                                                </a>
                                                                <div
                                                                    id="<?php echo "comentarioRespuesta" . $comentario["id_com_tutorial"]; ?>"
                                                                    class="comentarioRespuesta well">
                                                                    <?php echo htmlentities($comentario[0]); ?>
                                                                </div>
                                                            <?php else: ?>
                                                                <a onmouseover="mostrar_respuesta_comentario('<?php echo $comentario["id_com_tutorial"]; ?>')"
                                                                   onmouseout="ocultar_respuesta_comentario('<?php echo $comentario["id_com_tutorial"]; ?>')">
                                                                    <?php echo "#" ?>
                                                                </a>
                                                                <div
                                                                    id="<?php echo "comentarioRespuesta" . $comentario["id_com_tutorial"]; ?>"
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
                                                            echo '<a href="javascript:mostrar_comentario_tutorial(\'' . $comentario["id_com_tutorial"] . '\')"
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
                            echo '<a href = "tutorial/ver?id=' . $tutorial["id_tutorial"] . '&pag=' . ($pag - 1) . '" aria-label = "Anterior" onclick="javascript:recarga()">
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
                                echo '<li class="active"><a href="tutorial/ver?id=' . $tutorial["id_tutorial"] . '&pag=' . $i . '" onclick="javascript:recarga()">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="tutorial/ver?id=' . $tutorial["id_tutorial"] . '&pag=' . $i . '" onclick="javascript:recarga()">' . $i . '</a></li>';
                            }
                        }
                    } elseif (isset($pag) && $pag == 2) {
                        for ($i = 1; $i <= $num_pag && $i <= 5; $i++) {
                            if ($i == 2) {
                                echo '<li class="active"><a href="tutorial/ver?id=' . $tutorial["id_tutorial"] . '&pag=' . $i . '" onclick="javascript:recarga()">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="tutorial/ver?id=' . $tutorial["id_tutorial"] . '&pag=' . $i . '" onclick="javascript:recarga()">' . $i . '</a></li>';
                            }
                        }
                    } elseif (isset($pag) && $pag > 2 && $pag <= $num_pag - 2) {
                        for ($i = $pag - 2; $i <= $num_pag && $i <= $pag + 2; $i++) {
                            if ($i <= 0) {

                            } elseif ($i == $pag) {
                                echo '<li class="active"><a href="tutorial/ver?id=' . $tutorial["id_tutorial"] . '&pag=' . $i . '" onclick="javascript:recarga()">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="tutorial/ver?id=' . $tutorial["id_tutorial"] . '&pag=' . $i . '" onclick="javascript:recarga()">' . $i . '</a></li>';
                            }
                        }
                    } elseif (isset($pag) && $pag == $num_pag - 1) {
                        for ($i = $pag - 3; $i <= $num_pag && $i <= $pag + 1; $i++) {
                            if ($i <= 0) {

                            } elseif ($i == $pag) {
                                echo '<li class="active"><a href="tutorial/ver?id=' . $tutorial["id_tutorial"] . '&pag=' . $i . '" onclick="javascript:recarga()">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="tutorial/ver?id=' . $tutorial["id_tutorial"] . '&pag=' . $i . '" onclick="javascript:recarga()">' . $i . '</a></li>';
                            }
                        }
                    } elseif (isset($pag) && $pag == $num_pag) {
                        for ($i = $pag - 4; $i <= $num_pag && $i <= $pag; $i++) {
                            if ($i <= 0) {

                            } elseif ($i == $pag) {
                                echo '<li class="active"><a href="tutorial/ver?id=' . $tutorial["id_tutorial"] . '&pag=' . $i . '" onclick="javascript:recarga()">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="tutorial/ver?id=' . $tutorial["id_tutorial"] . '&pag=' . $i . '" onclick="javascript:recarga()">' . $i . '</a></li>';
                            }
                        }
                    }
                    ?>
                    <li>
                        <?php if (isset($pag) && $pag < $num_pag) {
                            echo '<a href="tutorial/ver?id=' . $tutorial["id_tutorial"] . '&pag=' . ($pag + 1) . '" aria-label="Siguiente" onclick="javascript:recarga()">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>';
                        } elseif (!isset($pag) && $num_pag > 1) {
                            echo '<a href="tutorial/ver?id=' . $tutorial["id_tutorial"] . '&pag=' . 2 . '" aria-label="Siguiente" onclick="javascript:recarga()">
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

<div id="formComentTutorial" class="formulario-js">
    <div class="panel panel-default" data-lightbox="busqueda">
        <div class="panel-heading">
            <h3 class="panel-title">
                Comentar noticia
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        onclick="ocultar_comentario_tutorial()">&times;</button>
            </h3>
        </div>
        <form id="idformComTut" method="POST" action="tutorial/comentar">
            <div class="panel-body">
                Introduzca el contenido del comentario y pulse Enviar
                <div></div>
                <label class="lbl-dest">Comentario:</label>
                <textarea type="text" class="form-control" name="texto_com" placeholder="..."></textarea>
            </div>
            <div class="panel-footer btn-form">
                <button type="reset" class="btn btn-default" onclick="ocultar_comentario_tutorial()">Cancelar</button>
                <button type="submit" class="btn btn-primary">Enviar</button>
            </div>
            <input type="hidden" name="id_tutorial" value="<?php echo $tutorial["id_tutorial"]; ?>">

            <div id="idRespComTut"></div>
        </form>
    </div>
</div>