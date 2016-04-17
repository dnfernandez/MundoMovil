<?php

require_once(__DIR__ . "/../core/ViewManager.php");
require_once(__DIR__ . "/../model/Usuario.php");
require_once(__DIR__ . "/../model/UsuarioMapper.php");


/**
 * Class BaseController
 *
 * Implementa un super controlador basico para los controladores de
 * la aplicacion.
 * Basicamente, proporciona algunos "protected" atributos y variables
 * de las vistas.
 */
class BaseController
{
    protected $view;
    protected $usuarioActual;

    private $usuarioMapper;


    public function __construct()
    {
        $this->view = ViewManager::getInstance();
        $this->usuarioMapper = new UsuarioMapper();

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION["usuarioActual"])) {
            $email = $_SESSION["usuarioActual"];
            $user = $this->usuarioMapper->listarUsuarioConcreto(null, $email);
            $this->usuarioActual = new Usuario($user["id_usuario"], $user["nom_usuario"], $user["email"], $user["ubicacion"], $user["contrasenha"],
                $user["avatar"], $user["fecha_reg"], $user["fecha_conex"], $user["rol"]);
            $this->view->setVariable("usuarioActual", $this->usuarioActual);
        }
    }


}