<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/picnic">
    <title>Users</title>
</head>
<body>
<h1>All Users</h1>
<?php  if ($create_permission){
    echo ("<a href=\"create\" style=\"color:white\"><div><button style=\"background:green\">Create</div></a>");
}
?>
<table>
    <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Store Credits</th>
        <th>Action</th>
    </tr>
    <?php foreach ($users as $user) : ?>
        <tr>
            <td><?= $user["first_name"] ?></td>
            <td><?= $user["last_name"] ?></td>
            <td><?= $user["email"] ?></td>
            <td><?= $user["credits"]?></td>
            <td>
                <?php if(isset($_SESSION["request_user"]) && $_SESSION["request_user"]["role_id"] == 1): ?>
                    <form id="purchaseForm_<?= $user["user_id"] ?>" action="/Site%20Hipermarket(1)/Hipermarket/purchases/index?user_id=<?= $user["user_id"] ?>" method="POST" style="display:inline;">
                        <input type="hidden" name="return_url" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                        <a href="#" onclick="document.getElementById('purchaseForm_<?= $user["user_id"] ?>').submit(); return false;">Purchases</a>
                    </form>
                <?php endif; ?>
                
                <?php if ($read_permission): ?>| <a href="show?user_id=<?= $user["user_id"] ?>">Show</a><?php endif; ?>
                <?php if ($update_permission && isset($_SESSION["request_user"]) && $_SESSION["request_user"]["user_id"] == $user["user_id"] || (isset($_SESSION["request_user"]) && $_SESSION["request_user"]["role_id"] == 1)): ?> 
                    <?php if ($user["email"] != "admin@admin.com"): ?> 
                        | <a href="edit?user_id=<?= $user["user_id"] ?>">Edit</a><?php endif; ?><?php endif; ?>
                <?php if ($delete_permission && isset($_SESSION["request_user"]) && $_SESSION["request_user"]["user_id"] == $user["user_id"] ||(isset($_SESSION["request_user"]) && $_SESSION["request_user"]["role_id"] == 1)): ?> 
                    <?php if ($user["email"] != "admin@admin.com" && $user["email"] != "guest@guest.com"): ?>
                        | <a href="delete?user_id=<?= $user["user_id"] ?>">Delete</a><?php endif; ?><?php endif; ?>
            </td>
            <?php if (isset($_GET["error"]) && $user["user_id"] == $_GET["user_id"]):?> 
                <td  style="color: red;"><?= $_GET["error"] ?> </td>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>
    
</table>
<a href="http://localhost/Site%20Hipermarket(1)/Hipermarket" style="color:white"><div><button style="background:orange">Back</div></a>
</body>
</html>