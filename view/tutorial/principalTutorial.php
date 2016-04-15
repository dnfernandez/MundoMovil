<?php
require_once(__DIR__ . "/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$usuarioActual = $view->getVariable("usuarioActual");
$tutoriales = $view->getVariable("tutoriales", array());
$total = $view->getVariable("total");
if (isset($_GET["pag"])) {
    if (is_numeric($_GET["pag"])) {
        $pag = $_GET["pag"];
    }
}
$filtro = $view->getVariable("filtro", "index");
?>

<div class="container content">
    <div class="row">
        <div class="col-md-12 ">
            <!---Si es usuario validado en el sistema--->
            <?php if (isset($usuarioActual)): ?>
                <span id="panelAdminMos">
						<a onclick="mostrar_panel_creacion()" class="btn glyphicon glyphicon-menu-down"></a>
					</span>
                <div id="panelAdmin" class="row botonesNoticia">
                    <div class="col-md-12">
                        <h4>Panel de administraci&oacute;n de tutorial</h4>
                        <a href="tutorial/crear" class="btn btn-default">Crear</a>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                                onclick="ocultar_panel_creacion()">&times;</button>
                    </div>
                </div>
            <?php endif; ?>
            <!---->
            <div class="row">
                <div class="col-md-9 bloqNot" id="bloqNot">
                    <div class="page-header encabezado">
                        TUTORIALES
                    </div>
                    <!--Repetir en bucle-->
                    <?php foreach ($tutoriales as $tutorial): ?>
                        <div class="pantTut">
                            <div class="tituloNot">
                                <h3><a href="tutorial/ver?id=<?php echo $tutorial['id_tutorial']; ?>">
                                        <?php echo htmlentities($tutorial["titulo"]); ?>
                                    </a></h3>
                            </div>
                            <div class="datosNot datosTut">
                                <div class="row">
                                    <div class="col-md-6 fec">
                                        <?php
                                        $date = date_create($tutorial["fecha"]);
                                        echo date_format($date, 'd.m.Y | H:i');
                                        ?>
                                    </div>
                                    <div class="col-md-6 aut">
                                        escrito por
                                        <?php if (!isset($usuarioActual)) {
                                            echo '<a class="enlaces-sinHref" onclick="javascript:alertify.message(\'Debes estar identificado en el sistema\')">' . htmlentities($tutorial["nom_usuario"]) . '</a>';
                                        } else {
                                            echo '<a href="usuario/ver?id=' . $tutorial["id_usuario"] . '">
                                            ' . htmlentities($tutorial["nom_usuario"]) . '
                                        </a>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="clavNot text-muted">
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
                        </div>
                    <?php endforeach; ?>
                    <!--Hasta aqui en bucle-->
                </div>
                <div class="col-md-3" id="idPanelPubli">
                    <div id="panel-inf1" class="panel-inf1 panel-fixed">
                        <div class="panel1">
                            <div class="cab-inf">
                                <h2>¿No encuentras lo que buscas?</h2>
                            </div>
                            <div class="centro-inf">
                                <h3>Preg&uacute;ntalo en nuestros foros</h3>
                            </div>
                            <div class="pie-inf">
                                <a href="foro/index">
                                    <img src="images/foro.png" alt="Foro MundoMovil">
                                </a>
                            </div>
                        </div>
                        <div class="panel2">
                            <div class="cab-inf">
                                <h2>¿Quieres estar al d&iacute;a en el mundo de la tecnolog&iacute;a?</h2>
                            </div>
                            <div class="centro-inf">
                                <h3>Consulta nuestras noticias</h3>
                            </div>
                            <div class="pie-inf">
                                <a href="noticia/index">
                                    <img src="images/noticias.png" alt="Foro MundoMovil">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-9">
            <nav class="paginacion">
                <ul class="pagination">
                    <li>
                        <?php if (isset($pag) && $pag > 1) {
                            echo '<a href = "tutorial/' . $filtro . '?pag=' . ($pag - 1) . '" aria-label = "Anterior" >
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
                                echo '<li class="active"><a href="tutorial/' . $filtro . '?pag=' . $i . '">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="tutorial/' . $filtro . '?pag=' . $i . '">' . $i . '</a></li>';
                            }
                        }
                    } elseif (isset($pag) && $pag == 2) {
                        for ($i = 1; $i <= $num_pag && $i <= 5; $i++) {
                            if ($i == 2) {
                                echo '<li class="active"><a href="tutorial/' . $filtro . '?pag=' . $i . '">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="tutorial/' . $filtro . '?pag=' . $i . '">' . $i . '</a></li>';
                            }
                        }
                    } elseif (isset($pag) && $pag > 2 && $pag <= $num_pag - 2) {
                        for ($i = $pag - 2; $i <= $num_pag && $i <= $pag + 2; $i++) {
                            if ($i <= 0) {

                            } elseif ($i == $pag) {
                                echo '<li class="active"><a href="tutorial/' . $filtro . '?pag=' . $i . '">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="tutorial/' . $filtro . '?pag=' . $i . '">' . $i . '</a></li>';
                            }
                        }
                    } elseif (isset($pag) && $pag == $num_pag - 1) {
                        for ($i = $pag - 3; $i <= $num_pag && $i <= $pag + 1; $i++) {
                            if ($i <= 0) {

                            } elseif ($i == $pag) {
                                echo '<li class="active"><a href="tutorial/' . $filtro . '?pag=' . $i . '">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="tutorial/' . $filtro . '?pag=' . $i . '">' . $i . '</a></li>';
                            }
                        }
                    } elseif (isset($pag) && $pag == $num_pag) {
                        for ($i = $pag - 4; $i <= $num_pag && $i <= $pag; $i++) {
                            if ($i <= 0) {

                            } elseif ($i == $pag) {
                                echo '<li class="active"><a href="tutorial/' . $filtro . '?pag=' . $i . '">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="tutorial/' . $filtro . '?pag=' . $i . '">' . $i . '</a></li>';
                            }
                        }
                    }
                    ?>
                    <li>
                        <?php if (isset($pag) && $pag < $num_pag) {
                            echo '<a href="tutorial/' . $filtro . '?pag=' . ($pag + 1) . '" aria-label="Siguiente">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>';
                        } elseif (!isset($pag) && $num_pag > 1) {
                            echo '<a href="tutorial/' . $filtro . '?pag=' . 2 . '" aria-label="Siguiente">
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
