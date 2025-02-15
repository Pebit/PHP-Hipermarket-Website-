<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/picnic">
    <title>items</title>
</head>
<body>
<h1>Cart:</h1>
<table>
    <tr>
        <th>Remove</th>
        <th>Price</th>
        <th>Amount</th>
        <th>Item</th>
        
    </tr>
    <?php foreach ($items as $item) : ?>
        <tr>
            <td>
                <form action="/Site%20Hipermarket(1)/Hipermarket/purchases/remove_from_cart" method="POST" style="display:inline;">
                    <input type="hidden" name="return_url" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                    <input type="hidden" name="item_id" value="<?= htmlspecialchars($item["item_id"]) ?>">
                    <input type="hidden" name="current_amount" value="<?= htmlspecialchars($item["amount"]) ?>">
                    <input type="number" name="remove_amount" min="1" max=<?= $item["amount"]?> value="0" required>
                    <button type="submit" style="background:red">remove from cart</button>
                </form>
            </td>
            <td><?= $item["price"] ?> lei</td>
            <td><?= $item["amount"] ?> x</td>
            <td><?= $item["item_name"] ?></td>
            <?php if(isset($_SESSION["cart_error"])):?>
                <td style="color: red;"><?= $_SESSION["cart_error"]; unset($_SESSION["cart_error"]); ?></td>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>
</table>
<h2>Total: <?= $total_price ?> lei<br>
Credits Aquired: <?= $credits ?></h2>
<?php if(isset($_GET["error"])):?>
                <p style="color: red;"><?= $_GET["error"]; ?></p>
<?php endif; ?>
<a href="http://localhost/Site%20Hipermarket(1)/Hipermarket" style="color:white"><div><button style="background:orange">Back</div></a>
<form action="finish" method="POST" style="display:inline;">
    <input type="hidden" name="return_url" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
    <button type="submit" style="background:green">BUY</button>
</form>

</body>
</html>