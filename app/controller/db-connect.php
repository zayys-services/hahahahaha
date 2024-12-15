<?php
$dbHost = 'localhost';
$dbName = 'cb_db_main';
$dbUser = 'root';
$dbPassword = 'pf6hCHwMXBJvZxuU8VDA7a';
try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $GLOBALS['pdo'] = $pdo;
 
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

