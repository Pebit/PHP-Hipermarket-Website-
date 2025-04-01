<?php
require_once "app/models/Purchase.php";
require_once "app/models/Item.php";
class PurchaseController{
    public static function index() {
        $return_url = isset($_POST["return_url"]) ? $_POST["return_url"] : "http://localhost/Site%20Hipermarket(1)/Hipermarket";
        if (isset($_GET['user_id']) && isset($_SESSION["request_user"]) && $_SESSION["request_user"]["user_id"] == $_GET['user_id']){
            // user looking at their own purchases
            $user_id = $_GET['user_id'];
            
            if($user_id){
                $purchases = Purchase::getUserPurchases($user_id);
                if ($purchases) {
                    require_once "app/views/users/purchases/user_index.php";
                } else {
                    $no_purchases = "This user doesn't have any purchases";
                    $return_url .= "?error=".urlencode($no_purchases)."&user_id=".urlencode($user_id);;
                    header("Location:".$return_url);
                }
            } else {
                $_SESSION['error'] = "User not found";
                    require_once "app/views/404.php";
            }
        } else if(isset($_GET['user_id']) && $_SESSION["request_user"]["role_id"] == 1) {
            // admin looking at another user's purchases 
            $user_id = $_GET['user_id'];
            
            if($user_id){
                $purchases = Purchase::getUserPurchases($user_id);
                if ($purchases) {
                    require_once "app/views/users/purchases/user_index.php";
                } else {
                    $no_purchases = "This user doesn't have any purchases";
                    $return_url .= "?error=".urlencode($no_purchases)."&user_id=".urlencode($user_id);;
                    header("Location:".$return_url);
                }
            } else {
                $_SESSION['error'] = "User not found";
                    require_once "app/views/404.php";
            }
        }else if(isset($_SESSION["request_user"]) && $_SESSION["request_user"]["role_id"] == 1){
            // admin looking at the purchases of all users
            $purchases = Purchase::getAllPurchases();
            require_once "app/views/users/purchases/index.php";
        } else {
            // user trying to look at others' purchases
            $_SESSION['error'] = "Invalid permissions";
            require_once "app/views/404.php";
        }
    }

    public static function show(){
        if(isset($_SESSION["create_purchase"])){
            $total_price = $_SESSION["create_purchase"]["purchase"]["total_price"];
            $credits = $_SESSION["create_purchase"]["purchase"]["purchase_credits"];
            // sold_items and their associated items "on the shelves" 
            $sold_items = Sold_Item::getPurchaseSold_Items($_SESSION["create_purchase"]["purchase"]["purchase_id"]);
            $items = []; 

            foreach ($sold_items as $sold_item) {
                $item = Item::getItem($sold_item["item_id"]); 
                if ($item) { 
                    $items[] = [
                        "item_id" => $item["item_id"],
                        "item_name" => $item["item_name"],
                        "price" => ($item["price"] * $sold_item["amount"]),
                        "amount" => $sold_item["amount"]
                    ]; 
                }
            }
            require_once "app/views/users/purchases/show.php";
        } else {
            $_SESSION['error'] = "Add items to cart before accessing this page";
            require_once "app/views/404.php";
        }
    }

    public static function add_to_cart() {
        $POST_item_id = isset($_POST["item_id"]) ? (int) $_POST["item_id"] : 0;
        $POST_amount = isset($_POST["amount"]) ? (int) $_POST["amount"] : 0;
        $return_url = isset($_POST["return_url"]) ? $_POST["return_url"] : "http://localhost/Site%20Hipermarket(1)/Hipermarket/"; 
        if($POST_item_id == 1){
            $_SESSION['error'] = "The purchase of \"store_credit\" is not allowed";
            require_once "app/views/404.php";
            return;
        }
        if(isset($_SESSION["create_purchase"])){
            // if a purchase already exists in the current session:
            if($POST_amount != 0){
                // if there's at least 1 item:
                $item = Item::getItem($POST_item_id);
                $purchase = $_SESSION["create_purchase"]["purchase"]; 
                $item_price = $POST_amount * $item["price"]; 
                
                $purchase["total_price"] = $purchase["total_price"] + $item_price;
                $purchase["purchase_credits"] = (int)($purchase["total_price"] / 3);

                // update $_SESSION variables
                $_SESSION["create_purchase"]["purchase"] = [
                    "purchase_id" => $purchase["purchase_id"],
                    "user_id" => $purchase["user_id"],
                    "total_price" => $purchase["total_price"],
                    "purchase_credits" => $purchase["purchase_credits"],
                    "purchase_date" => $purchase["purchase_date"],
                    "status" => $purchase["status"]
                ];

                // update the "purchases" table and create the "sold_items" item associated with the transaction
                Purchase::updatePurchase(
                    $purchase["purchase_id"],
                    $purchase["total_price"], 
                    $purchase["purchase_credits"]);

                // if we already have an identical sold item, we only update the amount
                $sold_items = Sold_Item::getPurchaseSold_Items($purchase["purchase_id"]);
                var_dump($sold_items);
                echo($purchase["purchase_id"]);
                foreach ($sold_items as $sold_item){
                    if ($sold_item["item_id"] == $item["item_id"]){
                        $sold_item["amount"] += $POST_amount;
                        Sold_Item::updateSold_Item($sold_item["item_id"], $sold_item["purchase_id"], $sold_item["amount"]);
                        header("Location: " . $return_url);
                        return;
                    }
                }
                Sold_Item::createSold_Item(
                    $item["item_id"], 
                    $purchase["purchase_id"], 
                    $POST_amount);
            }
            header("Location: " . $return_url);
        }

        if(!isset($_SESSION["create_purchase"])){
            // when there's no purchase:
            if(isset($_SESSION["request_user"])){
                // if the user is logged in:
                if($POST_amount != 0){
                    // if there's at least one item

                    $user_id = $_SESSION["request_user"]["user_id"];
                    $item = Item::getItem($POST_item_id);
                    $item_price = $POST_amount * $item["price"];
                    $credits = (int) ($item_price / 3);
                    
                    // purchase created in the database
                    Purchase::createPurchase(
                        $user_id, 
                        $item_price,
                        $credits,
                        0);
                    
                    $purchase = Purchase::getUserLastPurchase($user_id);
                    
                    if (!$purchase) {
                        $_SESSION['error'] = "failed to create Purchase";
                        require_once "app/views/404.php";
                        return;
                    }
                    // create the purchase session variables
                    $_SESSION["create_purchase"]["purchase"] = [
                        "purchase_id" => $purchase["purchase_id"],
                        "user_id" => $purchase["user_id"],
                        "total_price" => $purchase["total_price"],
                        "purchase_credits" => $purchase["purchase_credits"],
                        "purchase_date" => $purchase["purchase_date"],
                        "status" => $purchase["status"]
                    ];
                    // we create the sold item using the current item and purchase
                    Sold_Item::createSold_Item(
                        $item["item_id"], 
                        $purchase["purchase_id"], 
                        $POST_amount);

                    header("Location: " . $return_url);
                } else {
                    // if there's no item, then we only create an empty purchase
                    $user_id = $_SESSION["request_user"]["user_id"];
                    Purchase::createPurchase($user_id, 0,0,0);
                    $purchase = Purchase::getUserLastPurchase($user_id);
                    
                    $_SESSION["create_purchase"]["purchase"] = [
                        "purchase_id" => $purchase["purchase_id"],
                        "user_id" => $purchase["user_id"],
                        "total_price" => $purchase["total_price"],
                        "purchase_credits" => $purchase["purchase_credits"],
                        "purchase_date" => $purchase["purchase_date"],
                        "status" => $purchase["status"]
                    ];
                    header("Location: " . $return_url);
                }
            } else {
                // if there's no user => error
                $_SESSION['error'] = "User not logged in";
                require_once "app/views/404.php";
            }
        }
    }

    // helper functions
    public static function sold_item_validation($sold_item, &$item_id_errors){
        
        $item = Item::getItem($sold_item["item_id"]);
        
        if(!$item){
            // if the item was erased from the database while browsing => error
            $item_id_errors["missing_error"] = $item["item_name"]; 
            return 0;
        }
        if ($item["stock"] < 0){
            // if we're out of stock, we have to tell the user 
            // the maximum amount of items available for them
            $available_amount = $item["stock"] + $sold_item["amount"]; 
            $item_id_errors["amount_error"][] = $available_amount."x ".$item["item_name"];
            return 0;
        }
        return 1;
    }
    
    // total sum recalculation using list of given sold_items
    public static function sum_recalc($sold_items){
        
        $sum = 0;
        foreach($sold_items as $sold_item){
            $item = Item::getItem($sold_item["item_id"]);
            $sum += $item["price"] * $sold_item["amount"];
        }
        return $sum;
    }

    // we modify the store's stock of that sold_item (grab)
    public static function grab_from_shelf($sold_item){
        $item = Item::getItem($sold_item["item_id"]);
        if (!$item){
            return;
        }
        
        $new_amount = $item["stock"] - $sold_item["amount"];
        Item::updateItem($item["item_id"], $item["item_name"], $item["expiration_date"], $item["price"], 
            $new_amount);
    }

    // we modify the store's stock of that sold_item (put back)
    public static function put_back_on_shelf($sold_item){
        $item = Item::getItem($sold_item["item_id"]);
        if (!$item){
            return;
        }
        $old_amount = $item["stock"] + $sold_item["amount"];
        Item::updateItem($item["item_id"], $item["item_name"], $item["expiration_date"], $item["price"], 
            $old_amount);
    }
    
    // function for finishing a transaction
    public static function finish(){
        $return_url = $_POST["return_url"];
        if (isset($_SESSION["create_purchase"])){
            $purchase = $_SESSION["create_purchase"]["purchase"];
            $sold_items = Sold_Item::getPurchaseSold_Items($purchase["purchase_id"]);
            if(empty($sold_items))
            {
                $errors = "cannot finish an empty purchase";
                header("Location: " . $return_url ."&error=".$errors);
                return;
            }
            // removing the items bought from the database
            foreach ($sold_items as $sold_item){
                self::grab_from_shelf($sold_item);
            }
            
            // validating the transaction data
            $item_id_errors = [];
            foreach ($sold_items as $sold_item){
                self::sold_item_validation($sold_item, $item_id_errors);
            }
            // if errors are found => error message telling the customer what to do to fix the issue
            if (isset($item_id_errors["missing_error"]))
            {
                $errors = "the following items are missing from the database: ";
                foreach ($item_id_errors["missing_error"] as $error){
                    $errors .= $error." ";
                }
                $errors = $errors."\r\n If the problem persists, contact the site administrator.\r\n";
            }
            if(isset($item_id_errors["amount_error"])){
                $errors = "Too many items, store's stock: ";
                foreach ($item_id_errors["amount_error"] as $error){
                    $errors = $errors.$error." ";
                }
            }
            // re-adding each item back into the database
            if(isset($item_id_errors["amount_error"]) || isset($item_id_errors["missing_error"])){
                foreach ($sold_items as $sold_item){
                    self::put_back_on_shelf($sold_item);
                }
                header("Location: " . $return_url ."&error=".$errors);
                return;
            }
            // we verify the sum just in case
            $new_sum = self::sum_recalc($sold_items);
            // modified sum -> modified credits
            $new_credits = (int) ($new_sum / 3);
            if($purchase["total_price"] != $new_sum){
                $purchase["totlal_price"] = $new_sum;
                $purchase["purchase_credits"] = $new_credits;
                Purchase::updatePurchase(
                $purchase["purchase_id"],
                $purchase["total_price"],
                $purchase["purchase_credits"]);
            }

            // everything good => finish purchase (status = 1, purchase_date = <current date>)
            Purchase::finishPurchase($purchase["purchase_id"]);
            // making room for a possible new purchase
            unset($_SESSION["create_purchase"]);
            header("Location: /Site%20Hipermarket(1)/Hipermarket");
        }
        else{
            // if there's no purchase => error (normal user won't get here)
            $_SESSION['error'] = "no purchase available";
            require_once "app/views/404.php";
        }
    }
    public static function remove_from_cart(){
        $return_url = isset($_POST["return_url"]) ? $_POST["return_url"] : "http://localhost/Site%20Hipermarket(1)/Hipermarket/";
        
        $c_amount = isset($_POST["current_amount"]) ? (int)($_POST["current_amount"]) : 0;
        $rm_amount = isset($_POST["remove_amount"]) ? (int)($_POST["remove_amount"]) : 0;
        
        // error management
        if ($c_amount == 0 || $rm_amount == 0 || $c_amount < $rm_amount) {
            $_SESSION["cart_error"] = ($c_amount == 0) ? 
                "Failed getting item amount in cart (0 items)" : 
                (($c_amount < $rm_amount) ? "Amount chosen to be removed must be smaller than current amount" : 
                "Amount chosen to be removed must be bigger than 0");
        
            header("Location: " . $return_url);
            return;
        }
        
        
        $item_id = $_POST["item_id"];
        $purchase = $_SESSION["create_purchase"]["purchase"];
        $new_amount = $c_amount - $rm_amount;
        
        if ($new_amount == 0){
            // if all current item amount is removed then we eliminate the item from the list
            
            Sold_Item::deleteSold_Item($item_id, $purchase["purchase_id"]);
            $sold_items = Sold_Item::getPurchaseSold_Items($purchase["purchase_id"]);
            // we re-evaluate the sum and credits after removing the item
            $new_total = self::sum_recalc($sold_items);
            $new_credits = (int)($new_total/3);
            
            Purchase::updatePurchase($purchase["purchase_id"], $new_total, $new_credits);
            $_SESSION["create_purchase"]["purchase"]["total_price"] = $new_total = self::sum_recalc($sold_items);
            $_SESSION["create_purchase"]["purchase"]["purchase_credits"] = $new_credits;
            
            header("Location: " . $return_url);
            return;
        }
        // if only a portion of the amount was removed we just modify "amount"
        
        Sold_Item::updateSold_Item($item_id, $purchase["purchase_id"], $new_amount);

        // re-evaluate the sum and credits after removing the item
        $sold_items = Sold_Item::getPurchaseSold_Items($purchase["purchase_id"]);
        $new_total = self::sum_recalc($sold_items);
        $new_credits = (int)($new_total/3);

        Purchase::updatePurchase($purchase["purchase_id"], $new_total, $new_credits);
        $_SESSION["create_purchase"]["purchase"]["total_price"] = $new_total;
        $_SESSION["create_purchase"]["purchase"]["purchase_credits"] = $new_credits;

        header("Location: " . $return_url);
        return;
    }
}
?>