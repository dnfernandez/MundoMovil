<?php

require_once(__DIR__ . "/../core/ValidationException.php");

class Mensaje
{
    private $id_mensaje;
    private $texto;
    private $fecha;
    private $emisor;
    private $receptor;

    /**
     * Mensaje constructor.
     * @param $id_mensaje
     * @param $texto
     * @param $fecha
     * @param $emisor
     * @param $receptor
     */
    public function __construct($id_mensaje, $texto, $fecha, $emisor, $receptor)
    {
        $this->id_mensaje = $id_mensaje;
        $this->texto = $texto;
        $this->fecha = $fecha;
        $this->emisor = $emisor;
        $this->receptor = $receptor;
    }

    /**
     * @return mixed
     */
    public function getIdMensaje()
    {
        return $this->id_mensaje;
    }

    /**
     * @param mixed $id_mensaje
     */
    public function setIdMensaje($id_mensaje)
    {
        $this->id_mensaje = $id_mensaje;
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
    public function getEmisor()
    {
        return $this->emisor;
    }

    /**
     * @param mixed $emisor
     */
    public function setEmisor($emisor)
    {
        $this->emisor = $emisor;
    }

    /**
     * @return mixed
     */
    public function getReceptor()
    {
        return $this->receptor;
    }

    /**
     * @param mixed $receptor
     */
    public function setReceptor($receptor)
    {
        $this->receptor = $receptor;
    }

    /**
     * Método para comprobar si el objeto mensaje es válido para el registro en la base de datos
     */

    public function validoParaCrear()
    {
        $errors = array();

        if (strlen($this->texto) < 1) {
            $errors["textoMen"] = "El campo texto no puede estar vacio";
        }

        if (sizeof($errors) > 0) {
            throw new ValidationException ($errors, "Mensaje no valido");
        }
    }
}