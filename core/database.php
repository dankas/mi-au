<?php

$host = 'localhost';
$db   = 'meowdb';
$user = 'root';
$pass = 'root';
$port = '3308';
$dsn = "mysql:host=$host;port=$port;dbname=$db;";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    // Conexão bem-sucedida
} catch (PDOException $e) {
    die('Erro na conexão: ' . $e->getMessage());
}

