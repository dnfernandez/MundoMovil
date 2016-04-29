<?php
require_once(__DIR__ . "/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$usuarioActual = $view->getVariable("usuarioActual");
$noticia = $view->getVariable("noticiaD");
if (!isset($noticia)) {
    $noticia = $view->getVariable("noticia");
}
?>

<div class="container content">
    <div class="row">
        <div class="col-md-12 page-header">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Modificar noticia
                    </h3>
                </div>
                <form id="formModificarNoticia" method="POST" action="noticia/modificarNoticia"
                      enctype="multipart/form-data">
                    <input type="hidden" name="id_noticia" value="<?php echo $noticia["id_noticia"]; ?>">
                    <input type="hidden" name="id_usuario_not" value="<?php echo $noticia["id_usuario"]; ?>">
                    <input type="hidden" name="img_noticia_ant" value="<?php echo $noticia["rutaImagen"]; ?>">

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-7">
                                <div id="div-titNot" class="form-group">
                                    <label>T&iacute;tulo</label>
                                    <input type="text" class="form-control inp-log" name="titulo" id="titNot"
                                           onblur="valida_titulo(this.id)"
                                           value="<?php echo $noticia['titulo']; ?>">

                                    <div id="help-titNot" class="help-block"></div>
                                </div>
                                <div id="div-resNot" class="form-group">
                                    <label>Resumen</label>
                                    <textarea type="text" class="form-control"
                                              name="resumen" id="resNot"
                                              onblur="valida_texto(this.id)"><?php echo $noticia['resumen']; ?></textarea>

                                    <div id="help-resNot" class="help-block"></div>
                                </div>
                                <div id="div-clavNot" class="form-group">
                                    <label>Palabras clave</label>
                                    <input type="text" class="form-control inp-log" name="pal_clave"
                                           value="<?php echo $noticia['pal_clave']; ?>" id="clavNot"
                                           onblur="valida_clave(this.id)">
                                    <div id="help-clavNot" class="help-block"></div>
                                </div>
                            </div>
                            <div class=" col-md-5 imagenReg2">
                                <div id="div-files" class="form-group img-noticia">
                                    <img id="imgPerfil" src="<?php echo $noticia['rutaImagen']; ?>"
                                         alt="ImagenNoticia"
                                         class="img-rounded img-responsive">
                                </div>
                                <div class="form-group">
                                    <label>Imagen resumen</label>

                                    <div class="inp-file2">
                                        <input type="file" id="files" name="img_noticia" onchange="valida_imagen(this.id)">
                                        <div id="help-files" class="help-block"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div id="div-textareaCuerpoNoticia" class="form-group">
                                    <label>Cuerpo de noticia</label>
                                    <textarea id="textareaCuerpoNoticia" type="text" class="form-control" name="texto"
                                              placeholder=""><?php echo $noticia['texto']; ?></textarea>
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
                        <button type="button"
                                onclick="window.location.href='noticia/ver?id=<?php echo $noticia["id_noticia"]; ?>'"
                                class="btn btn-default">Cancelar
                        </button>
                        <button id="btnModNot" onclick="validaModificarNoticia([this.form.id,'titNot','resNot','clavNot','textareaCuerpoNoticia','files', this.id])" type="button" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
