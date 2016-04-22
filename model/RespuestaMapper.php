<?php

require_once(__DIR__ . "/../core/PDOConnection.php");
require_once(__DIR__ . "/../model/Respuesta.php");

class RespuestaMapper
{
    private $db;

    /**
     * RespuestaMapper constructor.
     */
    public function __construct()
    {
        $this->db = PDOConnection::getInstance();
    }

    /**
     * Metodo para insertar una respuesta
     */

    public function insertar(Respuesta $respuesta)
    {
        $stmt = $this->db->prepare("insert into respuesta(id_respuesta, id_foro, id_pregunta, texto, fecha, likes_pos, likes_neg, id_usuario) value (?,?,?,?,NOW(),?,?,?)");
        $stmt->execute(array($respuesta->getIdRespuesta(), $respuesta->getIdForo(), $respuesta->getIdPregunta(), $respuesta->getTexto(),
            $respuesta->getLikePos(), $respuesta->getLikesNeg(), $respuesta->getIdUsuario()));
    }


    /**
     * ## Metodo para un usuario validado en el sistema ##
     * Metodo para listar las respuestas de una pregunta
     * añadiendo informacion de los usuarios que las crearon
     * e informacion sobre las votaciones en respuestas del
     * usuario que esta validado en ese mismo instante
     */

    public function listarRespuestasUsuario($pag = 1, $id_pregunta, $id_usuario_act = null)
    {

        $limite = 10;
        if ($pag < 1) {
            $pag = 1;
        }
        $inicio = ($pag - 1) * $limite;

        $stmt = $this->db->prepare("select * from respuesta where id_pregunta=? order by fecha asc limit ?,? ");
        $stmt->execute(array($id_pregunta, $inicio, $limite));
        $respuestas = $stmt->fetchAll(PDO::FETCH_BOTH);
        $resp_final = array();
        foreach ($respuestas as $respuesta) {
            $res = $respuesta;
            $stmt2 = $this->db->prepare("select * from usuario where id_usuario=?");
            $stmt2->execute(array($respuesta["id_usuario"]));
            $usuario = $stmt2->fetch(PDO::FETCH_BOTH);

            if ($id_usuario_act != null) {
                $stmt3 = $this->db->prepare("select tipo_votacion from votacion where id_respuesta=? and id_usuario=?");
                $stmt3->execute(array($respuesta["id_respuesta"], $id_usuario_act));
                $votacion = $stmt3->fetch(PDO::FETCH_BOTH);
                if ($votacion == null) {
                    $votacion = array('tipo_votacion' => null);
                }
                $res_user = array_merge($res, $usuario, $votacion);
                array_push($resp_final, $res_user);
            } else {
                $res_user = array_merge($res, $usuario);
                array_push($resp_final, $res_user);
            }
        }
        return $resp_final;
    }

    /**
     * Metodo que lista las respuestas por el id de una pregunta
     */

    public function listarRespuestasPorIdPregunta($id_pregunta)
    {
        $stmt = $this->db->prepare("select * from respuesta where id_pregunta=?");
        $stmt->execute(array($id_pregunta));
        return $stmt->fetchAll(PDO::FETCH_BOTH);
    }

    /**
     * Metodo que permite contar el numero de respuestas de un tema o pregunta
     */

    public function contarRespuestas($id_pregunta)
    {
        $stmt = $this->db->prepare("select count(*) as total from respuesta where id_pregunta=?");
        $stmt->execute(array($id_pregunta));
        return $stmt->fetch(PDO::FETCH_BOTH);
    }

    /**
     * Metodo para que un usuario vote en una respuesta
     * Si un usuario no ha votado esa pregunta, se registra el voto
     * y se suma en positivo o negativo.
     * Si el usuario ya habia votado y vota por otro tipo,
     * se modifica el tipo del voto, y se incrementa y decrementa
     * postiva y negativamente en respuesta.
     * En caso contrario se lanza un error de ya votado
     */

    public function votarRespuestas($id_respuesta, $id_usuario, $tipo)
    {
        $stmt = $this->db->prepare("select tipo_votacion from votacion where id_respuesta=? and $id_usuario=? ");
        $stmt->execute(array($id_respuesta, $id_usuario));
        $votacion = $stmt->fetch(PDO::FETCH_BOTH);
        if ($votacion == null) {
            $stmt2 = $this->db->prepare("insert into votacion(id_respuesta, id_usuario, tipo_votacion) values(?,?,?)");
            $stmt2->execute(array($id_respuesta, $id_usuario, $tipo));
            if ($tipo == 0) {
                $stmt3 = $this->db->prepare("update respuesta set likes_neg = likes_neg+1 where id_respuesta=?");
                $stmt3->execute(array($id_respuesta));
            } elseif ($tipo == 1) {
                $stmt3 = $this->db->prepare("update respuesta set likes_pos = likes_pos+1 where id_respuesta=?");
                $stmt3->execute(array($id_respuesta));
            }
        } else {
            if ($votacion["tipo_votacion"] != $tipo) {
                $stmt2 = $this->db->prepare("update votacion set tipo_votacion=? where id_respuesta=? and id_usuario=?");
                $stmt2->execute(array($tipo, $id_respuesta, $id_usuario));
                if ($tipo == 0) {
                    $stmt3 = $this->db->prepare("update respuesta set likes_neg = likes_neg+1 where id_respuesta=?");
                    $stmt3->execute(array($id_respuesta));
                    $stmt4 = $this->db->prepare("update respuesta set likes_pos = likes_pos-1 where id_respuesta=?");
                    $stmt4->execute(array($id_respuesta));
                } elseif ($tipo == 1) {
                    $stmt3 = $this->db->prepare("update respuesta set likes_pos = likes_pos+1 where id_respuesta=?");
                    $stmt3->execute(array($id_respuesta));
                    $stmt4 = $this->db->prepare("update respuesta set likes_neg = likes_neg-1 where id_respuesta=?");
                    $stmt4->execute(array($id_respuesta));
                }
            } else {
                $errors = array();
                $errors["votado"] = "Ya has votado esta respuesta";
                if (sizeof($errors) > 0) {
                    throw new ValidationException ($errors, "voto no valido");
                }
            }
        }
    }

    /**
     * Metodo para contar el numero de respuestas hechas por un usuario
     */

    public function contarTotal($id_usuario)
    {
        $stmt = $this->db->prepare("select count(*) as total from respuesta where id_usuario=?");
        $stmt->execute(array($id_usuario));
        return $stmt->fetchColumn();
    }

    /**
     * Metodo para contar el numero de votos positivos de un usuario
     */

    public function contarPositivos($id_usuario)
    {
        $stmt = $this->db->prepare("select * from respuesta where id_usuario=?");
        $stmt->execute(array($id_usuario));
        $respuetas = $stmt->fetchAll(PDO::FETCH_BOTH);

        $contador = 0;
        foreach ($respuetas as $respuesta) {
            $contador += $respuesta["likes_pos"];
        }

        return $contador;
    }

    /**
     * Metodo para contar el numero de votos negativos de un usuario
     */

    public function contarNegativos($id_usuario)
    {
        $stmt = $this->db->prepare("select * from respuesta where id_usuario=?");
        $stmt->execute(array($id_usuario));
        $respuetas = $stmt->fetchAll(PDO::FETCH_BOTH);

        $contador = 0;
        foreach ($respuetas as $respuesta) {
            $contador += $respuesta["likes_neg"];
        }

        return $contador;
    }

}