<h1>View Books</h1>

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

    $stmt = $pdo->prepare("SELECT * FROM bookstore.books");
    $stmt->execute();
    $results = $stmt->fetchAll();
    var_dump($results);

    //foreach ($results as $result) {
    //    echo $result['title'] . ' - ' . $result['author'];
    //    echo '<br>';
    //}
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
