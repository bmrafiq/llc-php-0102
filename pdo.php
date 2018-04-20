<?php

// database connection
$dsn = 'mysql:dbname=llc_php;host=127.0.0.1';
$username = 'root';
$password = '';

try {
    $connection = new PDO($dsn, $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
    echo $exception->getMessage();
}


