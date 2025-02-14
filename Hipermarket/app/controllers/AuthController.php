<?php
require_once "app/models/User.php";

class AuthController {
    public static function login(){
        if (isset($_SESSION["request_user"])){
            header("Location: /Site%20Hipermarket(1)/Hipermarket");
            return;
        }
        if(!isset($_POST["email"])){
            require_once "app/views/auth/login.php";
            return;
        }

        // POST
        $email = htmlentities($_POST["email"]);
        $pass = $_POST["password"];

        $user = User::getUserByEmail($email);

        // for debugging login errors (uncomment for debugging)
        // if(!$user){
        //     $_SESSION["login_error"] = "Invalid email!";
        //     require_once "app/views/auth/login.php";
        // } else if (!password_verify($pass, $user["password"])){
        //     $_SESSION["login_error"] = "Invalid password!";
        //     require_once "app/views/auth/login.php";
        //     var_dump($user);
        // } else {
        //     // login successful
        //     $_SESSION["request_user"] = $user;
        //     header("Location: /Site%20Hipermarket(1)/Hipermarket");
        // }

        // regular login for no info leaks (comment this for debugging)
        if(!$user || !password_verify($pass, $user["password"])){
            $_SESSION["login_error"] = "Invalid email or password!";
            require_once "app/views/auth/login.php";
        } else {
            // login successful
            $_SESSION["request_user"] = $user;
            header("Location: /Site%20Hipermarket(1)/Hipermarket");
        }
    }
    public static function guest_login(){
        if (isset($_SESSION["request_user"])) {
            header("Location: /Site%20Hipermarket(1)/Hipermarket");
            return;
        }
        // Set guest user details
        $user = User::getUserByEmail("guest@guest.com");
        // Login the guest user
        $_SESSION["request_user"] = $user;
        header("Location: /Site%20Hipermarket(1)/Hipermarket");
    }
    public static function logout(){
        session_start();
        session_destroy();
        header("Location: /Site%20Hipermarket(1)/Hipermarket");
    }

    public static function landing_page(){
        require_once "app/views/landing_page.php";
    }
}
?>