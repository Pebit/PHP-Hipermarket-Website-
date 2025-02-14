<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/picnic">
    <title>Landing Page</title>
</head>
<body>
    <?php if (isset($_SESSION["request_user"])): ?>
        <h1>>> Welcome <?= $_SESSION["request_user"]["first_name"] ?> <<</h1>
        <ul>
            <li><a href="purchases/index?user_id=<?=$_SESSION["request_user"]["user_id"]?>"> Purchase History </a></li>
            <?php if(isset($_SESSION["create_purchase"])): ?>
                <li><a href="purchases/show?purchase_id=<?=$_SESSION["create_purchase"]["purchase"]["purchase_id"]?>"> Cart </a></li>
            <?php endif; ?>
            <li><a href="users/index">Users</a></li>
            <li><a href="items/index">Items</a></li>
            <li><a href="auth/logout">Logout</a></li>
        </ul>
    <?php else: ?>
        <h1>>> Welcome <<</h1>
        <ul>
            <li><a href="auth/login">Login</a></li>
            <li><a href="users/create"> Sign up </a></li>
            <li><a href="auth/guest_login">Continue as guest</a></li>
        </ul>
        
        
    <?php endif; ?>
</body>
</html>