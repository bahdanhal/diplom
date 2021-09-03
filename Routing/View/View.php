<?php
namespace View;
class View{
 
    public function load($filename, $params = false){
        $path = ROOT_DIR."/View/$filename";
        if(file_exists($path)){
            require($path);
        }else{
            $path = ROOT_DIR."/View/404.php";
            require_once($path);
        }
    }
}