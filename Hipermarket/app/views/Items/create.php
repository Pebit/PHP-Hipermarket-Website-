<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/picnic">
    <title>Create Item</title>
</head>
<body>
    <form action="create" method="post">
        <input type="hidden" name="is_post" value="1">
        <p><label for="item_name">Item Name</label>
            <input type="text" name="item_name" id="item_name" \
            value = "<?= $_SESSION['create_item']['item']['item_name'] ?>">
        </p>
        <p style="color: red;">
            <?php 
            if (isset($_SESSION['create_item']["errors"]['item_name_error'])):
                echo $_SESSION['create_item']["errors"]['item_name_error'];
                unset($_SESSION['create_item']["errors"]['item_name_error']);
                endif;
            ?>
        </p>
        <p><label for="expiration_date">Expiration Date</label>
            <input type="text" name="expiration_date" id="expiration_date"\
            value = "<?= $_SESSION['create_item']['item']['expiration_date'] ?>">
        </p>
        <p style="color: red;">
            <?php 
            if (isset($_SESSION['create_item']["errors"]['expiration_date_error'])):
                echo $_SESSION['create_item']["errors"]['expiration_date_error'];
                unset($_SESSION['create_item']["errors"]['expiration_date_error']);
                endif;
            ?>
        </p>
        <p><label for="price">price</label>
            <input type="text" name="price" id="price"\
            value = "<?= $_SESSION['create_item']['item']['price'] ?>">
        </p>
        <p style="color: red;">
            <?php 
            if (isset($_SESSION['create_item']["errors"]['price_error'])):
                echo $_SESSION['create_item']["errors"]['price_error'];
                unset($_SESSION['create_item']["errors"]['price_error']);
                endif;
            ?>
        </p>
        <p><label for="stock">stock</label>
            <input type="stock" name="stock" id="stock"\
            value = "<?= $_SESSION['create_item']['item']['stock'] ?>">
        </p>
        <p style="color: red;">
            <?php 
            if (isset($_SESSION['create_item']["errors"]['stock_error'])):
                echo $_SESSION['create_item']["errors"]['stock_error'];
                unset($_SESSION['create_item']["errors"]['stock_error']);
                endif;
            ?>
        </p>
        <input type="submit" value="Create" style="background:green">
    </form>
    <button style="background:orange"><a href="index" style="color:white">Back</a></button>
</body>
</html>