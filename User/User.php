<?php
namespace User;
use DB\DB;

class User
{
    private $auth;
    private $DB;
    private $fields;
    /**
     * 
     * @param DB $DB
     */
    public function __construct($DB)
    {
        $this->DB = $DB;
        $this->auth = $this->check();
    }
    
    /**
     * 
     * @return boolean
     */
    private function check()
    {
        if (isset($_SESSION["user_id"]) and isset($_SESSION["login"])){
            $this->fields = $this->DB->findBy("users", "id", $_SESSION["user_id"])[0]?? false;
            return $this->fields?true:false;
        }
        
        if (isset($_COOKIE["user_id"]) and isset($_COOKIE["session_code"])) {
            $user_id = $_COOKIE["user_id"];
            $session = $_COOKIE["session_code"];
            $node = $this->DB->findBy("sessions","user_id", $user_id)[0] ?? false;
            if ($node and $node["user_id"] == $user_id and (string)$node["session_code"] == $session) {
                //есть запись в таблице сессий, сверяем данные
                $this->fields = $this->DB->findBy("users", "id", $user_id)[0] ?? false;
                $this->setSession($user_id, $this->fields["login"], $session);
                return true;
            } 
        }
        return false;
    }
    
    /**
     * 
     * @param string $user_id
     * @param string $login
     * @param string $session
     */
    private function setSession($user_id, $login, $session)
    {
        $_SESSION["user_id"] = $user_id;
        $_SESSION["login"] = $login;
        setcookie("user_id", $user_id, time() + 3600 * 24 * 14, "/");
        setcookie("session_code", $session, time() + 3600 * 24 * 14, "/");
    }
    
    public function signin() {
        $login = $_POST["login"];
        $password = md5($_POST["password"]."s a l t"); //~ хэш пароля с солью
        $user = $this->DB->findBy("users", "login", $login)[0] ?? false;
        $response["ok"] = false;
        $response["login_error"] = false;
        if ($user and $user["password"] == $password) {
            
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["login"] = $login;
            $r_code = $this->generateCode(15);
            
            if (!isset($this->DB->findBy("sessions", "user_id", $_SESSION["user_id"])[0])) {
                $this->DB->create(
                    "sessions", 
                    ["user_id" => $_SESSION["user_id"]]
                );
            }
            $this->DB->update("sessions", "user_id", $_SESSION["user_id"],
                ["session_code" => $r_code]);
            //2 недели
            setcookie("user_id", $_SESSION["user_id"], time() + 3600 * 24 * 14, "/");
            setcookie("session_code", $r_code, time() + 3600 * 24 * 14, "/");
            $response["ok"] = true; 
        } else {
            if ($user){ 
                $response["password_error"] = true;
            }
            else{
                $response["login_error"] = true;
            }

        }
        echo json_encode($response);
    }
    
    /**
     * 
     * @param DB $usersDB for new user registration
     */
    public function reg() {
        $response = $this->validate();
        if (($response["ok"]) == true) {
            
            $password = md5($_POST["password"]."s a l t"); 
            $login = $_POST["login"];
            if(
                !$this->DB->create(
                    "users",
                    [
                        "login" => $login,
                        "password" => $password,  
                        "email" => $_POST["email"],
                        "name" => $_POST["name"],
                    ]
                )
            ){
                throw new Exception("Registration error");
            };

        }
        echo json_encode($response);
    }
    
    /**
     * 
     * @return array with checked password
     */
    public function validate() {
        //~ Проверка валидности данных
        $login = $_POST["login"];
        $password = $_POST["password"];
        $password_repeat = $_POST["confirm_password"];
        $name = $_POST["name"];
        $email = $_POST["email"];
        $response["ok"] = true;
        $response = $this->responseInit($response);
        if (empty($login) or empty($password) or 
            empty($password_repeat) or empty($name) or empty($email)){
            $response = $this->responseChange($response, "fields");//все поля обязательны
        }
        if ($password != $password_repeat){
            $response = $this->responseChange($response, "no_coincidence");//"Введенные пароли не совпадают";
        }
        if (!preg_match("/^([0-9a-zA-Z]{6,})$/", $login)){
            $response = $this->responseChange($response, "login_error");
        }
        if (!preg_match("/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{6,}$/",$password)){
            $response = $this->responseChange($response, "password_error"); //"Длина пароля должна быть от 6 символов, пароль должен".
            //" содержать как минимум одну цифру, минимум один спецсимвол и буквы в разных регистрах";
        }
  
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $response = $this->responseChange($response, "email_error");//"Проверьте правильность введенного email";
        }
        if (!preg_match("/^([0-9a-z]{2,})$/", $name)){
            $response = $this->responseChange($response, "name_error"); //"Длина имени должна быть от 2 символов, имя может состоять только из букв и цифр";
        }
        
        if ($this->DB->findBy("users", "login", $login)[0] ?? false){
            $response = $this->responseChange($response, "login_repeat");
        }
        if ($this->DB->findBy("users", "email", $email)[0] ?? false){
            $response = $this->responseChange($response, "email_repeat");//"Пользователь с таким email уже существует";
        }
           //$response["ok"] = true;    
        return $response;
    }
    
    /**
     * 
     * @param int $length
     * @return string
     */
    public function generateCode($length) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;
        while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0,$clen)];
        }
        return $code;
    }
    
    /**
     * 
     * @param array $response
     * @return array
     */
    private function responseInit($response) {
        $response["fields"] = false;
        $response["no_coincidence"] = false;
        $response["login_error"] = false;
        $response["password_error"] = false;
        $response["email_error"] = false;
        $response["name_error"] = false;
        $response["login_repeat"] = false;
        $response["email_repeat"] = false;

        
        return $response;
    }
    
    /**
     * 
     * @param array $response
     * @param string $field
     * @return array
     */
    private function responseChange($response, $field) {
        $response[$field] = true;
        $response["ok"] = false;
        
        return $response;
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function getAuth(){
        return $this->auth;
    }

    public function getFields(){
        return $this->fields;
    }
}