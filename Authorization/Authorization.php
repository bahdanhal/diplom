<?php
namespace Authorization;
class Authorization
{
    private $auth;
    public function __construct($usersDB, $sessionsDB)
    {
        $this->auth = $this->check($usersDB, $sessionsDB);
    }
    
    private function check($usersDB, $sessionsDB)
    {
        if (isset($_SESSION['user_id']) and isset($_SESSION['login'])){
            return true;
        }
        
        if (isset($_COOKIE['user_id']) and isset($_COOKIE['session_code'])) {
            $user_id = $sessionsDB->screening($_COOKIE['user_id']);
            $session = $sessionsDB->screening($_COOKIE['session_code']);
            $node = $sessionsDB->find("user_id", $user_id);
            if (isset($node) and $node['user_id'] == $user_id and $node['session_code'] == $session) {
               
                //есть запись в таблице сессий, сверяем данные
                $this->setSession($user_id, $usersDB->find('user_id', $user_id)['login'], $session);
                return true;
            }
        }
        return false;
    }
    
    private function setSession($user_id, $login, $session)
    {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['login'] = $login;
        //~ обновляем куки
        setcookie("user_id", $user_id, time() + 3600 * 24 * 14);
        setcookie("session_code", $session, time() + 3600 * 24 * 14);
    }
    
    public function signin($usersDB, $sessionsDB) {
        $login = $usersDB->screening($_POST['login']);
        $password = md5($usersDB->screening($_POST['password']).'s a l t'); //~ хэш пароля с солью
        $user = $usersDB->find('login', $login);
        $response['ok'] = false;
        $response['password_error'] = false;
        $response['login_error'] = false;
        if (isset($user) and $user['password'] == $password) {
            //пользователь найден в бд, логин совпадает с паролем
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['login'] = $login;
         
            $r_code = $this->generateCode(15);
            if (!$sessionsDB->find('user_id', $_SESSION['user_id'])) {
                $sessionsDB->create('user_id', $_SESSION['user_id']);
            }
            $sessionsDB->update('user_id', $_SESSION['user_id'],
                'session_code', $sessionsDB->screening($r_code));
            //2 недели
            setcookie("user_id", $_SESSION['user_id'], time() + 3600 * 24 * 14);
            setcookie("session_code", $sessionsDB->screening($r_code), time() + 3600 * 24 * 14);
            $response['ok'] = true; 
        } else {
            //~ пользователь не найден в бд, или пароль не соответствует введенному
            if ($usersDB->find('login', $login)){ 
                $response['password_error'] = true;
            }
            else{
                $response['login_error'] = true;
            }

        }
        echo json_encode($response);
    }
    
    public function reg($usersDB) {
        $response = $this->validate($usersDB);
        if (($response['ok'])==true) {
            
            $password = md5($usersDB->screening($_POST['password']).'s a l t'); 
            $login = $usersDB->screening($_POST['login']);
            $user_id = ($usersDB->max('user_id') + 1);
            //print_r($user_id);
            $usersDB->create('user_id', $user_id);
            $usersDB->update('user_id', $user_id, 'login', $login);
            $usersDB->update('user_id', $user_id, 'password', $password);
            $usersDB->update('user_id', $user_id, 'email', $_POST['email']);
            $usersDB->update('user_id', $user_id, 'name', $_POST['name']);
        }
        echo json_encode($response);
    }
    
    public function validate($usersDB) {
        //~ Проверка валидности данных
        $login = $_POST['login'];
        $password = $_POST['password'];
        $password_repeat = $_POST['confirm_password'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $response['ok'] = true;
        $response = $this->responseInit($response);
        if (empty($login) or empty($password) or 
            empty($password_repeat) or empty($name) or empty($email)){
            $response = $this->responseChange($response, 'fields');//все поля обязательны
        }
        if ($password != $password_repeat){
            $response = $this->responseChange($response, 'no_coincidence');//'Введенные пароли не совпадают';
        }
        if (!preg_match('/^([0-9a-zA-Z]{6,})$/', $login)){
            $response = $this->responseChange($response, 'login_error');
        }
        if (!preg_match('/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{6,}$/',$password)){
                $response = $this->responseChange($response, 'password_error'); //'Длина пароля должна быть от 6 символов, пароль должен'.
            //' содержать как минимум одну цифру, минимум один спецсимвол и буквы в разных регистрах';
        }
  
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $response = $this->responseChange($response, 'email_error');//'Проверьте правильность введенного email';
        }
        if (!preg_match('/^([0-9a-z]{2,})$/', $name)){
            $response = $this->responseChange($response, 'name_error'); //'Длина имени должна быть от 2 символов, имя может состоять только из букв и цифр';
        }

        $login = $usersDB->screening($login);
        
        if ($usersDB->find('login', $login)){
            $response = $this->responseChange($response, 'login_repeat');
        }
        if ($usersDB->find('email', $email)){
            $response = $this->responseChange($response, 'email_repeat');//'Пользователь с таким email уже существует';
        }
        
        return $response;
    }
    
    public function generateCode($length) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;
        while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0,$clen)];
        }
        return $code;
    }
    
    private function responseInit($response) {
        $response['fields'] = false;
        $response['no_coincidence'] = false;
        $response['login_error'] = false;
        $response['password_error'] = false;
        $response['email_error'] = false;
        $response['name_error'] = false;
        $response['login_repeat'] = false;
        $response['email_repeat'] = false;

        
        return $response;
    }
    
    private function responseChange($response, $field) {
        $response[$field] = true;
        $response['ok'] = false;
        
        return $response;
    }
    
    
    
    public function getAuth($usersDB, $sessionsDB){
        return $this->auth;
    }
}