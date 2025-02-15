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
            <li>
                <form id="purchaseForm" action="purchases/index?user_id=<?=$_SESSION["request_user"]["user_id"]?>" method="POST" style="display:inline;">
                    <input type="hidden" name="return_url" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                    <a href="#" onclick="document.getElementById('purchaseForm').submit(); return false;">Purchase History</a>
                </form>
                <?php if (isset($_GET["error"])):?> 
                    <p style="color: red;"><?= $_GET["error"];?> </p>
                <?php endif; ?>
            </li>
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