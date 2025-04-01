<?php
require_once "app/models/User.php";
require_once "app/models/Purchase.php";
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

        if(!$user || !password_verify($pass, $user["password"])){
            $_SESSION["login_error"] = "Invalid email or password!";
            require_once "app/views/auth/login.php";
        } else {
            // login successful
            $_SESSION["request_user"] = $user;
            $purchase = Purchase::getUserLastPurchase($user["user_id"]);
            if ($purchase != false && $purchase["status"] == 0){
                $_SESSION["create_purchase"]["purchase"] = $purchase;
            }
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
    public static function generatePassword($length = 8) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        return substr(str_shuffle($chars), 0, $length);
    }
    public static function forgot_password(){
        error_reporting(-1);
        ini_set('display_errors', 'On');
        set_error_handler("var_dump");
        if (isset($_SESSION["request_user"])){
            header("Location: /Site%20Hipermarket(1)/Hipermarket");
            return;
        }

        // after after the verification code is posted we check it (unless we change our mind on the email)
        if (isset($_POST["verification_code"]) && isset ($_SESSION["verification_code"]) && !isset($_POST["wrong_email"])){
            // if good, log the user in and redirect him to password_edit page
            if(password_verify($_POST["verification_code"], $_SESSION["verification_code"])){
                $user = User::getUserByEmail($_SESSION["email"]);
                $_SESSION["request_user"] = $user;
                unset($_SESSION["email"]);
                unset($_SESSION["verification_code"]);
                header("Location: ../users/edit_password?user_id=".$_SESSION["request_user"]["user_id"]);
                return;
            }
            // if the code provided is bad then try again
            else{
                require_once "app/views/auth/forgot_password.php";
                return;
            }
        }


        if(!isset($_POST["email"])){
            require_once "app/views/auth/forgot_password.php";
            
            return;
        }
        
        $email = htmlentities($_POST["email"]);
        $user = User::getUserByEmail($email);

        if(!$user){
            $_SESSION["login_error"] = "Email is not associated with any account";
            require_once "app/views/auth/forgot_password.php";
            return;
        }

        $pass = self::generatePassword();
        // if the user exists an email is sent with a randomly generated code

        $subject = "JonsiMarket Verification Code";
        $message = "Your verification code is: ".$pass;
        // $headers = "From: corsicarubanana34@gmail.com";

        if (!mail($email, $subject, $message)) {
            $_SESSION["login_error"] = "Failed to send email.";
            require_once "app/views/auth/forgot_password.php";
            return;
        }
        $_SESSION["verification_code"] = password_hash($pass, PASSWORD_DEFAULT);
        $_SESSION["email"] = $email;
        $_SESSION["email_successfull"] = "Email sent successfully to ".$email;
        require_once "app/views/auth/forgot_password.php";
        return;
    }
}
?>