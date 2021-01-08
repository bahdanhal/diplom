<?php
namespace App;
use DB\DB;
use Authorization\Authorization;
class App
{
    private $usersDB;
    private $sessionsDB;
    private $authorization;
    public function __construct()
    {
        $this->usersDB = new DB('user');
        //$this->usersDB->create('aaa', 'aaa');
        //print_r($this->usersDB->findAll('aaa', 'aaa'));
        //$this->usersDB->update('aaa', 'aaa', 'aaa', '11');
        //$this->usersDB->delete('aaa', '11');
        
        $this->sessionsDB = new DB('session');
        //$this->sessionsDB->create('aaa', 'aaa');
        //print_r($this->sessionsDB->findAll('aaa', 'aaa'));
        //$this->sessionsDB->update('aaa', 'aaa', 'aaa', '11');
        //$this->sessionsDB->delete('aaa', 'aaa');
        $this->authorization = new Authorization($this->usersDB, $this->sessionsDB);
    }
    
    public function run()
    {
        if($this->authorization->getAuth($this->usersDB, $this->sessionsDB)){
            $this->render("View/hello.php");
            
        }
        $this->render("View/login.php");
        
    }
    
    public function signin()
    {
        return $this->authorization->signin($this->usersDB, $this->sessionsDB);
    }
    
    public function reg()
    {
        return $this->authorization->reg($this->usersDB, $this->sessionsDB);
    }
    
    public function render($filename)
    {
        echo file_get_contents($filename);
    }
}

