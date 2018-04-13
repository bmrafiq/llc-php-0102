<?php
// database connection
define('DSN', 'mysql:dbname=llc_php;host=127.0.0.1');
define('USERNAME', 'root');
define('PASSWORD', '');

try {
    $connection = new PDO(DSN, USERNAME, PASSWORD);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $exception) {
    echo $exception->getMessage();
}
