<?php


require_once(__DIR__ . "/../core/PDOConnection.php");
require_once(__DIR__ . "/../model/Foro.php");

class ForoMapper
{
    private $db;

    /**
     * ForoMapper constructor.
     */
    public function __construct()
    {
        $this->db = PDOConnection::getInstance();
    }

    /**
     * Metodo para insertar un foro en la base de datos
     */

    public function insertar(Foro $foro)
    {
        $stmt = $this->db->prepare("insert into foro(id_foro,titulo,resumen,fecha,id_usuario) value(?,?,?,NOW(),?)");
        $stmt->execute(array($foro->getIdForo(), $foro->getTitulo(), $foro->getResumen(), $foro->getIdUsuario()));
    }

    /**
     * Metodo para actualizar un  foro
     */

    public function actualizar(Foro $foro)
    {
        $stmt = $this->db->prepare("update foro set titulo=?, resumen=?, fecha=NOW() where id_foro=?");
        $stmt->execute(array($foro->getTitulo(), $foro->getResumen(), $foro->getIdForo()));
    }

    /**
     * Metodo para eliminar un foro
     */

    public function eliminar($id_foro)
    {
        $stmt = $this->db->prepare("delete from foro where id_foro=?");
        $stmt->execute(array($id_foro));
    }

    /**
     * Metodo para listar todos los foros añadiendo el campo de numero total de preguntas adyacentes a ese foro
     */

    public function listarForos()
    {
        $stmt = $this->db->query("select * from foro");
        $foros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $forosFinal = array();
        foreach ($foros as $foro) {
            $foroTotal = $foro;
            $stmt2 = $this->db->prepare("select count(*) as total from pregunta, foro where pregunta.id_foro=foro.id_foro and foro.id_foro=?");
            $stmt2->execute(array($foro["id_foro"]));
            $numTotal = $stmt2->fetch(PDO::FETCH_BOTH);
            $numTotal = $numTotal["total"];
            array_push($foroTotal, $numTotal);
            array_push($forosFinal, $foroTotal);
        }

        return $forosFinal;
    }

    /**
     * Obtener el titulo de un foro
     */

    public function obtenerTituloForo($id_foro)
    {
        $stmt = $this->db->prepare("select titulo from foro where id_foro=?");
        $stmt->execute(array($id_foro));
        return $stmt->fetch(PDO::FETCH_BOTH);
    }

}