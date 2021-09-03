<?php
 
namespace Data;

class Data{
 
    public $data;
 
    function __construct() {   
        $this->data = array(
            "pages" => array(
                "/index" => "index",
                "/" => "index",
                "/registration" => "registration",
                "/dashboard" => "dashboard",
                "/exit" => "exit",
                "default" => "404",
                "/login" => "login",
                "/cabinet" => "cabinet",
                "/cabinet/catalog" => "catalog_admin",
                "/cabinet/orders" => "orders_admin",
                "/cabinet/basket" => "basket"
            ),
        );
    }
}
?>