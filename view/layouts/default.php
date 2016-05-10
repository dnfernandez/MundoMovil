<?php
require_once(__DIR__ . "/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$base = $view->getBase();
$usuarioActual = $view->getVariable("usuarioActual");
$mensajeSucces = $view->getVariable("mensajeSucces");
$mensajeError = $view->getVariable("mensajeError");
$notificacion = $view->getVariable("notificacion");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <base href="/<?php echo $base; ?>">

    <link rel="icon" href="images/logo.ico">

    <title>Mundo Movil</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/alertify.min.css" rel="stylesheet">
    <link href="css/default.min.css" rel="stylesheet">
    <link href="css/estilo.css" rel="stylesheet">

    <script src="plugins/ckeditor/ckeditor.js"></script>
    <script src="js/alertify.min.js" type="text/javascript"></script>
    <script src="js/md5.js" type="text/javascript"></script>

</head>

<body>
<nav class="navbar navbar-inverse navbar-fixed-top barra-nav">
    <div class="container">
        <div class="navbar-header">
            <div class="container-fluid">
                <a class="navbar-brand" rel="home" href="noticia/index" title="Mundo Movil">
                    <img class="logo img-responsive" src="images/letra.png">
                </a>
            </div>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-sec">
                <li><a href="noticia/index">NOTICIAS</a></li>
                <li><a href="tutorial/index">TUTORIALES</a></li>
                <li><a href="foro/index">FOROS</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-sec2">
                <li class="busqueda">
                    <a class="btn btn-link" onclick="mostrar_busqueda()">
                        <span class="glyphicon glyphicon-search"></span>
                    </a>
                </li>
                <?php if (!isset($usuarioActual)): ?>
                    <li>
                        <a class="btn btn-link" onclick="mostrar_login()">
                            <span class="glyphicon glyphicon-user"></span>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false">Hola, <?php echo $usuarioActual->getNomUsuario(); ?> <span
                                class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="usuario/general">Perfil</a></li>
                            <li class="dropdown dropdown-submenu contribuciones"><a href="#" class="dropdown-toggle"
                                                                                    data-toggle="dropdown">Mis
                                    contribuciones</a>
                                <ul class="dropdown-menu">
                                    <li><a href="usuario/misPreguntas">Mis preguntas</a></li>
                                    <li><a href="usuario/misTutoriales">Mis tutoriales</a></li>
                                    <?php if ($usuarioActual->getRol() == "administrador" || $usuarioActual->getRol() == "moderador"): ?>
                                        <li><a href="usuario/misNoticias">Mis noticias</a></li>
                                    <?php endif; ?>

                                </ul>
                            </li>
                            <?php if ($usuarioActual->getRol() == "administrador" || $usuarioActual->getRol() == "moderador"): ?>
                                <li><a href="usuario/administracion">Administrar usuarios</a></li>
                            <?php endif; ?>
                            <li><a href="usuario/logout" onclick="javascript:recarga2()">Cerrar sesi&oacute;n</a></li>
                        </ul>
                    </li>
                <?php endif ?>
            </ul>
        </div>
    </div>
</nav>

<!------------------------------------ Las vistas vienen aqui ------------------------------------------------->
<?= $view->getFragment(ViewManager::DEFAULT_FRAGMENT) ?>

<?php
if (isset($mensajeSucces)) {
    echo '<script>
                alertify.success("' . $mensajeSucces . '")
            </script>';
}

if (isset($mensajeError)) {
    echo '<script>
                alertify.error("' . $mensajeError . '")
            </script>';
}

if (isset($notificacion) && isset($notificacion["num_not"])) {
    if ($notificacion["num_not"] > 0) {
        echo '<script>
                alertify.notify("Tienes ' . $notificacion["num_not"] . (($notificacion["num_not"] == 1) ? " notificaci&oacute;n" : " notificaciones") . ' <br>Haz click para mostrar","custom",0, function(){mostrar_notificacion();});
            </script>';
    }
}
?>

<footer id="footer" class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p class="text-muted">
                    Traballo Fin de Grao 2016. Escola Superior de Enxeñer&iacute;a Inform&aacute;tica.
                    Diego Neira Fern&aacute;ndez
                </p>
            </div>
        </div>
    </div>

    <!--Boton de scroll to top-->

		<span id="top-link-block" class="hidden">
			<a href="#top" class="well well-sm" onclick="$('html,body').animate({scrollTop:0},'slow');return false;">
                <i class="glyphicon glyphicon-chevron-up"></i>
            </a>
		</span>

</footer>

<!--Sombra de pagina-->

<div id="fade" class="black_overlay"></div>

<!--- Formularios busqueda -->

<div id="formBusqueda" class="formulario-js">
    <div class="panel panel-default" data-lightbox="busqueda">
        <div class="panel-heading">
            <h3 class="panel-title">
                Buscar en MundoMovil
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        onclick="ocultar_busqueda()">&times;</button>
            </h3>
        </div>
        <form id="idformBusq" method="POST" onsubmit="defineActionBusqueda()">
            <div class="panel-body">
                Introduzca los t&eacute;minos de su b&uacute;squeda y pulse en Buscar
                <input type="text" class="form-control prim-imp" name="texto" placeholder="...">

                <div class="row">
                    <div class="col-md-5">
                        <div class="radio">
                            <label>
                                <input type="radio" name="opciones" id="opciones_1" checked="checked" value="noticia">
                                Noticias
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="opciones" id="opciones_2" value="tutorial">
                                Tutoriales
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="opciones" id="opciones_3" value="foro">
                                Foros
                            </label>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="radio">
                            <label>
                                Filtrar por:
                                <select class="form-control select-filtro" name="tipo_filtro">
                                    <option value="autor">autor</option>
                                    <option value="contenido">contenido</option>
                                    <option value="palabras">palabras clave</option>
                                </select>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer btn-form">
                <button type="reset" class="btn btn-default" onclick="ocultar_busqueda()">Cancelar</button>
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </form>
    </div>
</div>

<!--Formulario login-->

<div id="formLogin" class="formulario-js">
    <div class="panel panel-default" data-lightbox="busqueda">
        <div class="panel-heading">
            <h3 class="panel-title">
                Entrar en MundoMovil
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        onclick="ocultar_login()">&times;</button>
            </h3>
        </div>
        <form id="idformLogin" method="POST" action="usuario/login">
            <div class="panel-body">
                <div id="div-emailLogin" class="form-group">
                    <input type="text" class="form-control inp-log" name="email" placeholder="Introduce E-mail"
                           id="emailLogin" onblur="valida_email(this.id)">

                    <div id="help-emailLogin" class="help-block"></div>
                </div>
                <div id="div-passLogin" class="form-group">
                    <input type="password" class="form-control inp-log" name="contrasenha"
                           placeholder="Introduce contrase&ntilde;a" id="passLogin"
                           onblur="valida_contrasenha(this.id)">

                    <div id="help-passLogin" class="help-block"></div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-6">
                        <a class="btn btn-link" onclick="mostrar_registro()">
                            ¿No tienes cuenta? Reg&iacute;strate ahora
                        </a>
                    </div>
                    <div class="col-md-6 btn-form">
                        <button type="reset" class="btn btn-default"
                                onclick="limpia_form(['emailLogin','passLogin']);ocultar_login();">Cancelar
                        </button>
                        <button type="button" class="btn btn-primary"
                                onclick="recarga2(); validaLogin(['idformLogin','emailLogin','passLogin'])">Entrar
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!--Formulario registro-->

<div id="formRegistro" class="formulario-js">
    <div class="panel panel-default" data-lightbox="busqueda">
        <div class="panel-heading">
            <h3 class="panel-title">
                Registrarse en MundoMovil
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        onclick="ocultar_registro()">&times;</button>
            </h3>
        </div>
        <form id="idformRegistro" method="POST" action="usuario/registro" enctype="multipart/form-data">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-7">
                        <div id="div-nom_usuarioReg" class="form-group">
                            <input type="text" class="form-control inp-log" name="nom_usuario" id="nom_usuarioReg"
                                   onblur="valida_nom_usuario(this.id)"
                                   placeholder="Nombre usuario">

                            <div id="help-nom_usuarioReg" class="help-block"></div>
                        </div>
                        <div id="div-emailReg" class="form-group">
                            <input type="text" class="form-control inp-log" name="email" placeholder="E-mail"
                                   id="emailReg" onblur="valida_email(this.id)">

                            <div id="help-emailReg" class="help-block"></div>
                        </div>
                        <div id="div-ubicacionReg" class="form-group">
                            <input type="text" class="form-control inp-log" name="ubicacion" id="ubicacionReg"
                                   placeholder="Ubicaci&oacute;n" onblur="valida_alfanumerico(this.id)">

                            <div id="help-ubicacionReg" class="help-block"></div>
                        </div>
                        <div id="div-passReg" class="form-group">
                            <input type="password" class="form-control inp-log" name="contrasenha" id="passReg"
                                   onblur="valida_contrasenha(this.id)"
                                   placeholder="Contrase&ntilde;a">

                            <div id="help-passReg" class="help-block"></div>
                        </div>
                        <div id="div-passReg2" class="form-group">
                            <input type="password" class="form-control inp-log" name="contrasenha2" id="passReg2"
                                   onblur="valida_contrasenha(this.id)"
                                   placeholder="Repite contrase&ntilde;a">

                            <div id="help-passReg2" class="help-block"></div>
                        </div>
                    </div>
                    <div class="col-md-5 imagenReg">
                        <div class="form-group img-regis">
                            <img id="imgPerfil" src="images/perfil.jpg" alt="ImagenPerfil"
                                 class="img-rounded img-responsive">
                        </div>
                        <div class="form-group">
                            <label>Selecciona avatar</label>

                            <div class="inp-file">
                                <input type="file" id="files" name="img_perfil">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer btn-form">
                <button type="reset" class="btn btn-default"
                        onclick="limpia_form(['nom_usuarioReg','emailReg','ubicacionReg','passReg','passReg2']);ocultar_registro()">
                    Cancelar
                </button>
                <button type="button" id="btn-reg"
                        onclick="validaRegistro(['idformRegistro','nom_usuarioReg','emailReg','ubicacionReg','passReg','passReg2','btn-reg'])"
                        class="btn btn-primary">Registrar
                </button>
            </div>
        </form>
    </div>
</div>

<!--Panel notificaciones -->

<div id="panelNotificacion" class="formulario-js">
    <div class="panel panel-default" data-lightbox="busqueda">
        <div class="panel-heading">
            <h3 class="panel-title">
                Notificaciones de MundoMovil
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        onclick="ocultar_notificacion()">&times;</button>
            </h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <?php if (isset($notificacion["noticias"]) && !empty($notificacion["noticias"])): ?>
                        <div>
                            <label>Noticias</label>
                        </div>
                        <?php foreach ($notificacion["noticias"] as $noticia): ?>
                            <div>
                                <?php echo $noticia; ?>
                            </div>
                        <?php endforeach; ?>
                        <div class="sep-notificacion"></div>
                    <?php endif; ?>
                    <?php if (isset($notificacion["tutoriales"]) && !empty($notificacion["tutoriales"])): ?>
                        <div>
                            <label>Tutoriales</label>
                        </div>
                        <?php foreach ($notificacion["tutoriales"] as $tutorial): ?>
                            <div>
                                <?php echo $tutorial; ?>
                            </div>
                        <?php endforeach; ?>
                        <div class="sep-notificacion"></div>
                    <?php endif; ?>
                    <?php if (isset($notificacion["foros"]) && !empty($notificacion["foros"])): ?>
                        <div>
                            <label>Foros</label>
                        </div>
                        <?php foreach ($notificacion["foros"] as $foro): ?>
                            <div>
                                <?php echo $foro; ?>
                            </div>
                        <?php endforeach; ?>
                        <div class="sep-notificacion"></div>
                    <?php endif; ?>
                    <?php if (isset($notificacion["mensajes"])): ?>
                        <div>
                            <label>Mensajes</label>
                        </div>
                        <div>
                            <?php echo $notificacion["mensajes"] . " - "; ?>
                            <a target="_blank" href="mensaje/recibidos">Cons&uacute;ltalos aqu&iacute;</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/ie10-viewport-bug-workaround.js"></script>
<script src="js/ie-emulation-modes-warning.js"></script>
<script src="js/javascript.js" type="text/javascript"></script>
<script src="js/jquery.tablesorter.js" type="text/javascript"></script>
<script src="js/validacion.js" type="text/javascript"></script>
<script>
    $(function () {
        $('#tab_ordena').tablesorter({
            dateFormat: "ddmmyyyy",
            headers: {
                0: {sorter: "largeDate"},
                1: {sorter: "largeDate", dateFormat: "ddmmyyyy"}
            }

        });
    });
</script>

</body>
</html>