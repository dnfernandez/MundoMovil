<?php

require_once(__DIR__ . "/../core/ValidationException.php");

class Noticia
{
    private $id_noticia;
    private $titulo;
    private $resumen;
    private $pal_clave;
    private $rutaImagen;
    private $texto;
    private $fecha;
    private $id_usuario;

    /**
     * Noticia constructor.
     * @param $id_noticia
     * @param $titulo
     * @param $resumen
     * @param $pal_clave
     * @param $rutaImagen
     * @param $texto
     * @param $fecha
     * @param $id_usuario
     */
    public function __construct($id_noticia = NULL, $titulo = NULL, $resumen = NULL, $pal_clave = NULL, $rutaImagen = NULL, $texto = NULL, $fecha = NULL, $id_usuario = NULL)
    {
        $this->id_noticia = $id_noticia;
        $this->titulo = $titulo;
        $this->resumen = $resumen;
        $this->pal_clave = $pal_clave;
        $this->rutaImagen = $rutaImagen;
        $this->texto = $texto;
        $this->fecha = $fecha;
        $this->id_usuario = $id_usuario;
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
    public function getRutaImagen()
    {
        return $this->rutaImagen;
    }

    /**
     * @param mixed $rutaImagen
     */
    public function setRutaImagen($rutaImagen)
    {
        $this->rutaImagen = $rutaImagen;
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
     * Método para comprobar si el objeto Noticia es válido para el registro en la base de datos
     */

    public function validoParaCrear()
    {
        $errors = array();

        if (strlen($this->titulo) < 1) {
            $errors["tituloN"] = "El campo titulo no puede estar vacio";
        }

        if (strlen($this->resumen) < 1) {
            $errors["resumenN"] = "El campo resumen no puede estar vacio";
        }

        if (strlen($this->pal_clave) < 1) {
            $errors["pal_clavN"] = "El campo palabras clave no puede estar vacio";
        }

        /*if (strpos($this->rutaImagen, $this->id_noticia . ".") == false) {
            $errors["rutaImagenN"] = "El campo imagen resumen no puede estar vacio";
        }*/

        if (strlen($this->texto) < 1) {
            $errors["textoN"] = "El campo texto no puede estar vacio";
        }

        if (sizeof($errors) > 0) {
            throw new ValidationException ($errors, "Noticia no valida");
        }
    }

    /**
     * Metodo para comprobar si el objeto noticia es valido para modificarse
     */

    public function validoParaActualizar()
    {
        $errors = array();
        if (!isset($this->id_noticia)) {
            $errors["id_notica"] = "id_noticia es obligatorio";
        }

        try {
            $this->validoParaCrear();
        } catch (ValidationException $ex) {
            foreach ($ex->getErrors() as $key => $error) {
                $errors[$key] = $error;
            }
        }

        if (sizeof($errors) > 0) {
            throw new ValidationException($errors, "noticia no valida para actualizar");
        }

    }
}

