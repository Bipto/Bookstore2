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
    //var_dump($results);

    $html = '';
    $html .= '<div class="book-grid">';

    foreach ($results as $result) {
        $html .= '<a href="view_book?id=' . $result['book_id'] . '">';
        $html .= '<div class="book">';

        $imagePath = 'img/' . $result['image_path'];

        $html .= '<img src=' . $imagePath . ' class="book-image" loading="lazy">
                            <h3 class="book-title">' . $result['title'] . '</h3>
                            <span class="tooltiptext">' . $result['title'] . '</span>';


        $html .= '</div>';
        $html .= '</a>';
    }

    $html .= '</div>';

    echo $html;
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
