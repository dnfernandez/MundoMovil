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
                                <div id="div-titTut" class="form-group">
                                    <label>T&iacute;tulo</label>
                                    <input type="text" class="form-control inp-log" name="titulo" id="titTut"
                                           onblur="valida_titulo(this.id)"
                                           value="<?php echo htmlentities($tutorial['titulo']); ?>">

                                    <div id="help-titTut" class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="div-clavTut" class="form-group">
                                    <label>Palabras clave</label>
                                    <input type="text" class="form-control inp-log" name="pal_clave" id="clavTut"
                                           onblur="valida_clave(this.id)"
                                           value="<?php echo htmlentities($tutorial['pal_clave']); ?>">
                                    <div id="help-clavTut" class="help-block"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div id="div-textareaCuerpoTutorial" class="form-group">
                                    <label>Cuerpo del tutorial</label>
                                    <textarea id="textareaCuerpoTutorial" type="text" class="form-control" name="texto"
                                              placeholder=""><?php echo htmlentities($tutorial['texto']); ?></textarea>
                                    <script type="text/javascript">
                                        CKEDITOR.replace('textareaCuerpoTutorial');
                                        CKEDITOR.config.height = '35em';
                                        CKEDITOR.instances['textareaCuerpoTutorial'].on('blur', function() {
                                            valida_texto('textareaCuerpoTutorial',1);})
                                    </script>
                                    <div id="help-textareaCuerpoTutorial" class="help-block"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer btn-form">
                        <button type="button"
                                onclick="window.location.href='tutorial/ver?id=<?php echo $tutorial["id_tutorial"]; ?>'"
                                class="btn btn-default">Cancelar
                        </button>
                        <button id="btnModTut" type="button" onclick="validaModificarTutorial([this.form.id,'titTut','clavTut','textareaCuerpoTutorial', this.id])" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
