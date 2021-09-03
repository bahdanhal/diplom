<?php
namespace Basket;
use DB\DB;
use CatalogItem\CatalogItem;

class Basket
{
    private $DB;

    public function __construct()
    {
        global $_GLOBALS;
        $this->DB = $_GLOBALS['DB'];
    }

    public function getBasket($user_id)
    {
        return $this->DB->findBy('basket_items', 'user_id', $user_id);
    }

    public function removeFromBasket($user_id, $item_id)
    {
        $this->DB->delete('basket_items', [
            'user_id' => $user_id,
            'item_id' => $item_id,
            ]
        );
    }

    public function addToBasket($user_id, $item_id, $quantity = 1){
        $this->removeFromBasket($user_id, $item_id);
        $this->DB->create('basket_items', [
            'user_id' => $user_id,
            'item_id' => $item_id,
            'quantity' => $quantity,
            ]
        );
    }

    public function createOrder($user_id, $description){
        $basket = $this->getBasket($user_id);
        $sum = 0;
        $catalog = new CatalogItem();
        foreach($basket as $basketItem){
            $items[$basketItem['item_id']] = $catalog->getItem($basketItem['item_id'])[0];
            $items[$basketItem['item_id']]['quantity'] = $basketItem['quantity'];
            $sum += $items[$basketItem['item_id']]['price'] * $basketItem['quantity'];
        }
        $orderId = $this->DB->create('orders', [
                'user_id' => $user_id,
                'description' => $description,
                'status' => 'not paid',
                'sum' => $sum,
            ]
        );        
        
        if(!$basket){
            echo "<h1>Корзина пуста</h1>";
        } 
        foreach($basket as $basketItem){
            $item = $this->DB->findBy('catalog_items',  'id', $basketItem['item_id'])[0];
            
            $this->DB->create('order_items', [
                    'order_id' => $orderId,
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'category_id' => $item['category_id'],
                    'quantity' => $basketItem['quantity']
                ]

            );
        }
        
        $this->DB->delete('basket_items', ['user_id' => $user_id]);
    }

    public function nonAuthOrder($itemId, $description){
        $orderId = $this->DB->create('orders', [
                'description' => $description,
                'status' => 'not paid',
            ]
        );

        $item = $this->DB->findBy('catalog_items',  'id', $itemId)[0];
            
        $this->DB->create('order_items', [
                'order_id' => $orderId,
                'name' => $item['name'],
                'price' => $item['price'],
                'category_id' => $item['category_id'],
                'quantity' => 1
            ]

        );
        
    }
}