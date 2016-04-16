<?php

require_once(__DIR__ . "/../controller/BaseController.php");
require_once(__DIR__ . "/../model/Mensaje.php");
require_once(__DIR__ . "/../model/MensajeMapper.php");

class MensajeController extends BaseController
{
    private $mensajeMapper;

    /**
     * MensajeController constructor.
     */

    public function __construct()
    {
        parent::__construct();

        $this->mensajeMapper = new MensajeMapper();
    }

    /**
     * Metodo que lista los mensjes enviados por pagina
     */
    public function enviados()
    {
        if (isset($this->usuarioActual)) {
            $id_usuario = $this->usuarioActual->getIdUsuario();
            if (isset($_GET["pag"])) {
                $mensajes = $this->mensajeMapper->listarMensajesEnviados($_GET["pag"], $id_usuario);
            } else {
                $mensajes = $this->mensajeMapper->listarMensajesEnviados(1, $id_usuario);
            }
            $total = $this->mensajeMapper->contarMensajesEnviados($id_usuario)["total"];
            $this->view->setVariable("mensajes", $mensajes);
            $this->view->setVariable("total", $total);
            $this->view->render("mensaje", "perfilmEnviados");

        } else {
            $this->view->redirect("noticia", "index");
        }
    }

    /**
     * Metodo que permite eliminar un mensaje enviado
     */

    public function eliminarEnv()
    {
        if (isset($this->usuarioActual)) {
            if (isset($_POST["id_mensaje"])) {
                $id_mensaje = $_POST["id_mensaje"];
                $emisor = $_POST["id_emisor"];

                if ($emisor == $this->usuarioActual->getIdUsuario()) {
                    $this->mensajeMapper->eliminarEnviado($id_mensaje);
                    $this->view->setVariable("mensajeSucces", "Mensaje eliminado correctamente", true);
                    $this->view->redirectToReferer();
                } else {
                    $this->view->setVariable("mensajeError", "No tienes permisos para eliminar el mensaje", true);
                }
            }
        }
        $this->view->redirect("noticia", "index");
    }

    /**
     * Metodo que lista los mensjes recibidos por pagina
     */
    public function recibidos()
    {
        if (isset($this->usuarioActual)) {
            $id_usuario = $this->usuarioActual->getIdUsuario();
            if (isset($_GET["pag"])) {
                $mensajes = $this->mensajeMapper->listarMensajesRecibidos($_GET["pag"], $id_usuario);
            } else {
                $mensajes = $this->mensajeMapper->listarMensajesRecibidos(1, $id_usuario);
            }
            $total = $this->mensajeMapper->contarMensajesRecibidos($id_usuario)["total"];
            $this->view->setVariable("mensajes", $mensajes);
            $this->view->setVariable("total", $total);
            $this->view->render("mensaje", "perfilmRecibidos");

        } else {
            $this->view->redirect("noticia", "index");
        }
    }

    /**
     * Metodo que permite eliminar un mensaje recibido
     */

    public function eliminarRec()
    {
        if (isset($this->usuarioActual)) {
            if (isset($_POST["id_mensaje"])) {
                $id_mensaje = $_POST["id_mensaje"];
                $receptor = $_POST["id_receptor"];

                if ($receptor == $this->usuarioActual->getIdUsuario()) {
                    $this->mensajeMapper->eliminarRecibido($id_mensaje);
                    $this->view->setVariable("mensajeSucces", "Mensaje eliminado correctamente", true);
                    $this->view->redirectToReferer();
                } else {
                    $this->view->setVariable("mensajeError", "No tienes permisos para eliminar el mensaje", true);
                }
            }
        }
        $this->view->redirect("noticia", "index");
    }
}