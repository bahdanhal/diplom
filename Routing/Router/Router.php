<?php

namespace Router;
use Data\Data;
use View\View;
use CatalogItem\CatalogItem;
use Basket\Basket;
use Order\Order;
class Router
{
    private $app;
    private $page;
    private static $view;      
    public function run()
    {
        global $_GLOBALS;
        self::$view = new View();
        $data = new Data();
        if(isset($_GET['route'])){
            if(array_key_exists(htmlspecialchars(stripslashes(trim($_GET['route']))),$data->data["pages"])){
                $this->page = $data->data["pages"][htmlspecialchars(stripslashes(trim($_GET['route'])))];
            }else{
                if(array_key_exists(substr(htmlspecialchars(stripslashes(trim($_GET['route']))),0,-1),$data->data["pages"])){
                    $this->page = $data->data["pages"][substr(htmlspecialchars(stripslashes(trim($_GET['route']))),0,-1)];
                } else 
                $this->page = $data->data["pages"]["default"];
            }
        }else{
            $this->page = $data->data["pages"]["default"];         
        }
        $page = $this->page;
        $methodname = "action_$page";
        $params['user'] = $_GLOBALS['user'];
        self::$view->load("header.php", $params);
        self::$methodname();
        self::$view->load("footer.php", $params);
    }
 
    public static function action_index()
    { 
        $catalog = new CatalogItem();
        $params['items'] = $catalog->getCatalog();
        if(empty($params['items'])){
            self::action_404();
            return;
        }
        $basket = new Basket();
        global $_GLOBALS;
        if(!$_GLOBALS['user']->getAuth()){
            if(isset($_GET['nonAuthBuy']) && isset($_REQUEST['description'])){        
                $basket->nonAuthOrder($_GET['nonAuthBuy'], $_REQUEST['description']);
            }

            $params['nonAuth'] = true;
            self::$view->load("main.php", $params);
            return;
        }

        $user = $_GLOBALS['user']->getFields();

        if(isset($_GET['addToBasket'])){        
            $basket->addToBasket($user['id'], $_GET['addToBasket'], 
                (isset($_POST['quantity']) && $_POST['quantity'] > 0) ? $_POST['quantity'] : 1);
        }

        self::$view->load("main.php", $params);
        
    }

    public static function action_registration()
    { 
        global $_GLOBALS;
        if($_GLOBALS['user']->getAuth()){
            header('/');
        } else {
            self::$view->load("registration.php");
        }
    }

    public static function action_login()
    { 
        //Инициализируем модель вида
        global $_GLOBALS;
        if($_GLOBALS['user']->getAuth()){
            header('/');
        } else {
            self::$view->load("login.php");
        }
    }

    public static function action_exit()
    {
        session_start();

        $_SESSION = array();
        setcookie("user_id", '', time()-3600);
        setcookie("session_code", '', time()-3600);

        session_unset();
        session_destroy();
        header("Location: /index");
        exit();
    }

    public static function action_cabinet()
    { 
        global $_GLOBALS;
        if($_GLOBALS['user']->getAuth()){
            $params['user'] = $_GLOBALS['user']->getFields();
            self::$view->load("hello.php", $params);
        } else {
            self::action_404();
            return;
        }
    }

    public static function action_catalog_admin()
    {
        global $_GLOBALS;
        $user = $_GLOBALS['user']->getFields();
        if(!$_GLOBALS['user']->getAuth() && !$user['status'] == 'admin'){
            self::action_404();
            return;
        }

        $params['user'] = $user;

        $catalog = new CatalogItem();
        if(isset($_GET['add'])){
            $catalog->addItem();
        }
        if(isset($_GET['delete'])){
            $catalog->deleteItem($_GET['delete']);
        }
        if(isset($_REQUEST['id']) && isset($_REQUEST['element'])){
            $element = $_REQUEST['element'];
            if ($_FILES && $_FILES["filename"]["error"]== UPLOAD_ERR_OK){
                $name = "upload/" . $_FILES["filename"]["name"];
                move_uploaded_file($_FILES["filename"]["tmp_name"], $name);
                $element['photo']=$name;
            }
            if(isset($_REQUEST['deletePhoto']) && $_REQUEST['deletePhoto'] == true){
                $element['photo']='';
            }
            $catalog->updateItem($_REQUEST['id'], $element);
        }
        $params['items'] = $catalog->getCatalog();    
        if(empty($params['items'])){
            self::action_404();
            return;
        }
        self::$view->load("catalog.php", $params);
    }

    public static function action_orders_admin()
    {
        global $_GLOBALS;
        $user = $_GLOBALS['user']->getFields();
        if(!$_GLOBALS['user']->getAuth() && !$user['status'] == 'admin'){
            self::action_404();
            return;
        }
        $orders = new Order();
        if(isset($_GET['order'])){
            $params['order'] = $orders->getOrder($_GET['order'])[0];
            $params['orderItems'] = $orders->getOrderItems($_GET['order']);
            self::$view->load("order.php", $params);
            return;
        }
        $params['orders'] = $orders->getOrdersList();
        self::$view->load("orders.php", $params);
    }

    public static function action_basket()
    {
        global $_GLOBALS;
        if(!$_GLOBALS['user']->getAuth()){
            self::action_404();
            return;
        }
        $user = $_GLOBALS['user']->getFields();
        
        $basket = new Basket();
        $basketItems = $basket->getBasket($user['id']);
        if(empty($basketItems)){
            $params['empty'] = true;
            self::$view->load("basket.php", $params);
            return;
        }
        if(isset($_GET['confirmOrder'])){
            $basket->createOrder($user['id'], $_REQUEST['description']);
            $basketItems = array();
            $params['empty'] = true;
            $params['created'] = true;
        }

        $items = [];

        $catalog = new CatalogItem();

        $sum = 0;
        foreach($basketItems as $basketItem){
            $items[$basketItem['item_id']] = $catalog->getItem($basketItem['item_id'])[0];
            $items[$basketItem['item_id']]['quantity'] = $basketItem['quantity'];
            $sum += $items[$basketItem['item_id']]['price'] * $basketItem['quantity'];
        }
        $params['items'] = $items;
        $params['sum'] = $sum;
        self::$view->load("basket.php", $params);
    }


    public static function action_404()
    { 
        header("HTTP/1.0 404 Not Found");
        self::$view->load("404.php");
    }
}
?>