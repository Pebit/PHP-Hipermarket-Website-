<?php
require_once "app/models/User.php";

class UserController{
    public static function index() {
        try{
            $users = User::getAllUsers();
            $read_permission = (
                isset($_SESSION["request_user"])  &&
                User::hasPermission($_SESSION["request_user"]["user_id"], "read_user")
            );
            $create_permission = (
                isset($_SESSION["request_user"])  &&
                User::hasPermission($_SESSION["request_user"]["user_id"], "create_user")
            );
            $update_permission = (
                isset($_SESSION["request_user"])  &&
                User::hasPermission($_SESSION["request_user"]["user_id"], "update_user")
            );
            $delete_permission = (
                isset($_SESSION["request_user"])  &&
                User::hasPermission($_SESSION["request_user"]["user_id"], "delete_user")
            );
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
            require_once "app/views/users/show.php";
        } else {
            $_SESSION['error'] = "User not found";
            require_once "app/views/404.php";
        }
    }

    static function data_validation() {
        $errors = [];
        $len_name = strlen($_POST['last_name']);
        if ($len_name < 1 || $len_name > 128) {
            $errors['last_name_error'] = 'Last name must be between 1 and 128 characters';  
        }
        $len_name = strlen($_POST['first_name']);
        if ($len_name < 1 || $len_name > 128) {
            $errors['first_name_error'] = 'First name must be between 1 and 128 characters';  
        }
        if (strpos($_POST['email'], '@') === false) {
            $errors['email_error'] = 'Invalid email';
        }
        if (isset($_POST['password']) && strlen($_POST['password']) < 8) {
            $errors['password_error'] = 'Password must be at least 8 characters';
        }
        if (isset($_POST['role_id']) && !UserRole::getRole($_POST['role_id'])) {
            $errors['role_error'] = 'Invalid role';
        }

        return $errors;
    }

    public static function edit(){
        // preventing people without the permission to edit users from doing so
        if (!isset($_SESSION["request_user"]) || !User::hasPermission($_SESSION["request_user"]["user_id"], "update_user")){
            $_SESSION["error"]= "Invalid permissions";
            require_once "app/views/404.php";
            return;
        }

        $user_id = $_GET['user_id'] ? $_GET['user_id'] : $_POST['user_id'];

        // preventing non-admins from editing other users
        $role = UserRole::getRole($_SESSION["request_user"]["role_id"]);
        if ($role["name"] != "admin" && $user_id != $_SESSION["request_user"]["user_id"]){
                $_SESSION["error"]= "Invalid permissions";
                require_once "app/views/404.php";
                return;
            }
        
        $user = User::getUser($user_id);

        if (!$user) {
            $_SESSION['error'] = "User not found";
            require_once "app/views/404.php";
            return;
        }
        
        // POST
        if (isset($_POST['user_id'])) {
            // empty session variables
            $_SESSION["edit_user"] = [];
            // Data validation
            $errors = self::data_validation();
            if (count($errors) > 0) {
                $_SESSION["edit_user"] = $errors;
                header("Location: edit?user_id=".$_POST['user_id']);
                return;
            }

            User::updateUser(
                $user_id,
                htmlentities($_POST['first_name']), 
                htmlentities($_POST['last_name']), 
                htmlentities($_POST['email'])
            );

            $_SESSION['success'] = 'Record updated';
            header("Location: edit?user_id=".$_POST['user_id']);
            return;
        }
        else {
            require_once "app/views/users/edit.php";
        }
    }
    public static function edit_password(){
        // preventing people without 
        if (!isset($_SESSION['request_user']) || !User::hasPermission($_SESSION['request_user']['user_id'], "update_user")){
            $_SESSION['error']= "Invalid permissions";
            require_once "app/views/404.php";
            return;
        }

        $user_id = $_GET['user_id'] ? $_GET['user_id'] : $_SESSION["request_user"]["user_id"];

        // if the user tries to edit another user's password besides themselves, they will be redirected again to the 404 page
        if ($user_id != $_SESSION["request_user"]["user_id"]){
                $_SESSION['error']= "Invalid permissions";
                require_once "app/views/404.php";
                return;
            }
        
        $user = User::getUser($user_id);

        if (!$user) {
            $_SESSION['error'] = "User not found";
            require_once "app/views/404.php";
            return;
        }
        if(isset($_POST['password'])){
            // password validation
            if (strlen($_POST['password']) < 8) {
                $_SESSION['password_error'] = 'Password must be at least 8 characters';
                header("Location: edit_password?user_id=$user_id");
                return;
            }

            User::updateUserPassword(
                $user_id,
                password_hash($_POST["password"], PASSWORD_DEFAULT)
            );

            $_SESSION['success'] = 'Password updated for '.$user["first_name"]." ".$user["last_name"];
            header("Location: edit_password?user_id=$user_id");
            return;
        }
        else{
            require_once "app/views/users/edit_password.php";
        }
    }
    

    public static function delete() {
        // preventing people without the permission to delete users from doing so
        if (!isset($_SESSION["request_user"]) || 
            !User::hasPermission($_SESSION["request_user"]["user_id"], "delete_user")
        ){
            $_SESSION["error"]= "Invalid permissions";
            require_once "app/views/404.php";
            return;
        }

        $user_id = $_GET["user_id"];
        
        // preventing non-admins from deleting other users
        if($_SESSION["request_user"]["role_id"] != 1 && $_SESSION["request_user"]["user_id"] != $user_id ){
            $_SESSION["error"]= "Invalid permissions";
            require_once "app/views/404.php";
            return;
        }
        // if the user deletes themselves:
        // log them out (unset session user) and redirect them to landing page
        if($_SESSION["request_user"]["user_id"] == $user_id){
            User::deleteUser($user_id);
            unset($_SESSION["request_user"]);
            header("Location: http://localhost/Site%20Hipermarket(1)/Hipermarket");
        }
        else{
            User::deleteUser($user_id);
            header("Location: index");
        }
        

        
        return;
    }
    public static function create() {
        // if (!isset($_SESSION["request_user"])){
        //     header("Location: /Site%20Hipermarket(1)/Hipermarket");
        // }


        if (isset($_POST["is_post"])){
            // POST => create user
            $_SESSION["create_user"]["user"] = $_POST;

            $errors = self::data_validation();
            if (count($errors)){
                $_SESSION["create_user"]["errors"] = $errors;
                header("Location: create");
                return;
            }
           $pass = password_hash($_POST["password"], PASSWORD_DEFAULT);

            User::createUser(
                htmlentities($_POST["first_name"]), 
                htmlentities($_POST["last_name"]),
                htmlentities($_POST["email"]), 
                $pass,
                htmlentities($_POST["role_id"])
            );
            if (isset($_SESSION["request_user"]["role_id"]) && $_SESSION["request_user"]["role_id"] == 1)
                header("Location: index");
            else
                header("Location: http://localhost/Site%20Hipermarket(1)/Hipermarket/auth/login");
        }
        // GET => show form
        if (!isset($_SESSION["create_user"]["user"])){
            $_SESSION["create_user"]["user"] = [
                "first_name" => "",
                "last_name" => "",
                "email" => ""
            ];
        }
        $roles  = UserRole::getAllRoles();
        foreach ($roles as $role){
            if ($role["name"] == "user"){
                $user_id = $role["role_id"];
            }
        }
        require_once "app/views/users/create.php";
    }
}
?>