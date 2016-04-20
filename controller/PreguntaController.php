<?php

require_once(__DIR__ . "/../controller/BaseController.php");
require_once(__DIR__ . "/../model/Pregunta.php");
require_once(__DIR__ . "/../model/PreguntaMapper.php");

class PreguntaController extends BaseController
{
    private $preguntaMapper;

    /**
     * PreguntaController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->preguntaMapper = new PreguntaMapper();
    }

    public function eliminarPregunta()
    {
        if (isset($this->usuarioActual)) {
            if ($this->usuarioActual->getRol() == "administrador" || $this->usuarioActual->getRol() == "moderador"
                || $this->usuarioActual->getIdUsuario() == $_POST["id_pregunta_usuario"]
            ) {
                if(isset($_POST["id_pregunta"])){
                    $id_pregunta = $_POST["id_pregunta"];
                    $this->preguntaMapper->eliminar($id_pregunta);
                    $this->view->setVariable("mensajeSucces", "Pregunta eliminada correctamente", true);
                    $this->view->redirectToReferer();
                }else{
                    $error = "Es necesario el id de pregunta";
                    $this->view->setVariable("mensajeError", $error, true);
                    $this->view->redirectToReferer();
                }
            } else {
                $error = "No tienes suficientes permisos para esta acci&oacute;n";
                $this->view->setVariable("mensajeError", $error, true);
                $this->view->redirect("foro", "index");
            }
        } else {
            $error = "Se necesita estar validado en el sistema para esta acci&oacute;n";
            $this->view->setVariable("mensajeError", $error, true);
            $this->view->redirect("foro", "index");
        }
    }


}