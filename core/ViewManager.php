<?php

/**
 * Class ViewManager
 *
 * Esta clase implementa el pegamento que permite hacer que el controlador
 * se relacione con la vista. Sin ser de manera directa.
 *
 * Esta clase es un singleton. Deberia usarse getInstance() para conseguir
 * una instancia de view manager.
 *
 * Los objetivos de esta clase son:
 *
 * 1. Guardar las variables del controlador y hacer que esten disponibles
 * para las vistas. Incuyen variables "flash", las cuales son variables
 * que se mantienen en sesion y se eliminan justo despues de ser recuperadas.
 *
 * 2. Renderizar las vistas. Esto basicamente realiza un 'include' del fichero
 * de la vista, pero con los parametros de una orientacion modelo vista controlador.
 *
 * 3. Diseño del sistema. Basado en los bufers de salida de PHP. Una vez la vista es
 * inicializada, el bufer de salida es activado. Por defecto, todos los contenidos
 * que se generan en las vistas son salvados en el DEFAULT_FRAGMENT.
 * El DEFAULT_FRAGMENT se usa normalmente como los contenidos principales del layout
 * resultante. Sin embargo, puedes generar contenidos para otros fragmentos que pueden
 * ir en tu layout.
 */
class ViewManager
{
    /**
     * Clave para el fragmento por defecto
     */

    const DEFAULT_FRAGMENT = "__default__";

    /**
     * @var array
     * Contenidos almacenados temporalmente por cada fragmento
     */

    private $fragmentContents = array();

    /**
     * @var array
     * Valores de las variables de las vistas
     */

    private $variables = array();

    /**
     * @var string
     * Nombre del fragmento actual donde se acumula la salida
     */
    private $currentFragment = self::DEFAULT_FRAGMENT;

    /**
     * @var string
     * Nombre del layout que se utilizara en el renderLayout()
     */
    private $layout = "default";

    /**
     * ViewManager constructor.
     */
    public function __construct()
    {
        /**
         * Si no existe sesion se crea una y se activa el almacenamiento
         * en bufer de salida
         */
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        ob_start();
    }

    /**
     * Metodo que permite obtener la ruta para la etiqueta base de html.
     * Permite especificar una URL y un destino por defecto para
     * todos los enlaces relativos del documento web.
     * @return mixed|string
     */
    public function getBase()
    {
        $URI = $_SERVER['REQUEST_URI'];
        $array = explode("/", $URI);

        /**
         * Se elimina la primera y la ultima poscion del array si estan vacias
         */
        if ($array[0] == "") {
            unset($array[0]);
        }
        $pos_ult = count($array);
        if ($array[$pos_ult] == "") {
            unset($array[$pos_ult]);
        }

        /**
         * Se crea la direccion base hasta que se encuentra algun controlador
         */
        $arrayControladores = array("noticia","tutorial","foro","mensaje","comentario","pregunta","respuesta","usuario");
        $base = "";
        foreach ($array as $a) {
            if (in_array($a, $arrayControladores)) {
                break;
            } else {
                $base .= $a . "/";
            }
        }

        /**
         * Si se encuentra la index.php/ en la direccion base se elimina
         */
        if (strpos($base, "php/") != false) {
            $base = str_replace("index.php/", "", $base);
        }

        return $base;
    }
    /// GESTION DE BUFFER
    /**
     * Se guarda el contenido del bufer de salida en el fragmento actual.
     * Se limpia el bufer de salida.
     */

    private function guardarFragmentoActual()
    {
        //Se guarda fragmento actual
        $this->fragmentContents[$this->currentFragment] .= ob_get_contents();
        //Se limpia el bufer de salida
        ob_clean();
    }

    /**
     * Se cambia la salida del fragmento actual. Para ello se guarda
     * el fragemento actual antes de ser cambiado.
     * Las subsecuencias de salida se acumularan en el fragmento especificado.
     * @param $nombre. Nombre del fragmento que se va a guardar
     */

    public function moverAFragmento($nombre)
    {
        $this->guardarFragmentoActual();
        $this->currentFragment = $nombre;
    }

    /**
     * Cambia al fragmento por defecto.
     * La salida actual se guarda antes de cambiar.
     * La subsecuencia de salida sera acumulada en el fragmento por defecto.
     */
    public function moverAFragmentoPorDefecto()
    {
        $this->moverAFragmento(self::DEFAULT_FRAGMENT);
    }

    /**
     * Obtiene los contenidos acumulados en un fragmento especifico.
     * @param $fragment Nombre del fragmento para devolver sus contenidos
     * @param string $default
     * @return string
     */
    public function getFragment($fragment, $default = "")
    {
        if (!isset($this->fragmentContents[$fragment])) {
            return $default;
        }
        return $this->fragmentContents[$fragment];
    }


    /// GESTION DE VARIABLES

    /**
     * Establece una variable para la vista
     *
     * Las variables podrian estar mantenidas tambien en sesion (via parametro $flash)
     * @param $nombre
     * @param $valor
     * @param bool|false $flash
     */
    public function setVariable($nombre, $valor, $flash = false)
    {
        $this->variables[$nombre] = $valor;
        //una variable flash sera cargada en session_start
        if ($flash == true) {
            if (!isset($_SESSION["viewmanager__flasharray__"])) {
                $_SESSION["viewmanager__flasharray__"][$nombre] = $valor;
                print_r($_SESSION["viewmanager__flasharray__"]);
            } else {
                $_SESSION["viewmanager__flasharray__"][$nombre] = $valor;
            }
        }
    }

    /**
     * Devuelve la variable previamente establecida.
     *
     * Si la variable es una variable flash, se elimina de la sesion
     * despues de haber sido recuperada
     *
     * @param $nombre
     * @param null $default Si la variable no existe se devuelve algun valor
     * para esa variable por defecto.
     * @return null
     */

    public function getVariable($nombre, $default = NULL)
    {
        if (!isset($this->variables[$nombre])) {
            if (isset($_SESSION["viewmanager__flasharray__"]) && isset($_SESSION["viewmanager__flasharray__"][$nombre])) {
                $toRet = $_SESSION["viewmanager__flasharray__"][$nombre];
                unset($_SESSION["viewmanager__flasharray__"][$nombre]);
                return $toRet;
            }
            return $default;
        }
        return $this->variables[$nombre];
    }

    /**
     * Se establece un mensaje flash
     * Los mensajes flash son utiles para pasar texto de una pagina a otra
     * via HTTP redirects, siendo mantenidos en sesion
     * @param $flash_mensaje Mensaje para mantener en sesion
     */

    public function setFlash($flash_mensaje)
    {
        $this->setVariable("__flashmensaje__", $flash_mensaje, true);
    }

    /**
     * Devuelve el mensaje flash y lo elimina
     * @return null
     */

    public function popFlash()
    {
        return $this->getVariable("__flashmensaje__", "");
    }

    ///RENDERIZADO


    /**
     * Establece la layout que va a ser usada cuando renderLayout sea llamado
     * @param $layout
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    /**
     * Renderiza la vista especificada del controlador especificado
     *
     * Si el controlador es $controlador=micotrolador y $vista=myvista
     * se seleccionara el fichero php: /view/micontrolador/mivista.php
     *
     * Si se usa un layout (via setLayout) o el layout por defecto no ha
     * sido especificado. Llama antes a setLayout
     *
     * @param $controlador
     * @param $vista
     */

    public function render($controlador, $vista)
    {
        include(__DIR__ . "/../view/$controlador/$vista.php");
        $this->renderLayout();
    }

    /**
     * Envia un HTTP 302 redirection a la action dentro del controlador
     * @param $controlador
     * @param $accion
     * @param null $cadenaPregunta
     */
    public function redirect($controlador, $accion, $cadenaPregunta = NULL)
    {
        header("Location: ../$controlador/$accion" . (isset($cadenaPregunta) ? "?$cadenaPregunta" : ""));
        die();
    }

    /**Envia un HTTP 302 redirection a la pagina de referencia, que es
     * la pagina en donde estaba el usuario, justo antes de hacer la
     * solicitud actual.
     * @param null $cadenaPregunta
     */
    public function redirectToReferer($cadenaPregunta = NULL)
    {
        header("Location: " . $_SERVER["HTTP_REFERER"] . (isset($cadenaPregunta) ? "&$cadenaPregunta" : ""));
        die();
    }

    /**
     * Renderiza el layout
     * Basicamente incluye el fichero /view/layouts/[layout].php.
     */

    public function renderLayout()
    {
        /**
         * Se mueve el fragmento layout con todos los contenidos generados
         * previamente salvandolos in $this->fragmentContents
         */
        $this->moverAFragmento("layout");
        /**
         * dibuja el layout.
         */
        include(__DIR__ . "/../view/layouts/" . $this->layout . ".php");

        ob_flush();
    }

    /**
     * Singleton
     */

    private static $viewmanager_singleton = NULL;

    public static function getInstance()
    {
        if (self::$viewmanager_singleton == NULL) {
            self::$viewmanager_singleton = new ViewManager();
        }
        return self::$viewmanager_singleton;
    }
}

/**
 * Se fuerza a una primera instanciacion de viewManager ya que la salida del bufer
 * se necesita que este incluida en los casos en los que ni el controlador ni la vista
 * consiguieron instanciar al ViewManager
 */
ViewManager::getInstance();