<?php
namespace App;
use DB\DB;
use User\User;
class App
{
    private $DB;
    private $User;
    /**
     * 
     */
    public function __construct()
    {
        $this->DB = new DB("localhost", "site", "root", "");
        $this->user = new User($this->DB);
    }
    
    /**
     * application start
     */
    public function run()
    {
        
        if(!empty($this->user->getAuth())){

            $this->render("View/hello.php");
            
        }
        $this->render("View/login.php");
        
        
    }
    
    /**
     * 
     * @return array with response about login
     */
    public function signin()
    {
        return $this->user->signin($this->DB);
    }
    
    /**
     * 
     * @return array with response about registration
     */
    public function reg()
    {
        return $this->user->reg($this->DB, $this->DB);
    }
    
    /**
     * 
     * @param string $filename with html template
     */
    public function render($filename)
    {
        if($filename == "View/hello.php"){
            $name = $this->DB->findBy("users", "user_id", $_COOKIE["user_id"])["name"];
        }
        require_once ($filename);
        exit;
    }
}

