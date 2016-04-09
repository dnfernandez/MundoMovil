<?php

require_once(__DIR__ . "/../controller/BaseController.php");
require_once(__DIR__ . "/../model/Noticia.php");
require_once(__DIR__ . "/../model/NoticiaMapper.php");

class NoticiaController extends BaseController
{
    private $noticiaMapper;

    /**
     * NoticiaController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->noticiaMapper = new NoticiaMapper();
    }

    public function index()
    {
        if(isset($_GET["pag"])){
            $noticias = $this->noticiaMapper->listarNoticiasFiltro($_GET["pag"], null, null, null);
        }else {
            $noticias = $this->noticiaMapper->listarNoticiasFiltro(1, null, null, null);
        }
        $total = $this->noticiaMapper->contarNoticias()["total"];
        $this->view->setVariable("noticias", $noticias);
        $this->view->setVariable("total", $total);
        $this->view->render("noticia", "principalNoticia");
    }


}