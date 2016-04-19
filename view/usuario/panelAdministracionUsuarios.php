<?php
require_once(__DIR__ . "/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$usuarioActual = $view->getVariable("usuarioActual");
$usuarios = $view->getVariable("usuarios", array());
$total = $view->getVariable("total");
if (isset($_GET["pag"])) {
    if (is_numeric($_GET["pag"])) {
        $pag = $_GET["pag"];
    }
}
$filtro = $view->getVariable("filtro", "administracion");
?>
<div class="container content">
    <div class="row">
        <div class="col-md-12 ">
            <div class="page-header encabezado">
                Panel de Administraci&oacute;n de Usuarios
            </div>
            <div class="row">
                <div class="col-md-5 filtrar">
                    <form id="filtroUsuarios" action="usuario/filtro" method="POST">
                        <input type="text" class="form-control prim-imp" name="texto" placeholder="...">
                        <input type="hidden" name="tipo" value="usuario">

                        <div class="radio">
                            <label>
                                <input type="radio" name="tipo_filtro" checked="checked" id="opc_1" value="nom_usuario">
                                Nombre Usuario
                            </label>
                            <label>
                                <input type="radio" name="tipo_filtro" id="opc_2" value="email">
                                E-mail
                            </label>
                        </div>
                        <input type="submit" class="btn btn-default" value="Filtrar">
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                Usuarios del sistema - <?php echo $total . " usuarios"; ?>
                            </h3>
                        </div>
                        <div class="panel-body panelMensajes">
                            <table id="tab_ordena" class="tabla table tablaMen">
                                <thead class="textoTabla tituloMen">
                                <tr>
                                    <th class="primEl col-md-2 vertAlign">Nombre usuario</th>
                                    <th class="col-md-2 vertAlign">E-mail</th>
                                    <th class="col-md-1 vertAlign">Estado</th>
                                    <th class="col-md-1 vertAlign">Baneado?</th>
                                    <th class="col-md-1 vertAlign">Acci&oacute;n</th>
                                    <th class="col-md-1 vertAlign">Tipo Usuario</th>
                                    <?php if ($usuarioActual->getRol() == "administrador"): ?>
                                        <th class="col-md-1 vertAlign">Modificar Tipo</th>
                                        <th class="col-md-1 vertAlign">Eliminar</th>
                                    <?php endif; ?>
                                </tr>
                                </thead>
                                <tbody class="textoTabla">
                                <!--bucle-->
                                <?php foreach ($usuarios as $usuario): ?>
                                    <tr>
                                        <td class="menMensaje primEl col-md-2 vertAlign">
                                            <a href="usuario/ver?id=<?php echo $usuario["id_usuario"]; ?>">
                                                <?php echo htmlentities($usuario["nom_usuario"]); ?>
                                            </a>
                                        </td>
                                        <td class="col-md-2 vertAlign">
                                            <?php echo htmlentities($usuario["email"]); ?>
                                        </td>
                                        <td class="col-md-1 vertAlign">
                                            <?php
                                            if ($usuario["estado"] == 1) {
                                                echo "Activo";
                                            } else {
                                                echo "Inactivo";
                                            }
                                            ?>
                                        </td>
                                        <td class="col-md-1 vertAlign">
                                            <?php
                                            if ($usuario["baneado"] == 1) {
                                                echo "S&iacute;";
                                            } else {
                                                echo "No";
                                            }
                                            ?>
                                        </td>
                                        <td class="col-md-1 vertAlign">
                                            <?php if ($usuarioActual->getRol() == "moderador" && $usuario["rol"] == "usuario"): ?>
                                                <form id="banearUsuario<?php echo $usuario['id_usuario']; ?>"
                                                      method="post" action="usuario/banearUsuario">
                                                    <a
                                                        onclick="javascript:alertify.confirm('¿Est\u00E1 seguro de querer banear/desbanear al usuario a <?php echo $usuario["nom_usuario"]; ?>?').autoCancel(10).set('title','MundoMovil')
                                                            .set('oncancel', function(closeEvent){ recarga2();location.reload();})
                                                            .set('onok', function(closeEvent){ document.getElementById('banearUsuario<?php echo $usuario['id_usuario']; ?>').submit(); recarga2();})
                                                            ;"
                                                        class="btn btn-default">Ban/Desban
                                                    </a>
                                                    <input type="hidden" name="id_usuario"
                                                           value="<?php echo $usuario["id_usuario"]; ?>">
                                                </form>
                                            <?php elseif ($usuarioActual->getRol() == "administrador" && ($usuario["rol"] == "usuario" || $usuario["rol"] == "moderador")): ?>
                                                <form id="banearUsuario<?php echo $usuario['id_usuario']; ?>"
                                                      method="post" action="usuario/banearUsuario">
                                                    <a
                                                        onclick="javascript:alertify.confirm('¿Est\u00E1 seguro de querer banear/desbanear al usuario a <?php echo $usuario["nom_usuario"]; ?>?').autoCancel(10).set('title','MundoMovil')
                                                            .set('oncancel', function(closeEvent){ recarga2();location.reload();})
                                                            .set('onok', function(closeEvent){ document.getElementById('banearUsuario<?php echo $usuario['id_usuario']; ?>').submit(); recarga2();})
                                                            ;"
                                                        class="btn btn-default">Ban/Desban
                                                    </a>
                                                    <input type="hidden" name="id_usuario"
                                                           value="<?php echo $usuario["id_usuario"]; ?>">
                                                </form>
                                            <?php else:
                                                echo "Sin permisos";
                                            endif; ?>
                                        </td>
                                        <td class="col-md-1 vertAlign">
                                            <span class="<?php if ($usuario["rol"] == 'administrador') {
                                                echo 'admin-estilo';
                                            } elseif ($usuario['rol'] == 'moderador') {
                                                echo 'moderador-estilo';
                                            } ?>">
                                                <?php echo ucwords($usuario["rol"]); ?>
                                            </span>
                                        </td>
                                        <?php if ($usuarioActual->getRol() == "administrador"):
                                            if ($usuario["rol"] != "administrador"):?>
                                                <td class="col-md-1 vertAlign">
                                                    <form id="modificarUsuario<?php echo $usuario['id_usuario']; ?>"
                                                          method="post" action="usuario/modificarRolUsuario">
                                                        <a
                                                            onclick="javascript:alertify.confirm('¿Est\u00E1 seguro de querer modificar el tipo de usuario a <?php echo $usuario["nom_usuario"]; ?>?').autoCancel(10).set('title','MundoMovil')
                                                                .set('oncancel', function(closeEvent){ recarga2();location.reload();})
                                                                .set('onok', function(closeEvent){ document.getElementById('modificarUsuario<?php echo $usuario['id_usuario']; ?>').submit(); recarga2();})
                                                                ;"
                                                            class="btn btn-warning a-btn">Modificar
                                                        </a>
                                                        <input type="hidden" name="id_usuario"
                                                               value="<?php echo $usuario["id_usuario"]; ?>">
                                                    </form>
                                                </td>
                                                <td class="col-md-1 vertAlign">
                                                    <form id="eliminarUsuario<?php echo $usuario['id_usuario']; ?>"
                                                          method="post" action="usuario/eliminarUsuario">
                                                        <a
                                                            onclick="javascript:alertify.confirm('¿Est\u00E1 seguro de querer eliminar a <?php echo $usuario["nom_usuario"]; ?>?').autoCancel(10).set('title','MundoMovil')
                                                                .set('onok', function(closeEvent){ document.getElementById('eliminarUsuario<?php echo $usuario['id_usuario']; ?>').submit();recarga2();})
                                                                .set('oncancel', function(closeEvent){ recarga2();location.reload();});"
                                                            class="btn btn-danger a-btn">Eliminar
                                                        </a>
                                                        <input type="hidden" name="id_usuario"
                                                               value="<?php echo $usuario["id_usuario"]; ?>">
                                                    </form>
                                                </td>
                                            <?php else: ?>
                                                <td class="col-md-1 vertAlign">
                                                    Sin permisos
                                                </td>
                                                <td class="col-md-1 vertAlign">
                                                    Sin permisos
                                                </td>
                                            <?php endif;
                                        endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                                <!---->
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
    <div class="row pasa_pag">
        <div class="col-md-12">
            <nav class="paginacion">
                <ul class="pagination">
                    <li>
                        <?php if (isset($pag) && $pag > 1) {
                            echo '<a href = "administracion/' . $filtro . '?pag=' . ($pag - 1) . '" aria-label = "Anterior" >
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
                    $num_pag = ceil($total / 40);
                    if (!isset($pag) || $pag == 1) {
                        for ($i = 1; $i <= $num_pag && $i <= 5; $i++) {
                            if ($i == 1) {
                                echo '<li class="active"><a href="usuario/' . $filtro . '?pag=' . $i . '">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="usuario/' . $filtro . '?pag=' . $i . '">' . $i . '</a></li>';
                            }
                        }
                    } elseif (isset($pag) && $pag == 2) {
                        for ($i = 1; $i <= $num_pag && $i <= 5; $i++) {
                            if ($i == 2) {
                                echo '<li class="active"><a href="usuario/' . $filtro . '?pag=' . $i . '">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="usuario/' . $filtro . '?pag=' . $i . '">' . $i . '</a></li>';
                            }
                        }
                    } elseif (isset($pag) && $pag > 2 && $pag <= $num_pag - 2) {
                        for ($i = $pag - 2; $i <= $num_pag && $i <= $pag + 2; $i++) {
                            if ($i <= 0) {

                            } elseif ($i == $pag) {
                                echo '<li class="active"><a href="usuario/' . $filtro . '?pag=' . $i . '">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="usuario/' . $filtro . '?pag=' . $i . '">' . $i . '</a></li>';
                            }
                        }
                    } elseif (isset($pag) && $pag == $num_pag - 1) {
                        for ($i = $pag - 3; $i <= $num_pag && $i <= $pag + 1; $i++) {
                            if ($i <= 0) {

                            } elseif ($i == $pag) {
                                echo '<li class="active"><a href="usuario/' . $filtro . '?pag=' . $i . '">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="usuario/' . $filtro . '?pag=' . $i . '">' . $i . '</a></li>';
                            }
                        }
                    } elseif (isset($pag) && $pag == $num_pag) {
                        for ($i = $pag - 4; $i <= $num_pag && $i <= $pag; $i++) {
                            if ($i <= 0) {

                            } elseif ($i == $pag) {
                                echo '<li class="active"><a href="usuario/' . $filtro . '?pag=' . $i . '">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="usuario/' . $filtro . '?pag=' . $i . '">' . $i . '</a></li>';
                            }
                        }
                    }
                    ?>
                    <li>
                        <?php if (isset($pag) && $pag < $num_pag) {
                            echo '<a href="usuario/' . $filtro . '?pag=' . ($pag + 1) . '" aria-label="Siguiente">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>';
                        } elseif (!isset($pag) && $num_pag > 1) {
                            echo '<a href="usuario/' . $filtro . '?pag=' . 2 . '" aria-label="Siguiente">
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