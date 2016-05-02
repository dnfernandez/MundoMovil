<?php

require_once(__DIR__ . "/../core/PDOConnection.php");
require_once(__DIR__ . "/../model/Tutorial.php");

class TutorialMapper
{
    private $db;

    /**
     * TutorialMapper constructor.
     */
    public function __construct()
    {
        $this->db = PDOConnection::getInstance();
    }

    /**
     * Metodo para insertar un tutorial
     */

    public function insertar(Tutorial $tutorial)
    {
        $stmt = $this->db->prepare("insert into tutorial(id_tutorial, titulo, pal_clave, texto, fecha, id_usuario) values(?,?,?,?,NOW(),?)");
        $stmt->execute(array($tutorial->getIdTutorial(), $tutorial->getTitulo(), $tutorial->getPalClave(), $tutorial->getTexto(),$tutorial->getIdUsuario()));
    }

    /**
     * Metodo para modificar un tutorial
     */

    public function actualizar(Tutorial $tutorial)
    {
        $stmt = $this->db->prepare("update tutorial set titulo=?, pal_clave=?, texto=?, fecha=NOW() where id_tutorial=?");
        $stmt->execute(array($tutorial->getTitulo(), $tutorial->getPalClave(), $tutorial->getTexto(), $tutorial->getIdTutorial()));
    }

    /**
     * Metodo para eliminar un tutorial
     */

    public function eliminar($id_tutorial)
    {
        $stmt = $this->db->prepare("delete from tutorial where id_tutorial=?");
        $stmt->execute(array($id_tutorial));
    }

    /**
     * Metodo que permite comprobar si un tutorial existe
     */

    public function existe($id_tutorial)
    {
        $stmt = $this->db->prepare("select count(*) from tutorial where id_tutorial=?");
        $stmt->execute(array($id_tutorial));
        if ($stmt->fetchColumn() > 0) {
            return true;
        }
    }

    /**
     * Listar tutorial por id
     */

    public function listarPorId($id_tutorial)
    {
        $stmt = $this->db->prepare("select * from tutorial, usuario where tutorial.id_usuario=usuario.id_usuario and id_tutorial=?");
        $stmt->execute(array($id_tutorial));
        $tutorial = $stmt->fetch(PDO::FETCH_BOTH);
        if ($tutorial != null) {
            return $tutorial;
        } else {
            return null;
        }

    }

    /**
     * Metodo para listar tutoriales filtrando por texto, autor, palabras clave o nada limitando el numero por pagina
     * Si autor !=null filtramos por autor
     * Si pal_clave !=null filtramos por palabras clave, para ello las descomponemos y buscamos una por una
     * Si text != null filtramos por texto
     * Si todas son null mostramos todos los tutoriales
     */

    public function listarTutorialesFiltro($pag = 1, $autor = null, $pal_clave = null, $texto = null)
    {
        $limite = 10;
        if ($pag < 1) {
            $pag = 1;
        }
        $inicio = ($pag - 1) * $limite;

        if ($autor != null) {
            $stmt = $this->db->prepare("select * from tutorial, usuario where tutorial.id_usuario=usuario.id_usuario and usuario.nom_usuario like :elemento order by fecha desc limit :ini,:lim");
            $stmt->execute(array(':elemento' => '%' . $autor . '%', ':ini' => $inicio, ':lim' => $limite));
        } elseif ($pal_clave != null) {
            $arrayClave = explode(" ", $pal_clave);
            $sentencia = "select * from tutorial, usuario where tutorial.id_usuario=usuario.id_usuario and (";
            $execute = array();
            $cont = 1;
            foreach ($arrayClave as $cla) {
                if ($cont < count($arrayClave)) {
                    $sentencia .= " tutorial.pal_clave like ? OR ";
                } else {
                    $sentencia .= " tutorial.pal_clave like ? ";
                }
                array_push($execute, "%$cla%");
                $cont++;
            }
            $sentencia .= ") order by fecha desc limit ?,?";
            array_push($execute, "$inicio");
            array_push($execute, "$limite");

            $stmt = $this->db->prepare($sentencia);
            $stmt->execute($execute);

        } elseif ($texto != null) {
            $stmt = $this->db->prepare("select * from tutorial, usuario where usuario.id_usuario=tutorial.id_usuario and (tutorial.texto like :elemento or tutorial.titulo like :elemento2) order by fecha desc limit :ini,:lim");
            $stmt->execute(array(':elemento' => '%' . $texto . '%', ':elemento2' => '%' . $texto . '%', ':ini' => $inicio, ':lim' => $limite));
        } else {
            $stmt = $this->db->prepare("select * from tutorial, usuario where usuario.id_usuario=tutorial.id_usuario order by fecha desc limit ?,?");
            $stmt->execute(array($inicio, $limite));
        }
        $tutoriales = $stmt->fetchAll(PDO::FETCH_BOTH);

        if ($tutoriales != null) {
            return $tutoriales;
        } else {
            return null;
        }
    }

    /**
     * Metodo para contar el numero de tutoriales hechos por un usuario
     */

    public function contarTotal($id_usuario)
    {
        $stmt = $this->db->prepare("select count(*) as total from tutorial where id_usuario=?");
        $stmt->execute(array($id_usuario));
        return $stmt->fetchColumn();
    }

    /**
     * Metodo para contar el numero de paginas existentes de tutoriales (cada pagina tiene 10 tutoriales)
     * pudiendo contar filtrando por autor, pal_clave y texto
     */

    public function contarTutoriales($autor = null, $pal_clave = null, $texto = null){
        if ($autor != null) {
            $stmt = $this->db->prepare("select count(*) as total from tutorial, usuario where tutorial.id_usuario=usuario.id_usuario and usuario.nom_usuario like :elemento");
            $stmt->execute(array(':elemento' => '%' . $autor . '%'));
        } elseif ($pal_clave != null) {
            $arrayClave = explode(" ", $pal_clave);
            $sentencia = "select count(*) as total from tutorial where ";
            $execute = array();
            $cont = 1;
            foreach ($arrayClave as $cla) {
                if ($cont < count($arrayClave)) {
                    $sentencia .= " tutorial.pal_clave like ? OR ";
                } else {
                    $sentencia .= " tutorial.pal_clave like ? ";
                }
                array_push($execute, "%$cla%");
                $cont++;
            }

            $stmt = $this->db->prepare($sentencia);
            $stmt->execute($execute);

        } elseif ($texto != null) {
            $stmt = $this->db->prepare("select count(*) as total from tutorial where tutorial.texto like :elemento or tutorial.titulo like :elemento2");
            $stmt->execute(array(':elemento' => '%' . $texto . '%', ':elemento2' => '%' . $texto . '%'));
        } else {
            $stmt = $this->db->prepare("select count(*) as total from tutorial");
            $stmt->execute();
        }

        return $stmt->fetch(PDO::FETCH_BOTH);
    }

    /**
     * Metodo que lista todas los tutoriales de un usuario por su id_usuario
     * @param $id_usuario
     * @return array
     */

    public function listarTutorialesPorIdUsuario($id_usuario)
    {
        $stmt = $this->db->prepare("select * from tutorial where id_usuario=?");
        $stmt->execute(array($id_usuario));
        return $stmt->fetchAll(PDO::FETCH_BOTH);
    }
}
