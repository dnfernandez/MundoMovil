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
                <form id="formCrearNoticia" method="POST" action="noticia/modificarNoticia" enctype="multipart/form-data">
                    <input type="hidden" name="id_noticia" value="<?php echo $noticia["id_noticia"]; ?>">
                    <input type="hidden" name="id_usuario_not" value="<?php echo $noticia["id_usuario"]; ?>">
                    <input type="hidden" name="img_noticia_ant" value="<?php echo $noticia["rutaImagen"]; ?>">

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label>T&iacute;tulo</label>
                                    <input type="text" class="form-control inp-log" name="titulo"
                                           value="<?php echo $noticia['titulo']; ?>">
                                </div>
                                <div class=" form-group">
                                    <label>Resumen</label>
                                    <textarea type="text" class="form-control"
                                              name="resumen"><?php echo $noticia['resumen']; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Palabras clave</label>
                                    <input type="text" class="form-control inp-log" name="pal_clave"
                                           value="<?php echo $noticia['pal_clave']; ?>">
                                </div>
                            </div>
                            <div class=" col-md-5 imagenReg2">
                                    <div class="form-group img-noticia">
                                        <img id="imgPerfil" src="<?php echo $noticia['rutaImagen']; ?>"
                                             alt="ImagenNoticia"
                                             class="img-rounded img-responsive">
                                    </div>
                                    <div class="form-group">
                                        <label>Imagen resumen</label>

                                        <div class="inp-file2">
                                            <input type="file" id="files" name="img_noticia">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Cuerpo de noticia</label>
                                    <textarea id="textareaCuerpoNoticia" type="text" class="form-control" name="texto"
                                              placeholder=""><?php echo $noticia['texto']; ?></textarea>
                                        <script type="text/javascript">
                                            CKEDITOR.replace('textareaCuerpoNoticia');
                                            CKEDITOR.config.height = '35em';
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer btn-form">
                            <button type="button" onclick="window.location.href='noticia/ver?id=<?php echo $noticia["id_noticia"];?>'" class="btn btn-default">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>
