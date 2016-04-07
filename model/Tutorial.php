<?php

require_once(__DIR__ . "/../core/ValidationException.php");

class Tutorial
{

    private $id_tutorial;
    private $titulo;
    private $pal_clave;
    private $texto;
    private $fecha;
    private $id_usuario;

    /**
     * Tutorial constructor.
     * @param $id_tutorial
     * @param $titulo
     * @param $pal_clave
     * @param $texto
     * @param $fecha
     * @param $id_usuario
     */
    public function __construct($id_tutorial, $titulo, $pal_clave, $texto, $fecha, $id_usuario)
    {
        $this->id_tutorial = $id_tutorial;
        $this->titulo = $titulo;
        $this->pal_clave = $pal_clave;
        $this->texto = $texto;
        $this->fecha = $fecha;
        $this->id_usuario = $id_usuario;
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
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * @param mixed $titulo
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    /**
     * @return mixed
     */
    public function getPalClave()
    {
        return $this->pal_clave;
    }

    /**
     * @param mixed $pal_clave
     */
    public function setPalClave($pal_clave)
    {
        $this->pal_clave = $pal_clave;
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
     * Método para comprobar si el objeto Tutorial es válido para el registro en la base de datos
     */

    public function validoParaCrear()
    {
        $errors = array();

        if (strlen($this->titulo) < 1) {
            $errors["tituloT"] = "El campo titulo no puede estar vacio";
        }

        if (strlen($this->pal_clave) < 1) {
            $errors["pal_clavT"] = "El campo palabras clave no puede estar vacio";
        }

        if (strlen($this->texto) < 1) {
            $errors["textoT"] = "El campo texto no puede estar vacio";
        }

        if (sizeof($errors) > 0) {
            throw new ValidationException ($errors, "Tutorial no valido");
        }
    }

    /**
     * Metodo para comprobar si el objeto Tutorial es valido para modificarse
     */

    public function validoParaActualizar()
    {
        $errors = array();
        if (!isset($this->id_tutorial)) {
            $errors["id_tutorial"] = "id_tutorial es obligatorio";
        }

        try {
            $this->validoParaCrear();
        } catch (ValidationException $ex) {
            foreach ($ex->getErrors() as $key => $error) {
                $errors[$key] = $error;
            }
        }

        if (sizeof($errors) > 0) {
            throw new ValidationException($errors, "tutorial no valido para actualizar");
        }

    }
}