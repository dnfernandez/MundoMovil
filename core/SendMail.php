<?php

require_once(__DIR__ . "/../model/Usuario.php");
require_once(__DIR__ . "/../model/UsuarioMapper.php");

/** Metodo que devuelve la url del sistema
 *
 * Obtiene la uri y extrae de ella el controlador y la accion
 * Forma una url con la direccion HTTP_HOST + el puerto + la uri y la nueva controlador/accion
 * @return string
 */

function getURL($tipo)
{
    $directorio = getcwd();
    $d_actual = explode("/", $directorio);
    $d_actual = $d_actual[count($d_actual) - 1];
    $pos = strpos($_SERVER["REQUEST_URI"], $d_actual);
    $uri = substr($_SERVER["REQUEST_URI"], 0, $pos) . $d_actual;
	
	if ($uri=="html" || $uri == "www"){
		$uri="";
	}

    if ($_SERVER["SERVER_PORT"] == 80) {
        $url = $_SERVER["HTTP_HOST"] . $uri . "/usuario/$tipo?cod_act=";
    } else {
        $url = $_SERVER["HTTP_HOST"] . ":" . $_SERVER["SERVER_PORT"] . $uri . "/usuario/$tipo?cod_act=";
    }

    return $url;
}

function enviar_email(Usuario $usuario)
{
    $usuarioMapper = new UsuarioMapper();
    $codigo_validacion = $usuarioMapper->obtenerCodigoActivacionUsuario($usuario->getEmail());
    $url = getURL("activar");
    $nombre_origen = "MundoMovil";
    $email_origen = "mundomovil.info@gmail.com";
    $asunto = "Activación de cuenta de usuario en MundoMovil";
    $mensaje = "Hola " . $usuario->getNomUsuario() . ".<br> Gracias por registrarse en MundoMovil. Para activar su cuenta pulse en el siguiente enlace: <br>\t<b><a href='" . $url . $codigo_validacion["cod_validacion"] . "'>" . $url . $codigo_validacion["cod_validacion"] . "</a></b><br>Atentamente: equipo de MundoMovil";
    $formato = "html";

    $headers = "From: $nombre_origen <$email_origen> \r\n";
    $headers .= "Return-Path: <$email_origen> \r\n";
    $headers .= "Reply-To: $email_origen \r\n";
    $headers .= "X-Sender: $email_origen \r\n";
    $headers .= "X-Priority: 3 \r\n";
    $headers .= "MIME-Version: 1.0 \r\n";
    $headers .= "Content-Transfer-Encoding: 7bit \r\n";

    if ($formato == "html") {

        $headers .= "Content-Type: text/html; charset=iso-8859-1 \r\n";
    } else {
        $headers .= "Content-Type: text/plain; charset=iso-8859-1 \r\n";
    }

    return @mail($usuario->getEmail(), $asunto, $mensaje, $headers);
}

function enviar_email_contrasenha(Usuario $usuario)
{
    $usuarioMapper = new UsuarioMapper();
    $codigo_validacion = $usuarioMapper->obtenerCodigoActivacionUsuario($usuario->getEmail());
    $url = getURL("restablecer");
    $nombre_origen = "MundoMovil";
    $email_origen = "mundomovil.info@gmail.com";
    $asunto = "Modificación de contraseña de usuario en MundoMovil";
    $mensaje = "Hola " . $usuario->getNomUsuario() . "<br>Para modificar su contraseña pulse en el siguiente enlace: <br>\t<b><a href='" . $url . $codigo_validacion["cod_validacion"] . "'>" . $url . $codigo_validacion["cod_validacion"] . "</a></b><br>Atentamente: equipo de MundoMovil";
    $formato = "html";

    $headers = "From: $nombre_origen <$email_origen> \r\n";
    $headers .= "Return-Path: <$email_origen> \r\n";
    $headers .= "Reply-To: $email_origen \r\n";
    $headers .= "X-Sender: $email_origen \r\n";
    $headers .= "X-Priority: 3 \r\n";
    $headers .= "MIME-Version: 1.0 \r\n";
    $headers .= "Content-Transfer-Encoding: 7bit \r\n";

    if ($formato == "html") {

        $headers .= "Content-Type: text/html; charset=iso-8859-1 \r\n";
    } else {
        $headers .= "Content-Type: text/plain; charset=iso-8859-1 \r\n";
    }

    return @mail($usuario->getEmail(), $asunto, $mensaje, $headers);
}