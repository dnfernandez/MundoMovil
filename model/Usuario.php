<?php

require_once(__DIR__ . "/../core/ValidationException.php");

class Usuario
{
    private $id_usuario;
    private $nom_usuario;
    private $email;
    private $ubicacion;
    private $contrasenha;
    private $avatar;
    private $fecha_Reg;
    private $fecha_Conex;
    private $rol;

    /**
     * Usuario constructor.
     * @param $id_usuario
     * @param $nom_usuario
     * @param $email
     * @param $ubicacion
     * @param $contrasenha
     * @param $avatar
     * @param $fecha_Reg
     * @param $fecha_Conex
     * @param $rol
     */
    public function __construct($id_usuario = NULL, $nom_usuario = NULL, $email = NULL, $ubicacion = NULL, $contrasenha = NULL, $avatar = NULL, $fecha_Reg = NULL, $fecha_Conex = NULL, $rol = NULL)
    {
        $this->id_usuario = $id_usuario;
        $this->nom_usuario = $nom_usuario;
        $this->email = $email;
        $this->ubicacion = $ubicacion;
        $this->contrasenha = $contrasenha;
        $this->avatar = $avatar;
        $this->fecha_Reg = $fecha_Reg;
        $this->fecha_Conex = $fecha_Conex;
        $this->rol = $rol;
    }

    /**
     * @return null
     */
    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    /**
     * @param null $id_usuario
     */
    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    /**
     * @return null
     */
    public function getNomUsuario()
    {
        return $this->nom_usuario;
    }

    /**
     * @param null $nom_usuario
     */
    public function setNomUsuario($nom_usuario)
    {
        $this->nom_usuario = $nom_usuario;
    }

    /**
     * @return null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param null $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return null
     */
    public function getUbicacion()
    {
        return $this->ubicacion;
    }

    /**
     * @param null $ubicacion
     */
    public function setUbicacion($ubicacion)
    {
        $this->ubicacion = $ubicacion;
    }

    /**
     * @return null
     */
    public function getContrasenha()
    {
        return $this->contrasenha;
    }

    /**
     * @param null $contrasenha
     */
    public function setContrasenha($contrasenha)
    {
        $this->contrasenha = $contrasenha;
    }

    /**
     * @return null
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param null $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * @return null
     */
    public function getFechaReg()
    {
        return $this->fecha_Reg;
    }

    /**
     * @param null $fecha_Reg
     */
    public function setFechaReg($fecha_Reg)
    {
        $this->fecha_Reg = $fecha_Reg;
    }

    /**
     * @return null
     */
    public function getFechaConex()
    {
        return $this->fecha_Conex;
    }

    /**
     * @param null $fecha_Conex
     */
    public function setFechaConex($fecha_Conex)
    {
        $this->fecha_Conex = $fecha_Conex;
    }

    /**
     * @return null
     */
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * @param null $rol
     */
    public function setRol($rol)
    {
        $this->rol = $rol;
    }


    /**
     * Método para comprobar si el objeto usuario es válido para el registro en la base de datos
     */

    public function validoParaCrear()
    {
        $errors = array();

        if (strlen($this->nom_usuario) < 1) {
            $errors["nomUsuario"] = "El campo nombre usuario no puede estar vacio";
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = "El email no es v&aacute;lido";
        }

        if (strlen($this->ubicacion) < 1) {
            $errors["ubicacion"] = "El campo ubicacion no puede estar vacio";
        }

        if (strlen($this->contrasenha) < 5) {
            $errors["contrasenha"] = "La contrase&ntilde;a no puede ser inferior a 5 caracteres";
        }

        if (strpos($this->avatar, $this->id_usuario . ".") == false) {
            $errors["avatar"] = "El campo avatar no puede estar vacio";
        }

        if (sizeof($errors) > 0) {
            throw new ValidationException ($errors, "Usuario no valido");
        }
    }

    /**
     * Metodo para comprobar si el objeto usuario es valido para modificarse
     */

    public function validoParaActualizar()
    {
        $errors = array();
        if (!isset($this->id_usuario)) {
            $errors["id_usuario"] = "id_usuario es obligatorio";
        }

        try {
            $this->validoParaCrear();
        } catch (ValidationException $ex) {
            foreach ($ex->getErrors() as $key => $error) {
                $errors[$key] = $error;
            }
        }

        if (sizeof($errors) > 0) {
            throw new ValidationException($errors, "Usuario no valido para actualizar");
        }

    }


}