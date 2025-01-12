<?php
class Item {
    public static function getAllItems() {
        global $pdo;
        try {
            $sql = "SELECT *
                    FROM items";
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
?>