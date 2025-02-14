<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/picnic">
    <title>Items</title>
</head>
<body>
<h1>All Items</h1>
<?php  if ($create_permission){
    echo ("<a href=\"create\" style=\"color:white\"><div><button style=\"background:green\">Create</div></a>");}
?>
<?php if (empty($items)) : ?>
    <p>OUT OF STOCK</p>
<?php else : ?>
    <table>
        <tr>
            <th>Item Name</th>
            <th>Price</th>
            <th>Expiration Date</th>
            <th>Items in Stock</th>
            <th>Action</th>
        </tr>
        <?php foreach ($items as $item) : ?>
            <?php if ($item["item_id"] != 1 || (isset($_SESSION["request_user"]) && $_SESSION["request_user"]["role_id"] == 1)): ?>
            <tr>
                <td><?= htmlspecialchars($item["item_name"]) ?></td>
                <td><?= htmlspecialchars($item["price"]) ?> lei</td>
                <td><?= htmlspecialchars($item["expiration_date"]) ?></td>
                <td><?= htmlspecialchars($item["stock"]) ?></td>
                <td>
                    <a href="show?item_id=<?= $item["item_id"] ?>">Show</a>
                    <?php if ($update_permission): ?> | <a href="edit?item_id=<?= $item["item_id"] ?>">Edit</a><?php endif; ?>
                        <?php if ($item["item_id"] != 1): ?>
                        <?php if ($delete_permission): ?> | <a href="delete?item_id=<?= $item["item_id"] ?>">Delete</a><?php endif; ?>
                        <?php if ($purchase_permission): ?>
                            <form action="/Site%20Hipermarket(1)/Hipermarket/purchases/add_to_cart" method="POST" style="display:inline;">
                                <input type="hidden" name="return_url" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                                <input type="hidden" name="item_id" value="<?= htmlspecialchars($item["item_id"]) ?>">
                                <input type="number" name="amount" min="1" max=<?= $item["stock"]?> value="1" required>
                                <button type="submit">Add to Cart</button>
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
<a href="http://localhost/Site%20Hipermarket(1)/Hipermarket" style="color:white"><div><button style="background:orange">Back</div></a>
</body>
</html>
