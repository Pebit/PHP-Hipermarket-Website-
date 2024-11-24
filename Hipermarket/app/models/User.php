<?php
class User {
    public static function getAllUsers() {
        global $pdo;
        try {
            $sql = "SELECT * 
                    FROM users";
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e){
            error_log("Database query error: " . $e->getMessage());
            return false;
        }
    }

    public static function getUser($user_id) {
        global $pdo;
        try {
            $sql = "SELECT *
                    FROM users 
                    WHERE user_id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(":user_id" => $user_id));
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database query error: " . $e->getMessage());
            return false;
        }
    }
}
?>