<?php
namespace Auth;
use User\User;
use DB\DB;
class Auth
{
    private $user;

    public  function __construct($user)
    {
        $this->user = $user;
        
    }
    
    /**
     * 
     * @return array with response about login
     */
    public function signin()
    {
        return $this->user->signin();
    }
    
    /**
     * 
     * @return array with response about registration
     */
    public function reg()
    {
        return $this->user->reg();
    }
}
    