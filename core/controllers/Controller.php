<?php

namespace Core\Controllers;


use \PicORM\PicORM;
use Core\Helpers\Flashbag;
use Core\Helpers\Twig;
use Core\Helpers\Url;

class Controller
{
    protected $twig;
    protected $url;
    protected $flashbag;
    protected $db = null;
    protected $pdo;
    

    function __construct()
    {
        $this->url = new Url();
        $this->flashbag = new Flashbag();
        $this->twig = new Twig($this->flashbag);
        $this->setOrm();
    }

    private function setOrm() 
    {
        if ($this->db == null && getenv('MYSQL_DATABASE') != '') {
            $coucou = PicORM::configure(array(
                'datasource' => new \PDO('mysql:dbname='.getenv('MYSQL_DATABASE').';host='.getenv('MYSQL_HOST').'', getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES '.getenv('MYSQL_CHARSET')))
            ));

            $this->pdo = PicORM::getDataSource();
        }
    }

}