<?php
require_once(__DIR__ . "/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$usuarioActual = $view->getVariable("usuarioActual");
$datos = $view->getVariable("datos");
?>
<div class="container content">
    <div class="row">
        <div class="col-md-12 page-header">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Crear tutorial
                    </h3>
                </div>
                <form id="formCrearTutorial" method="POST" action="tutorial/crearTutorial">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>T&iacute;tulo</label>
                                    <input type="text" class="form-control inp-log" name="titulo"
                                           value="<?php echo $datos['titulo']; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Palabras clave</label>
                                    <input type="text" class="form-control inp-log" name="pal_clave"
                                           placeholder="smartphone sony android ..."
                                           value="<?php echo $datos['pal_clave']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Cuerpo del tutorial</label>
                                    <textarea id="textareaCuerpoNoticia" type="text" class="form-control" name="texto"
                                              placeholder=""><?php echo $datos['texto']; ?></textarea>
                                    <script type="text/javascript">
                                        CKEDITOR.replace('textareaCuerpoNoticia');
                                        CKEDITOR.config.height = '35em';
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer btn-form">
                        <button type="button" onclick="window.location.href='tutorial/index'" class="btn btn-default">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
