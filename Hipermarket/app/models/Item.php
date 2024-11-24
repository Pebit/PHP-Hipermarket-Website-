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
}
?>