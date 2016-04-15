<?php
require_once(__DIR__ . "/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$tutorial = $view->getVariable("tutorialD");
if (!isset($tutorial)) {
    $tutorial = $view->getVariable("tutorial");
}
$usuarioActual = $view->getVariable("usuarioActual");
?>

<div class="container content">
    <div class="row">
        <div class="col-md-12 page-header">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Modificar tutorial
                    </h3>
                </div>
                <form id="formCrearTutorial" method="POST" action="tutorial/modificarTutorial">
                    <input type="hidden" name="id_tutorial" value="<?php echo $tutorial["id_tutorial"]; ?>">
                    <input type="hidden" name="id_usuario_tut" value="<?php echo $tutorial["id_usuario"]; ?>">

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>T&iacute;tulo</label>
                                    <input type="text" class="form-control inp-log" name="titulo"
                                           value="<?php echo $tutorial['titulo']; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Palabras clave</label>
                                    <input type="text" class="form-control inp-log" name="pal_clave"
                                           value="<?php echo $tutorial['pal_clave']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Cuerpo del tutorial</label>
                                    <textarea id="textareaCuerpoTutorial" type="text" class="form-control" name="texto"
                                              placeholder=""><?php echo $tutorial['texto']; ?></textarea>
                                    <script type="text/javascript">
                                        CKEDITOR.replace('textareaCuerpoTutorial');
                                        CKEDITOR.config.height = '35em';
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer btn-form">
                        <button type="button" onclick="window.location.href='tutorial/ver?id=<?php echo $tutorial["id_tutorial"];?>'" class="btn btn-default">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
