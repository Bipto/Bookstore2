<?php

$host = $_ENV['POSTGRES_HOST'];
$port = $_ENV['POSTGRES_PORT'];
$dbname = $_ENV['POSTGRES_DB'];
$user = $_ENV['POSTGRES_USER'];
$password = $_ENV['POSTGRES_PASSWORD'];

try {
    $pdo = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    echo "Connected successfully!";

    $pdo->exec("
        CREATE SCHEMA IF NOT EXISTS bookstore
    ");

    $pdo->exec("
        CREATE TABLE bookstore.books (
            bookid INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
            title VARCHAR(50) NOT NULL,
            author VARCHAR(50) NOT NULL,
            bookdescription TEXT NOT NULL,
            genre VARCHAR(30) NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            stockcount INTEGER NOT NULL,
            imagepath VARCHAR(150)
        )
    ");
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
