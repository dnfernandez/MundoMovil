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
        $stmt = $this->db->prepare("insert into comentario_noticia(id_com_noticia,id_noticia, id_usuario, texto, fecha, id_com_respondido) values (?,?,?,?,NOW(),?)");
        $stmt->execute(array($comentario->getIdComNoticia(), $comentario->getIdNoticia(), $comentario->getIdUsuario(),
            $comentario->getTexto(), $comentario->getIdComRespondido()));
    }

    /**
     * Elimina el comentario cuyo id es pasado como argumento
     * @param $id_comentario
     */
    public function eliminar($id_comentario){
        $stmt = $this->db->prepare("delete from comentario_noticia where id_com_noticia=? ");
        $stmt->execute(array($id_comentario));
    }


    /**
     * @param $id_noticia
     * @return array
     * Metodo que permite listar los comentarios de una noticia,
     * añadiendo el texto del comentario al que responden, en caso de tenerlo
     */

    public function listarComentariosPorNoticia($pag = 1, $id_noticia, $limitar = true)
    {
        $limite = 10;
        if ($pag < 1) {
            $pag = 1;
        }
        $inicio = ($pag - 1) * $limite;

        if(!$limitar){
            $stmt = $this->db->prepare("select * from comentario_noticia, usuario where comentario_noticia.id_usuario=usuario.id_usuario and id_noticia=?");
            $stmt->execute(array($id_noticia));
        }else {
            $stmt = $this->db->prepare("select * from comentario_noticia, usuario where comentario_noticia.id_usuario=usuario.id_usuario and id_noticia=? limit ?,?");
            $stmt->execute(array($id_noticia, $inicio, $limite));
        }
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

    /**
     * Metodo que cuenta el numero total de comentarios de una noticia
     * @param $id_noticia
     * @return mixed
     */
    public function contarComentariosNoticia($id_noticia){
        $stmt = $this->db->prepare("select count(*) as total from comentario_noticia where id_noticia=?");
        $stmt->execute(array($id_noticia));
        return $stmt->fetch(PDO::FETCH_BOTH);
    }

    /**
     * Metodo que devuelve un array con los id_com_noticia y unos "ids secuenciales" asociados
     * @param $id_noticia
     * @return array
     */
    public function calcularIdSecuenciales($id_noticia){
        $stmt = $this->db->prepare("select * from comentario_noticia where id_noticia=?");
        $stmt->execute(array($id_noticia));
        $comentarios = $stmt->fetchAll(PDO::FETCH_BOTH);
        $array = array();

        $cont=1;
        foreach($comentarios as $comentario){
            $array[$comentario["id_com_noticia"]]=$cont;
            $cont++;
        }

        return $array;
    }
}