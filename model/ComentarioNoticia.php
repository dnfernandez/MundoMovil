<?php

require_once(__DIR__ . "/../core/ValidationException.php");

class ComentarioNoticia
{
    private $id_com_noticia;
    private $id_noticia;
    private $id_usuario;
    private $texto;
    private $fecha;
    private $id_com_respondido;

    /**
     * ComentarioNoticia constructor.
     * @param $id_com_noticia
     * @param $id_noticia
     * @param $id_usuario
     * @param $texto
     * @param $fecha
     * @param $id_com_respondido
     */
    public function __construct($id_com_noticia = NULL, $id_noticia = NULL, $id_usuario = NULL, $texto = NULL, $fecha = NULL, $id_com_respondido = NULL)
    {
        $this->id_com_noticia = $id_com_noticia;
        $this->id_noticia = $id_noticia;
        $this->id_usuario = $id_usuario;
        $this->texto = $texto;
        $this->fecha = $fecha;
        $this->id_com_respondido = $id_com_respondido;
    }

    /**
     * @return mixed
     */
    public function getIdComNoticia()
    {
        return $this->id_com_noticia;
    }

    /**
     * @param mixed $id_com_noticia
     */
    public function setIdComNoticia($id_com_noticia)
    {
        $this->id_com_noticia = $id_com_noticia;
    }


    /**
     * @return mixed
     */
    public function getIdNoticia()
    {
        return $this->id_noticia;
    }

    /**
     * @param mixed $id_noticia
     */
    public function setIdNoticia($id_noticia)
    {
        $this->id_noticia = $id_noticia;
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
     * Método para comprobar si el objeto ComentarioNoticia es válido para el registro en la base de datos
     */

    public function validoParaCrear()
    {
        $errors = array();

        if (strlen($this->texto) < 1) {
            $errors["textoCN"] = "El campo texto no puede estar vacio";
        }

        if (sizeof($errors) > 0) {
            throw new ValidationException ($errors, "Comentario no valido");
        }
    }
}