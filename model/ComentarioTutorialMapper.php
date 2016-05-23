<?php

require_once(__DIR__ . "/../core/PDOConnection.php");
require_once(__DIR__ . "/../model/ComentarioTutorial.php");

class ComentarioTutorialMapper
{
    private $db;

    /**
     * ComentarioTutorialMapper constructor.
     */
    public function __construct()
    {
        $this->db = PDOConnection::getInstance();
    }

    /**
     * @param ComentarioTutorial $comentario
     * Metodo que inserta un comentario en la base de datos
     */

    public function insertar(ComentarioTutorial $comentario)
    {
        $stmt = $this->db->prepare("insert into comentario_tutorial(id_com_tutorial,id_tutorial, id_usuario, texto, fecha, id_com_respondido) values (?,?,?,?,NOW(),?)");
        $stmt->execute(array($comentario->getIdComTutorial(), $comentario->getIdTutorial(), $comentario->getIdUsuario(),
            $comentario->getTexto(), $comentario->getIdComRespondido()));
    }

    /**
     * Elimina el comentario cuyo id es pasado como argumento
     * @param $id_comentario
     */
    public function eliminar($id_comentario)
    {
        $stmt = $this->db->prepare("delete from comentario_tutorial where id_com_tutorial=? ");
        $stmt->execute(array($id_comentario));
    }

    /**
     * @param $id_tutorial
     * @return array
     * Metodo que permite listar los comentarios de un tutorial,
     * añadiendo el texto del comentario al que responden, en caso de tenerlo
     */

    public function listarComentariosPorTutorial($pag = 1, $id_tutorial, $limitar = true)
    {
        $limite = 10;
        if ($pag < 1) {
            $pag = 1;
        }
        $inicio = ($pag - 1) * $limite;
        if (!$limitar) {
            $stmt = $this->db->prepare("select * from comentario_tutorial, usuario where comentario_tutorial.id_usuario = usuario.id_usuario and id_tutorial=? order by fecha asc");
            $stmt->execute(array($id_tutorial));
        } else {
            $stmt = $this->db->prepare("select * from comentario_tutorial, usuario where comentario_tutorial.id_usuario = usuario.id_usuario and id_tutorial=? order by fecha asc limit ?,?");
            $stmt->execute(array($id_tutorial, $inicio, $limite));
        }
        $comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $comentarios2 = array();
        foreach ($comentarios as $coment) {
            $com = $coment;
            if ($coment["id_com_respondido"] != null) {
                $id = $coment["id_com_respondido"];
                $stmt2 = $this->db->prepare("select texto from comentario_tutorial where id_com_tutorial=?");
                $stmt2->execute(array($id));
                $texto = $stmt2->fetch(PDO::FETCH_BOTH);
                $textoRes = $texto["texto"];
                array_push($com, $textoRes);
            } else {
                $textoRes = null;
                array_push($com, $textoRes);
            }
            array_push($comentarios2, $com);
        }
        return $comentarios2;
    }

    /**
     * Metodo que cuenta el numero total de comentarios de un tutorial
     * @param $id_tutorial
     * @return mixed
     */
    public function contarComentariosTutorial($id_tutorial)
    {
        $stmt = $this->db->prepare("select count(*) as total from comentario_tutorial where id_tutorial=?");
        $stmt->execute(array($id_tutorial));
        return $stmt->fetch(PDO::FETCH_BOTH);
    }

    /**
     * Metodo que devuelve un array con los id_com_tutorial y unos "ids secuenciales" asociados
     * @param $id_tutorial
     * @return array
     */
    public function calcularIdSecuenciales($id_tutorial)
    {
        $stmt = $this->db->prepare("select * from comentario_tutorial where id_tutorial=? order by fecha asc");
        $stmt->execute(array($id_tutorial));
        $comentarios = $stmt->fetchAll(PDO::FETCH_BOTH);
        $array = array();

        $cont = 1;
        foreach ($comentarios as $comentario) {
            $array[$comentario["id_com_tutorial"]] = $cont;
            $cont++;
        }

        return $array;
    }


}