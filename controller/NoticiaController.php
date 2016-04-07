<?php

require_once(__DIR__."/../controller/BaseController.php");
require_once(__DIR__."/../model/Noticia.php");
require_once(__DIR__."/../model/NoticiaMapper.php");

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

    public function index(){
        $this->view->render("noticia","principalNoticia");
    }

}