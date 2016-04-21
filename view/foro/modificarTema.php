<?php
require_once(__DIR__ . "/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$usuarioActual = $view->getVariable("usuarioActual");
$pregunta = $view->getVariable("preguntaD");
if (!isset($pregunta)) {
    $pregunta = $view->getVariable("pregunta");
}
?>
<div class="container content">
    <div class="row">
        <div class="col-md-12 page-header">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Modificar tema / pregunta
                    </h3>
                </div>
                <form id="formCrearTutorial" method="POST" action="pregunta/modificarPregunta">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>T&iacute;tulo</label>
                                    <input type="text" class="form-control inp-log" name="titulo"
                                           value="<?php echo $pregunta['titulo']; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Palabras clave</label>
                                    <input type="text" class="form-control inp-log" name="pal_clave"
                                           value="<?php echo $pregunta['pal_clave']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Cuerpo de la pregunta</label>
                                    <textarea id="textareaCuerpoPregunta" type="text" class="form-control"
                                              name="texto"><?php echo $pregunta['texto']; ?></textarea>
                                    <script type="text/javascript">
                                        CKEDITOR.replace('textareaCuerpoPregunta');
                                        CKEDITOR.config.height = '35em';
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="id_foro" value="<?php echo $pregunta["id_foro"]; ?>">
                    <input type="hidden" name="id_pregunta" value="<?php echo $pregunta["id_pregunta"]; ?>">

                    <div class="panel-footer btn-form">
                        <button type="button"
                                onclick="window.location.href='pregunta/ver?id=<?php echo $pregunta["id_pregunta"]; ?>'"
                                class="btn btn-default">Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>