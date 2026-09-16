<?php

require_once '/var/www/shared/db.php';

try {


    $db = new RelationalDatabase(
        $_ENV['POSTGRES_DRIVER'],
        $_ENV['POSTGRES_HOST'],
        $_ENV['POSTGRES_PORT'],
        $_ENV['POSTGRES_DB'],
        $_ENV['POSTGRES_USER'],
        $_ENV['POSTGRES_PASSWORD'],
        $_ENV['POSTGRES_CHARSET']
    );

    $queryBuilder = QueryBuilder::table('bookstore.books')->select();
    $results = $db->executeAndReturnAll($queryBuilder);

    $html = '';
    $html .= '<div class="book-grid">';

    foreach ($results as $result) {
        $url = 'view_book/' . rawurlencode($result['book_id']);

        $html .= '<a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '">';


        $html .= '<div class="book">';

        $imagePath = '/img/' . $result['image_path'];

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
