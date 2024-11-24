<?php
require_once "app/models/Item.php";

class ItemController{
    public static function index() {
        try{
            $items = Item::getAllItems();
            require_once "app/views/Items/index.php";
        } catch (Exception $e){
            $_SESSION['error'] = "Error fetching items: " . $e->getMessage();
            require_once "app/views/404.php";
        }
    }

    public static function show() {
        $item_id = $_GET['item_id'];
        $item = Item::getItem($item_id);

        if ($item) {
            //require_once "app/views/items/show.php";
        } else {
            $_SESSION['error'] = "Item not found";
            require_once "app/views/404.php";
        }
    }
}
?>