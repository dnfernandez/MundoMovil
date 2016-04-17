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

    /**
     * Metodo que permite enviar un mensaje
     */

    public function enviar()
    {
        $error = false;
        if (isset($this->usuarioActual)) {
            if (!empty($_POST["id_usuario_dest"])) {
                if (!empty($_POST["texto"])) {
                    $destinatario = $_POST["id_usuario_dest"];
                    $texto = $_POST["texto"];
                    $emisor = $this->usuarioActual->getIdUsuario();

                    $mensaje = new Mensaje(null, $texto, null, $emisor, $destinatario);

                    try {
                        $mensaje->validoParaCrear();
                        $this->mensajeMapper->insertar($mensaje);
                        $this->view->setVariable("mensajeSucces", "Mensaje enviado correctamente", true);
                        $this->view->redirectToReferer();
                    } catch (ValidationException $ex) {
                        $errores = $ex->getErrors();
                        $this->view->setVariable("errores", $errores, true);
                    }

                } else {
                    $error = "El texto no puede estar vac&iacute;o";
                }
            } else {
                $error = "Se id de usuario destinatario";
            }
        } else {
            $error = "Se necesita estar validado en el sistema para esta acci&oacute;n";
        }

        if ($error != false) {
            $this->view->setVariable("mensajeError", $error, true);
            if (!$this->view->redirectToReferer()) {
                $this->view->redirect("noticia", "index");
            }
        }
    }

    /**
     * Metodo que permite ver un mensaje enviado
     */

    public function enviado()
    {
        $error = false;
        if (isset($this->usuarioActual)) {
            $id_usuario = $this->usuarioActual->getIdUsuario();
            if (isset($_GET["id"])) {
                $id_mensaje = $_GET["id"];
                $mensaje = $this->mensajeMapper->listarMensajeEnviado($id_mensaje);
                if ($mensaje != null) {
                    $emisor = $mensaje["emisor"];
                    if ($emisor == $id_usuario) {
                        $this->view->setVariable("mensaje", $mensaje);
                        $this->view->render("mensaje", "perfilVerMenEnv");
                    } else {
                        $error = "No tienes permiso para ver ese mensaje";
                    }
                } else {
                    $error = "No existe un mensaje con ese id";
                }
            } else {
                $error = "Se necesita id de mensaje";
            }
        } else {
            $error = "Se necesita estar validado en el sistema para esta acci&oacute;n";
        }

        if ($error != false) {
            $this->view->setVariable("mensajeError", $error, true);
            if (!$this->view->redirectToReferer()) {
                $this->view->redirect("noticia", "index");
            }
        }
    }

    /**
     * Metodo que permite ver un mensaje recibido
     */

    public function recibido()
    {
        $error = false;
        if (isset($this->usuarioActual)) {
            $id_usuario = $this->usuarioActual->getIdUsuario();
            if (isset($_GET["id"])) {
                $id_mensaje = $_GET["id"];
                $mensaje = $this->mensajeMapper->listarMensajeRecibido($id_mensaje);
                if ($mensaje != null) {
                    $receptor = $mensaje["receptor"];
                    if ($receptor == $id_usuario) {
                        $this->view->setVariable("mensaje", $mensaje);
                        $this->view->render("mensaje", "perfilVerMenRec");
                    } else {
                        $error = "No tienes permiso para ver ese mensaje";
                    }
                } else {
                    $error = "No existe un mensaje con ese id";
                }
            } else {
                $error = "Se necesita id de mensaje";
            }
        } else {
            $error = "Se necesita estar validado en el sistema para esta acci&oacute;n";
        }

        if ($error != false) {
            $this->view->setVariable("mensajeError", $error, true);
            if (!$this->view->redirectToReferer()) {
                $this->view->redirect("noticia", "index");
            }
        }
    }
}