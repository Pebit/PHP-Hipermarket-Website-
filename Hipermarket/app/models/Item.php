<?php
class Item {
    public static function getAllItems() {
        global $pdo;
        try {
            $sql = "SELECT *
                    FROM items
                    ORDER BY expiration_date ASC";
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database query error: " . $e->getMessage());
            return false;
        }
    }

    public static function getAllUnexpiredItems() {
        global $pdo;
        try {
            $sql = "SELECT *
                    FROM items
                    WHERE expiration_date > CURDATE()
                    ORDER BY item_name ASC";
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database query error: " . $e->getMessage());
            return false;
        }
    }

    public static function getItem($item_id) {
        global $pdo;
        try{
            $sql = "SELECT * 
                    FROM items 
                    WHERE item_id = :item_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(":item_id" => $item_id));
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e){
            error_log("Database query error: " . $e->getMessage());
            return false;
        }
    }
    public static function updateItem($item_id, $item_name, $expiration_date, $price, $stock) {
        global $pdo;

        $sql = "UPDATE items
                SET item_name = :item_name, expiration_date = :expiration_date, price = :price, stock = :stock
                WHERE item_id = :item_id";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute(array(
            ":item_id" => $item_id,
            ":item_name" => $item_name,
            ":expiration_date" => $expiration_date,
            ":price" => $price,
            ":stock" => $stock
        ));
    }
    public static function deleteItem($item_id) {
        global $pdo;

        $sql = "DELETE FROM items
                WHERE item_id = :item_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([":item_id" => $item_id]);
    }
    public static function createItem($item_name, $expiration_date, $price, $stock) {
        global $pdo;

        $sql = "INSERT INTO items (item_name, expiration_date, price, stock)
                VALUES (:item_name, :expiration_date, :price, :stock)";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute(array(
            ":item_name" => $item_name,
            ":expiration_date" => $expiration_date,
            ":price" => $price,
            ":stock" => $stock
        ));
    }

    

    public static function getSysdate(){
        try {
            global $pdo;
            // MySQL query to fetch the formatted current date
            $sql = "SELECT DATE_FORMAT(SYSDATE(), '%Y-%m-%d') AS  `current_date`";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['current_date'];
        } catch (PDOException $e) {
            error_log("Database query error: " . $e->getMessage());
            return false;
        }
    }    
}
class Sold_Item {
    public static function createSold_Item($item_id, $purchase_id, $amount) {
        global $pdo;

        $sql = "INSERT INTO sold_items (item_id, purchase_id, amount)
                VALUES (:item_id, :purchase_id, :amount)";
        $stmt = $pdo->prepare($sql);

        $stmt->execute(array(
            ":item_id" => $item_id,
            ":purchase_id" => $purchase_id,
            ":amount" => $amount
        ));
    }

    public static function updateSold_Item($item_id, $purchase_id, $amount) {
        global $pdo;

        $sql = "UPDATE sold_items
                SET amount = :amount
                WHERE item_id = :item_id AND purchase_id = :purchase_id";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute(array(
            ":item_id" => $item_id,
            ":purchase_id" => $purchase_id,
            ":amount" => $amount
        ));
    }

    public static function deleteSold_Item($item_id, $purchase_id) {
        global $pdo;

        $sql = "DELETE FROM sold_items
                WHERE item_id = :item_id AND purchase_id = :purchase_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ":item_id" => $item_id,
            ":purchase_id" => $purchase_id
        ));
    }

    public static function getPurchaseSold_Items($purchase_id) {
        try {
            global $pdo;

            $sql = "SELECT * 
                    FROM sold_items 
                    WHERE purchase_id = :purchase_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(":purchase_id" => $purchase_id));
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e){
            error_log("Database query error: " . $e->getMessage());
            return false;
        }
    }
}

?>

