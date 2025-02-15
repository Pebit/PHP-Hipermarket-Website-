<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/picnic">
    <title>items</title>
</head>
<body>
<table>
    <tr>
        <th>User ID</th>
        <th>Date</th>
        <th>Money Spent</th>
        <th>Credits Gained</th>
        <th>Transaction Status</th>
    </tr>
    <?php foreach ($purchases as $purchase) : ?>
        <tr>
            <td><?= $purchase["user_id"] ?></td>
            <td><?= $purchase["purchase_date"] ?></td>
            <td><?= $purchase["total_price"] ?> lei</td>
            <td><?= $purchase["purchase_credits"] ?> </td>
            <td><?php if($purchase["status"] == 0):?>pending<?php else:?>finished<?php endif;?></td>
        </tr>
    <?php endforeach; ?>
</table>
<a href=<?= $return_url?> style="color:white"><div><button style="background:orange">Back</div></a>
</body>
</html>