<?php

require_once(__DIR__."/../core/ValidationException.php");
class Pregunta
{
    private $id_pregunta;
    private $id_foro;
    private $titulo;
    private $pal_clave;
    private $texto;
    private $fecha;
    private $id_usuario;

    /**
     * Pregunta constructor.
     * @param $id_pregunta
     * @param $id_foro
     * @param $titulo
     * @param $pal_clave
     * @param $texto
     * @param $fecha
     * @param $id_usuario
     */
    public function __construct($id_pregunta, $id_foro, $titulo, $pal_clave, $texto, $fecha, $id_usuario)
    {
        $this->id_pregunta = $id_pregunta;
        $this->id_foro = $id_foro;
        $this->titulo = $titulo;
        $this->pal_clave = $pal_clave;
        $this->texto = $texto;
        $this->fecha = $fecha;
        $this->id_usuario = $id_usuario;
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
     * Método para comprobar si el objeto Pregunta es válido para el registro en la base de datos
     */

    public function validoParaCrear()
    {
        $errors = array();

        if (strlen($this->titulo) < 1) {
            $errors["tituloP"] = "El campo titulo no puede estar vacio";
        }

        if (strlen($this->pal_clave) < 1) {
            $errors["pal_clavP"] = "El campo palabras clave no puede estar vacio";
        }

        if (strlen($this->texto) < 1) {
            $errors["textoP"] = "El campo texto no puede estar vacio";
        }

        if (sizeof($errors) > 0) {
            throw new ValidationException ($errors, "Pregunta no valida");
        }
    }

    /**
     * Metodo para comprobar si el objeto pregunta es valido para modificarse
     */

    public function validoParaActualizar()
    {
        $errors = array();
        if (!isset($this->id_noticia)) {
            $errors["id_pregunta"] = "id_pregunta es obligatorio";
        }

        try {
            $this->validoParaCrear();
        } catch (ValidationException $ex) {
            foreach ($ex->getErrors() as $key => $error) {
                $errors[$key] = $error;
            }
        }

        if (sizeof($errors) > 0) {
            throw new ValidationException($errors, "pregunta no valida para actualizar");
        }

    }


}