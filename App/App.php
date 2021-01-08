<?php
namespace App;
use DB\DB;
use Authorization\Authorization;
class App
{
    private $usersDB;
    private $sessionsDB;
    private $authorization;
    /**
     * 
     */
    public function __construct()
    {
        $this->usersDB = new DB('users');
        $this->sessionsDB = new DB('sessions');
        $this->authorization = new Authorization($this->usersDB, $this->sessionsDB);
    }
    
    /**
     * application start
     */
    public function run()
    {
        
        if(!empty($this->authorization->getAuth($this->usersDB, $this->sessionsDB))){

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
        return $this->authorization->signin($this->usersDB, $this->sessionsDB);
    }
    
    /**
     * 
     * @return array with response about registration
     */
    public function reg()
    {
        return $this->authorization->reg($this->usersDB, $this->sessionsDB);
    }
    
    /**
     * 
     * @param string $filename with html template
     */
    public function render($filename)
    {
        if($filename == "View/hello.php"){
            $name = (string)$this->usersDB->find('user_id', $_COOKIE['user_id'])->name;
        }
        
        require_once ($filename);
        exit;
    }
}

