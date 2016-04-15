<?php

require_once(__DIR__ . "/../controller/BaseController.php");
require_once(__DIR__ . "/../model/Tutorial.php");
require_once(__DIR__ . "/../model/TutorialMapper.php");
require_once(__DIR__ . "/../model/ComentarioTutorial.php");
require_once(__DIR__ . "/../model/ComentarioTutorialMapper.php");

class TutorialController extends BaseController
{

    private $tutorialMapper;
    private $comentarioTutorialMapper;

    /**
     * TutorialController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->tutorialMapper = new TutorialMapper();
        $this->comentarioTutorialMapper = new ComentarioTutorialMapper();
    }

    /**
     * Metodo principal del controlador Tutorial. Lista los tutoriales por pagina
     * Si existe $_GET["filtro"]:
     *      Se obtienen los tutoriales filtrando por el tipo que es y se muestran.
     *      Para ello se carga el texto y el tipo de filtrado de las variables de
     *      sesion y se obtienen los tutoriales filtrandolos como sea necesario.
     *      Se envian las variables tutoriales, total(numero de tutoriales existentes) y filtro
     *      para indicar que la pagina renderizada a continuacion proviene del metodo filtro.
     * Si no existe:
     *      Se obtienen los tutoriales sin filtros y se renderiza la pagina para mostrarlos.
     */

    public function index()
    {
        if (isset($_GET["filtro"])) {

            $texto = $_SESSION["__sesion__herramienta__"]["__filtro_texto__"];
            $tipo_filtro = $_SESSION["__sesion__herramienta__"]["__filtro_tipo__"];

            if (isset($_GET["pag"])) {
                if ($tipo_filtro == "autor") {
                    $tutoriales = $this->tutorialMapper->listarTutorialesFiltro($_GET["pag"], $texto, null, null);
                    $total = $this->tutorialMapper->contarTutoriales($texto)["total"];
                } elseif ($tipo_filtro == "contenido") {
                    $tutoriales = $this->tutorialMapper->listarTutorialesFiltro($_GET["pag"], null, null, $texto);
                    $total = $this->tutorialMapper->contarTutoriales(null, null, $texto)["total"];
                } elseif ($tipo_filtro == "palabras") {
                    $tutoriales = $this->tutorialMapper->listarTutorialesFiltro($_GET["pag"], null, $texto, null);
                    $total = $this->tutorialMapper->contarTutoriales(null, $texto, null)["total"];
                } else {
                    $this->view->redirect("tutorial", "index");
                }
            } else {
                if ($tipo_filtro == "autor") {
                    $tutoriales = $this->tutorialMapper->listarTutorialesFiltro(1, $texto, null, null);
                    $total = $this->tutorialMapper->contarTutoriales($texto)["total"];
                } elseif ($tipo_filtro == "contenido") {
                    $tutoriales = $this->tutorialMapper->listarTutorialesFiltro(1, null, null, $texto);
                    $total = $this->tutorialMapper->contarTutoriales(null, null, $texto)["total"];
                } elseif ($tipo_filtro == "palabras") {
                    $tutoriales = $this->tutorialMapper->listarTutorialesFiltro(1, null, $texto, null);
                    $total = $this->tutorialMapper->contarTutoriales(null, $texto, null)["total"];
                } else {
                    $this->view->redirect("tutorial", "index");
                }
            }

            $this->view->setVariable("tutoriales", $tutoriales);
            $this->view->setVariable("total", $total);
            $this->view->setVariable("filtro", "filtro");
        } else {
            if (isset($_GET["pag"])) {
                $tutoriales = $this->tutorialMapper->listarTutorialesFiltro($_GET["pag"], null, null, null);
            } else {
                $tutoriales = $this->tutorialMapper->listarTutorialesFiltro(1, null, null, null);
            }
            $total = $this->tutorialMapper->contarTutoriales()["total"];
            $this->view->setVariable("tutoriales", $tutoriales);
            $this->view->setVariable("total", $total);
        }


        $this->view->render("tutorial", "principalTutorial");
    }

    /**
     * Metodo que permite filtrar tutoriales por autor, contenido o palabras clave
     *
     * Si es una nueva busqueda (existe $_POST["opciones"]=="tutorial") se almacena
     * el texto y el tipo de filtrado en variables de sesion.
     * Luego redirige a tutorial/index?filtro
     */

    public function filtro()
    {
        if ($_POST["opciones"] == "tutorial") {
            $texto = $_POST["texto"];
            $tipo_filtro = $_POST["tipo_filtro"];
            $_SESSION["__sesion__herramienta__"]["__filtro_texto__"] = $texto;
            $_SESSION["__sesion__herramienta__"]["__filtro_tipo__"] = $tipo_filtro;
        }

        $this->view->redirect("tutorial", "index", "filtro");
    }

    /**
     * Metodo que permite ver un tutorial con sus comentarios asociados.
     * A través del id de un tutorial se cargan sus datos y sus comentarios.
     * A mayores se obtienen unos id secuenciales para los comentarios, es decir, los ids_com_tutorial
     * se enmascaran con unos ids secuenciales que son los que se van a visualizar.
     */

    public function ver()
    {
        if (isset($_GET["id"])) {
            $id_tutorial = $_GET["id"];
            if ($this->tutorialMapper->existe($id_tutorial)) {
                $tutorial = $this->tutorialMapper->listarPorId($id_tutorial);
                $num_comentarios = $this->comentarioTutorialMapper->contarComentariosTutorial($id_tutorial)["total"];
                $comentarios = $this->comentarioTutorialMapper->listarComentariosPorTutorial($_GET["pag"], $id_tutorial);
                $array_id = $this->comentarioTutorialMapper->calcularIdSecuenciales($id_tutorial);

                $this->view->setVariable("tutorial", $tutorial);
                $this->view->setVariable("comentarios", $comentarios);
                $this->view->setVariable("num_comentarios", $num_comentarios);
                $this->view->setVariable("id_comentarios", $array_id);
                $this->view->render("tutorial", "verTutorial");
            } else {
                $this->view->setVariable("mensajeError", "No existe tutorial con ese id", true);
                $this->view->redirect("tutorial", "index");
            }
        } else {
            $this->view->redirect("tutorial", "index");
        }
    }

    /**
     * Metodo que permite crear un comentario.
     * Se comprueba que exista el codigo de tutorial y que haya un usuario validado.
     * Se crea el comentario y se inserta, en caso de error se muestra el error
     * correspondiente y se redirige a HTTP_REFERER
     */

    public function comentar()
    {
        if (isset($_POST["id_tutorial"]) && !empty($_POST["id_tutorial"])) {
            if (isset($this->usuarioActual)) {
                $id_tutorial = $_POST["id_tutorial"];
                $id_usuario = $this->usuarioActual->getIdUsuario();
                $texto = $_POST["texto_com"];
                $id_com_res = $_POST["idComRes"];
                $comentario = new ComentarioTutorial(null, $id_tutorial, $id_usuario, $texto, null, $id_com_res);

                try {
                    $comentario->validoParaCrear();
                    $this->comentarioTutorialMapper->insertar($comentario);
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
            $error = "No existe id_tutorial";
        }

        $this->view->setVariable("mensajeError", $error, true);
        $this->view->redirectToReferer();

    }

    /**
     * Metodo que permite eliminar un comentario
     *
     * Solo pueden eliminar un comentario los moderadores y administradores
     * en caso de que no cumpla con las reglas de participacion.
     * Entonces si es administrador o moderador el usuario validado
     * se elimina el comentario, sino se redirige a la pagina de inicio de tutoriales
     */

    public function eliminar_comentario()
    {
        if (isset($this->usuarioActual) && ($this->usuarioActual->getRol() == "administrador" || $this->usuarioActual->getRol() == "moderador")) {
            if (isset($_GET["id"])) {
                $this->comentarioTutorialMapper->eliminar($_GET["id"]);
                $this->view->setVariable("mensajeSucces", "Comentario eliminado con &eacute;xito", true);
                $this->view->redirectToReferer();
            } else {
                $this->view->setVariable("mensajeError", "No existe ese comentario", true);
            }
        } else {
            $this->view->setVariable("mensajeError", "No tienes permisos para realizar esa acci&oacute;n", true);
        }
        $this->view->redirect("tutorial", "index");
    }

    /**
     * Metodo que si existe un usuario en el sistema
     * renderiza la vista para crear un tutorial
     */

    public function crear()
    {
        if (isset($this->usuarioActual)) {
            $this->view->render("tutorial", "crearTutorial");
        } else {
            $this->view->setVariable("mensajeError", "Se necesita login para esa acci&oacute;n", true);
            $this->view->redirect("tutorial", "index");
        }
    }

    /**
     * Metodo que permite crear un tutorial
     * Para ello comprueba que exista un usuario y que sea administrador o moderador
     * Luego comprueba que existan sus datos correspodientes e inserta el tutorial.
     * En caso contrario muestra mensajes de error y redirige a las paginas necesarias.
     */

    public function crearTutorial()
    {
        if (isset($this->usuarioActual)) {

            if (!empty($_POST["titulo"]) && !empty($_POST["pal_clave"]) && !empty($_POST["texto"])
            ) {
                $titulo = $_POST["titulo"];
                $pal_clave = $_POST["pal_clave"];
                $texto = $_POST["texto"];

                $tutorial = new Tutorial(null, $titulo, $pal_clave, $texto, null, $this->usuarioActual->getIdUsuario());

                try {
                    $tutorial->validoParaCrear();
                    $this->tutorialMapper->insertar($tutorial);
                    $this->view->setVariable("mensajeSucces", "Tutorial creado correctamente", true);
                    $this->view->redirect("tutorial", "index");
                } catch (ValidationException $ex) {
                    $errores = $ex->getErrors();
                    $this->view->setVariable("errores", $errores, true);
                }

            } else {
                $error = "No puede haber campos vac&iacute;os";
            }
            $datos = array("titulo" => $_POST["titulo"], "pal_clave" => $_POST["pal_clave"], "texto" => $_POST["texto"]);
            $this->view->setVariable("datos", $datos, true);
            $this->view->setVariable("mensajeError", $error, true);
            $this->view->redirect("tutorial", "crear");

        } else {
            $this->view->setVariable("mensajeError", "Se necesita login para esa acci&oacute;n", true);
            $this->view->redirect("tutorial", "index");
        }
    }

    /**
     * Metodo que permite eliminar un tutorial.
     * Para ello se comprueba que haya un usuario identificado en el sistema y
     * que sea administrador o moderador o el creador del tutorial.
     * Si cumple esto se elimina el tutorial.
     */

    public function eliminar()
    {
        if (isset($this->usuarioActual)) {
            if ($_POST["id_tutorial"]) {
                $id_tutorial = $_POST["id_tutorial"];
                $id_usuario = $_POST["id_usuario_tut"];

                if ($this->usuarioActual->getIdUsuario() == $id_usuario || $this->usuarioActual->getRol() == "administrador" || $this->usuarioActual->getRol() == "moderador") {
                    $this->tutorialMapper->eliminar($id_tutorial);
                    $this->view->setVariable("mensajeSucces", "Tutorial eliminado correctamente", true);
                    $this->view->redirect("tutorial", "index");
                } else {
                    $this->view->setVariable("mensajeError", "No puedes eliminar el tutorial por no ser su creador", true);
                    $this->view->redirect("tutorial", "index");
                }
            } else {
                $this->view->setVariable("mensajeError", "Se necesita id_tutorial", true);
                $this->view->redirect("tutorial", "index");
            }
        } else {
            $this->view->setVariable("mensajeError", "Se necesita login para esa acci&oacute;n", true);
            $this->view->redirect("tutorial", "index");
        }
    }

    /**
     * Metodo que si existe un usuario en el sistema y es administrador o moderador o el creador del tutorial
     * renderiza la vista para modificar un tutorial
     */

    public function modificar()
    {
        if (isset($this->usuarioActual)) {
            if (isset($_GET["id"])) {
                $id_tutorial = $_GET["id"];
                $id_usuario = $this->tutorialMapper->listarPorId($id_tutorial)["id_usuario"];

                if ($this->usuarioActual->getIdUsuario() == $id_usuario || $this->usuarioActual->getRol() == "administrador" || $this->usuarioActual->getRol() == "moderador") {
                    $tutorial = $this->tutorialMapper->listarPorId($id_tutorial);
                    $this->view->setVariable("tutorial", $tutorial,true);
                    $this->view->render("tutorial", "modificarTutorial");

                } else {
                    $this->view->setVariable("mensajeError", "No puedes modificar el tutorial por no ser su creador", true);
                    $this->view->redirect("tutorial", "ver", "id=" . $id_tutorial);
                }
            } else {
                $this->view->setVariable("mensajeError", "Se necesita id de tutorial", true);
                $this->view->redirect("tutorial", "index");
            }

        } else {
            $this->view->setVariable("mensajeError", "Se necesita login para esa acci&oacute;n", true);
            $this->view->redirect("tutorial", "index");
        }
    }

    /**
     * Metodo que permite modificar un tutorial
     * Para ello comprueba que exista un usuario y que sea administrador o moderador
     * o el creador del tutorial.
     * Luego comprueba que existan sus datos correspodientes e actualiza el tutorial.
     * En caso contrario muestra mensajes de error y redirige a las paginas necesarias.
     */

    public function modificarTutorial()
    {
        if (isset($this->usuarioActual)) {
            if ($_POST["id_tutorial"]) {
                $id_tutorial = $_POST["id_tutorial"];
                $id_usuario = $_POST["id_usuario_tut"];

                if ($this->usuarioActual->getIdUsuario() == $id_usuario || $this->usuarioActual->getRol() == "administrador" || $this->usuarioActual->getRol() == "moderador") {

                    if (!empty($_POST["titulo"]) && !empty($_POST["pal_clave"]) && !empty($_POST["texto"])) {
                        $titulo = $_POST["titulo"];
                        $pal_clave = $_POST["pal_clave"];
                        $texto = $_POST["texto"];


                        $tutorial = new Tutorial($id_tutorial, $titulo, $pal_clave, $texto, null, $id_usuario);

                        try {
                            $tutorial->validoParaActualizar();
                            $this->tutorialMapper->actualizar($tutorial);
                            $this->view->setVariable("mensajeSucces", "Tutorial modificado correctamente", true);
                            $this->view->redirect("tutorial", "ver", "id=" . $id_tutorial);
                        } catch (ValidationException $ex) {
                            $errores = $ex->getErrors();
                            $this->view->setVariable("errores", $errores, true);
                        }

                    } else {
                        $error = "No puede haber campos vac&iacute;os";
                    }

                    $datos = array("id_tutorial" => $id_tutorial, "titulo" => $_POST["titulo"], "pal_clave" => $_POST["pal_clave"],
                        "texto" => $_POST["texto"], "id_usuario" => $id_usuario);
                    $this->view->setVariable("tutorialD", $datos, true);
                    $this->view->setVariable("mensajeError", $error, true);
                    $this->view->redirect("tutorial","modificar","id=".$id_tutorial);
                } else {
                    $this->view->setVariable("mensajeError", "No puedes eliminar el tutorial por no ser su creador", true);
                    $this->view->redirect("tutorial", "ver", "id=" . $id_tutorial);
                }
            } else {
                $this->view->redirect("tutorial", "index");
            }
        } else {
            $this->view->setVariable("mensajeError", "Se necesita login para esa acci&oacute;n", true);
            $this->view->redirect("tutorial", "index");
        }
    }

}