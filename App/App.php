<?php
namespace App;
use DB\DB;
use User\User;
use Router\Router;
class App
{
    private $DB;
    public $user;

    public function __construct()
    {
        $this->DB = new DB("localhost", "site", "root", "");
        $this->user = new User($this->DB);
        global $_GLOBALS; 
        $_GLOBALS['user'] = $this->user;
        $_GLOBALS['DB'] = $this->DB;
    }
    
    public function run()
    {
        $router = new Router(); 
        $router->run();    
        
    }
    
}

