<?php
$id = $_GET['id'] ?? 0;

if ((int) $_GET['id'] === 0) {
    header('Location: users.php');
}

$connection = mysqli_connect('127.0.0.1', 'root', '', 'llc_php');

if ($connection === false) {
    $errors[] = mysqli_connect_error();
} else {
    $query = "DELETE FROM users WHERE id = '$id'";
    $result = mysqli_query($connection, $query);

    header('Location: users.php');
}
