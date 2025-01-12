<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/picnic">
    <title>Users</title>
</head>
<body>
<h1>All Users</h1>
<button><a href="create" style="color:white">Create</a></button>
<table>
    <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Store Credits</th>
        <th>Action</th>
    </tr>
    <?php foreach ($users as $user) : ?>
        <tr>
            <td><?= $user["first_name"] ?></td>
            <td><?= $user["last_name"] ?></td>
            <td><?= $user["email"] ?></td>
            <td><?= $user["credits"]?></td>
            <td>
                <a href="show?user_id=<?= $user["user_id"] ?>">Show</a> |
                <a href="edit?user_id=<?= $user["user_id"] ?>">Edit</a> |
                <a href="delete?user_id=<?= $user["user_id"] ?>">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>