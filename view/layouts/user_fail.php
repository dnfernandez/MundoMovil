<?php
require_once(__DIR__ . "/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$base = $view->getBase();
$usuarioActual = $view->getVariable("usuarioActual");
$mensajeSucces = $view->getVariable("mensajeSucces");
$mensajeRegistro = $view->getVariable("mensajeRegistro");
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

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/alertify.min.css" rel="stylesheet">
    <link href="css/default.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/estilo.css" rel="stylesheet">


    <script src="plugins/ckeditor/ckeditor.js"></script>
    <script src="js/alertify.min.js" type="text/javascript"></script>
    <script src="js/md5.js" type="text/javascript"></script>

</head>

<body onload="url_referer()">
<div class="container content2">
    <div class="row">
        <div class="col-md-12">
            <!------------------------------------ Las vistas vienen aqui ------------------------------------------------->
            <?= $view->getFragment(ViewManager::DEFAULT_FRAGMENT) ?>
            <?php
            if (isset($mensajeSucces)) {
                echo '<script>
               alertify.success("' . $mensajeSucces . '");
            </script>';
            }

            if (isset($mensajeRegistro)) {
                echo '<script>
               alertify.alert("MundoMovil","' . $mensajeRegistro . '");
            </script>';
            }

            if (isset($mensajeError)) {
                echo '<script>
               alertify.error("' . $mensajeError . '");
            </script>';
            }
            ?>

        </div>
    </div>
</div>


<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/ie10-viewport-bug-workaround.js"></script>
<script src="js/ie-emulation-modes-warning.js"></script>
<script src="js/javascript.js" type="text/javascript"></script>
<script src="js/validacion.js" type="text/javascript"></script>

</body>
</html>