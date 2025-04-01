<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/picnic">
    <title>Edit User</title>
</head>
<body>
    <h1>Edit User</h1>
    <form method="post">
        <input type="hidden" name="user_id" value="<?= $user["user_id"] ?>">
        <p><label for="first_name">First Name</label>
            <input type="text" name="first_name" id="first_name" value="<?= $user["first_name"] ?>">
            <?php  
                if (isset($_SESSION["edit_user"]) && isset($_SESSION["edit_user"]['first_name_error'])) 
                echo $_SESSION["edit_user"]['first_name_error'];
            ?>
        </p>
        <p><label for="last_name">Last Name</label>
            <input type="text" name="last_name" id="last_name" value="<?= $user["last_name"] ?>">
            <?php  
                if (isset($_SESSION["edit_user"]) && isset($_SESSION["edit_user"]['last_name_error'])) 
                echo $_SESSION["edit_user"]['last_name_error'];
            ?>
        </p>
        <p><label for="email">Email</label>
            <input type="text" name="email" id="email" value="<?= $user["email"] ?>">
            <?php  
                if (isset($_SESSION["edit_user"]) && isset($_SESSION["edit_user"]['email_error'])) 
                echo $_SESSION["edit_user"]['email_error'];
                if (isset($_SESSION["edit_user"]) && isset($_SESSION["edit_user"]['role_error'])) 
                echo $_SESSION["edit_user"]['role_error'];
            ?>
        </p>
        <p style="color:green;">
            <?php if (isset($_SESSION['success'])){
                echo($_SESSION['success']);
                unset($_SESSION['success']);}?>
        </p>
        <input type="submit" value=Update style="background:green">
    </form>
    <a href="index" style="color:white"><div><button style="background:orange">Back</div></a>
</body>
</html>