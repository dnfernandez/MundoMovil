<?php
require_once(__DIR__ . "/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$base = $view->getBase();
$usuarioActual = $view->getVariable("usuarioActual");
$mensajeSucces = $view->getVariable("mensajeSucces");
$mensajeError = $view->getVariable("mensajeError");
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

    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">
    <link href="css/estilo.css" rel="stylesheet">
    <link href="css/alertify.min.css" rel="stylesheet">
    <link href="css/default.min.css" rel="stylesheet">
    <script src="plugins/ckeditor/ckeditor.js"></script>
    <script src="js/alertify.min.js" type="text/javascript"></script>

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
                            <li><a href="#">Perfil</a></li>
                            <?php if ($usuarioActual->getRol() == "administrador" || $usuarioActual->getRol() == "moderador"): ?>
                                <li><a href="#">Administrar usuarios</a></li>
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
        <form id="idformBusq" method="POST" action="">
            <div class="panel-body">
                Introduzca los t&eacute;minos de su b&uacute;squeda y pulse en Buscar
                <input type="text" class="form-control prim-imp" name="" placeholder="...">

                <div class="row">
                    <div class="col-md-5">
                        <div class="radio">
                            <label>
                                <input type="radio" name="opciones" id="opciones_1" value="opcion_1">
                                Noticias
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="opciones" id="opciones_2" value="opcion_2">
                                Tutoriales
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="opciones" id="opciones_3" value="opcion_3">
                                Foros
                            </label>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="radio">
                            <label>
                                Filtrar por:
                                <select class="form-control select-filtro" name="">
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
                <button type="button" class="btn btn-primary" >Buscar</button>
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
                <div class="form-group">
                    <input type="text" class="form-control inp-log" name="email" placeholder="Introduce E-mail">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control inp-log" name="contrasenha"
                           placeholder="Introduce contrase&ntilde;a">
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
                        <button type="reset" class="btn btn-default" onclick="ocultar_login()">Cancelar</button>
                        <button type="submit" class="btn btn-primary" onclick="recarga2()">Entrar</button>
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
                        <div class="form-group">
                            <input type="text" class="form-control inp-log" name="nom_usuario" placeholder="Nombre usuario">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control inp-log" name="email" placeholder="E-mail">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control inp-log" name="ubicacion" placeholder="Ubicaci&oacute;n">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control inp-log" name="contrasenha" placeholder="Contrase&ntilde;a">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control inp-log" name="contrasenha2"
                                   placeholder="Repite contrase&ntilde;a">
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
                <button type="reset" class="btn btn-default" onclick="ocultar_registro()">Cancelar</button>
                <button type="submit" class="btn btn-primary">Registrar</button>
            </div>
        </form>
    </div>
</div>


<script src="js/jquery.js"></script>
<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="js/bootstrap.js"></script>
<script src="js/ie10-viewport-bug-workaround.js"></script>
<script src="js/ie-emulation-modes-warning.js"></script>
<script src="js/javascript.js" type="text/javascript"></script>

</body>
</html>