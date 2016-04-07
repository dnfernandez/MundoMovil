<?php

require_once(__DIR__ . "/../controller/BaseController.php");
require_once(__DIR__ . "/../model/Usuario.php");
require_once(__DIR__ . "/../model/UsuarioMapper.php");

class UsuarioController extends BaseController
{
    private $usuarioMapper;

    /**
     * UsuarioController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->usuarioMapper = new UsuarioMapper();
    }

    public function login(){
        if(isset($_POST["email"])){
            $email = $_POST["email"];
            $contrasenha = $_POST["contrasenha"];
            if(!$this->usuarioMapper->comprobarBaneoUsuario(null,$email)){
                if(!$this->usuarioMapper->comprobarEstadoUsuario(null,$email)){
                    if($this->usuarioMapper->comprobarUsuario($email,$contrasenha)){
                        $_SESSION["usuarioActual"] = $email;
                        $this->view->redirectToReferer();
                    }else{
                        echo "Datos incorrectos";
                    }
                }else{
                    echo "Usuario todavia no activo";
                }
            }else{
                echo "Usuario baneado";
            }
        }
    }

    public function logout(){
        session_destroy();
        $this->view->redirectToReferer();
    }


}