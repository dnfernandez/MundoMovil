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
     */
    public function index()
    {
        if (isset($_GET["pag"])) {
            $noticias = $this->noticiaMapper->listarNoticiasFiltro($_GET["pag"], null, null, null);
        } else {
            $noticias = $this->noticiaMapper->listarNoticiasFiltro(1, null, null, null);
        }
        $total = $this->noticiaMapper->contarNoticias()["total"];
        $this->view->setVariable("noticias", $noticias);
        $this->view->setVariable("total", $total);
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
     *
     */

    public function crearNoticia()
    {
        if (isset($this->usuarioActual)) {
            if ($this->usuarioActual->getRol() == "administrador" || $this->usuarioActual->getRol() == "moderador") {
                if (!empty($_POST["titulo"]) && !empty($_POST["resumen"]) && !empty($_POST["pal_clave"]) && !empty($_POST["texto"])
                    && isset($_FILES["img_noticia"]['name']) && $_FILES["img_noticia"]['name'] != ""
                ) {
                    $titulo = $_POST["titulo"];
                    $resumen = $_POST["resumen"];
                    $pal_clave = $_POST["pal_clave"];
                    $texto = $_POST["texto"];

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
                            $this->view->setVariable("mensajeError", "No valida", true);
                            $this->view->redirect("noticia", "crear");
                        }
                    }
                } else {
                    $this->view->setVariable("mensajeError", "No puede haber campos en va&iacute;os", true);
                    $this->view->redirect("noticia", "crear");
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


}