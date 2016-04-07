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
        $stmt = $this->db->prepare("insert into comentario_tutorial(id_com_tutorial,id_tutorial, id_usuario, texto, fecha, id_com_respondido) values (?,?,?,?,?,?)");
        $stmt->execute(array($comentario->getIdComTutorial(), $comentario->getIdTutorial(), $comentario->getIdUsuario(),
            $comentario->getTexto(), $comentario->getFecha(), $comentario->getIdComRespondido()));
    }

    /**
     * @param $id_tutorial
     * @return array
     * Metodo que permite listar los comentarios de un tutorial,
     * añadiendo el texto del comentario al que responden, en caso de tenerlo
     */

    public function listarComentariosPorTutorial($pag = 1, $id_tutorial)
    {
        $limite = 10;
        if ($pag < 1) {
            $pag = 1;
        }
        $inicio = ($pag - 1) * $limite;

        $stmt = $this->db->prepare("select * from comentario_tutorial where id_tutorial=? limit ?,?");
        $stmt->execute(array($id_tutorial, $inicio, $limite));
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


}