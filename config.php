<?php
$host = 'localhost';
$dbname = 'dbaw2pxqqn1txb';
$username = 'ugxt49qfpxrp9';
$password = 'nr9gmmgtaigd';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}
?>
