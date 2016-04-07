<?php

require_once(__DIR__ . "/../core/ValidationException.php");

class ComentarioTutorial
{

    private $id_com_tutorial;
    private $id_tutorial;
    private $id_usuario;
    private $texto;
    private $fecha;
    private $id_com_respondido;

    /**
     * ComentarioTutorial constructor.
     * @param $id_com_tutorial
     * @param $id_tutorial
     * @param $id_usuario
     * @param $texto
     * @param $fecha
     * @param $id_com_respondido
     */
    public function __construct($id_com_tutorial, $id_tutorial, $id_usuario, $texto, $fecha, $id_com_respondido)
    {
        $this->id_com_tutorial = $id_com_tutorial;
        $this->id_tutorial = $id_tutorial;
        $this->id_usuario = $id_usuario;
        $this->texto = $texto;
        $this->fecha = $fecha;
        $this->id_com_respondido = $id_com_respondido;
    }

    /**
     * @return mixed
     */
    public function getIdComTutorial()
    {
        return $this->id_com_tutorial;
    }

    /**
     * @param mixed $id_com_tutorial
     */
    public function setIdComTutorial($id_com_tutorial)
    {
        $this->id_com_tutorial = $id_com_tutorial;
    }

    /**
     * @return mixed
     */
    public function getIdTutorial()
    {
        return $this->id_tutorial;
    }

    /**
     * @param mixed $id_tutorial
     */
    public function setIdTutorial($id_tutorial)
    {
        $this->id_tutorial = $id_tutorial;
    }

    /**
     * @return mixed
     */
    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    /**
     * @param mixed $id_usuario
     */
    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    /**
     * @return mixed
     */
    public function getTexto()
    {
        return $this->texto;
    }

    /**
     * @param mixed $texto
     */
    public function setTexto($texto)
    {
        $this->texto = $texto;
    }

    /**
     * @return mixed
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param mixed $fecha
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    /**
     * @return mixed
     */
    public function getIdComRespondido()
    {
        return $this->id_com_respondido;
    }

    /**
     * @param mixed $id_com_respondido
     */
    public function setIdComRespondido($id_com_respondido)
    {
        $this->id_com_respondido = $id_com_respondido;
    }

    /**
     * Método para comprobar si el objeto ComentarioTutorial es válido para el registro en la base de datos
     */

    public function validoParaCrear()
    {
        $errors = array();

        if (strlen($this->texto) < 1) {
            $errors["textoCT"] = "El campo texto no puede estar vacio";
        }

        if (sizeof($errors) > 0) {
            throw new ValidationException ($errors, "Comentario no valido");
        }
    }
}