<?php

require_once(__DIR__ . "/../controller/BaseController.php");
require_once(__DIR__ . "/../model/Pregunta.php");
require_once(__DIR__ . "/../model/PreguntaMapper.php");
require_once(__DIR__ . "/../model/ForoMapper.php");
require_once(__DIR__ . "/../model/RespuestaMapper.php");

class PreguntaController extends BaseController
{
    private $preguntaMapper;
    private $foroMapper;
    private $respuestaMapper;

    /**
     * PreguntaController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->preguntaMapper = new PreguntaMapper();
        $this->foroMapper = new ForoMapper();
        $this->respuestaMapper = new RespuestaMapper();
    }

    /**
     * Metodo que permite eliminar una pregunta/tema
     *
     * Para ello se comprueba que exista un usuario identificado en el sistema
     * y que sea administrador, moderador o el usuario creador de la pregunta/tema
     *
     * En caso afirmativo se elimina la pregunta correspondiente,
     * en caso contrario se redirige y se muestran los mensajes de error
     * que sean oportunos.
     */
    public function eliminarPregunta()
    {
        if (isset($this->usuarioActual)) {
            if (isset($_POST["id_pregunta"])) {
                $id_pregunta = $_POST["id_pregunta"];
                $id_usuario = $this->preguntaMapper->listarPreguntaPorId($id_pregunta)["id_usuario"];
                if ($this->usuarioActual->getRol() == "administrador" || $this->usuarioActual->getRol() == "moderador"
                    || $this->usuarioActual->getIdUsuario() == $id_usuario
                ) {
                    $this->preguntaMapper->eliminar($id_pregunta);
                    $this->view->setVariable("mensajeSucces", "Pregunta eliminada correctamente", true);
                    if (isset($_POST["id_foro"])) {
                        $this->view->redirect("foro", "ver", "id=" . $_POST["id_foro"]);
                    } else {
                        $this->view->redirectToReferer();
                    }

                } else {
                    $error = "No tienes suficientes permisos para esta acci&oacute;n";
                    $this->view->setVariable("mensajeError", $error, true);
                    $this->view->redirect("foro", "index");
                }
            } else {
                $error = "Es necesario el id de pregunta";
                $this->view->setVariable("mensajeError", $error, true);
                $this->view->redirectToReferer();
            }
        } else {
            $error = "Se necesita estar validado en el sistema para esta acci&oacute;n";
            $this->view->setVariable("mensajeError", $error, true);
            $this->view->redirect("foro", "index");
        }
    }

    /**
     * Metodo que permite cargar la vista para crear un tema o pregunta
     * de un foro.
     *
     * Es necesario que exista un usuario identificado ne el sistema
     * y el id de un foro pasado por get.
     */
    public function crear()
    {
        if (isset($this->usuarioActual)) {
            if (isset($_GET["id"])) {
                if ($this->foroMapper->existe($_GET["id"])) {
                    $this->view->render("foro", "crearTema");
                } else {
                    $this->view->setVariable("mensajeError", "No existe ese id de foro", true);
                    $this->view->redirect("foro", "index");
                }
            } else {
                $this->view->setVariable("mensajeError", "Se necesita el id de un foro", true);
                if (!$this->view->redirectToReferer()) {
                    $this->view->redirect("foro", "index");
                }
            }
        } else {
            $this->view->setVariable("mensajeError", "Debes estar indentificado en el sistema para realizar esa acci&oacute;n", true);
            if (!$this->view->redirectToReferer()) {
                $this->view->redirect("foro", "index");
            }
        }
    }

    /**
     * Metodo que permite crear una pregunta o tema
     * Para ello comprueba que exista un usuario validado en el sistema
     * Luego comprueba que existan sus datos correspodientes e inserta la pregunta.
     * En caso contrario muestra mensajes de error y redirige a las paginas necesarias.
     */

    public function crearPregunta()
    {
        if (isset($this->usuarioActual)) {
            $id_foro = $_POST["id_foro"];
            if ($this->foroMapper->existe($id_foro)) {
                $titulo = $_POST["titulo"];
                $palabras = $_POST["pal_clave"];
                $texto = $_POST["texto"];
                if (!empty($titulo) && !empty($palabras) && !empty($texto)) {
                    $pregunta = new Pregunta(null, $id_foro, $titulo, $palabras, $texto, null, $this->usuarioActual->getIdUsuario());

                    try {
                        $pregunta->validoParaCrear();
                        $this->preguntaMapper->insertar($pregunta);
                        $this->view->setVariable("mensajeSucces", "Pregunta/tema creado correctamente", true);
                        $this->view->redirect("foro", "ver", "id=" . $id_foro);
                    } catch (ValidationException $ex) {
                        $errores = $ex->getErrors();
                        $this->view->setVariable("errores", $errores, true);
                    }
                } else {
                    $this->view->setVariable("mensajeError", "No puede haber campos vac&iacute;os", true);
                }
                $datos = array("titulo" => $titulo, "pal_clave" => $palabras, "texto" => $texto);
                $this->view->setVariable("datos", $datos, true);
                $this->view->redirect("pregunta", "crear", "id=" . $id_foro);
            } else {
                $this->view->setVariable("mensajeError", "No existe un foro con ese id", true);
                $this->view->redirect("foro", "index");
            }
        } else {
            $this->view->setVariable("mensajeError", "Debes estar indentificado en el sistema para realizar esa acci&oacute;n", true);
            if (!$this->view->redirectToReferer()) {
                $this->view->redirect("foro", "index");
            }
        }
    }

    /**
     * Metodo que permite cargar la vista para modificar un tema o pregunta
     * de un foro.
     *
     * Es necesario que exista un usuario identificado ne el sistema
     * y el id de un tema pasado por get.
     */
    public function modificar()
    {
        if (isset($this->usuarioActual)) {
            if (isset($_GET["id"])) {
                if ($this->preguntaMapper->existe($_GET["id"])) {
                    $pregunta = $this->preguntaMapper->listarPreguntaPorId($_GET["id"]);
                    if ($pregunta["id_usuario"] == $this->usuarioActual->getIdUsuario() || $this->usuarioActual->getRol() == "administrador" || $this->usuarioActual->getRol() == "moderador") {
                        $this->view->setVariable("pregunta", $pregunta);
                        $this->view->render("foro", "modificarTema");
                    } else {
                        $this->view->setVariable("mensajeError", "No tienes permisos para realizar esa acci&oacute;n", true);
                        $this->view->redirect("foro", "index");
                    }
                } else {
                    $this->view->setVariable("mensajeError", "No existe ese tema / pregunta con ese id", true);
                    $this->view->redirect("foro", "index");
                }
            } else {
                $this->view->setVariable("mensajeError", "Se necesita el id de un tema", true);
                if (!$this->view->redirectToReferer()) {
                    $this->view->redirect("foro", "index");
                }
            }
        } else {
            $this->view->setVariable("mensajeError", "Debes estar indentificado en el sistema para realizar esa acci&oacute;n", true);
            if (!$this->view->redirectToReferer()) {
                $this->view->redirect("foro", "index");
            }
        }
    }

    /**
     * Metodo que permite modificar una pregunta o tema
     * Para ello comprueba que exista un usuario validado en el sistema
     * Luego comprueba que existan sus datos correspodientes e inserta la pregunta.
     * En caso contrario muestra mensajes de error y redirige a las paginas necesarias.
     */

    public function modificarPregunta()
    {
        if (isset($this->usuarioActual)) {
            $id_pregunta = $_POST["id_pregunta"];
            if ($this->preguntaMapper->existe($id_pregunta)) {
                $id_foro = $_POST["id_foro"];
                $titulo = $_POST["titulo"];
                $palabras = $_POST["pal_clave"];
                $texto = $_POST["texto"];
                if (!empty($titulo) && !empty($palabras) && !empty($texto)) {
                    $pregunta = new Pregunta($id_pregunta, $id_foro, $titulo, $palabras, $texto, null, $this->usuarioActual->getIdUsuario());

                    try {
                        $pregunta->validoParaActualizar();
                        $this->preguntaMapper->actualizar($pregunta);
                        $this->view->setVariable("mensajeSucces", "Pregunta/tema actualizada correctamente", true);
                        $this->view->redirect("pregunta", "ver", "id=" . $id_pregunta);
                    } catch (ValidationException $ex) {
                        $errores = $ex->getErrors();
                        $this->view->setVariable("errores", $errores, true);
                    }
                } else {
                    $this->view->setVariable("mensajeError", "No puede haber campos vac&iacute;os ", true);
                }
                $pregunta = array("titulo" => $titulo, "pal_clave" => $palabras, "texto" => $texto, "id_pregunta" => $id_pregunta, "id_foro" => $id_foro);
                $this->view->setVariable("preguntaD", $pregunta, true);
                $this->view->redirect("pregunta", "modificar", "id=" . $id_pregunta);
            } else {
                $this->view->setVariable("mensajeError", "No existe un tema con ese id", true);
                $this->view->redirect("foro", "index");
            }
        } else {
            $this->view->setVariable("mensajeError", "Debes estar indentificado en el sistema para realizar esa acci&oacute;n", true);
            if (!$this->view->redirectToReferer()) {
                $this->view->redirect("foro", "index");
            }
        }
    }

    /**
     * Metodo que permite cargar los datos para visualizar una pregunta o tema
     * y sus respuestas asociadas.
     */

    public function ver()
    {
        if (isset($_GET["id"])) {
            $id_pregunta = $_GET["id"];
            if ($this->preguntaMapper->existe($id_pregunta)) {

                if (isset($this->usuarioActual)) {
                    if (isset($_GET["pag"])) {
                        $respuestas = $this->respuestaMapper->listarRespuestasUsuario($_GET["pag"], $id_pregunta, $this->usuarioActual->getIdUsuario());
                    } else {
                        $respuestas = $this->respuestaMapper->listarRespuestasUsuario(1, $id_pregunta, $this->usuarioActual->getIdUsuario());
                    }
                } else {
                    if (isset($_GET["pag"])) {
                        $respuestas = $this->respuestaMapper->listarRespuestasUsuario($_GET["pag"], $id_pregunta);
                    } else {
                        $respuestas = $this->respuestaMapper->listarRespuestasUsuario(1, $id_pregunta);
                    }
                }

                $pregunta = $this->preguntaMapper->listarPreguntaPorId($id_pregunta);
                $total = $this->respuestaMapper->contarRespuestas($id_pregunta)["total"];

                $this->view->setVariable("pregunta", $pregunta);
                $this->view->setVariable("respuestas", $respuestas);
                $this->view->setVariable("total", $total);
                $this->view->render("foro", "verTema");

            } else {
                $this->view->setVariable("mensajeError", "No existe foro con ese id", true);
                if (!$this->view->redirectToReferer()) {
                    $this->view->redirect("foro", "index");
                }
            }
        } else {
            $this->view->setVariable("mensajeError", "Se necesita id de pregunta", true);
            if (!$this->view->redirectToReferer()) {
                $this->view->redirect("foro", "index");
            }
        }

    }

}