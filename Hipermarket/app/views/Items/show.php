<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/picnic">
    <title>item profile</title>
</head>
<body>
    <h1>item profile</h1>
    <p>Item Name: <?= $item["item_name"] ?></p>
    <p>Expiration Date: <?= $item["expiration_date"] ?></p>
    <p>price: <?= $item["price"] ?> </p>
    <p>stock: <?= $item["stock"] ?></p>
    <?php if ($update_permission): ?> <a href="edit?item_id=<?= $item["item_id"] ?>" style="color:white"><div><button>Edit</div></a><?php endif; ?>
    <a href="index" style="color:white"><div><button style="background:orange">Back</div></a>
</body>
</html>