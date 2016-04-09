<?php

require_once(__DIR__ . "/../core/PDOConnection.php");
require_once(__DIR__ . "/../model/Noticia.php");

class NoticiaMapper
{
    private $db;

    public function  __construct()
    {
        $this->db = PDOConnection::getInstance();
    }

    /**
     * Metodo para insertar una noticia
     */

    public function insertar(Noticia $noticia)
    {
        $stmt = $this->db->prepare("insert into noticia(id_noticia, titulo, resumen, pal_clave, rutaImagen, texto, fecha, id_usuario) values(?,?,?,?,?,?,NOW(),?)");
        $stmt->execute(array($noticia->getIdNoticia(), $noticia->getTitulo(),
            $noticia->getResumen(), $noticia->getPalClave(), $noticia->getRutaImagen(), $noticia->getTexto(), $noticia->getIdUsuario()));
    }

    /**
     * Metodo para modificar una noticia
     */

    public function actualizar(Noticia $noticia)
    {
        $stmt = $this->db->prepare("update noticia set titulo=?, resumen=?, pal_clave=?, rutaImagen=?, texto=?, fecha=? where id_noticia=?");
        $stmt->execute(array($noticia->getTitulo(), $noticia->getResumen(), $noticia->getPalClave(), $noticia->getRutaImagen(), $noticia->getTexto(), $noticia->getFecha(), $noticia->getIdNoticia()));
    }

    /**
     * Metodo para eliminar una noticia
     */

    public function eliminar($id_noticia)
    {
        $stmt = $this->db->prepare("delete from noticia where id_noticia=?");
        $stmt->execute(array($id_noticia));
    }

    /**
     * Metodo para listar una noticia por su id
     */

    public function listarNoticiaPorId($id_noticia)
    {
        $stmt = $this->db->prepare("select * from noticia, usuario where usuario.id_usuario=noticia.id_usuario and id_noticia=?");
        $stmt->execute(array($id_noticia));
        $noticia = $stmt->fetch(PDO::FETCH_BOTH);
        if ($noticia != null) {
            return $noticia;
        } else {
            return null;
        }
    }

    /**
     * Metodo para comprobar si existe una noticia
     */

    public function existe($id_noticia)
    {
        $stmt = $this->db->prepare("select count(*) from noticia where id_noticia=?");
        $stmt->execute(array($id_noticia));
        if ($stmt->fetchColumn() > 0) {
            return true;
        }
    }

    /**
     * Metodo para listar noticias filtrando por texto, autor, palabras clave o nada limitando el numero por pagina
     * Si autor !=null filtramos por autor
     * Si pal_clave !=null filtramos por palabras clave, para ello las descomponemos y buscamos una por una
     * Si text != null filtramos por texto
     * Si todas son null mostramos todas las noticias
     */

    public function listarNoticiasFiltro($pag = 1, $autor = null, $pal_clave = null, $texto = null)
    {
        $limite = 10;
        if ($pag < 1) {
            $pag = 1;
        }
        $inicio = ($pag - 1) * $limite;

        if ($autor != null) {
            $stmt = $this->db->prepare("select * from noticia, usuario where noticia.id_usuario=usuario.id_usuario and usuario.nom_usuario like :elemento order by fecha desc limit :ini,:lim");
            $stmt->execute(array(':elemento' => '%' . $autor . '%', ':ini' => $inicio, ':lim' => $limite));
        } elseif ($pal_clave != null) {
            $arrayClave = explode(" ", $pal_clave);
            $sentencia = "select * from noticia, usuario where noticia.id_usuario=usuario.id_usuario";
            $execute = array();
            $cont = 1;
            foreach ($arrayClave as $cla) {
                if ($cont < count($arrayClave)) {
                    $sentencia .= " noticia.pal_clave like ? OR ";
                } else {
                    $sentencia .= " noticia.pal_clave like ? ";
                }
                array_push($execute, "%$cla%");
                $cont++;
            }
            $sentencia .= " order by fecha desc limit ?,?";
            array_push($execute, "$inicio");
            array_push($execute, "$limite");

            $stmt = $this->db->prepare($sentencia);
            $stmt->execute($execute);

        } elseif ($texto != null) {
            $stmt = $this->db->prepare("select * from noticia, usuario where noticia.id_usuario=usuario.id_usuario noticia.texto like :elemento or noticia.titulo like :elemento2 order by fecha desc limit :ini,:lim");
            $stmt->execute(array(':elemento' => '%' . $texto . '%', ':elemento2' => '%' . $texto . '%', ':ini' => $inicio, ':lim' => $limite));
        } else {
            $stmt = $this->db->prepare("select * from noticia, usuario where noticia.id_usuario=usuario.id_usuario order by fecha desc limit ?,?");
            $stmt->execute(array($inicio, $limite));
        }
        $noticias = $stmt->fetchAll(PDO::FETCH_BOTH);

        if ($noticias != null) {
            return $noticias;
        } else {
            return null;
        }
    }

    /**
     * Metodo para contar el numero de noticias hechas por un usuario
     */

    public function contarTotal($id_usuario)
    {
        $stmt = $this->db->prepare("select count(*) as total from noticia where id_usuario=?");
        $stmt->execute(array($id_usuario));
        return $stmt->fetchColumn();
    }

    /**
     * Metodo para contar el numero de paginas existentes de noticias (cada pagina tiene 10 noticias)
     */

    public function contarNoticias(){
        $stmt = $this->db->query("select count(*) as total from noticia");
        return $stmt->fetch(PDO::FETCH_BOTH);
    }

    /**
     * Metodo para obtener el ultimo id de noticia
     * @return mixed
     */
    public function obtenerUltimoIdNoticia()
    {
        $stmt = $this->db->query("select max(id_noticia) as max_id from noticia");
        return $stmt->fetch(PDO::FETCH_BOTH);
    }
}