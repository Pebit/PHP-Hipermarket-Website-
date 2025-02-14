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
        <>
            <td>
                <form action="/Site%20Hipermarket(1)/Hipermarket/purchases/add_to_cart" method="POST" style="display:inline;">
                    <input type="hidden" name="return_url" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                    <input type="hidden" name="item_id" value="<?= htmlspecialchars($item["item_id"]) ?>">
                    <input type="hidden" name="current_amount" value="<?= htmlspecialchars($item["amount"]) ?>">
                    <input type="number" name="remove_amount" min="1" max=<?= $item["amount"]?> value="0" required>
                    <button type="submit">remove from cart</button>
                </form>
            </td>
            <td><?= $item["price"] ?> lei</td>
            <td><?= $item["amount"] ?> x</td>
            <td><?= $item["item_name"] ?></td>
            <?php if(isset($error)):?>
                <td><?= $error ?></td>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>
</table>
<h2>Total: <?= $total_price ?> lei<br>
Credits Aquired: <?= $credits ?></h2>
<a href="http://localhost/Site%20Hipermarket(1)/Hipermarket" style="color:white"><div><button style="background:orange">Back</div></a>
<a href="finish"><div><button style="background:green">BUY</div></a>
</body>
</html>