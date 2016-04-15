<?php

require_once(__DIR__ . "/../core/PDOConnection.php");
require_once(__DIR__ . "/../model/Usuario.php");

class UsuarioMapper
{
    private $db;

    public function __construct()
    {
        $this->db = PDOConnection::getInstance();
    }

    /**
     * @return string
     * Metodo que genera un codigo unico de 15 cifras aletatorio
     */


    private function generarCodigoActivacion()
    {
        $characters = '1234567890';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 15; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        $stmt = $this->db->prepare("select count(*) from usuario where cod_validacion=?");
        $stmt->execute(array(intval($randomString)));
        if ($stmt->fetchColumn() > 0) {
            $this->generarCodigoActivacion();
        } else {
            return $randomString;
        }

    }

    /**
     * Metodo para insertar un usuario en la base de datos
     */

    public function insertar(Usuario $usuario)
    {
        $cod_validacion = $this->generarCodigoActivacion();
        $stmt1 = $this->db->query("select NOW() as fecha_actual");
        $fechareg = $stmt1->fetch(PDO::FETCH_BOTH);

        $stmt2 = $this->db->prepare("insert into usuario(id_usuario, nom_usuario, email, contrasenha, ubicacion, avatar, fecha_reg, fecha_conex, rol, estado, baneado, cod_validacion)
                values(?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt2->execute(array($usuario->getIdUsuario(), $usuario->getNomUsuario(), $usuario->getEmail(), $usuario->getContrasenha(), $usuario->getUbicacion(),
            $usuario->getAvatar(), $fechareg["fecha_actual"], $usuario->getFechaConex(), $usuario->getRol(), 0, 0, $cod_validacion));
    }

    /**
     * Metodo para actualizar un usuario en la base de datos
     */

    public function actualizar(Usuario $usuario)
    {
        $stmt = $this->db->prepare("update usuario set contrasenha=?, ubicacion=?, avatar=? where id_usuario=?");
        $stmt->execute(array($usuario->getContrasenha(), $usuario->getUbicacion(), $usuario->getAvatar(), $usuario->getIdUsuario()));
    }

    /**
     * Metodo para eliminar un usuario de la base de datos
     */

    public function eliminar($id_usuario)
    {
        $stmt = $this->db->prepare("delete from usuario where id_usuario=?");
        $stmt->execute(array($id_usuario));
    }

    /**
     * Metodo para comprobar si existe un usuario por id o nombreUsuario o email
     */

    public function existe($id_usuario = null, $nombre_usuario = null, $email = null)
    {
        if ($id_usuario != null) {
            $stmt = $this->db->prepare("select count(*) from usuario where id_usuario=?");
            $stmt->execute(array($id_usuario));
        } elseif ($nombre_usuario != null) {
            $stmt = $this->db->prepare("select count(*) from usuario where nom_usuario=?");
            $stmt->execute(array($nombre_usuario));
        } elseif ($email != null) {
            $stmt = $this->db->prepare("select count(*) from usuario where email=?");
            $stmt->execute(array($email));
        }

        if ($stmt->fetchColumn() > 0) {
            return true;
        }
    }

    /**
     * Metodo para listar datos de un usuario por su id
     */

    public function listarUsuarioPorId($id_usuario)
    {
        $stmt = $this->db->prepare("select * from usuario where id_usuario=?");
        $stmt->execute(array($id_usuario));
        $usuario = $stmt->fetch(PDO::FETCH_BOTH);
        if ($usuario != null) {
            return $usuario;
        } else {
            return null;
        }
    }

    /**
     * Metodo que permite listar datos de un usuario por su nombre de usuario o su email
     */

    public function listarUsuarioConcreto($nombre_usuario = null, $email = null)
    {
        if (!($nombre_usuario == null && $email == null)) {

            if ($nombre_usuario != null) {
                $stmt = $this->db->prepare("select * from usuario where nom_usuario=?");
                $stmt->execute(array($nombre_usuario));
            } elseif ($email != null) {
                $stmt = $this->db->prepare("select * from usuario where email=?");
                $stmt->execute(array($email));
            }
            $usuario = $stmt->fetch(PDO::FETCH_BOTH);
            if ($usuario != null) {
                return $usuario;
            } else {
                return null;
            }
        } else {
            return false;
        }
    }

    /**
     * Metodo que lista todos los usuarios y sus datos asociados
     * permitiendo filtrar por nombre de usuario y email
     */

    public function listarUsuarios($pag = 1, $nom_usuario = null, $email = null)
    {
        $limite = 40;
        if ($pag < 1) {
            $pag = 1;
        }
        $inicio = ($pag - 1) * $limite;
        if ($nom_usuario != null) {
            $stmt = $this->db->prepare("select * from usuario where nom_usuario like ? limit ?,?");
            $stmt->execute(array('%' . $nom_usuario . '%', $inicio, $limite));
        } elseif ($email != null) {
            $stmt = $this->db->prepare("select * from usuario where email like ? limit ?,?");
            $stmt->execute(array('%' . $email . '%', $inicio, $limite));
        } else {
            $stmt = $this->db->prepare("select * from usuario limit ?,?");
            $stmt->execute(array($inicio, $limite));
        }
        $usuarios = $stmt->fetchAll(PDO::FETCH_BOTH);
        if ($usuarios != null) {
            return $usuarios;
        } else {
            return null;
        }
    }

    /**
     * Metodo que permite loguear un usuario a partir de su email y si password
     */

    public function comprobarUsuario($email, $contrasenha)
    {
        $stmt = $this->db->prepare("select count(*) from usuario where email=? and contrasenha=?");
        $stmt->execute(array($email, $contrasenha));
        if ($stmt->fetchColumn() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Metodo que actualiza la fecha/hora de la última conexión al sistema
     */

    public function actualizarFechaConexion($id_usuario = null, $email = null)
    {
        if ($id_usuario != null) {
            if ($this->existe($id_usuario)) {
                $stmt = $this->db->prepare("update usuario set fecha_conex = NOW() where id_usuario=?");
                $stmt->execute(array($id_usuario));
            }
        } elseif ($email != null) {
            if ($this->existe(null, null, $email)) {
                $stmt = $this->db->prepare("update usuario set fecha_conex = NOW() where email=?");
                $stmt->execute(array($email));
            }
        }
    }

    /**
     * Metodo que actualiza el rol de usuario
     */

    public function actualizarRolUsuario($id_usuario)
    {
        if ($this->existe($id_usuario)) {
            $stmt = $this->db->prepare("select rol from usuario where id_usuario=?");
            $stmt->execute(array($id_usuario));
            $rol = $stmt->fetch(PDO::FETCH_BOTH);
            $rol = $rol[0];
            if ($rol == "usuario") {
                $stmt2 = $this->db->prepare("update usuario set rol='moderador' where id_usuario=?");
                $stmt2->execute(array($id_usuario));
            } elseif ($rol == "moderador") {
                $stmt2 = $this->db->prepare("update usuario set rol='usuario' where id_usuario=?");
                $stmt2->execute(array($id_usuario));
            }
        }
    }

    /**
     * Metodo que comprueba si un usuario esta activo en el sistema por id_usuario o email
     */

    public function comprobarEstadoUsuario($id_usuario = null, $email = null)
    {
        if ($id_usuario != null) {
            $stmt = $this->db->prepare("select count(*) from usuario where id_usuario=? and estado=1");
            $stmt->execute(array($id_usuario));
        } elseif ($email != null) {
            $stmt = $this->db->prepare("select count(*) from usuario where email=? and estado=1");
            $stmt->execute(array($email));
        }

        if ($stmt->fetchColumn() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Metodo que comprueba si un usuario esta baneado en el sistema por id_usuario o email
     */

    public function comprobarBaneoUsuario($id_usuario = null, $email = null)
    {
        if ($id_usuario != null) {
            $stmt = $this->db->prepare("select count(*) from usuario where id_usuario=? and baneado=1");
            $stmt->execute(array($id_usuario));
        } elseif ($email != null) {
            $stmt = $this->db->prepare("select count(*) from usuario where email=? and baneado=1");
            $stmt->execute(array($email));
        }

        if ($stmt->fetchColumn() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Metodo que cambia el baneo de un usuario, es decir, si esta baneado lo desbanea
     * y si no esta baneado lo banea
     */

    public function actualizaBaneoUsuario($id_usuario)
    {
        if ($this->existe($id_usuario)) {
            $stmt = $this->db->prepare("select baneado from usuario where id_usuario=?");
            $stmt->execute(array($id_usuario));
            $baneado = $stmt->fetch(PDO::FETCH_BOTH);
            $baneado = $baneado[0];
            if ($baneado == 0) {
                $stmt2 = $this->db->prepare("update usuario set baneado=1 where id_usuario=?");
                $stmt2->execute(array($id_usuario));
            } elseif ($baneado == 1) {
                $stmt2 = $this->db->prepare("update usuario set baneado=0 where id_usuario=?");
                $stmt2->execute(array($id_usuario));
            }
        }
    }

    /**
     * Metodo que actualiza el estado de un usuario (pasa de inactivo(0) a activo(1))
     */

    private function actualizaEstadoUsuario($id_usuario)
    {
        if ($this->existe($id_usuario)) {
            $stmt = $this->db->prepare("update usuario set estado=1 where id_usuario=?");
            $stmt->execute(array($id_usuario));
        }
    }

    /**
     * Metodo que comprueba quien es el usuario con el codigo de validacion pasado por parametro
     * y actualiza el estado de ese usuario a activo
     */

    public function comprobarCodigoValidacion($cod_validacion)
    {
        $stmt = $this->db->prepare("select id_usuario from usuario where cod_validacion=?");
        $stmt->execute(array($cod_validacion));
        $id_usuario = $stmt->fetch(PDO::FETCH_BOTH);
        $id_usuario = $id_usuario["id_usuario"];
        if (!$this->comprobarEstadoUsuario($id_usuario)) {
            $this->actualizaEstadoUsuario($id_usuario);
            return true;
        }else{
            return false;
        }
    }

    /**
     * Metodo que permite obtener el ultimo id de un usuario para utilizarlo en su foto de perfil
     * @return mixed
     */
    public function obtenerUltimoIdUsuario()
    {
        $stmt = $this->db->query("select max(id_usuario) as max_id from usuario");
        return $stmt->fetch(PDO::FETCH_BOTH);
    }

    /**
     * Metodo que permite obtener el cod_validacion de un usuario
     * @param $email
     * @return mixed
     */
    public function obtenerCodigoActivacionUsuario($email)
    {
        $stmt = $this->db->prepare("select cod_validacion from usuario where email=?");
        $stmt->execute(array($email));
        return $stmt->fetch(PDO::FETCH_BOTH);
    }
}