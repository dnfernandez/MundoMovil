<?php

require_once(__DIR__ . "/../controller/BaseController.php");
require_once(__DIR__ . "/../model/Noticia.php");
require_once(__DIR__ . "/../model/NoticiaMapper.php");
require_once(__DIR__ . "/../model/ComentarioNoticia.php");
require_once(__DIR__ . "/../model/ComentarioNoticiaMapper.php");

class NoticiaController extends BaseController
{
    private $noticiaMapper;
    private $comentarioNoticiaMapper;

    /**
     * NoticiaController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->noticiaMapper = new NoticiaMapper();
        $this->comentarioNoticiaMapper = new ComentarioNoticiaMapper();
    }

    /**
     * Metodo principal del controlador Noticia. Lista las noticias por pagina
     * Si existe $_GET["filtro"]:
     *      Se obtienen las noticias filtrando por el tipo que es y se muestran.
     *      Para ello se carga el texto y el tipo de filtrado de las variables de
     *      sesion y se obtienen las noticias filtrandolas como sea necesario.
     *      Se envian las variables noticias, total(numero de noticias existentes) y filtro
     *      para indicar que la pagina renderizada a continuacion proviene del metodo filtro.
     * Si no existe:
     *      Se obtienen las noticias sin filtros y se renderiza la pagina para mostrarlas.
     */

    public function index()
    {
        if (isset($_GET["filtro"])) {

            $texto = $_SESSION["__sesion__herramienta__"]["__filtro_texto__"];
            $tipo_filtro = $_SESSION["__sesion__herramienta__"]["__filtro_tipo__"];

            if (isset($_GET["pag"])) {
                if ($tipo_filtro == "autor") {
                    $noticias = $this->noticiaMapper->listarNoticiasFiltro($_GET["pag"], $texto, null, null);
                    $total = $this->noticiaMapper->contarNoticias($texto)["total"];
                } elseif ($tipo_filtro == "contenido") {
                    $noticias = $this->noticiaMapper->listarNoticiasFiltro($_GET["pag"], null, null, $texto);
                    $total = $this->noticiaMapper->contarNoticias(null, null, $texto)["total"];
                } elseif ($tipo_filtro == "palabras") {
                    $noticias = $this->noticiaMapper->listarNoticiasFiltro($_GET["pag"], null, $texto, null);
                    $total = $this->noticiaMapper->contarNoticias(null, $texto, null)["total"];
                } else {
                    $this->view->redirect("noticia", "index");
                }
            } else {
                if ($tipo_filtro == "autor") {
                    $noticias = $this->noticiaMapper->listarNoticiasFiltro(1, $texto, null, null);
                    $total = $this->noticiaMapper->contarNoticias($texto)["total"];
                } elseif ($tipo_filtro == "contenido") {
                    $noticias = $this->noticiaMapper->listarNoticiasFiltro(1, null, null, $texto);
                    $total = $this->noticiaMapper->contarNoticias(null, null, $texto)["total"];
                } elseif ($tipo_filtro == "palabras") {
                    $noticias = $this->noticiaMapper->listarNoticiasFiltro(1, null, $texto, null);
                    $total = $this->noticiaMapper->contarNoticias(null, $texto, null)["total"];
                } else {
                    $this->view->redirect("noticia", "index");
                }
            }

            $this->view->setVariable("noticias", $noticias);
            $this->view->setVariable("total", $total);
            $this->view->setVariable("filtro", "filtro");
        } else {
            if (isset($_GET["pag"])) {
                $noticias = $this->noticiaMapper->listarNoticiasFiltro($_GET["pag"], null, null, null);
            } else {
                $noticias = $this->noticiaMapper->listarNoticiasFiltro(1, null, null, null);
            }
            $total = $this->noticiaMapper->contarNoticias()["total"];
            $this->view->setVariable("noticias", $noticias);
            $this->view->setVariable("total", $total);
        }


        $this->view->render("noticia", "principalNoticia");
    }

    /**
     * Metodo que permite ver una noticia con sus comentarios asociados.
     * A través del id de una noticia se cargan sus datos y sus comentarios.
     * A mayores se obtienen unos id secuenciales para los comentarios, es decir, los ids_com_noticia
     * se enmascaran con unos ids secuenciales que son los que se van a visualizar.
     */
    public function ver()
    {
        if (isset($_GET["id"])) {
            $id_noticia = $_GET["id"];
            if ($this->noticiaMapper->existe($id_noticia)) {
                $noticia = $this->noticiaMapper->listarNoticiaPorId($id_noticia);
                $num_comentarios = $this->comentarioNoticiaMapper->contarComentariosNoticia($id_noticia)["total"];
                $comentarios = $this->comentarioNoticiaMapper->listarComentariosPorNoticia($_GET["pag"], $id_noticia);
                $array_id = $this->comentarioNoticiaMapper->calcularIdSecuenciales($id_noticia);

                $this->view->setVariable("noticia", $noticia);
                $this->view->setVariable("comentarios", $comentarios);
                $this->view->setVariable("num_comentarios", $num_comentarios);
                $this->view->setVariable("id_comentarios", $array_id);
                $this->view->render("noticia", "verNoticia");
            } else {
                $this->view->setVariable("mensajeError", "No existe noticia con ese id", true);
                $this->view->redirect("noticia", "index");
            }
        } else {
            $this->view->redirect("noticia", "index");
        }
    }

    /**
     * Metodo que permite crear un comentario.
     * Se comprueba que exista el codigo de noticia y que haya un usuario validado.
     * Se crea el comentario y se inserta, en caso de error se muestra el error
     * correspondiente y se redirige a HTTP_REFERER
     */

    public function comentar()
    {
        if (isset($_POST["id_noticia"]) && !empty($_POST["id_noticia"])) {
            if (isset($this->usuarioActual)) {
                $id_noticia = $_POST["id_noticia"];
                $id_usuario = $this->usuarioActual->getIdUsuario();
                $texto = $_POST["texto_com"];
                $id_com_res = $_POST["idComRes"];
                $comentario = new ComentarioNoticia(null, $id_noticia, $id_usuario, $texto, null, $id_com_res);

                try {
                    $comentario->validoParaCrear();
                    $this->comentarioNoticiaMapper->insertar($comentario);
                    $this->view->setVariable("mensajeSucces", "Comentario creado correctamente", true);
                    $this->view->redirectToReferer();

                } catch (ValidationException $ex) {
                    $errores = $ex->getErrors();
                    $this->view->setVariable("errores", $errores, true);
                    $error = "No se pudo crear el comentario";
                }

            } else {
                $error = "Se necesita login para esa acci&oacute;n";
            }
        } else {
            $error = "No existe id_noticia";
        }

        $this->view->setVariable("mensajeError", $error, true);
        $this->view->redirectToReferer();

    }

    /**
     * Metodo que si existe un usuario en el sistema y es administrador o moderador
     * renderiza la vista para crear una noticia
     */

    public function crear()
    {
        if (isset($this->usuarioActual)) {
            if ($this->usuarioActual->getRol() == "administrador" || $this->usuarioActual->getRol() == "moderador") {
                $this->view->render("noticia", "crearNoticia");
            } else {
                $this->view->setVariable("mensajeError", "No tienes suficientes privilegios", true);
                $this->view->redirect("noticia", "index");
            }
        } else {
            $this->view->setVariable("mensajeError", "Se necesita login para esa acci&oacute;n", true);
            $this->view->redirect("noticia", "index");
        }
    }

    /**
     * Metodo que permite crear una noticia
     * Para ello comprueba que exista un usuario y que sea administrador o moderador
     * Luego comprueba que existan sus datos correspodientes e inserta la noticia.
     * En caso contrario muestra mensajes de error y redirige a las paginas necesarias.
     */

    public function crearNoticia()
    {
        if (isset($this->usuarioActual)) {
            if ($this->usuarioActual->getRol() == "administrador" || $this->usuarioActual->getRol() == "moderador") {
                if (isset($_FILES["img_noticia"]['name']) && $_FILES["img_noticia"]['name'] != "") {
                    $temp = $this->noticiaMapper->obtenerUltimoIdNoticia()["max_id"];
                    $temp++;
                    $formato_valido = true;

                    $cadena = basename($_FILES['img_noticia']['name']);
                    $total = strpos($cadena, ".");
                    $extensionImg = substr($cadena, $total);
                    $extensionImg = strtolower($extensionImg);
                    if (!in_array($extensionImg, [".jpg", ".png", ".jpeg", ".gif"])) {
                        $formato_valido = false;
                    } else {
                        $target_path = "img_noticia/";
                        $target_path = $target_path . "noticia" . $temp . $extensionImg;
                        move_uploaded_file($_FILES['img_noticia']['tmp_name'], $target_path);
                    }
                }

                if (!empty($_POST["titulo"]) && !empty($_POST["resumen"]) && !empty($_POST["pal_clave"]) && !empty($_POST["texto"])
                ) {
                    $titulo = $_POST["titulo"];
                    $resumen = $_POST["resumen"];
                    $pal_clave = $_POST["pal_clave"];
                    $texto = $_POST["texto"];

                    if ($formato_valido == true) {

                        $noticia = new Noticia(null, $titulo, $resumen, $pal_clave, $target_path, $texto, null, $this->usuarioActual->getIdUsuario());

                        try {
                            $noticia->validoParaCrear();
                            $this->noticiaMapper->insertar($noticia);
                            $this->view->setVariable("mensajeSucces", "Noticia creada correctamente", true);
                            $this->view->redirect("noticia", "index");
                        } catch (ValidationException $ex) {
                            $errores = $ex->getErrors();
                            $this->view->setVariable("errores", $errores, true);
                        }
                    } else {
                        $error = "Debes incluir una imagen para el resumen";
                    }
                } else {
                    $error = "No puede haber campos vac&iacute;os";
                }
                $datos = array("titulo" => $_POST["titulo"], "resumen" => $_POST["resumen"], "pal_clave" => $_POST["pal_clave"], "img_noticia" => $target_path, "texto" => $_POST["texto"]);
                $this->view->setVariable("datos", $datos, true);
                $this->view->setVariable("mensajeError", $error, true);
                $this->view->redirect("noticia", "crear");

            } else {
                $this->view->setVariable("mensajeError", "No tienes suficientes privilegios", true);
                $this->view->redirect("noticia", "index");
            }
        } else {
            $this->view->setVariable("mensajeError", "Se necesita login para esa acci&oacute;n", true);
            $this->view->redirect("noticia", "index");
        }
    }

    /**
     * Metodo que permite eliminar una noticia.
     * Para ello se comprueba que haya un usuario identificado en el sistema y
     * que sea administrador o moderador. Si es moderador debe ser el creador
     * de la noticia para poder eliminarla.
     * Si cumple esto se elimina la noticia.
     */

    public function eliminar()
    {
        if (isset($this->usuarioActual)) {
            if ($this->usuarioActual->getRol() == "administrador" || $this->usuarioActual->getRol() == "moderador") {
                if ($_POST["id_noticia"]) {
                    $id_notica = $_POST["id_noticia"];
                    $id_usuario = $_POST["id_usuario_not"];

                    if ($this->usuarioActual->getIdUsuario() == $id_usuario || $this->usuarioActual->getRol() == "administrador") {
                        $this->noticiaMapper->eliminar($id_notica);
                        $this->view->setVariable("mensajeSucces", "Noticia eliminada correctamente", true);
                        $this->view->redirect("noticia", "index");
                    } else {
                        $this->view->setVariable("mensajeError", "No puedes eliminar la noticia por no ser su creador", true);
                        $this->view->redirect("noticia", "index");
                    }
                } else {
                    $this->view->setVariable("mensajeError", "Se necesita id_noticia", true);
                    $this->view->redirect("noticia", "index");
                }
            } else {
                $this->view->setVariable("mensajeError", "No tienes suficientes privilegios", true);
                $this->view->redirect("noticia", "index");
            }
        } else {
            $this->view->setVariable("mensajeError", "Se necesita login para esa acci&oacute;n", true);
            $this->view->redirect("noticia", "index");
        }
    }


    /**
     * Metodo que si existe un usuario en el sistema y es administrador o si es moderador y creador de la noticia
     * renderiza la vista para modificar una noticia
     */

    public function modificar()
    {
        if (isset($this->usuarioActual)) {
            if ($this->usuarioActual->getRol() == "administrador" || $this->usuarioActual->getRol() == "moderador") {
                if (isset($_GET["id"])) {
                    $id_notica = $_GET["id"];
                    $id_usuario = $this->noticiaMapper->listarNoticiaPorId($id_notica)["id_usuario"];

                    if ($this->usuarioActual->getIdUsuario() == $id_usuario || $this->usuarioActual->getRol() == "administrador") {
                        $noticia = $this->noticiaMapper->listarNoticiaPorId($id_notica);
                        $this->view->setVariable("noticia", $noticia);
                        $this->view->render("noticia", "modificarNoticia");
                    } else {
                        $this->view->setVariable("mensajeError", "No puedes modificar la noticia por no ser su creador", true);
                        $this->view->redirect("noticia", "ver", "id=" . $id_notica);
                    }
                } else {
                    $this->view->setVariable("mensajeError", "Se necesita id_noticia", true);
                    $this->view->redirect("noticia", "index");
                }
            } else {
                $this->view->setVariable("mensajeError", "No tienes suficientes privilegios", true);
                $this->view->redirect("noticia", "index");
            }
        } else {
            $this->view->setVariable("mensajeError", "Se necesita login para esa acci&oacute;n", true);
            $this->view->redirect("noticia", "index");
        }
    }

    /**
     * Metodo que permite modificar una noticia
     * Para ello comprueba que exista un usuario y que sea administrador o si es moderador
     * y creador de la noticia.
     * Luego comprueba que existan sus datos correspodientes e actualiza la noticia.
     * En caso contrario muestra mensajes de error y redirige a las paginas necesarias.
     */

    public function modificarNoticia()
    {
        if (isset($this->usuarioActual)) {
            if ($this->usuarioActual->getRol() == "administrador" || $this->usuarioActual->getRol() == "moderador") {
                if (isset($_POST["id_noticia"])) {
                    $id_notica = $_POST["id_noticia"];
                    $id_usuario = $_POST["id_usuario_not"];

                    if ($this->usuarioActual->getIdUsuario() == $id_usuario || $this->usuarioActual->getRol() == "administrador") {

                        if (isset($_FILES["img_noticia"]['name']) && !empty($_FILES["img_noticia"])) {
                            $formato_valido = true;

                            $cadena = basename($_FILES['img_noticia']['name']);
                            $total = strpos($cadena, ".");
                            $extensionImg = substr($cadena, $total);
                            $extensionImg = strtolower($extensionImg);
                            if (!in_array($extensionImg, [".jpg", ".png", ".jpeg", ".gif"])) {
                                $formato_valido = false;
                            } else {
                                $target_path = "img_noticia/";
                                $target_path = $target_path . "noticia" . $id_notica . $extensionImg;
                                move_uploaded_file($_FILES['img_noticia']['tmp_name'], $target_path);
                                $this->view->setVariable("recarga", "true", true);
                            }
                        }

                        if (empty($target_path)) {
                            $target_path = $_POST["img_noticia_ant"];
                            $formato_valido = true;
                        }

                        if (!empty($_POST["titulo"]) && !empty($_POST["resumen"]) && !empty($_POST["pal_clave"]) && !empty($_POST["texto"])) {
                            $titulo = $_POST["titulo"];
                            $resumen = $_POST["resumen"];
                            $pal_clave = $_POST["pal_clave"];
                            $texto = $_POST["texto"];

                            if ($formato_valido == true) {

                                $noticia = new Noticia($id_notica, $titulo, $resumen, $pal_clave, $target_path, $texto, null, $id_usuario);

                                try {
                                    $noticia->validoParaActualizar();
                                    $this->noticiaMapper->actualizar($noticia);
                                    $this->view->setVariable("mensajeSucces", "Noticia modificada correctamente", true);
                                    $this->view->redirect("noticia", "ver", "id=" . $id_notica);
                                } catch (ValidationException $ex) {
                                    $errores = $ex->getErrors();
                                    $this->view->setVariable("errores", $errores, true);
                                }
                            } else {
                                $error = "Formato de imagen no v&aacutelida";
                            }
                        } else {
                            $error = "No puede haber campos vac&iacute;os";
                        }
                        $datos = array("id_noticia" => $id_notica, "titulo" => $_POST["titulo"], "resumen" => $_POST["resumen"],
                            "pal_clave" => $_POST["pal_clave"], "rutaImagen" => $target_path, "texto" => $_POST["texto"], "id_usuario" => $id_usuario);
                        $this->view->setVariable("noticiaD", $datos, true);
                        $this->view->setVariable("mensajeError", $error, true);
                        $this->view->redirect("noticia", "modificar", "id=" . $id_notica);


                    } else {
                        $this->view->setVariable("mensajeError", "No puedes eliminar la noticia por no ser su creador", true);
                        $this->view->redirect("noticia", "ver", "id=" . $id_notica);
                    }
                } else {
                    //$this->view->setVariable("mensajeError", "Se necesita id_noticia", true);
                    $this->view->redirect("noticia", "index");
                }
            } else {
                $this->view->setVariable("mensajeError", "No tienes suficientes privilegios", true);
                $this->view->redirect("noticia", "index");
            }
        } else {
            $this->view->setVariable("mensajeError", "Se necesita login para esa acci&oacute;n", true);
            $this->view->redirect("noticia", "index");
        }
    }

    /**
     * Metodo que permite filtrar noticias por autor, contenido o palabras clave
     *
     * Si es una nueva busqueda (existe $_POST["opciones"]=="noticia") se almacenan
     * el texto y el tipo de filtrado en variables de sesion.
     * Luego redirige a noticia/index?filtro
     */

    public function filtro()
    {
        if (isset($_POST["opciones"]) && $_POST["opciones"] == "noticia") {
            $texto = $_POST["texto"];
            $tipo_filtro = $_POST["tipo_filtro"];
            $_SESSION["__sesion__herramienta__"]["__filtro_texto__"] = $texto;
            $_SESSION["__sesion__herramienta__"]["__filtro_tipo__"] = $tipo_filtro;
        }
        if (isset($_GET["pag"])) {
            $this->view->redirect("noticia", "index", "filtro&pag=" . $_GET['pag']);
        } else {
            $this->view->redirect("noticia", "index", "filtro");
        }
    }

    /**
     * Metodo que permite eliminar un comentario
     *
     * Solo pueden eliminar un comentario los moderadores y administradores
     * en caso de que no cumpla con las reglas de participacion.
     * Entonces si es administrador o moderador el usuario validado
     * se elimina el comentario, sino se redirige a la pagina de inicio de noticias
     */

    public function eliminar_comentario()
    {
        if (isset($this->usuarioActual) && ($this->usuarioActual->getRol() == "administrador" || $this->usuarioActual->getRol() == "moderador")) {
            if (isset($_GET["id"])) {
                $this->comentarioNoticiaMapper->eliminar($_GET["id"]);
                $this->view->setVariable("mensajeSucces", "Comentario eliminado con &eacute;xito", true);
                $this->view->redirectToReferer();
            } else {
                $this->view->setVariable("mensajeError", "No existe ese comentario", true);
            }
        } else {
            $this->view->setVariable("mensajeError", "No tienes permisos para realizar esa acci&oacute;n", true);
        }
        $this->view->redirect("noticia", "index");
    }
}