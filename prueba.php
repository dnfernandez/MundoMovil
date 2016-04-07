<?php

require_once(__DIR__ . "/./model/NoticiaMapper.php");
require_once(__DIR__ . "/./model/ComentarioNoticiaMapper.php");
require_once(__DIR__ . "/./model/UsuarioMapper.php");
require_once(__DIR__ . "/./model/MensajeMapper.php");
require_once(__DIR__ . "/./model/TutorialMapper.php");
require_once(__DIR__ . "/./model/ComentarioTutorialMapper.php");
require_once(__DIR__ . "/./model/ForoMapper.php");
require_once(__DIR__ . "/./model/PreguntaMapper.php");
require_once(__DIR__ . "/./model/RespuestaMapper.php");

$noticia = new Noticia(1, "escoba", "asa", "asa as", "2.", "asd", "2015-02-02 00:00:00", 1);
$noticiaMapper = new NoticiaMapper();

/*$noticiaMapper->eliminar($noticia->getIdNoticia());
$noticiaMapper->insertar($noticia);
$noticia2 = new Noticia(1,"escobeta","asadora","asa as","2.","asd","2016-02-02 00:00:00",1);
$noticiaMapper->actualizar($noticia2);*/

$noticias = $noticiaMapper->listarNoticiasFiltro(1, null, null, null);
if ($noticias != null) {
    foreach ($noticias as $not) {
        echo $not["id_noticia"] . " ---- Fecha: " . $not["fecha"] . "<br>";
    }
}

/*$lnotId = $noticiaMapper->listarNoticiaPorId(10);

if($lnotId!=null){
    echo "<br>".$lnotId["id_noticia"]. " ---- Fecha: ". $not["fecha"]."<br>";
}*/

echo "<br><br>";

$comentarioMapper = new ComentarioNoticiaMapper();
$comentarioN1 = new ComentarioNoticia(null, 1, 1, "asdasdsa", "2015-02-02 00:00:00", null);
$comentarioN2 = new ComentarioNoticia(null, 1, 1, "as", "2015-02-02 00:00:00", 3);

//$comentarioMapper->insertar($comentarioN1);
//$comentarioMapper->insertar($comentarioN2);

$noticiasCom = $comentarioMapper->listarComentariosPorNoticia(1, 1);
if ($noticiasCom != null) {
    foreach ($noticiasCom as $notC) {
        $cadena = "";
        $cont = 1;
        foreach ($notC as $val) {
            if ($cont < count($notC)) {
                $cadena .= $val . " --- ";
            }
            $cont++;
        }
        $cadena .= $notC[0];
        echo $cadena . "<br>";

    }
}

$usuario = new Usuario(2, "manolo", "man@gmail.com", "ourense", "abc123.", "2.jpg", null, "2016-05-05 00:00:00", "administrador");
$usuarioMapper = new UsuarioMapper();
if (!$usuarioMapper->existe($usuario->getIdUsuario(), null, null)) {
    $usuarioMapper->insertar($usuario);
}
$usuario2 = new Usuario(2, "manolete", "man@gmail.com", "ourenses", "abc123.", "2.jpg", null, "2016-05-05 00:00:00", "administrador");
$usuarioMapper->actualizar($usuario2);

$usuario3 = new Usuario(null, "diego1570", "diego8787@gmail.com", "ourense", "abc123.", "3.jpg", null, "2016-05-05 00:00:00", "administrador");
if (!$usuarioMapper->existe(4)) {
    if (!$usuarioMapper->existe(null, $usuario3->getNomUsuario())) {
        if (!$usuarioMapper->existe(null, null, $usuario3->getEmail())) {
            $usuarioMapper->insertar($usuario3);
        } else {
            echo "<br>Ya existe email_usuario";
        }
    } else {
        echo "<br>Ya existe nom_usuario";
    }
} else {
    echo "<br>Ya existe id_usuario";
}

$user = $usuarioMapper->listarUsuarioPorId(2);
echo "<br>" . $user[0] . " -- " . $user[11] . "<br>";

$users = $usuarioMapper->listarUsuarios(1, "manolete", null);
foreach ($users as $user) {
    echo "<br>" . $user[0] . " -- " . $user[11];
}

if ($usuarioMapper->comprobarUsuario($usuario2->getEmail(), $usuario2->getContrasenha())) {
    $usuarioMapper->actualizarFechaConexion($usuario2->getIdUsuario());
}

$usuarioMapper->actualizarRolUsuario(1);

echo "<br>Estado1:  " . $usuarioMapper->comprobarEstadoUsuario(2);
echo "<br>Estado2:  " . $usuarioMapper->comprobarEstadoUsuario(null, "man@gmail.com");

$usuarioMapper->actualizaBaneoUsuario(2);

echo "<br>Baneado1:  " . $usuarioMapper->comprobarBaneoUsuario(2);
echo "<br>Baneado2:  " . $usuarioMapper->comprobarBaneoUsuario(null, "man@gmail.com");

$usuarioMapper->comprobarCodigoValidacion("220451494120427");

echo "<br>Estado3:  " . $usuarioMapper->comprobarEstadoUsuario(4);


$mensaje = new Mensaje(null, "paparrapa", "2015-02-02 00:00:00", 1, 4);
$mensajeMapper = new MensajeMapper();
//$mensajeMapper->insertar($mensaje);

echo "<br>";
echo "Mensaje recibido<br>";

$cont = 0;
foreach ($mensajeMapper->listarMensajeRecibido(2) as $m) {
    if ($cont % 2 != 0) {
        if ($cont < 10) {
            echo $m . " -- ";
        } else {
            echo $m;
        }
    }
    $cont++;
}

echo "<br>";
echo "<br>";
echo "Enviados:<br>";

foreach ($mensajeMapper->listarMensajesEnviados() as $men) {
    $cont = 0;
    foreach ($men as $m) {
        if ($cont % 2 != 0) {
            if ($cont < 10) {
                echo $m . " -- ";
            } else {
                echo $m;
            }
        }
        $cont++;
    }
    echo "<br>";
}


echo "<br>";
echo "Recibidos<br>";

foreach ($mensajeMapper->listarMensajesRecibidos() as $men) {
    $cont = 0;
    foreach ($men as $m) {
        if ($cont % 2 != 0) {
            if ($cont < 10) {
                echo $m . " -- ";
            } else {
                echo $m;
            }
        }
        $cont++;
    }
    echo "<br>";
}

$tutorial = new Tutorial (null, "tutorial asd", "android samsung", "texto", "2015-12-12 00:00:00", 1);
$tutorialMapper = new TutorialMapper();
//$tutorialMapper->insertar($tutorial);
$tutorial2 = new Tutorial (10, "tutorialasdasd", "android samsung", "texto asd", "2015-12-12 00:00:00", 1);
$tutorialMapper->actualizar($tutorial2);
$tutorialMapper->eliminar(9);
if ($tutorialMapper->existe(10)) {
    print_r($tutorialMapper->listarPorId(10));
}

echo "<br><br>";

foreach ($tutorialMapper->listarTutorialesFiltro(1) as $tuto) {
    echo $tuto["id_tutorial"] . " --- " . $tuto["pal_clave"] . "<br>";
}


$comentarioT1 = new ComentarioTutorial(null, 1, 1, "asdasd asd asd", "2025-05-25 00:00:00", 1);
$comentarioTMapper = new ComentarioTutorialMapper();
//$comentarioTMapper->insertar($comentarioT1);

echo "<br><br>";

foreach ($comentarioTMapper->listarComentariosPorTutorial(1, 1) as $c) {
    echo $c["id_com_tutorial"] . " --- " . $c["texto"] . " --- " . $c["id_com_respondido"] . " --- " . $c[0] . "<br>";
}

echo "<br>FOROOOOOOOOO<br>";

$foro = new Foro(4, "forofo", "es un foro", "2015-01-02 00:00:00", 1);
$foroMapper = new ForoMapper();
//$foroMapper->insertar($foro);
$foroMapper->actualizar($foro);
$foroMapper->eliminar(3);

foreach ($foroMapper->listarForos() as $foro) {
    echo $foro["id_foro"] . " -- " . $foro["titulo"] . " -- " . $foro[0] . "<br>";
}

echo "<br>PREGUNTA<br>";

$pregunta = new Pregunta(2, 1, "preguntada", "asd asda", "asda sdadasd a", "2015-02-02 00:00:00", 1);
$preguntaMapper = new PreguntaMapper();
//$preguntaMapper->insertar($pregunta);
$preguntaMapper->actualizar($pregunta);
$preguntaMapper->eliminar(5);

foreach ($preguntaMapper->listarPreguntasPorForo(1, 1) as $preg) {
    echo $preg["id_pregunta"] . " --- " . $preg["id_foro"] . " --- " . $preg[0] . "<br>";
}

echo "<br>";

foreach ($preguntaMapper->listarPreguntasFiltradas(1, "ojo", null, null) as $preg) {
    echo $preg["id_pregunta"] . " --- " . $preg["id_foro"] . " --- " . $preg[0] . "<br>";
}

$respuesta = new Respuesta(null, 1, 1, "respuesta", "2015-12-12 00:00:00", 5, 3, 1);
$respuestaMapper = new RespuestaMapper();
//$respuestaMapper->insertar($respuesta);

foreach ($respuestaMapper->listarRespuestasPorPregunta(1, 1) as $res) {
    echo "Id respuesta: " . $res["id_respuesta"] . " --- Id usuario: " . $res["id_usuario"] . " --- Nombre usuario: " . $res["nom_usuario"] . "<br>";
}

//$respuestaMapper->votarRespuestas(2,1,1);
//$respuestaMapper->votarRespuestas(1,1,0);

echo "<br>RESPUESTA USUARIO ACTIVO<br>";

foreach ($respuestaMapper->listarRespuestasUsuario(1, 1, 1) as $res) {
    echo "Id respuesta: " . $res["id_respuesta"] . " --- Id usuario: " . $res["id_usuario"] . " --- Nombre usuario: " .
        $res["nom_usuario"] . " --- Tipo: " . $res["tipo_votacion"] . "<br>";
}
$tot = $respuestaMapper->contarTotal(1);
echo "<br>Total de algo: " . $tot;
$tot = $respuestaMapper->contarPositivos(1);
echo "<br>Total de positivos: " . $tot;
$tot = $respuestaMapper->contarNegativos(1);
echo "<br>Total de negativos: " . $tot;

$user32 = $usuarioMapper->listarUsuarioConcreto(null, "diego@gmail.com");
echo "<br>Usuario concreto: " . $user32[0] . " -- " . $user32[11] . "<br>";
print_r($user32);

echo "<br>El ultimo id es: " . $usuarioMapper->obtenerUltimoIdUsuario()["max_id"];