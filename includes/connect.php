<?php
$host = 'localhost';
$user = 'root';
$password ='';
$dbname = 'rpta';
$dsn ='mysql:host='.$host.';'.'dbname='.$dbname.';'.'charset=utf8';
// Create connection
try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection Faild :' . $e->getMessage() . '<br>';
    die();
}
?>