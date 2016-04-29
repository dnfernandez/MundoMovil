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
                                <div id="div-titTut" class="form-group">
                                    <label>T&iacute;tulo</label>
                                    <input type="text" class="form-control inp-log" name="titulo" id="titTut"
                                           onblur="valida_titulo(this.id)"
                                           value="<?php echo $datos['titulo']; ?>">

                                    <div id="help-titTut" class="help-block"></div>
                                </div>
                            </div>
                            <div id="div-clavTut" class="col-md-6">
                                <div class="form-group">
                                    <label>Palabras clave</label>
                                    <input type="text" class="form-control inp-log" name="pal_clave" id="clavTut"
                                           onblur="valida_clave(this.id)"
                                           placeholder="smartphone sony android ..."
                                           value="<?php echo $datos['pal_clave']; ?>">

                                    <div id="help-clavTut" class="help-block"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div id="div-textareaCuerpoNoticia" class="form-group">
                                    <label>Cuerpo del tutorial</label>
                                    <textarea id="textareaCuerpoNoticia" type="text" class="form-control" name="texto"
                                              placeholder=""><?php echo $datos['texto']; ?></textarea>
                                    <script type="text/javascript">
                                        CKEDITOR.replace('textareaCuerpoNoticia');
                                        CKEDITOR.config.height = '35em';
                                        CKEDITOR.instances['textareaCuerpoNoticia'].on('blur', function() {
                                            valida_texto('textareaCuerpoNoticia',1);})
                                    </script>
                                    <div id="help-textareaCuerpoNoticia" class="help-block"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer btn-form">
                        <button type="button" onclick="window.location.href='tutorial/index'" class="btn btn-default">
                            Cancelar
                        </button>
                        <button id="btnCrearTut" type="button" onclick="validaCrearTutorial([this.form.id,'titTut','clavTut','textareaCuerpoNoticia', this.id])" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
