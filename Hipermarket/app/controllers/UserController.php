<?php
require_once "app/models/User.php";

class UserController{
    public static function index() {
        try{
            $users = User::getAllUsers();
            require_once "app/views/users/index.php";
        } catch (Exception $e){
            $_SESSION['error'] = "Error fetching users: " . $e->getMessage();
            require_once "app/views/404.php";
        }
    }

    public static function show() {
        $user_id = $_GET['user_id'];
        $user = User::getUser($user_id);

        if ($user) {
            //require_once "app/views/users/show.php";
        } else {
            $_SESSION['error'] = "User not found";
            require_once "app/views/404.php";
        }
    }
}
?>