<?php
require_once(__DIR__ . "/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$usuarioActual = $view->getVariable("usuarioActual");
$noticias = $view->getVariable("noticias",array());
$total = $view->getVariable("total");
if (is_numeric($_GET["pag"])) {
    $pag = $_GET["pag"];
}
?>

<div class="container content">
    <div class="row">
        <div class="col-md-12">
            <?php if (isset($usuarioActual)):
                if ($usuarioActual->getRol() == "administrador" || $usuarioActual->getRol() == "moderador"): ?>
                    <span id="panelAdminMos">
                        <a onclick="mostrar_panel_creacion()" class="btn glyphicon glyphicon-menu-down"></a>
                    </span>
                    <div id="panelAdmin" class="row botonesNoticia">
                        <div class="col-md-12">
                            <h4>Panel de administraci&oacute;n de noticia</h4>
                            <a href="noticia/crear" class="btn btn-default">Crear</a>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                                    onclick="ocultar_panel_creacion()">&times;</button>
                        </div>
                    </div>
                <?php endif;
            endif; ?>
            <!---->
            <div class="row">
                <div class="col-md-9 bloqNot" id="bloqNot">
                    <div class="page-header encabezado">
                        NOTICIAS
                    </div>
                    <!--Repetir en bucle-->
                    <?php foreach ($noticias as $noticia): ?>
                        <div class="pantNot">
                            <div class="tituloNot">
                                <h3><a href="<?php echo "noticia/ver?id=" . $noticia["id_noticia"]; ?>">
                                        <?php echo $noticia["titulo"]; ?>
                                    </a></h3>
                            </div>
                            <div class="imgNot">
                                <img src="<?php echo $noticia["rutaImagen"]; ?>" alt="imagen noticia"
                                     class="img-responsive">
                            </div>
                            <div class="datosNot">
                                <div class="row">
                                    <div class="col-md-6 fec">
                                        <?php
                                        $date = date_create($noticia["fecha"]);
                                        echo date_format($date, 'd.m.Y | H:i');
                                        ?>
                                    </div>
                                    <div class="col-md-6 aut">
                                        escrito por
                                        <a href="<?php echo 'usuario/ver?id=' . $noticia["id_usuario"]; ?>">
                                            <?php echo $noticia["nom_usuario"]; ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="resNot">
                                <?php echo $noticia["resumen"]; ?>
                            </div>
                            <div class="clavNot text-muted">
                                <?php
                                $claves = explode(" ", $noticia["pal_clave"]);
                                foreach ($claves as $c) {
                                    echo '<a href="noticia/filtro_etiqueta?etiqueta=' . $c . '">
                                            ' . $c . '
                                        </a>';
                                }
                                ?>
                            </div>
                        </div>
                        <!--Hasta aqui en bucle-->
                    <?php endforeach; ?>
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
                                <h2>¿Quieres aprender nuevos trucos?</h2>
                            </div>
                            <div class="centro-inf">
                                <h3>Sigue nuestros tutoriales</h3>
                            </div>
                            <div class="pie-inf">
                                <a href="tutorial/index">
                                    <img src="images/tutoriales.png" alt="Foro MundoMovil">
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
                            echo '<a href = "noticia/index?pag=' . ($pag - 1) . '" aria-label = "Anterior" >
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
                                echo '<li class="active"><a href="noticia/index?pag=' . $i . '">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="noticia/index?pag=' . $i . '">' . $i . '</a></li>';
                            }
                        }
                    } elseif (isset($pag) && $pag == 2) {
                        for ($i = 1; $i <= $num_pag && $i <= 5; $i++) {
                            if ($i == 2) {
                                echo '<li class="active"><a href="noticia/index?pag=' . $i . '">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="noticia/index?pag=' . $i . '">' . $i . '</a></li>';
                            }
                        }
                    } elseif (isset($pag) && $pag > 2 && $pag <= $num_pag - 2) {
                        for ($i = $pag - 2; $i <= $num_pag && $i <= $pag + 2; $i++) {
                            if ($i <= 0) {

                            } elseif ($i == $pag) {
                                echo '<li class="active"><a href="noticia/index?pag=' . $i . '">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="noticia/index?pag=' . $i . '">' . $i . '</a></li>';
                            }
                        }
                    } elseif (isset($pag) && $pag == $num_pag - 1) {
                        for ($i = $pag - 3; $i <= $num_pag && $i <= $pag + 1; $i++) {
                            if ($i <= 0) {

                            } elseif ($i == $pag) {
                                echo '<li class="active"><a href="noticia/index?pag=' . $i . '">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="noticia/index?pag=' . $i . '">' . $i . '</a></li>';
                            }
                        }
                    } elseif (isset($pag) && $pag == $num_pag) {
                        for ($i = $pag - 4; $i <= $num_pag && $i <= $pag; $i++) {
                            if ($i <= 0) {

                            } elseif ($i == $pag) {
                                echo '<li class="active"><a href="noticia/index?pag=' . $i . '">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="noticia/index?pag=' . $i . '">' . $i . '</a></li>';
                            }
                        }
                    }
                    ?>
                    <li>
                        <?php if (isset($pag) && $pag < $num_pag) {
                            echo '<a href="noticia/index?pag=' . ($pag + 1) . '" aria-label="Siguiente">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>';
                        } elseif (!isset($pag) && $num_pag>1) {
                            echo '<a href="noticia/index?pag=' . 2 . '" aria-label="Siguiente">
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
