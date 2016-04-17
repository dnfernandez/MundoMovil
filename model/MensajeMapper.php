<?php

require_once(__DIR__ . "/../core/PDOConnection.php");
require_once(__DIR__ . "/../model/Mensaje.php");

class MensajeMapper
{
    private $db;

    /**
     * MensajeMapper constructor.
     */

    public function __construct()
    {
        $this->db = PDOConnection::getInstance();
    }

    /**
     * @param Mensaje $mensaje
     * Metodo para insertar un mensaje en las tablas mensaje_enviado y mensaje_recibido simultaneamente
     */

    public function insertar(Mensaje $mensaje)
    {
        $stmt = $this->db->prepare("insert into mensaje_enviado (id_mensaje_env, texto, fecha, emisor, receptor) values (?,?,NOW(),?,?)");
        $stmt->execute(array($mensaje->getIdMensaje(), $mensaje->getTexto(), $mensaje->getEmisor(), $mensaje->getReceptor()));

        $stmt2 = $this->db->prepare("insert into mensaje_recibido (id_mensaje_rec, texto, fecha, emisor, receptor) values (?,?,NOW(),?,?)");
        $stmt2->execute(array($mensaje->getIdMensaje(), $mensaje->getTexto(), $mensaje->getEmisor(), $mensaje->getReceptor()));
    }

    /**
     * @param $id_mensaje
     * Metodo que permite eliminar un mensaje de la tabla de mensaje_enviado
     */

    public function eliminarEnviado($id_mensaje)
    {
        $stmt = $this->db->prepare("delete from mensaje_enviado where id_mensaje_env=?");
        $stmt->execute(array($id_mensaje));
    }

    /**
     * @param $id_mensaje
     * Metodo que permite eliminar un mensaje de la tabla de mensaje recibido
     */

    public function eliminarRecibido($id_mensaje)
    {
        $stmt = $this->db->prepare("delete from mensaje_recibido where id_mensaje_rec=?");
        $stmt->execute(array($id_mensaje));
    }

    /**
     * Metodo para listar los mensajes enviados
     */

    public function listarMensajesEnviados($pag = 1, $id_usuario)
    {
        $limite = 15;
        if ($pag < 1) {
            $pag = 1;
        }
        $inicio = ($pag - 1) * $limite;
        $stmt = $this->db->prepare("select * from mensaje_enviado, usuario where mensaje_enviado.emisor=? and mensaje_enviado.receptor=usuario.id_usuario order by fecha desc limit ?,?");
        $stmt->execute(array($id_usuario, $inicio, $limite));
        $mensajes = $stmt->fetchAll(PDO::FETCH_BOTH);
        if ($mensajes != null) {
            return $mensajes;
        } else {
            return null;
        }
    }

    /**
     * Metodo para contar los mensajes enviados
     */

    public function contarMensajesEnviados($id_usuario)
    {
        $stmt = $this->db->prepare("select count(*) as total from mensaje_enviado where mensaje_enviado.emisor=? ");
        $stmt->execute(array($id_usuario));
        return $stmt->fetch(PDO::FETCH_BOTH);
    }

    /**
     * Metodo para listar los mensajes recibidos
     */

    public function listarMensajesRecibidos($pag = 1, $id_usuario)
    {
        $limite = 15;
        if ($pag < 1) {
            $pag = 1;
        }
        $inicio = ($pag - 1) * $limite;
        $stmt = $this->db->prepare("select * from mensaje_recibido, usuario where receptor=? and mensaje_recibido.emisor=usuario.id_usuario order by fecha desc limit ?,?");
        $stmt->execute(array($id_usuario, $inicio, $limite));
        $mensajes = $stmt->fetchAll(PDO::FETCH_BOTH);
        if ($mensajes != null) {
            return $mensajes;
        } else {
            return null;
        }
    }

    /**
     * Metodo para contar los mensajes recibidos
     */

    public function contarMensajesRecibidos($id_usuario)
    {
        $stmt = $this->db->prepare("select count(*) as total from mensaje_recibido where mensaje_recibido.receptor=? ");
        $stmt->execute(array($id_usuario));
        return $stmt->fetch(PDO::FETCH_BOTH);
    }


    /**
     * Marcar mensaje enviado como leido
     */

    private function marcarEnviadoLeido($id_mensaje)
    {
        $stmt = $this->db->prepare("update mensaje_enviado set leido=1 where id_mensaje_env=?");
        $stmt->execute(array($id_mensaje));
    }

    /**
     * Marcar mensaje recibido como leido
     */

    private function marcarRecibidoLeido($id_mensaje)
    {
        $stmt = $this->db->prepare("update mensaje_recibido set leido=1 where id_mensaje_rec=?");
        $stmt->execute(array($id_mensaje));
    }

    /**
     * Metodo para listar los datos de un mensaje enviado
     */

    public function listarMensajeEnviado($id_mensaje)
    {
        $stmt = $this->db->prepare("select * from mensaje_enviado, usuario where mensaje_enviado.receptor=usuario.id_usuario and id_mensaje_env=?");
        $stmt->execute(array($id_mensaje));
        $mensaje = $stmt->fetch(PDO::FETCH_BOTH);
        if ($mensaje != null) {
            $this->marcarEnviadoLeido($id_mensaje);
            return $mensaje;
        } else {
            return null;
        }
    }

    /**
     * Metodo para listar los datos de un mensaje recibido
     */

    public function listarMensajeRecibido($id_mensaje)
    {
        $stmt = $this->db->prepare("select * from mensaje_recibido, usuario where mensaje_recibido.emisor=usuario.id_usuario and id_mensaje_rec=?");
        $stmt->execute(array($id_mensaje));
        $mensaje = $stmt->fetch(PDO::FETCH_BOTH);
        if ($mensaje != null) {
            $this->marcarRecibidoLeido($id_mensaje);
            return $mensaje;
        } else {
            return null;
        }
    }
}