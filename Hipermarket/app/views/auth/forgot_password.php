<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <?php if(isset ($_SESSION["verification_code"])):?>
        <h1> Wrong email? Enter it again and retry:</h1>
    <?php else:?>
        <h1>Enter your email</h1>
    <?php endif; ?>
    <p style="color:red">
        <?php  
            if (isset($_SESSION["login_error"])) 
                echo $_SESSION["login_error"];
            unset($_SESSION["login_error"]);
        ?></p>
    <form method="post">
        <?php if(isset ($_SESSION["verification_code"])):?>
            <input type="hidden" name="wrong_email" value="1">
        <?php endif; ?>
        <p><label for="email">email:</label>
                <input type="text" name="email" id="email" value=<?= isset($_SESSION["email"]) ? $_SESSION["email"] : "" ?>>
        </p>
        <?php if(isset ($_SESSION["verification_code"])):?>
            <input type="submit" value="resend verification code">
        <?php else:?>
        <input type="submit" value="send verification code">
        <?php endif; ?>
        <p style="color:green">
            <?php  
                if (isset($_SESSION["email_successfull"])) 
                    echo $_SESSION["email_successfull"];
                unset($_SESSION["email_successfull"]);
            ?>
        </p>
    </form>
    <form method="post">
        <?php if(isset ($_SESSION["verification_code"])):?>
            <p> 
                <label for="verification_code">verification code:</label>
                <input type="text" name="verification_code" id="verification_code">
            </p>
            <input type="submit" value="verify my account">
        <?php endif; ?>
    </form>
</body>
</html>