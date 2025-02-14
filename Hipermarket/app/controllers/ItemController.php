<?php
require_once "app/models/User.php";
require_once "app/models/Item.php";

class ItemController{
    public static function index() {
        try{
            $create_permission = (
                isset($_SESSION["request_user"])  &&
                User::hasPermission($_SESSION["request_user"]["user_id"], "create_item")
            );
            $update_permission = (
                isset($_SESSION["request_user"])  &&
                User::hasPermission($_SESSION["request_user"]["user_id"], "update_item")
            );
            $delete_permission = (
                isset($_SESSION["request_user"])  &&
                User::hasPermission($_SESSION["request_user"]["user_id"], "delete_item")
            );
            $purchase_permission = (
                isset($_SESSION["request_user"])  &&
                User::hasPermission($_SESSION["request_user"]["user_id"], "purchase_item")
            );

            if(isset($_SESSION["request_user"]) && $_SESSION["request_user"]["role_id"] == 1){
                $items = Item::getAllItems();
                require_once "app/views/Items/index.php";
            }else {
                $items = Item::getAllUnexpiredItems();
                require_once "app/views/Items/index.php";
            }
        } catch (Exception $e){
            $_SESSION['error'] = "Error fetching items: " . $e->getMessage();
            echo("problema in ItemController.php"); 
            require_once "app/views/404.php";
        }
    }

    public static function show() {
        $item_id = $_GET['item_id'];
        $item = Item::getItem($item_id);

        if ($item) {
            $update_permission = (
                isset($_SESSION["request_user"])  &&
                User::hasPermission($_SESSION["request_user"]["user_id"], "update_item")
            );
            require_once "app/views/items/show.php";
        } else {
            $_SESSION['error'] = "Item not found";
            require_once "app/views/404.php";
        }
    }

    static function data_validation() {
        $errors = [];
        $len_name = strlen($_POST['item_name']);
        if ($len_name < 1 || $len_name > 182) {
            $errors['item_name_error'] = 'Item name must be between 1 and 128 characters';  
        }

        $current_date = Item::getSysdate();
        if ($current_date == false) {
            $errors['expiration_date_error'] = '(SERVER-SIDE) Error fetching current date';
        } else {
            $expiration_date = $_POST['expiration_date'];
            if ($expiration_date && DateTime::createFromFormat('Y-m-d', $expiration_date) != false) {
                if ($expiration_date < $current_date && $_POST['item_name'] != 'store_credit') {
                    $errors['expiration_date_error'] = 'Adding expired items is prohibited';
                }
            } else {
                $errors['expiration_date_error'] = 'Invalid expiration date provided';
            }
        }
        
        if ($_POST['price'] < 0 && $_POST['item_name'] != 'store_credit') {
            $errors['price_error'] = 'Adding negative item values is prohibited';
        }
        if ($_POST['stock']!="" && $_POST['stock'] < 0) {
            $errors['stock_error'] = 'Adding negative item amounts is prohibited';
        }
        

        return $errors;
    }

    public static function edit(){
        if (!isset($_SESSION["request_user"]) || !User::hasPermission($_SESSION["request_user"]["user_id"], "update_item")){
            $_SESSION["error"]= "Invalid permissions";
            require_once "app/views/404.php";
            return;
        }

        $item_id = $_GET['item_id'] ? $_GET['item_id'] : $_POST['item_id'];
        $item = Item::getItem($item_id);

        if (!$item) {
            $_SESSION['error'] = "Item not found";
            require_once "app/views/404.php";
            return;
        }
        
        // POST
        if (isset($_POST['item_id'])) {
            // empty session variables
            $_SESSION["edit_item"] = [];
            // Data validation
            $errors = self::data_validation();
            if (count($errors) > 0) {
                $_SESSION["edit_item"] = $errors;
                header("Location: edit?item_id=".$_POST['item_id']);
                return;
            }

            Item::updateItem(
                $item_id,
                htmlentities($_POST['item_name']),
                htmlentities($_POST['expiration_date']),
                htmlentities($_POST['price']),
                htmlentities($_POST['stock'])
            );

            $_SESSION['success'] = 'Record updated';
            header("Location: edit?item_id=".$_POST['item_id']);
            return;
        }
        else {
            require_once "app/views/items/edit.php";
        }
    }
    public static function create() {
        if (!isset($_SESSION["request_user"]) || !User::hasPermission($_SESSION["request_user"]["user_id"], "create_item")){
            $_SESSION["error"]= "Invalid permissions";
            require_once "app/views/404.php";
            return;
        }
        if (isset($_POST["is_post"])){
            // POST => create item
            $_SESSION["create_item"]["item"] = $_POST;

            $errors = self::data_validation();
            if (count($errors) > 0){
                $_SESSION["create_item"]["errors"] = $errors;
                header("Location: create");
                return;
            }
            Item::createItem(
                htmlentities($_POST["item_name"]), 
                htmlentities($_POST["expiration_date"]),
                htmlentities($_POST["price"]), 
                htmlentities($_POST["stock"])
            );
            header("Location: index");
        }
        // GET => show form
        if (!isset($_SESSION["create_item"]["item"])){
            $_SESSION["create_item"]["item"] = [
                "item_name" => "",
                "expiration_date" => "",
                "price" => "",
                "stock" => ""
            ];
        }
        require_once "app/views/items/create.php";
    }
    public static function delete() {
        if (!isset($_SESSION["request_user"]) || !User::hasPermission($_SESSION["request_user"]["user_id"], "delete_item")){
            $_SESSION["error"]= "Invalid permissions";
            require_once "app/views/404.php";
            return;
        }
        $item_id = $_GET["item_id"];

        item::deleteItem($item_id);

        header("Location: index");
        return;
    }
    
}    
?>