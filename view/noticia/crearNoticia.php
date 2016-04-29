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
                        Crear noticia
                    </h3>
                </div>
                <form id="formCrearNoticia" method="POST" action="noticia/crearNoticia" enctype="multipart/form-data">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-7">
                                <div id="div-titNot" class="form-group">
                                    <label>T&iacute;tulo</label>
                                    <input type="text" class="form-control inp-log" name="titulo" id="titNot"
                                           onblur="valida_titulo(this.id)"
                                           value="<?php echo $datos['titulo']; ?>">

                                    <div id="help-titNot" class="help-block"></div>
                                </div>
                                <div id="div-resNot" class="form-group">
                                    <label>Resumen</label>
                                    <textarea type="text" class="form-control"
                                              name="resumen" id="resNot"
                                              onblur="valida_texto(this.id)"><?php echo $datos['resumen']; ?></textarea>
                                    <div id="help-resNot" class="help-block"></div>
                                </div>
                                <div id="div-clavNot" class="form-group">
                                    <label>Palabras clave</label>
                                    <input type="text" class="form-control inp-log" name="pal_clave"
                                           placeholder="smartphone sony android ..." id="clavNot" onblur="valida_clave(this.id)"
                                           value="<?php echo $datos['pal_clave']; ?>">

                                    <div id="help-clavNot" class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-md-5 imagenReg2">
                                <div class="form-group img-noticia">
                                    <img id="imgPerfil"
                                        <?php if (isset($datos["img_noticia"]) && $datos["img_noticia"] != ""):
                                            echo 'src="' . $datos["img_noticia"] . '" alt="ImagenNoticia"';
                                        else:
                                            echo 'src="images/notFound.jpg" alt="ImagenNoticia"';
                                        endif; ?>
                                         class="img-rounded img-responsive">
                                </div>
                                <div id="div-files" class="form-group">
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
                                              placeholder=""><?php echo htmlentities($datos['texto']); ?></textarea>
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
                        <button type="button" onclick="window.location.href='noticia/index'" class="btn btn-default">
                            Cancelar
                        </button>
                        <button id="btnCrearNot" type="button" onclick="validaCrearNoticia([this.form.id,'titNot','resNot','clavNot','textareaCuerpoNoticia','files', this.id])" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>