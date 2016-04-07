<?php

define("DEFAULT_CONTROLLER", "noticia");
define("DEFAULT_ACTION", "index");

$poscicion = 0;
$existe_controlador = 0;

/**
 * Metodo que carga el controlador pasado en la URL y llama al metodo de ese controlador
 * que tambien es pasado en la URL.
 * La URL puede ser de 2 maneras:
 *      1. ../index.php?controller=noticia&action=ver&id=1 (Pasado por GET)
 *      2. ../noticia/ver?id=1 (Rutas semiamigables)
 * Si la URL es del primer caso se instancia el controlador pasado por get y se ejecuta su
 * metodo.
 * Si la URL es del segundo caso, se comprueba si el controlador y la accion estan en la URI:
 *          -Si estan, se obtienen y se instancian
 *          -Si no estan se toma un controlador y una accion por defecto y se instancian.
 */

function run()
{
    try {
        /**
         * Busca el controlador en caso de no ser pasado por GET
         */
        if (!isset($_GET["controller"])) {
            $controllerURI = getControllerFromURI();
            if ($controllerURI != null) {
                $_GET["controller"] = $controllerURI;
            } else {
                $_GET["controller"] = DEFAULT_CONTROLLER;
            }
        }

        /**
         * Busca la accion en caso de no ser pasada por GET
         */

        if (!isset($_GET["action"])) {
            $actionURI = getActionFromURI();
            if ($actionURI != null) {
                $_GET["action"] = $actionURI;
            } else {
                $_GET["action"] = DEFAULT_ACTION;
            }
        }

        /**
         * Carga el controlador correspondiente y crea una instancia de el
         */
        $controller = loadController($_GET["controller"]);

        /**
         * Llama a la accion o metodo correspondiente
         */
        $actionName = $_GET["action"];
        $controller->$actionName();
    } catch (Exception $ex) {
        die("Ha ocurrido una excepcion! " . $ex->getMessage());
    }
}

/**
 * Metodo que separa la URI por '/', almacenando las palabras en un array.
 * Ese array se recorre buscando el controlador.
 * Si el controlador no se encuentra se redirecciona a la pagina de inicio
 * de la aplicacion:
 *      Para ello se comprueba si el directorio de la aplicacion esta
 *      en la URI, en caso de estar se crea una URI de redireccion eliminando
 *      el sobrante de la URI principal.
 *      Si la URI no termina en barra (significa que
 *      sería un fichero y no una carpeta) ni en /index.php se redirige a la URI de
 *      redireccion.
 *      En caso de terminar en barra (significa que es una carpeta ) se comprueba
 *      que esa carpeta no coincide con la que contiene la aplicacion y se redirige,
 *      en caso contrario $controlador se deja a null para que tome el controlador por
 *      defecto.
 */

function getControllerFromURI()
{
    $URI = $_SERVER['REQUEST_URI'];
    $array = explode("/", $URI);
    /**
     * $arrayControladores es un array en el que se incluyen los controladores existentes
     * en el sistema, para tratar de encontrarlos en la URI actual y determinar que
     * controlador es el encargado de realizar la accion
     */
    $arrayControladores = array("noticia","tutorial","foro","mensaje","comentario","pregunta","respuesta","usuario");
    $controlador = null;
    $cont = 0;

    foreach ($array as $a) {
        if (in_array($a, $arrayControladores)) {
            $controlador = $a;
            $GLOBALS['existe_controlador'] = true;
            $GLOBALS['posicion'] = $cont;
            break;
        } else {
            $cont++;
        }
    }

    if ($controlador == null) {
        $directorio = getcwd();
        $d_actual = explode("/", $directorio);
        $d_actual = $d_actual[count($d_actual) - 1];
        $pos = strpos($URI, $d_actual);

        if ($pos == false) {
            $redireccion = "/";
        } else {
            $redireccion = substr($URI, 1, $pos - 1) . $d_actual;
        }

        if (substr($URI, -1) != '/') {
            if ($URI != "/index.php") {
                header("Location: /" . $redireccion . "");
            }
        } else {
            if ($d_actual != $array[count($array) - 2] && $URI != '/') {
                header("Location: /" . $redireccion . "");
            }
        }
    }

    return $controlador;
}

/**
 * Metodo similar a getControllerFromURI cuyo objetivo en este caso es obtener la
 * accion a realizar por el controlador.
 * En este caso se descompone la URI por las '/' y si existe controlador se obtiene
 * la accion, en caso contrario se deja a null para que tome la accion por defecto.
 */

function getActionFromURI()
{
    $URI = $_SERVER['REQUEST_URI'];
    $array = explode("/", $URI);
    $accion = null;

    if ($GLOBALS['existe_controlador'] == 1) {
        $pos = $GLOBALS['posicion'] + 1;
        $array2 = explode("?", $array[$pos]);
        $accion = $array2[0];
    }

    return $accion;

}

/**
 * Carga el fichero del controlador requerido y crea una instancia de el
 * @param $controllerName Es el nombre del controlador encontrado en la URI
 * @return Object Una instancia del controlador
 */

function loadController($controllerName)
{
    $controllerClassName = getControllerClassName($controllerName);

    require_once(__DIR__ . "/controller/" . $controllerClassName . ".php");
    return new $controllerClassName();
}

/**
 * @param $controllerName
 * @return string
 * Metodo que devuelve el nombre del controlador pasado como parametro en el
 * formato de nombres de controladores correspondiente.
 * Por ejemplo: $controllerName = "noticia" devolverá NoticiaController
 */

function getControllerClassName($controllerName)
{
    return strtoupper(substr($controllerName, 0, 1)) . substr($controllerName, 1) . "Controller";
}

run();