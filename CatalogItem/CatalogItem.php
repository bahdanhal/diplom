<?php
namespace CatalogItem;
use DB\DB;

class CatalogItem
{
    private $DB;

    public function __construct()
    {
        global $_GLOBALS;
        $this->DB = $_GLOBALS['DB'];
    }

    public function getCatalog()
    {
        return $this->DB->findBy('catalog_items', false, false, false, (isset($_GET['page']) && $_GET['page'] > 0)
            ? [($_GET['page'] - 1) * 10, $_GET['page'] * 10]
            : [0, 10]
        );
    }

    public function getItem($id)
    {
        return $this->DB->findBy('catalog_items', 'id', $id);
    }

    public function addItem($values = array()){
        return $this->DB->create('catalog_items', $values);
    }

    public function updateItem($id, $values){
        $this->DB->update('catalog_items', 'id', $id, $values);
    }

    public function deleteItem($id){
        $this->DB->delete('catalog_items', ['id' => $id]);
    }
}