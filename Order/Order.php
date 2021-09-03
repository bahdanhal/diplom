<?php
namespace Order;
use DB\DB;

class Order
{
    private $DB;

    public function __construct()
    {
        global $_GLOBALS;
        $this->DB = $_GLOBALS['DB'];
    }

    public function getOrdersList()
    {
        return $this->DB->findBy('orders', false, false, false, (isset($_GET['page']) && $_GET['page'] > 0)
            ? [($_GET['page'] - 1) * 10, $_GET['page'] * 10]
            : [0, 10]
        );
        
    }

    public function getOrder($id)
    {
        return $this->DB->findBy('orders', 'id', $id);
    }

    public function getOrderItems($id)
    {
        return $this->DB->findBy('order_items', 'order_id', $id);
    }

    public function deleteOrder($id){
        $this->DB->delete('orders', ['id' => $id]);
        $this->DB->delete('order_items', ['order_id' => $id]);
    }
}