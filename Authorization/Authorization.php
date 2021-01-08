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
        if (isset($_SESSION['session']) and isset($_SESSION['user_login'])){
            return true;
        }
        
        //~ проверяем наличие кук
        if (isset($_COOKIE['user_id']) and isset($_COOKIE['session'])) {
            //~ куки есть - сверяем с таблицей сессий
            $user_id = $sessionsDB->screening($_COOKIE['user_id']);
            $session = $sessionsDB->screening($_COOKIE['session']);
            $node = $sessionsDB->find("id", $user_id);
            if (isset($node) and $node['user_id'] == $user_id and $node['session'] == $session
                and $node['user_agent_sess']==$_SERVER['HTTP_USER_AGENT']) {
                //~ Есть запись в таблице сессий, сверяем данные
                    //~ Данные верны, стартуем сессию
                $this->setSession($user_id, $usersDB->find('user_id', $user_id)['login'], $session);
                return true;
            }
        }
        return false;
    }
    
    private function setSession($user_id, $login, $session)
    {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_login'] = $login;
        //~ обновляем куки
        setcookie("user_id", $user_id, time()+3600*24*14);
        setcookie("session", $session, time()+3600*24*14);
    }
    
    public function signin($usersDB, $sessionsDB) {
        $login = $usersDB->screening($_POST['login']);
        $password = md5($usersDB->screening($_POST['password']).'s a l t'); //~ хеш пароля с солью
        $user = $usersDB->find('login', $login);
        $response['ok'] = false;
        $response['password_error'] = false;
        $response['login_error'] = false;
        if (isset($user) and $user['password'] == $password) {
            //~ пользователь найден в бд, логин совпадает с паролем
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_login'] = $login;
            //~ добавляем/обновляем запись в таблице сессий и ставим куку
            $r_code = $this->generateCode(15);
            if ($sessionsDB->find('id_user', $_SESSION['id_user'])) {
                //~ запись уже есть - обновляем
                $sessionsDB->update('id_user', $_SESSION['id_user'], 'code_sess', $r_code);
            } else {
                //~ записи нету - добавляем
                $sessionsDB->update('id_user', $_SESSION['id_user'], 'user_agent_sess', $_SERVER['HTTP_USER_AGENT']);
            }
            //~ ставим куки на 2 недели
            setcookie("id_user", $_SESSION['id_user'], time()+3600*24*14);
            setcookie("code_user", $r_code, time()+3600*24*14);
            //return true;
            $response['ok'] = true; 
        } else {
            //~ пользователь не найден в бд, или пароль не соответствует введенному
            if ($usersDB->find('login_user', $login)){ 
                $response['password_error'] = true;
            }
            else{
                $response['login_error'] = true;
            }
            //$_SESSION['error'] = $this->error_print($error);
            //return false;
        }
        echo json_encode($response);
    }
    
    public function reg($usersDB, $sessionsDB) {
        ;
    }
    
    function generateCode($length) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;
        while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0,$clen)];
        }
        return $code;
    }
    
    public function getAuth($usersDB, $sessionsDB){
        return $this->auth;
    }
}