<?php

require_once(__DIR__ . "/../controller/BaseController.php");
require_once(__DIR__ . "/../model/Respuesta.php");
require_once(__DIR__ . "/../model/RespuestaMapper.php");

class RespuestaController extends BaseController
{
    private $respuestaMapper;

    /**
     * RespuestaController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->respuestaMapper = new RespuestaMapper();
    }

    /**
     * Metodo que permite crear una respuesta a una pregunta o tema
     *
     * Es necesario que exista un usuario identificado en el sistema
     * y que se le pase por POST el id pregunta.
     *
     * Si cumple esto se crea la respuesta en caso contrario se redirige
     * y se muestra error correspondiente
     */

    public function crearRespuesta()
    {
        if (isset($this->usuarioActual)) {
            if (isset($_POST["id_pregunta"])) {
                $id_pregunta = $_POST["id_pregunta"];
                $id_foro = $_POST["id_foro"];
                $texto = $_POST["texto"];
                $id_usuario = $this->usuarioActual->getIdUsuario();

                if (!empty($texto)) {
                    $respuesta = new Respuesta(null, $id_foro, $id_pregunta, $texto, null, 0, 0, $id_usuario);

                    try {
                        $respuesta->validoParaCrear();
                        $this->respuestaMapper->insertar($respuesta);
                        $this->view->setVariable("mensajeSucces", "Respuesta creada correctamente", true);
                        $this->view->redirectToReferer();
                    } catch (ValidationException $ex) {
                        $errores = $ex->getErrors();
                        $this->view->setVariable("errores", $errores, true);
                    }
                } else {
                    $this->view->setVariable("mensajeError", "No puede haber campos vac&iacute;os", true);
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
        } else {
            $this->view->setVariable("mensajeError", "Se necesita login para esta acci&oacute;n", true);
            if (!$this->view->redirectToReferer()) {
                $this->view->redirect("foro", "index");
            }
        }
    }

    /**
     * Metodo que permite votar a un usuario una respuesta
     * positiva o negativamente
     */

    public function votar()
    {
        if (isset($this->usuarioActual)) {
            if (isset($_POST["tipo_voto"]) && isset($_POST["id_respuesta"])) {
                $tipo_voto = $_POST["tipo_voto"];
                $id_respuesta = $_POST["id_respuesta"];
                $id_usuario = $this->usuarioActual->getIdUsuario();
                try {
                    if ($tipo_voto == "positivo") {
                        $this->respuestaMapper->votarRespuestas($id_respuesta, $id_usuario, 1);
                    } elseif ($tipo_voto == "negativo") {
                        $this->respuestaMapper->votarRespuestas($id_respuesta, $id_usuario, 0);
                    }
                    $this->view->setVariable("mensajeSucces","Voto enviado correctamente",true);
                    $this->view->redirectToReferer();
                } catch (ValidationException $ex) {
                    $this->view->redirectToReferer();
                    $errores = $ex->getErrors();
                    $this->view->setVariable("errores", $errores, true);
                }
            } else {
                $this->view->setVariable("mensajeError", "Se necesita id de respuesta y tipo de voto", true);
                if (!$this->view->redirectToReferer()) {
                    $this->view->redirect("foro", "index");
                }
            }
        } else {
            $this->view->setVariable("mensajeError", "Se necesita login para esta acci&oacute;n", true);
            if (!$this->view->redirectToReferer()) {
                $this->view->redirect("foro", "index");
            }
        }
    }

    /**
     * Metodo que permite eliminar una respuesta de una pregunta
     *
     * Solo pueden eliminar una respuesta los moderadores y administradores
     * en caso de que no cumpla con las reglas de participacion.
     * Entonces si es administrador o moderador el usuario validado
     * se elimina la respuesta, sino se redirige a la pagina de inicio de noticias
     */

    public function eliminar_respuesta()
    {
        if (isset($this->usuarioActual) && ($this->usuarioActual->getRol() == "administrador" || $this->usuarioActual->getRol() == "moderador")) {
            if (isset($_GET["id"])) {
                $this->respuestaMapper->eliminar($_GET["id"]);
                $this->view->setVariable("mensajeSucces", "Respuesta eliminada con &eacute;xito", true);
                $this->view->redirectToReferer();
            } else {
                $this->view->setVariable("mensajeError", "No existe esa respuesta", true);
            }
        } else {
            $this->view->setVariable("mensajeError", "No tienes permisos para realizar esa acci&oacute;n", true);
        }
        $this->view->redirect("noticia", "index");
    }

}