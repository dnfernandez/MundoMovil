<?php

require_once(__DIR__ . "/../controller/BaseController.php");
require_once(__DIR__ . "/../model/Foro.php");
require_once(__DIR__ . "/../model/ForoMapper.php");
require_once(__DIR__ . "/../model/PreguntaMapper.php");

class ForoController extends BaseController
{
    private $foroMapper;
    private $preguntaMapper;

    /**
     * ForoController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->foroMapper = new ForoMapper();
        $this->preguntaMapper = new PreguntaMapper();
    }

    /**
     * Metodo principal que lista todos los foros
     */

    public function index()
    {
        $foros = $this->foroMapper->listarForos();
        $this->view->setVariable("foros", $foros);
        $this->view->render("foro", "principalForo");
    }

    /**
     * Metodo que permite ver las preguntas de un foro
     *
     * Para ello se comprueba que exista un id de un foro o un filtro.
     * Si existe filtro se obtienen las preguntas aplicando el filtro
     * existente pudiendo ser por autor, contenido o palabras.
     *
     * Si existe id de foro se obtienen las preguntas de ese foro
     *
     * En caso contrario se realizan las redirecciones necesarias y se
     * muestran los mensajes oportunos.
     */

    public function ver()
    {
        if (isset($_GET["id"]) || isset($_GET["filtro"])) {
            if (isset($_GET["filtro"])) {

                $texto = $_SESSION["__sesion__herramienta__"]["__filtro_texto__"];
                $tipo_filtro = $_SESSION["__sesion__herramienta__"]["__filtro_tipo__"];

                if (isset($_GET["pag"])) {
                    if ($tipo_filtro == "autor") {
                        $preguntas = $this->preguntaMapper->listarPreguntasFiltradas($_GET["pag"], null, null, $texto);
                        $total = $this->preguntaMapper->contarPreguntasFiltradas(null, null, $texto)["total"];
                    } elseif ($tipo_filtro == "contenido") {
                        $preguntas = $this->preguntaMapper->listarPreguntasFiltradas($_GET["pag"], $texto, null, null);
                        $total = $this->preguntaMapper->contarPreguntasFiltradas($texto, null, null)["total"];
                    } elseif ($tipo_filtro == "palabras") {
                        $preguntas = $this->preguntaMapper->listarPreguntasFiltradas($_GET["pag"], null, $texto, null);
                        $total = $this->preguntaMapper->contarPreguntasFiltradas(null, $texto, null)["total"];
                    } else {
                        $this->view->redirect("foro", "index");
                    }
                } else {
                    if ($tipo_filtro == "autor") {
                        $preguntas = $this->preguntaMapper->listarPreguntasFiltradas(1, null, null, $texto);
                        $total = $this->preguntaMapper->contarPreguntasFiltradas(null, null, $texto)["total"];
                    } elseif ($tipo_filtro == "contenido") {
                        $preguntas = $this->preguntaMapper->listarPreguntasFiltradas(1, $texto, null, null);
                        $total = $this->preguntaMapper->contarPreguntasFiltradas($texto, null, null)["total"];
                    } elseif ($tipo_filtro == "palabras") {
                        $preguntas = $this->preguntaMapper->listarPreguntasFiltradas(1, null, $texto, null);
                        $total = $this->preguntaMapper->contarPreguntasFiltradas(null, $texto, null)["total"];
                    } else {
                        $this->view->redirect("foro", "index");
                    }
                }
                $this->view->setVariable("preguntas", $preguntas);
                $this->view->setVariable("total", $total);
                $this->view->setVariable("filtro", "filtro");

            } elseif (isset($_GET["id"])) {
                $id_foro = $_GET["id"];
                if ($this->foroMapper->existe($id_foro)) {
                    if (isset($_GET["pag"])) {
                        $preguntas = $this->preguntaMapper->listarPreguntasPorForo($_GET["pag"], $id_foro);
                    } else {
                        $preguntas = $this->preguntaMapper->listarPreguntasPorForo(1, $id_foro);
                    }
                    $titulo = $this->foroMapper->obtenerTituloForo($id_foro)["titulo"];
                    $total = $this->preguntaMapper->contarPreguntasPorForo($id_foro)["total"];
                    $this->view->setVariable("preguntas", $preguntas);
                    $this->view->setVariable("total", $total);
                    $this->view->setVariable("titulo", $titulo);
                } else {
                    $this->view->setVariable("mensajeError", "No existe foro con ese id", true);
                    $this->view->redirect("foro", "index");
                }
            } else {
                $this->view->redirect("foro", "index");
            }

            $this->view->render("foro", "principalTematicaForo");

        } else {
            $this->view->setVariable("mensajeError", "Se necesita id de foro o busqueda por filtro", true);
            $this->view->redirect("foro", "index");
        }
    }

    /**
     * Metodo que permite crear un foro
     *
     * Para ello se comprueba que exista un usuario validado en el sistema
     * y que sea administrador o moderador.
     * En caso de cumplir los requisitos se crea el foro, sino, se muestran
     * los mensajes correspondientes.
     */

    public function crear()
    {
        if (isset($this->usuarioActual)) {
            if ($this->usuarioActual->getRol() == "administrador" || $this->usuarioActual->getRol() == "moderador") {
                $titulo = $_POST["titulo"];
                $resumen = $_POST["resumen"];
                if (!empty($titulo) && !empty($resumen)) {
                    $foro = new Foro(null, $titulo, $resumen, null, $this->usuarioActual->getIdUsuario());
                    try {
                        $foro->validoParaCrear();
                        $this->foroMapper->insertar($foro);
                        $this->view->setVariable("mensajeSucces", "Foro creado correctamente", true);
                        $this->view->redirect("foro", "index");
                    } catch (ValidationException $ex) {
                        $errores = $ex->getErrors();
                        $this->view->setVariable("errores", $errores, true);
                    }
                } else {
                    $error = "No puede haber campos vac&iacute;os";
                    $this->view->setVariable("mensajeError", $error, true);
                    $this->view->redirect("foro", "index");
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

    /**
     * Metodo que permite eliminar un foro
     *
     * Para ello se comprueba que exista un usuario validado en el sistema
     * y que sea administrador o moderador.
     * En caso de cumplir los requisitos se elimina el foro, sino, se muestran
     * los mensajes correspondientes.
     */

    public function eliminarForo()
    {
        if (isset($this->usuarioActual)) {
            if ($this->usuarioActual->getRol() == "administrador" || $this->usuarioActual->getRol() == "moderador") {
                $id_foro = $_POST["id_foro"];
                if (!empty($id_foro)) {
                    $this->foroMapper->eliminar($id_foro);
                    $this->view->setVariable("mensajeSucces", "Foro eliminado correctamente", true);
                    $this->view->redirect("foro", "index");
                } else {
                    $error = "Se necesita el id de foro";
                    $this->view->setVariable("mensajeError", $error, true);
                    $this->view->redirect("foro", "index");
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

    /**
     * Metodo que permite modificar un foro
     *
     * Para ello se comprueba que exista un usuario validado en el sistema
     * y que sea administrador o moderador.
     * En caso de cumplir los requisitos se modifica el foro, sino, se muestran
     * los mensajes correspondientes.
     */

    public function modificarForo()
    {
        if (isset($this->usuarioActual)) {
            if ($this->usuarioActual->getRol() == "administrador" || $this->usuarioActual->getRol() == "moderador") {
                $titulo = $_POST["titulo"];
                $resumen = $_POST["resumen"];
                $id_foro = $_POST["id_foro"];
                if (isset($id_foro)) {
                    if (!empty($titulo) && !empty($resumen)) {
                        $foro = new Foro($id_foro, $titulo, $resumen, null, $this->usuarioActual->getIdUsuario());
                        try {
                            $foro->validoParaActualizar();
                            $this->foroMapper->actualizar($foro);
                            $this->view->setVariable("mensajeSucces", "Foro actualizado correctamente", true);
                            $this->view->redirect("foro", "index");
                        } catch (ValidationException $ex) {
                            $errores = $ex->getErrors();
                            $this->view->setVariable("errores", $errores, true);
                        }
                    } else {
                        $error = "No puede haber campos vac&iacute;os";
                        $this->view->setVariable("mensajeError", $error, true);
                        $this->view->redirect("foro", "index");
                    }
                } else {
                    $error = "Se necesita id de foro";
                    $this->view->setVariable("mensajeError", $error, true);
                    $this->view->redirect("foro", "index");
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

    /**
     * Metodo que permite filtrar preguntas de foros por autor, contenido o palabras clave
     *
     * Si es una nueva busqueda (existe $_POST["opciones"]=="foro") se almacenan
     * el texto y el tipo de filtrado en variables de sesion.
     * Luego redirige a foro/index?filtro
     */

    public function filtro()
    {
        if ($_POST["opciones"] == "foro") {
            $texto = $_POST["texto"];
            $tipo_filtro = $_POST["tipo_filtro"];
            $_SESSION["__sesion__herramienta__"]["__filtro_texto__"] = $texto;
            $_SESSION["__sesion__herramienta__"]["__filtro_tipo__"] = $tipo_filtro;
        }

        if (isset($_GET["pag"])) {
            $this->view->redirect("foro", "ver", "filtro&pag=" . $_GET['pag']);
        } else {
            $this->view->redirect("foro", "ver", "filtro");
        }
    }

}