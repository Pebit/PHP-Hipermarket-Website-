<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/picnic">
    <title>User profile</title>
</head>
<body>
    <h1>User profile</h1>
    <p>First Name: <?= $user["first_name"] ?></p>
    <p>Last Name: <?= $user["last_name"] ?></p>
    <!-- <p>Credits: <?= $user["credits"] ?> </p> -->
    <p>Email: <?= $user["email"] ?></p>
    <a href="edit?user_id=<?= $user["user_id"] ?>" style="color:white"><div><button>Edit</div></a>
    <a href="index" style="color:white"><div><button style="background:orange">Back</div></a>
</body>
</html>