<?php

require_once(__DIR__."/../core/ValidationException.php");

class Respuesta
{
    private $id_respuesta;
    private $id_foro;
    private $id_pregunta;
    private $texto;
    private $fecha;
    private $like_pos;
    private $likes_neg;
    private $id_usuario;

    /**
     * Respuesta constructor.
     * @param $id_respuesta
     * @param $id_foro
     * @param $id_pregunta
     * @param $texto
     * @param $fecha
     * @param $like_pos
     * @param $likes_neg
     * @param $id_usuario
     */
    public function __construct($id_respuesta, $id_foro, $id_pregunta, $texto, $fecha, $like_pos, $likes_neg, $id_usuario)
    {
        $this->id_respuesta = $id_respuesta;
        $this->id_foro = $id_foro;
        $this->id_pregunta = $id_pregunta;
        $this->texto = $texto;
        $this->fecha = $fecha;
        $this->like_pos = $like_pos;
        $this->likes_neg = $likes_neg;
        $this->id_usuario = $id_usuario;
    }

    /**
     * @return mixed
     */
    public function getIdRespuesta()
    {
        return $this->id_respuesta;
    }

    /**
     * @param mixed $id_respuesta
     */
    public function setIdRespuesta($id_respuesta)
    {
        $this->id_respuesta = $id_respuesta;
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
    public function getIdPregunta()
    {
        return $this->id_pregunta;
    }

    /**
     * @param mixed $id_pregunta
     */
    public function setIdPregunta($id_pregunta)
    {
        $this->id_pregunta = $id_pregunta;
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
    public function getLikePos()
    {
        return $this->like_pos;
    }

    /**
     * @param mixed $like_pos
     */
    public function setLikePos($like_pos)
    {
        $this->like_pos = $like_pos;
    }

    /**
     * @return mixed
     */
    public function getLikesNeg()
    {
        return $this->likes_neg;
    }

    /**
     * @param mixed $likes_neg
     */
    public function setLikesNeg($likes_neg)
    {
        $this->likes_neg = $likes_neg;
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
     * Método para comprobar si el objeto Respuesta es válido para el registro en la base de datos
     */

    public function validoParaCrear()
    {
        $errors = array();

        if (strlen($this->texto) < 1) {
            $errors["texto"] = "El campo texto no puede estar vacio";
        }

        if (sizeof($errors) > 0) {
            throw new ValidationException ($errors, "Respuesta no valida");
        }
    }
}