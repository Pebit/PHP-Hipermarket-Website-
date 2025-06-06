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
    public static function getUserByEmail($email) {
        global $pdo;

        $sql = "SELECT *
                FROM users 
                WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(":email" => $email));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public static function updateUser($user_id, $first_name, $last_name, $email) {
        global $pdo;

        $sql = "UPDATE users
                SET first_name = :first_name, last_name = :last_name, email = LOWER(:email)
                WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute(array(
            ":user_id" => $user_id,
            ":first_name" => $first_name,
            ":last_name" => $last_name,
            ":email" => $email
        ));
    }
    public static function updateUserPassword($user_id, $password) {
        global $pdo;

        $sql = "UPDATE users
                SET password = :password
                WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute(array(
            ":user_id" => $user_id,
            ":password" => $password
        ));
    }
    
    public static function deleteUser($user_id) {
        global $pdo;

        $sql = "DELETE FROM users
                WHERE user_id = :user_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([":user_id" => $user_id]);
    }
    public static function createUser($first_name, $last_name, $email, $password, $role_id) {
        global $pdo;

        $sql = "INSERT INTO users (first_name, last_name, email, password, role_id)
                VALUES (:first_name, :last_name, LOWER(:email), :password, :role_id)";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute(array(
            ":first_name" => $first_name,
            ":last_name" => $last_name,
            ":email" => $email,
            ":password" => $password,
            ":role_id" => $role_id
        ));
    }

    public static function hasPermission($user_id, $permission_name){
        global $pdo;
        $sql = "SELECT count(*) as count
                FROM users us
                JOIN roles_permissions rp on us.role_id = rp.role_id
                JOIN permissions p on  rp.permission_id = p.permission_id
                WHERE us.user_id=:user_id and p.name = :permission_name";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
                        ":user_id" => $user_id, 
                        ":permission_name"=>$permission_name
                    ));
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res["count"] > 0;
    }

}
class UserRole {
    public static function getAllRoles() {
        global $pdo;
        $sql = "SELECT * 
                FROM user_roles";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getRole($role_id) {
        global $pdo;

        $sql = "SELECT *
                FROM user_roles 
                WHERE role_id = :role_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(":role_id" => $role_id));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>