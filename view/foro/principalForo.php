<?php
require_once(__DIR__ . "/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$usuarioActual = $view->getVariable("usuarioActual");
$foros = $view->getVariable("foros", array());
?>
<div class="container content">
    <div class="row">
        <div class="col-md-12 ">
            <?php if (isset($usuarioActual)):
                if ($usuarioActual->getRol() == "administrador" || $usuarioActual->getRol() == "moderador"): ?>
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
                                <h4>Panel de administraci&oacute;n de foro</h4>
                                <a href="javascript:mostrar_crear_foro()" class="btn btn-default">Crear foro</a>
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
                <?php endif;
            endif; ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header encabezado">
                        FOROS
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                Tem&aacute;tica de foros
                            </h3>
                        </div>
                        <div class="panel-body panelMensajes">
                            <table id="tab_ordena" class="tabla table tablaMen">
                                <thead class="textoTabla tituloMen">
                                <tr>
                                    <th class="primEl col-md-6">Foro</th>
                                    <th class="col-md-2">Fecha</th>
                                    <th class="col-md-2">Temas/Preguntas</th>
                                    <!-- Si es moderador o superior-->
                                    <?php if (isset($usuarioActual)):
                                        if ($usuarioActual->getRol() == "administrador" || $usuarioActual->getRol() == "moderador"): ?>
                                            <th class="col-md-2">Acci&oacute;n</th>
                                            <!---->
                                        <?php endif;
                                    endif; ?>
                                </tr>
                                </thead>
                                <tbody class="textoTabla">
                                <!--bucle-->
                                <?php foreach ($foros as $foro): ?>
                                    <tr>
                                        <td class="temForo primEl col-md-6">
                                            <a href="foro/ver?id=<?php echo $foro['id_foro']; ?>" class="titTemForo">
                                                <span class="glyphicon glyphicon-comment"></span>
                                                <?php echo $foro["titulo"]; ?>
                                            </a>

                                            <div class="resumForo">
                                                <?php echo $foro["resumen"]; ?>
                                            </div>
                                        </td>
                                        <td class="col-md-2 vertAlign">
                                            <?php
                                            $date = date_create($foro["fecha"]);
                                            echo date_format($date, 'd.m.Y H:i');
                                            ?>
                                        </td>
                                        <td class="col-md-2 vertAlign">
                                            <?php echo $foro[0]; ?>
                                        </td>
                                        <!-- Si es moderador o superior-->
                                        <?php if (isset($usuarioActual)):
                                            if ($usuarioActual->getRol() == "administrador" || $usuarioActual->getRol() == "moderador"): ?>
                                                <td class="col-md-2 vertAlign">
                                                    <a href="javascript:mostrar_modificar_foro(<?php echo '\'' . $foro["titulo"] . '\',\'' . $foro["resumen"] . '\',\'' . $foro["id_foro"] . '\''; ?>)">
                                                        Modificar
                                                    </a>
                                                    |
                                                    <form class="sin-salto"
                                                          id="eliminarForo<?php echo $foro['id_foro']; ?>"
                                                          method="post" action="foro/eliminarForo">
                                                        <a
                                                            onclick="javascript:alertify.confirm('¿Est\u00E1 seguro de querer eliminar a <?php echo $foro["titulo"]; ?>?').autoCancel(10).set('title','MundoMovil')
                                                                .set('onok', function(closeEvent){ document.getElementById('eliminarForo<?php echo $foro['id_foro']; ?>').submit();recarga2();})
                                                                .set('oncancel', function(closeEvent){ recarga2();location.reload();});"
                                                            class="enlaces-sinHref">Eliminar
                                                        </a>
                                                        <input type="hidden" name="id_foro"
                                                               value="<?php echo $foro["id_foro"]; ?>">
                                                    </form>
                                                </td>
                                                <!---->
                                            <?php endif;
                                        endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                                <!--Hasta aqui-->
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--- Formularios crear foro-->

<div id="formCrearForo" class="formulario-js">
    <div class="panel panel-default" data-lightbox="busqueda">
        <div class="panel-heading">
            <h3 class="panel-title">
                Crear tem&aacute;tica de foro
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        onclick="ocultar_crear_foro()">&times;</button>
            </h3>
        </div>
        <form id="idformCrearForo" method="POST" action="foro/crear">
            <div class="panel-body">
                Introduzca el t&iacute;tulo y descripci&oacute;n del foro y pulse Crear
                <div></div>
                <label class="lbl-dest">T&iacute;tulo:</label>
                <input type="text" name="titulo" class="form-control">
                <label class="lbl-dest">Descripci&oacute;n:</label>
                <textarea type="text" class="form-control" name="resumen"></textarea>
            </div>
            <div class="panel-footer btn-form">
                <button type="reset" class="btn btn-default" onclick="ocultar_crear_foro()">Cancelar</button>
                <button type="submit" class="btn btn-primary">Crear</button>
            </div>
        </form>
    </div>
</div>

<!--- Formularios modificar foro-->

<div id="formModificarForo" class="formulario-js">
    <div class="panel panel-default" data-lightbox="busqueda">
        <div class="panel-heading">
            <h3 class="panel-title">
                Modificar tem&aacute;tica de foro
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        onclick="ocultar_modificar_foro()">&times;</button>
            </h3>
        </div>
        <form id="idformModificarforo" method="POST" action="foro/modificarForo">
            <div class="panel-body">
                Introduzca el t&iacute;tulo y descripci&oacute;n del foro y pulse Modificar
                <div></div>
                <label class="lbl-dest">T&iacute;tulo:</label>
                <input type="text" id="inputModForo" name="titulo" class="form-control">
                <label class="lbl-dest">Descripci&oacute;n:</label>
                <textarea type="text" class="form-control" name="resumen" id="textareaModForo"></textarea>

                <div id="div_id_foro"></div>
            </div>
            <div class="panel-footer btn-form">
                <button type="reset" class="btn btn-default" onclick="ocultar_modificar_foro()">Cancelar</button>
                <button type="submit" class="btn btn-primary">Modificar</button>
            </div>
        </form>
    </div>
</div>