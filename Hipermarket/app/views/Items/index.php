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
<table>
    <tr>
        <th>Item Name</th>
        <th>Price</th>
        <th>Expiration Date</th>
        <th>Items in Stock</th>
    </tr>
    <?php foreach ($items as $item) : ?>
        <tr>
            <td><?= $item["item_name"] ?></td>
            <td><?= $item["price"] ?></td>
            <td><?= $item["expiration_date"] ?></td>
            <td><?= $item["stock"] ?></td>  
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
