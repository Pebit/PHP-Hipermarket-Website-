<?php
class Purchase {
    public static function getUserPurchases($user_id) {
        global $pdo;
        try {
            $sql = "SELECT *
                    FROM purchases
                    WHERE user_id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(":user_id" => $user_id));
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database query error: " . $e->getMessage());
            return false;
        }
    }

    public static function getAllPurchases() {
        global $pdo;
        try {
            $sql = "SELECT * 
                    FROM purchases";
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e){
            error_log("Database query error: " . $e->getMessage());
            return false;
        }
    }
    
    public static function getPurchase($purchase_id) {
        global $pdo;
        try {
            $sql = "SELECT *
                    FROM purchases
                    WHERE purchase_id = :purchase_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(":purchase_id" => $purchase_id));
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database query error: " . $e->getMessage());
            return false;
        }
    }

    public static function getUserLastPurchase($user_id) {
        global $pdo;
        try {
            $sql = "SELECT *
                    FROM purchases
                    WHERE user_id = :user_id
                    ORDER BY purchase_date DESC
                    LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(":user_id" => $user_id));
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database query error: " . $e->getMessage());
            return false;
        }
    }

    public static function updatePurchase($purchase_id, $total_price, $purchase_credits) {
        global $pdo;

        $sql = "UPDATE purchases
                SET total_price = :total_price, purchase_credits = :purchase_credits
                WHERE purchase_id = :purchase_id";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute(array(
            ":purchase_id" => $purchase_id,
            ":total_price" => $total_price,
            ":purchase_credits" => $purchase_credits
        ));
    }
    public static function finishPurchase($purchase_id){
        global $pdo;

        $sql = "UPDATE purchases
                SET purchase_date = CURDATE(), status = 1
                WHERE purchase_id = :purchase_id";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute(array(":purchase_id" => $purchase_id));
    }
    public static function createPurchase($user_id, $total_price, $purchase_credits, $status) {
        global $pdo;

        $sql = "INSERT INTO purchases (user_id, total_price, purchase_credits, purchase_date, status)
                VALUES (:user_id, :total_price, :purchase_credits, CURDATE(), :status)";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute(array(
            ":user_id" => $user_id,
            ":total_price" => $total_price,
            ":purchase_credits" => $purchase_credits,
            ":status" => $status
        ));
    }
}
?>