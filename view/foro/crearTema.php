<?php
require_once(__DIR__ . "/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$usuarioActual = $view->getVariable("usuarioActual");
$datos = $view->getVariable("datos");
$id_foro = $_GET["id"];
?>
<div class="container content">
    <div class="row">
        <div class="col-md-12 page-header">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Crear tema / pregunta
                    </h3>
                </div>
                <form id="formCrearPregunta" method="POST" action="pregunta/crearPregunta">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div id="div-titPreg" class="form-group">
                                    <label>T&iacute;tulo</label>
                                    <input type="text" class="form-control inp-log" name="titulo"
                                           value="<?php echo $datos['titulo']; ?>" id="titPreg"
                                           onblur="valida_titulo(this.id)">

                                    <div id="help-titPreg" class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="div-palPreg" class="form-group">
                                    <label>Palabras clave</label>
                                    <input type="text" class="form-control inp-log" name="pal_clave"
                                           value="<?php echo $datos['pal_clave']; ?>" id="palPreg"
                                           onblur="valida_clave(this.id)">

                                    <div id="help-palPreg" class="help-block"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div id="div-textareaCuerpoPregunta" class="form-group">
                                    <label>Cuerpo de la pregunta</label>
                                    <textarea id="textareaCuerpoPregunta" type="text" class="form-control" name="texto"
                                              placeholder=""><?php echo htmlentities($datos['texto']); ?></textarea>
                                    <script type="text/javascript">
                                        CKEDITOR.replace('textareaCuerpoPregunta');
                                        CKEDITOR.config.height = '35em';
                                        CKEDITOR.instances['textareaCuerpoPregunta'].on('blur', function () {
                                            valida_texto('textareaCuerpoPregunta', 1);
                                        })
                                    </script>
                                    <div id="help-textareaCuerpoPregunta" class="help-block"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="id_foro" value="<?php echo $id_foro; ?>">

                    <div class="panel-footer btn-form">
                        <button type="button" onclick="window.location.href='foro/ver?id=<?php echo $id_foro; ?>'"
                                class="btn btn-default">Cancelar
                        </button>
                        <button id="btnCrearPreg"
                                onclick="validaCrearPregunta([this.form.id,'titPreg','palPreg','textareaCuerpoPregunta', this.id])"
                                type="button" class="btn btn-primary">Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
