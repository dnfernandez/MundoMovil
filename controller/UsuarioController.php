<?php

require_once(__DIR__ . "/../controller/BaseController.php");
require_once(__DIR__ . "/../model/Usuario.php");
require_once(__DIR__ . "/../model/UsuarioMapper.php");
require_once(__DIR__ . "/../core/SendMail.php");
require_once(__DIR__ . "/../model/NoticiaMapper.php");
require_once(__DIR__ . "/../model/TutorialMapper.php");
require_once(__DIR__ . "/../model/PreguntaMapper.php");
require_once(__DIR__ . "/../model/RespuestaMapper.php");

class UsuarioController extends BaseController
{
    private $usuarioMapper;
    private $noticiaMapper;
    private $tutorialMapper;
    private $preguntaMapper;
    private $respuestaMapper;

    /**
     * UsuarioController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->usuarioMapper = new UsuarioMapper();
        $this->noticiaMapper = new NoticiaMapper();
        $this->tutorialMapper = new TutorialMapper();
        $this->preguntaMapper = new PreguntaMapper();
        $this->respuestaMapper = new RespuestaMapper();
    }

    /**
     * Metodo que permite loguear a un usuario en el sistema.
     *
     * Si existe un email se comprueba y contraseña:
     * 1º. Si el usuario es un usuario que esta activado en el sistema.
     * 2º. Si el usuario no esta baneado.
     * 3º. Si los datos introducidos son correctos.
     * Si cumple estos 3 pasos se valida en el sistema y se redirige a la pagina de la que viene,
     * si dicha pagina es el login_error, se redirige a noticia/index
     * En caso de no cumplir algunos de los 3 puntos anteriores se redirige a login_error y se muestra
     * el error correspondiente.
     */

    public function login()
    {
        if (isset($_POST["email"]) && !empty($_POST["email"]) && isset($_POST["contrasenha"]) && !empty($_POST["contrasenha"])) {
            $error = null;
            $email = $_POST["email"];
            $contrasenha = $_POST["contrasenha"];
            if ($this->usuarioMapper->comprobarUsuario($email, $contrasenha)) {
                if ($this->usuarioMapper->comprobarEstadoUsuario(null, $email)) {
                    if (!$this->usuarioMapper->comprobarBaneoUsuario(null, $email)) {
                        $_SESSION["usuarioActual"] = $email;
                        $this->usuarioMapper->actualizarFechaConexion(null, $email);
                        $this->view->setVariable("mensajeSucces", "Usuario logueado correctamente", true);
                        if (strpos($_SERVER["HTTP_REFERER"], "login_error")) {
                            $this->view->redirect("noticia", "index");
                        } else {
                            $this->view->redirectToReferer();
                        }
                    } else {
                        $error = "Usuario baneado";
                    }
                } else {
                    $error = "Este usuario todav&iacute;a no esta activado";
                }
            } else {
                $error = "Login incorrecto";
            }
            $this->view->setFlash($error);
            $datos = array("email" => $email);
            $this->view->setVariable("datos", $datos, true);
            $this->view->redirect("usuario", "login_error");
        }

        $this->view->redirect("usuario", "login_error");
    }

    /**
     * Metodo que renderiza la pagina de error de login si no existe un usuario
     * validado en el sistema, en caso contrario es redirigido a noticia/index
     */

    public function login_error()
    {
        if (!isset($this->usuarioActual)) {
            $this->view->setLayout("user_fail");
            $this->view->render("usuario", "login");
        } else {
            $this->view->redirect("noticia", "index");
        }
    }

    /**
     * Metodo que permite desconectar a un usuario del sistema
     */

    public function logout()
    {
        session_destroy();
        session_start();
        $this->view->setVariable("mensajeError", "Usuario desconectado", true);
        if (!$this->view->redirectToReferer()) {
            $this->view->redirect("noticia", "index");
        }
    }

    /**
     * Metodo que permite el registro de un usuario en el sistema
     *
     * Si existe nom_usuario, email, ubicacion y contrasenhas:
     *
     * 1º Si existe imagen de perfil y si cumple con el formato adecuado
     * 2º Si el nom_usuario esta libre o ya existe un usuario en el sistema con el mismo.
     * 3º Si el email ya esta en uso por otro usuario
     * 4º Si las contraseñas son iguales
     *
     * Si esto se cumple se trata de registrar al usuario, para ello tiene que ser valido para crear.
     * Si se registra se redirige al login, en otro caso se envia a registro_error
     */

    public function registro()
    {
        if (isset($_POST["nom_usuario"]) && !empty($_POST["nom_usuario"]) && isset($_POST["email"]) && !empty($_POST["email"])
            && isset($_POST["ubicacion"]) && isset($_POST["contrasenha"]) && isset($_POST["contrasenha2"])
        ) {
            $error = null;
            $nom_usuario = $_POST["nom_usuario"];
            $email = $_POST["email"];
            $ubicacion = $_POST["ubicacion"];
            $contrasenha = $_POST["contrasenha"];
            $contrasenha2 = $_POST["contrasenha2"];

            $temp = $this->usuarioMapper->obtenerUltimoIdUsuario()["max_id"];
            $temp++;
            $formato_valido = true;

            if (isset($_FILES["img_perfil"]['name']) && $_FILES["img_perfil"]['name'] != "") {
                $cadena = basename($_FILES['img_perfil']['name']);
                $total = strpos($cadena, ".");
                $extensionImg = substr($cadena, $total);
                $extensionImg = strtolower($extensionImg);
                if (!in_array($extensionImg, [".jpg", ".png", ".jpeg", ".gif"])) {
                    $error = "Formato de imagen no v&aacute;lido";
                    $target_path = "images/perfil.jpg";
                    $formato_valido = false;
                } else {
                    $target_path = "img_perfil/";
                    $target_path = $target_path . "perfil" . $temp . $extensionImg;
                    move_uploaded_file($_FILES['img_perfil']['tmp_name'], $target_path);
                }
            } else {
                $target_path = "images/perfil.jpg";
            }

            if (!$this->usuarioMapper->existe(null, $nom_usuario)) {
                if (!$this->usuarioMapper->existe(null, null, $email)) {
                    if ($contrasenha == $contrasenha2) {
                        if ($formato_valido == true) {

                            $usuario = new Usuario(null, $nom_usuario, $email, $ubicacion, $contrasenha, $target_path, null, "0000-00-00 00:00", "usuario");

                            try {
                                $usuario->validoParaCrear();
                                $this->usuarioMapper->insertar($usuario);
                                $this->view->setVariable("mensajeRegistro", "Ha sido registrado en MundoMovil como: <strong>" . $nom_usuario . "</strong> de manera satisfactoria.<br> Le enviaremos un email, para activar su cuenta, al correo: <strong>" . $email . "</strong>.", true);
                                enviar_email($usuario);
                                $this->view->redirect("usuario", "login_error");
                            } catch (ValidationException $ex) {
                                $errores = $ex->getErrors();
                                $this->view->setVariable("errores", $errores, true);
                            }
                        }
                    } else {
                        $error = "Las contraseñas no coinciden";
                    }
                } else {
                    $error = "Ya existe un usuario con ese email";
                }
            } else {
                $error = "Ya existe un usuario con ese nombre";
            }

            $this->view->setFlash($error);
            $datos = array("nom_usuario" => $nom_usuario, "email" => $email, "ubicacion" => $ubicacion, "img_perfil" => $target_path);
            $this->view->setVariable("datos", $datos, true);
            $this->view->redirect("usuario", "registro_error");

        }
        $this->view->redirect("usuario", "registro_error");
    }

    /**
     * Metodo que renderiza la pagina de error de registro si no existe un usuario
     * validado en el sistema, en caso contrario es redirigido a noticia/index
     */

    public function registro_error()
    {
        if (!isset($this->usuarioActual)) {
            $this->view->setLayout("user_fail");
            $this->view->render("usuario", "registro");
        } else {
            $this->view->redirect("noticia", "index");
        }
    }

    /**
     * Metodo que permite activar la cuenta de un usuario
     *
     * Para ello se comprueba el codigo de validacion obtenido por $_GET y se activa a dicho
     * usuario. Se redirige a ventana de login
     */

    public function activar()
    {
        if (isset($_GET["cod_act"]) && !empty($_GET["cod_act"])) {
            $cod_act = $_GET["cod_act"];
            if ($this->usuarioMapper->comprobarCodigoValidacion($cod_act)) {
                $this->view->setVariable("mensajeSucces", "El usuario ha sido activado", true);
            } else {
                $this->view->setVariable("mensajeError", "No se ha podido activar al usuario", true);
            }
            $this->view->redirect("usuario", "login_error");
        } else {
            $this->view->redirect("noticia", "index");
        }
    }

    /**
     * Metodo que obtiene las estadísticas de un usuario y las renderiza en Perfil General
     */

    public function general()
    {
        if (isset($this->usuarioActual)) {
            $id_usuario = $this->usuarioActual->getIdUsuario();
            $num_not = $this->noticiaMapper->contarTotal($id_usuario);
            $num_tut = $this->tutorialMapper->contarTotal($id_usuario);
            $num_preg = $this->preguntaMapper->contarTotal($id_usuario);
            $num_res = $this->respuestaMapper->contarTotal($id_usuario);
            $num_pos = $this->respuestaMapper->contarPositivos($id_usuario);
            $num_neg = $this->respuestaMapper->contarNegativos($id_usuario);

            $datos = array("num_not" => $num_not, "num_tut" => $num_tut, "num_preg" => $num_preg, "num_res" => $num_res, "num_pos" => $num_pos, "num_neg" => $num_neg);
            $this->view->setVariable("datos", $datos);
            $this->view->render("usuario", "perfilGeneral");
        } else {
            $this->view->redirect("noticia", "index");
        }
    }

    /**
     * Metodo que renderiza el perfil del usuario actual
     */

    public function perfil()
    {
        if (isset($this->usuarioActual)) {
            $this->view->render("usuario", "perfilPerfil");
        } else {
            $this->view->redirect("noticia", "index");
        }
    }

    /**
     * Metodo que permite modificar los datos de un usuario, tales como
     * contraseña, ubicacion y foto de perfil
     *
     * Para ello se comprueba que exista un usuario identificado en el sistema.
     * Si la imagen de perfil se modifica se copia la nueva imagen al servidor
     * y se obtiene la nueva ruta, en caso contrario se obtiene la ruta anterior.
     *
     * Si las contraseñas estan vacías, se entiende que no se quieren modificar,
     * por lo tanto se obtiene la contraseña antigua para no modificarla.
     *
     * Finalmente se modifica al usuario con los cambios realizados, en caso de
     * fallo se muestran los errores correspondientes.
     */

    public function modificar()
    {
        if (isset($this->usuarioActual)) {
            $ubicacion = $_POST["ubicacion"];
            $contrasenha = $_POST["contrasenha"];
            $contrasenha2 = $_POST["contrasenha2"];

            $formato_valido = true;

            if (isset($_FILES["img_perfil"]['name']) && $_FILES["img_perfil"]['name'] != "") {
                $cadena = basename($_FILES['img_perfil']['name']);
                $total = strpos($cadena, ".");
                $extensionImg = substr($cadena, $total);
                $extensionImg = strtolower($extensionImg);
                if (!in_array($extensionImg, [".jpg", ".png", ".jpeg", ".gif"])) {
                    $target_path = "images/perfil.jpg";
                    $formato_valido = false;
                } else {
                    $target_path = "img_perfil/";
                    $target_path = $target_path . "perfil" . $this->usuarioActual->getIdUsuario() . $extensionImg;
                    move_uploaded_file($_FILES['img_perfil']['tmp_name'], $target_path);
                }
            }

            if ($formato_valido == false || $target_path == "") {
                $target_path = $this->usuarioActual->getAvatar();
                $formato_valido = true;
            }

            if (!empty($ubicacion)) {
                if ($contrasenha == $contrasenha2) {
                    if ($contrasenha == "") {
                        $contrasenha = $this->usuarioActual->getContrasenha();
                    }
                    if ($formato_valido == true) {

                        $usuario = new Usuario($this->usuarioActual->getIdUsuario(), $this->usuarioActual->getNomUsuario(), $this->usuarioActual->getEmail(),
                            $ubicacion, $contrasenha, $target_path, null, "0000-00-00 00:00", $this->usuarioActual->getRol());

                        try {
                            $usuario->validoParaActualizar();
                            $this->usuarioMapper->actualizar($usuario);
                            $this->view->setVariable("mensajeSucces", "Cambios guardados correctamente", true);
                            $this->view->redirect("usuario", "perfil");
                        } catch (ValidationException $ex) {
                            $errores = $ex->getErrors();
                            $this->view->setVariable("errores", $errores, true);
                        }
                    }
                } else {
                    $this->view->setVariable("mensajeError", "Las contrase&ntilde;as no coinciden", true);
                }
            } else {
                $this->view->setVariable("mensajeError", "El campo ubicaci&oacute;n no puede estar vac&iacute;o", true);
            }
            $this->view->redirect("usuario", "perfil");
        }
        $this->view->redirect("noticia", "index");
    }

    /**
     * Metodo que permite ver informacion de otros usuarios
     */

    public function ver()
    {
        $error = false;
        if (isset($this->usuarioActual)) {
            if (isset($_GET["id"]) && !empty($_GET["id"])) {
                $id_usuario = $_GET["id"];
                if ($this->usuarioMapper->existe($id_usuario)) {
                    $usuario = $this->usuarioMapper->listarUsuarioPorId($id_usuario);
                    $num_not = $this->noticiaMapper->contarTotal($id_usuario);
                    $num_tut = $this->tutorialMapper->contarTotal($id_usuario);
                    $num_preg = $this->preguntaMapper->contarTotal($id_usuario);
                    $num_res = $this->respuestaMapper->contarTotal($id_usuario);
                    $num_pos = $this->respuestaMapper->contarPositivos($id_usuario);
                    $num_neg = $this->respuestaMapper->contarNegativos($id_usuario);

                    $datos = array("num_not" => $num_not, "num_tut" => $num_tut, "num_preg" => $num_preg, "num_res" => $num_res, "num_pos" => $num_pos, "num_neg" => $num_neg);

                    $datos = array_merge($usuario, $datos);
                    $this->view->setVariable("datos", $datos);
                    $this->view->render("usuario", "perfilOtrosUsuarios");
                } else {
                    $error = "No existe un usuario con ese id";
                }
            } else {
                $error = "Se necesita id de usuario";
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