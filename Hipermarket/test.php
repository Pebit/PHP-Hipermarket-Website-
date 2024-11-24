<?php
require_once 'config/pdo.php';
require_once 'app/models/User.php';
require_once 'app/models/Item.php';

session_start();

$users = User::getAllUsers();

echo "<p>Users:</p>";
foreach ($users as $user) {
    echo "<pre>";
    print_r($user);
    echo "</pre>";
}

$items = Item::getAllItems();
echo "<p>Items:</p>";
foreach ($items as $item) {
    echo "<pre>";
    print_r($item);
    echo "</pre>";
}


?>