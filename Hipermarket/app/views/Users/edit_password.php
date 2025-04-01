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
        <p><label for="password">New Password</label>
            <input type="password" name="password" id="password">
        </p>
        <p style="color: red;">
            <?php 
            if (isset($_SESSION['password_error'])):
                echo $_SESSION['password_error'];
                unset($_SESSION['password_error']);
                endif;
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