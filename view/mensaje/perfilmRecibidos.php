<?php
require_once(__DIR__ . "/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$usuarioActual = $view->getVariable("usuarioActual");
$mensajes = $view->getVariable("mensajes", array());
$total = $view->getVariable("total");
if (isset($_GET["pag"])) {
    if (is_numeric($_GET["pag"])) {
        $pag = $_GET["pag"];
    }
}
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
                        <a href="usuario/perfil" class="list-group-item">Perfil</a>
                        <a href="mensaje/enviados" class="list-group-item">Mensajes Enviados</a>
                        <a href="mensaje/recibidos" class="list-group-item activo">Mensajes Recibidos</a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                Mensajes Recibidos
                            </h3>
                        </div>
                        <div class="panel-body panelMensajes">
                            <table id="tab_ordena" class="tabla table tablaMen">
                                <thead class="textoTabla tituloMen">
                                <tr>
                                    <th class="primEl col-md-4">Mensaje</th>
                                    <th class="col-md-3">Fecha</th>
                                    <th class="col-md-3">Origen</th>
                                    <th class="col-md-2">Acci&oacute;n</th>
                                </tr>
                                </thead>
                                <tbody class="textoTabla">
                                <?php foreach ($mensajes as $mensaje): ?>
                                    <tr>
                                        <td class="menMensaje primEl col-md-4">
                                            <a href="mensaje/recibido?id=<?php echo $mensaje["id_mensaje_rec"]; ?>">
                                                <?php if ($mensaje["leido"] == 0) {
                                                    echo '<span class="glyphicon glyphicon-envelope"></span>';
                                                } ?>
                                                <?php echo htmlentities($mensaje["texto"]); ?>
                                            </a>
                                        </td>
                                        <td class="col-md-3">
                                            <?php
                                            $date = date_create($mensaje["fecha"]);
                                            echo date_format($date, 'd.m.Y H:i');
                                            ?>
                                        </td>
                                        <td class="col-md-3">
                                            <a href="usuario/ver?id=<?php echo $mensaje["emisor"]; ?>">
                                                <?php echo htmlentities($mensaje["nom_usuario"]); ?>
                                            </a>
                                        </td>
                                        <td class="col-md-2">
                                            <form id="borrarMenRec<?php echo $mensaje["id_mensaje_rec"];?>" method="post" action="mensaje/eliminarRec">
                                                <a
                                                    onclick="javascript:alertify.confirm('¿Est\u00E1 seguro de querer eliminar el mensaje?').autoCancel(10).set('title','MundoMovil')
                                                    .set('onok', function(closeEvent){ document.getElementById('borrarMenRec<?php echo $mensaje["id_mensaje_rec"];?>').submit();} );;recarga2();"
                                                    class="enlaces-sinHref glyphicon glyphicon-trash">
                                                </a>
                                                <input type="hidden" name="id_mensaje"
                                                       value="<?php echo $mensaje["id_mensaje_rec"]; ?>">
                                                <input type="hidden" name="id_receptor"
                                                       value="<?php echo $mensaje["receptor"]; ?>">
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="row pasa_pag">
                        <div class="col-md-12">
                            <nav class="paginacion">
                                <ul class="pagination">
                                    <li>
                                        <?php if (isset($pag) && $pag > 1) {
                                            echo '<a href = "mensaje/recibidos?pag=' . ($pag - 1) . '" aria-label = "Anterior" >
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
                                    $num_pag = ceil($total / 15);
                                    if (!isset($pag) || $pag == 1) {
                                        for ($i = 1; $i <= $num_pag && $i <= 5; $i++) {
                                            if ($i == 1) {
                                                echo '<li class="active"><a href="mensaje/recibidos?pag=' . $i . '">' . $i . '</a></li>';
                                            } else {
                                                echo '<li><a href="mensaje/recibidos?pag=' . $i . '">' . $i . '</a></li>';
                                            }
                                        }
                                    } elseif (isset($pag) && $pag == 2) {
                                        for ($i = 1; $i <= $num_pag && $i <= 5; $i++) {
                                            if ($i == 2) {
                                                echo '<li class="active"><a href="mensaje/recibidos?pag=' . $i . '">' . $i . '</a></li>';
                                            } else {
                                                echo '<li><a href="mensaje/recibidos?pag=' . $i . '">' . $i . '</a></li>';
                                            }
                                        }
                                    } elseif (isset($pag) && $pag > 2 && $pag <= $num_pag - 2) {
                                        for ($i = $pag - 2; $i <= $num_pag && $i <= $pag + 2; $i++) {
                                            if ($i <= 0) {

                                            } elseif ($i == $pag) {
                                                echo '<li class="active"><a href="mensaje/recibidos?pag=' . $i . '">' . $i . '</a></li>';
                                            } else {
                                                echo '<li><a href="mensaje/recibidos?pag=' . $i . '">' . $i . '</a></li>';
                                            }
                                        }
                                    } elseif (isset($pag) && $pag == $num_pag - 1) {
                                        for ($i = $pag - 3; $i <= $num_pag && $i <= $pag + 1; $i++) {
                                            if ($i <= 0) {

                                            } elseif ($i == $pag) {
                                                echo '<li class="active"><a href="mensaje/recibidos?pag=' . $i . '">' . $i . '</a></li>';
                                            } else {
                                                echo '<li><a href="mensaje/recibidos?pag=' . $i . '">' . $i . '</a></li>';
                                            }
                                        }
                                    } elseif (isset($pag) && $pag == $num_pag) {
                                        for ($i = $pag - 4; $i <= $num_pag && $i <= $pag; $i++) {
                                            if ($i <= 0) {

                                            } elseif ($i == $pag) {
                                                echo '<li class="active"><a href="mensaje/recibidos?pag=' . $i . '">' . $i . '</a></li>';
                                            } else {
                                                echo '<li><a href=mensaje/recibidos?pag=' . $i . '">' . $i . '</a></li>';
                                            }
                                        }
                                    }
                                    ?>
                                    <li>
                                        <?php if (isset($pag) && $pag < $num_pag) {
                                            echo '<a href="mensaje/recibidos?pag=' . ($pag + 1) . '" aria-label="Siguiente">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>';
                                        } elseif (!isset($pag) && $num_pag > 1) {
                                            echo '<a href="mensaje/recibidos?pag=' . 2 . '" aria-label="Siguiente">
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
    </div>
</div>
