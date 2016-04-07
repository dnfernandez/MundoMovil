<?php

require_once(__DIR__ . "/../core/PDOConnection.php");
require_once(__DIR__ . "/../model/ComentarioNoticia.php");

class ComentarioNoticiaMapper
{

    private $db;

    public function  __construct()
    {
        $this->db = PDOConnection::getInstance();
    }

    /**
     * @param ComentarioNoticia $comentario
     * Metodo que inserta un comentario en la base de datos
     */

    public function insertar(ComentarioNoticia $comentario)
    {
        $stmt = $this->db->prepare("insert into comentario_noticia(id_com_noticia,id_noticia, id_usuario, texto, fecha, id_com_respondido) values (?,?,?,?,?,?)");
        $stmt->execute(array($comentario->getIdComNoticia(), $comentario->getIdNoticia(), $comentario->getIdUsuario(),
            $comentario->getTexto(), $comentario->getFecha(), $comentario->getIdComRespondido()));
    }

    /**
     * @param $id_noticia
     * @return array
     * Metodo que permite listar los comentarios de una noticia,
     * añadiendo el texto del comentario al que responden, en caso de tenerlo
     */

    public function listarComentariosPorNoticia($pag = 1, $id_noticia)
    {
        $limite = 10;
        if ($pag < 1) {
            $pag = 1;
        }
        $inicio = ($pag - 1) * $limite;

        $stmt = $this->db->prepare("select * from comentario_noticia where id_noticia=? limit ?,?");
        $stmt->execute(array($id_noticia, $inicio, $limite));
        $comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $comentarios2 = array();
        foreach ($comentarios as $coment) {
            $com = $coment;
            if ($coment["id_com_respondido"] != null) {
                $id = $coment["id_com_respondido"];
                $stmt2 = $this->db->prepare("select texto from comentario_noticia where id_com_noticia=?");
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