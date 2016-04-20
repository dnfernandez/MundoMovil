<?php
require_once(__DIR__ . "/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$usuarioActual = $view->getVariable("usuarioActual");
$preguntas = $view->getVariable("preguntas", array());
$total = $view->getVariable("total");
if (isset($_GET["pag"])) {
    if (is_numeric($_GET["pag"])) {
        $pag = $_GET["pag"];
    }
}
$filtro = $view->getVariable("filtro", "ver?id=" . $_GET["id"]);
if ($filtro == "filtro") {
    $get_pag = "?pag=";
} else {
    $get_pag = "&pag=";
}

$titulo = $view->getVariable("titulo", "Filtrado de foros");
?>
<div class="container content">
    <div class="row">
        <div class="col-md-12">
            <?php if (isset($usuarioActual)): ?>
                <form id="formPanelMos" method="post" action="usuario/preferencias">
                        <span id="panelAdminMos">
                            <button type="submit" onclick="mostrar_panel_creacion()"
                                    class="btn-panel glyphicon glyphicon-menu-down"></button>
                        </span>
                    <input type="hidden" name="visibilidad" value="mostrar">
                </form>
                <form id="formPanelOcul" method="post" action="usuario/preferencias">
                    <div id="panelAdmin" class="row botonesNoticia">
                        <div class="col-md-12">
                            <h4>Panel de administraci&oacute;n de tema / pregunta de foro</h4>
                            <a href="pregunta/crear" class="btn btn-default">Crear tema / pregunta</a>
                            <button type="submit" class="close" data-dismiss="modal" aria-hidden="true"
                                    onclick="ocultar_panel_creacion()">&times;</button>
                        </div>
                    </div>
                    <input type="hidden" name="visibilidad" value="ocultar">
                </form>
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
            <?php endif; ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header encabezado">
                        <?php echo $titulo; ?>
                    </div>
                    <div class="volver2">
                        <a href="foro/index"><< Volver a foros</a>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                Temas de <?php echo $titulo; ?>
                            </h3>
                        </div>
                        <div class="panel-body panelMensajes">
                            <table id="tab_ordena" class="tabla table tablaMen">
                                <thead class="textoTabla tituloMen">
                                <tr>
                                    <th class="primEl col-md-6">Temas/Preguntas</th>
                                    <th class="col-md-2">Autor</th>
                                    <th class="col-md-2">Fecha</th>
                                    <th class="col-md-1">Respuestas</th>
                                    <!-- Si es moderador o superior-->
                                    <?php if (isset($usuarioActual)):
                                        if ($usuarioActual->getRol() == "administrador" || $usuarioActual->getRol() == "moderador"): ?>
                                            <th class="col-md-1">Acci&oacute;n</th>
                                        <?php endif;
                                    endif; ?>
                                    <!---->
                                </tr>
                                </thead>
                                <tbody class="textoTabla">
                                <!--bucle-->
                                <?php foreach ($preguntas as $pregunta): ?>
                                    <tr>
                                        <td class="temForo primEl col-md-6">
                                            <a href="pregunta/ver?id=<?php echo $pregunta["id_pregunta"]; ?>">
                                                <?php echo $pregunta["titulo"]; ?>
                                            </a>
                                        </td>
                                        <td class="col-md-2 vertAlign">
                                            <?php if (!isset($usuarioActual)) {
                                                echo '<a class="enlaces-sinHref" onclick="javascript:alertify.message(\'Debes estar identificado en el sistema\')">' . htmlentities($pregunta["nom_usuario"]) . '</a>';
                                            } else {
                                                echo '<a href="usuario/ver?id=' . $pregunta["id_usuario"] . '">
                                            ' . htmlentities($pregunta["nom_usuario"]) . '
                                        </a>';
                                            }
                                            ?>
                                        </td>
                                        <td class="col-md-2 vertAlign">
                                            <?php
                                            $date = date_create($pregunta["fecha"]);
                                            echo date_format($date, 'd.m.Y H:i');
                                            ?>
                                        </td>
                                        <td class="col-md-1 vertAlign">
                                            <?php echo $pregunta[0]; ?>
                                        </td>
                                        <!-- Si es moderador o superior-->
                                        <?php if (isset($usuarioActual)):
                                            if ($usuarioActual->getRol() == "administrador" || $usuarioActual->getRol() == "moderador"): ?>
                                                <td class="col-md-1 vertAlign">
                                                    <form class="sin-salto"
                                                          id="eliminarPregunta<?php echo $pregunta['id_pregunta']; ?>"
                                                          method="post" action="pregunta/eliminarPregunta">
                                                        <a
                                                            onclick="javascript:alertify.confirm('¿Est\u00E1 seguro de querer eliminar a <?php echo $pregunta["titulo"]; ?>?').autoCancel(10).set('title','MundoMovil')
                                                                .set('onok', function(closeEvent){ document.getElementById('eliminarPregunta<?php echo $pregunta['id_pregunta']; ?>').submit();recarga2();})
                                                                .set('oncancel', function(closeEvent){ recarga2();location.reload();});"
                                                            class="enlaces-sinHref">Eliminar
                                                        </a>
                                                        <input type="hidden" name="id_pregunta"
                                                               value="<?php echo $pregunta["id_pregunta"]; ?>">
                                                        <input type="hidden" name="id_pregunta_usuario"
                                                               value="<?php echo $pregunta["id_usuario"]; ?>">
                                                    </form>
                                                </td>
                                            <?php endif;
                                        endif; ?>
                                        <!---->
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row pasa_pag">
                <div class="col-md-12">
                    <nav class="paginacion">
                        <ul class="pagination">
                            <li>
                                <?php if (isset($pag) && $pag > 1) {
                                    echo '<a href = "foro/' . $filtro . $get_pag . ($pag - 1) . '" aria-label = "Anterior" >
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
                                        echo '<li class="active"><a href="foro/' . $filtro . $get_pag . $i . '">' . $i . '</a></li>';
                                    } else {
                                        echo '<li><a href="foro/' . $filtro . $get_pag . $i . '">' . $i . '</a></li>';
                                    }
                                }
                            } elseif (isset($pag) && $pag == 2) {
                                for ($i = 1; $i <= $num_pag && $i <= 5; $i++) {
                                    if ($i == 2) {
                                        echo '<li class="active"><a href="foro/' . $filtro . $get_pag . $i . '">' . $i . '</a></li>';
                                    } else {
                                        echo '<li><a href="foro/' . $filtro . $get_pag . $i . '">' . $i . '</a></li>';
                                    }
                                }
                            } elseif (isset($pag) && $pag > 2 && $pag <= $num_pag - 2) {
                                for ($i = $pag - 2; $i <= $num_pag && $i <= $pag + 2; $i++) {
                                    if ($i <= 0) {

                                    } elseif ($i == $pag) {
                                        echo '<li class="active"><a href="foro/' . $filtro . $get_pag . $i . '">' . $i . '</a></li>';
                                    } else {
                                        echo '<li><a href="foro/' . $filtro . $get_pag . $i . '">' . $i . '</a></li>';
                                    }
                                }
                            } elseif (isset($pag) && $pag == $num_pag - 1) {
                                for ($i = $pag - 3; $i <= $num_pag && $i <= $pag + 1; $i++) {
                                    if ($i <= 0) {

                                    } elseif ($i == $pag) {
                                        echo '<li class="active"><a href="foro/' . $filtro . $get_pag . $i . '">' . $i . '</a></li>';
                                    } else {
                                        echo '<li><a href="foro/' . $filtro . $get_pag . $i . '">' . $i . '</a></li>';
                                    }
                                }
                            } elseif (isset($pag) && $pag == $num_pag) {
                                for ($i = $pag - 4; $i <= $num_pag && $i <= $pag; $i++) {
                                    if ($i <= 0) {

                                    } elseif ($i == $pag) {
                                        echo '<li class="active"><a href="foro/' . $filtro . $get_pag . $i . '">' . $i . '</a></li>';
                                    } else {
                                        echo '<li><a href="foro/' . $filtro . $get_pag . $i . '">' . $i . '</a></li>';
                                    }
                                }
                            }
                            ?>
                            <li>
                                <?php if (isset($pag) && $pag < $num_pag) {
                                    echo '<a href="foro/' . $filtro . $get_pag . ($pag + 1) . '" aria-label="Siguiente">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>';
                                } elseif (!isset($pag) && $num_pag > 1) {
                                    echo '<a href="foro/' . $filtro . $get_pag . 2 . '" aria-label="Siguiente">
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
    </div>
</div>