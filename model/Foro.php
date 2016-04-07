<?php

require_once(__DIR__ . "/../core/ValidationException.php");

class Foro
{
    private $id_foro;
    private $titulo;
    private $resumen;
    private $fecha;
    private $id_usuario;

    /**
     * Foro constructor.
     * @param $id_foro
     * @param $titulo
     * @param $resumen
     * @param $fecha
     * @param $id_usuario
     */
    public function __construct($id_foro, $titulo, $resumen, $fecha, $id_usuario)
    {
        $this->id_foro = $id_foro;
        $this->titulo = $titulo;
        $this->resumen = $resumen;
        $this->fecha = $fecha;
        $this->id_usuario = $id_usuario;
    }

    /**
     * @return mixed
     */
    public function getIdForo()
    {
        return $this->id_foro;
    }

    /**
     * @param mixed $id_foro
     */
    public function setIdForo($id_foro)
    {
        $this->id_foro = $id_foro;
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
    public function getResumen()
    {
        return $this->resumen;
    }

    /**
     * @param mixed $resumen
     */
    public function setResumen($resumen)
    {
        $this->resumen = $resumen;
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
     * Método para comprobar si el objeto Foro es válido para el registro en la base de datos
     */

    public function validoParaCrear()
    {
        $errors = array();

        if (strlen($this->titulo) < 1) {
            $errors["tituloF"] = "El campo titulo no puede estar vacio";
        }

        if (strlen($this->resumen) < 1) {
            $errors["resumenF"] = "El campo resumen no puede estar vacio";
        }

        if (sizeof($errors) > 0) {
            throw new ValidationException ($errors, "Foro no valido");
        }
    }

    /**
     * Metodo para comprobar si el objeto foro es valido para modificarse
     */

    public function validoParaActualizar()
    {
        $errors = array();
        if (!isset($this->id_foro)) {
            $errors["id_foro"] = "id_foro es obligatorio";
        }

        try {
            $this->validoParaCrear();
        } catch (ValidationException $ex) {
            foreach ($ex->getErrors() as $key => $error) {
                $errors[$key] = $error;
            }
        }

        if (sizeof($errors) > 0) {
            throw new ValidationException($errors, "foro no valido para actualizar");
        }

    }


}