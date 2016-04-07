<?php

require_once(__DIR__ . "/../core/PDOConnection.php");
require_once(__DIR__ . "/../model/Pregunta.php");

class PreguntaMapper
{
    private $db;

    /**
     * PreguntaMapper constructor.
     */
    public function __construct()
    {
        $this->db = PDOConnection::getInstance();
    }

    /**
     * Metodo para insertar una pregunta
     */

    public function insertar(Pregunta $pregunta)
    {
        $stmt = $this->db->prepare("insert into pregunta(id_pregunta,id_foro,titulo,pal_clave,texto,fecha,id_usuario) values (?,?,?,?,?,?,?)");
        $stmt->execute(array($pregunta->getIdPregunta(), $pregunta->getIdForo(), $pregunta->getTitulo(), $pregunta->getPalClave(), $pregunta->getTexto(), $pregunta->getFecha(), $pregunta->getIdUsuario()));
    }

    /**
     * Metodo para actualizar una pregunta
     */

    public function actualizar(Pregunta $pregunta)
    {
        $stmt = $this->db->prepare("update pregunta set titulo=? ,pal_clave=? ,texto=? ,fecha=? where id_pregunta=?");
        $stmt->execute(array($pregunta->getTitulo(), $pregunta->getPalClave(), $pregunta->getTexto(), $pregunta->getFecha(), $pregunta->getIdPregunta()));
    }

    /**
     * Metodo para eliminar una pregunta
     */

    public function eliminar($id_pregunta)
    {
        $stmt = $this->db->prepare("delete from pregunta where id_pregunta=?");
        $stmt->execute(array($id_pregunta));
    }

    /**
     * Metodo para listar todas las preguntas de un foro concreto
     */

    public function listarPreguntasPorForo($pag = 1, $id_foro)
    {
        $limite = 10;
        if ($pag < 1) {
            $pag = 1;
        }
        $inicio = ($pag - 1) * $limite;

        $stmt = $this->db->prepare("select * from pregunta where $id_foro=? order by fecha desc limit ?,?");
        $stmt->execute(array($id_foro, $inicio, $limite));
        $preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $preguntasFinal = array();
        foreach ($preguntas as $pregunta) {
            $preguntaTotal = $pregunta;
            $stmt2 = $this->db->prepare("select count(*) as total from pregunta, respuesta where pregunta.id_pregunta=respuesta.id_pregunta and pregunta.id_pregunta=?");
            $stmt2->execute(array($pregunta["id_pregunta"]));
            $numTotal = $stmt2->fetch(PDO::FETCH_BOTH);
            $numTotal = $numTotal["total"];
            array_push($preguntaTotal, $numTotal);
            array_push($preguntasFinal, $preguntaTotal);
        }

        return $preguntasFinal;
    }

    /**
     * Medoto para filtrar preguntas por texto, palabras clave o autor
     */

    public function listarPreguntasFiltradas($pag = 1, $texto = null, $pal_clave = null, $autor = null)
    {
        $limite = 10;
        if ($pag < 1) {
            $pag = 1;
        }
        $inicio = ($pag - 1) * $limite;

        if ($texto != null) {
            $stmt = $this->db->prepare("select * from pregunta where pregunta.titulo like :elemento1 or pregunta.texto like :elemento2 order by fecha desc limit :ini,:lim");
            $stmt->execute( array(':elemento1' => '%' . $texto . '%', ':elemento2' => '%' . $texto . '%', ':ini' => $inicio, ':lim' => $limite));

        } elseif ($pal_clave != null) {
            $arrayClave = explode(" ", $pal_clave);
            $sentencia = "select * from pregunta where";
            $execute = array();
            $cont = 1;
            foreach ($arrayClave as $cla) {
                if ($cont < count($arrayClave)) {
                    $sentencia .= " pregunta.pal_clave like ? OR ";
                } else {
                    $sentencia .= " pregunta.pal_clave like ? ";
                }
                array_push($execute, "%$cla%");
                $cont++;
            }
            $sentencia .= " order by fecha desc limit ?,?";
            array_push($execute, "$inicio");
            array_push($execute, "$limite");

            $stmt = $this->db->prepare($sentencia);
            $stmt->execute($execute);
        } elseif ($autor != null) {
            $stmt = $this->db->prepare("select * from pregunta where pregunta.id_usuario like ? order by fecha desc limit ?,?");
            $stmt->execute(array('%' . $autor . '%', $inicio, $limite));
        }

        if (($texto || $pal_clave || $autor) != null) {
            $preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $preguntasFinal = array();
            foreach ($preguntas as $pregunta) {
                $preguntaTotal = $pregunta;
                $stmt2 = $this->db->prepare("select count(*) as total from pregunta, respuesta where pregunta.id_pregunta=respuesta.id_pregunta and pregunta.id_pregunta=?");
                $stmt2->execute(array($pregunta["id_pregunta"]));
                $numTotal = $stmt2->fetch(PDO::FETCH_BOTH);
                $numTotal = $numTotal["total"];
                array_push($preguntaTotal, $numTotal);
                array_push($preguntasFinal, $preguntaTotal);
            }

            return $preguntasFinal;
        }
    }

    /**
     * Metodo para listar los datos de una pregunta concreta por su id
     */

    public function listarPreguntaPorId($id_pregunta)
    {
        $stmt = $this->db->prepare("select * from pregunta where id_pregunta=?");
        $stmt->execute(array($id_pregunta));
        $pregunta = $stmt->fetch(PDO::FETCH_BOTH);
        return $pregunta;
    }

    /**
     * Metodo para comprobar si existe una pregunta
     */

    public function existe($id_pregunta)
    {
        $stmt = $this->db->prepare("select count(*) from pregunta where id_pregunta=?");
        $stmt->execute($id_pregunta);
        if ($stmt->fetchColumn() > 0) {
            return true;
        }
    }

    /**
     * Metodo para contar el numero de preguntas hechas por un usuario
     */

    public function contarTotal($id_usuario){
        $stmt = $this->db->prepare("select count(*) as total from pregunta where id_usuario=?");
        $stmt->execute(array($id_usuario));
        return $stmt->fetchColumn();
    }

}