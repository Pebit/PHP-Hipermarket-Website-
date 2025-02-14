<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name = "viewport", content = "width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/picnic">
    <title>Edit Item</title>
</head>
<body>
    <h1>Edit Item</h1>
    <form method="post">
        <input type="hidden" name="item_id" value="<?= $item["item_id"] ?>">
        <p><label for="first_name">Item Name</label>
            <input type="text" name="item_name" id="item_name" value="<?= $item["item_name"] ?>">
            <?php  
                if (isset($_SESSION["edit_item"]) && isset($_SESSION["edit_item"]['item_name_error'])) 
                echo $_SESSION["edit_item"]['item_name_error'];
            ?>
        </p>
        <p><label for="expiration_date">Expiration Date</label>
            <input type="text" name="expiration_date" id="expiration_date" value="<?= $item["expiration_date"] ?>">
            <?php  
                if (isset($_SESSION["edit_item"]) && isset($_SESSION["edit_item"]['expiration_date_error'])) 
                echo $_SESSION["edit_item"]['expiration_date_error'];
            ?>
        </p>
        <p><label for="price">Price</label>
            <input type="text" name="price" id="price" value="<?= $item["price"] ?>">
            <?php  
                if (isset($_SESSION["edit_item"]) && isset($_SESSION["edit_item"]['price_error'])) 
                echo $_SESSION["edit_item"]['price_error'];
            ?>
        </p>
        <p><label for="stock">Stock</label>
            <input type="text" name="stock" id="stock" value="<?= $item["stock"] ?>">
            <?php  
                if (isset($_SESSION["edit_item"]) && isset($_SESSION["edit_item"]['stock_error'])) 
                echo $_SESSION["edit_item"]['stock_error'];
            ?>
        </p>
        <input type="submit" value=Update style="background:green">
    </form>
    <a href="index" style="color:white"><div><button style="background:orange">Back</div></a>
</body>
</html>
