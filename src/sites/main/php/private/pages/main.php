<?php

require_once '/var/www/shared/api.php';

/* $response = API::get('/books');
$results = json_decode($response, true);

if ($results['success']) {
    $html = '<div class="book-grid">';

    $decodedData = json_decode(
        $results['data'],
        true
    );

    foreach ($decodedData as $result) {
        $encodedId = rawurlencode($result['book_id']);
        $url = htmlspecialchars("view_book/{$encodedId}", ENT_QUOTES, 'UTF-8');
        $imagePath = "/img/{$result['image_path']}";

        $html .= "
                <a href='{$url}'>
                    <div class='book'>
                        <img src='{$imagePath}' class='book-image' loading='lazy'>
                        <h3 class='book-title'>{$result['title']}</h3>
                        <span class='tooltiptext'>{$result['title']}</span>
                    </div>
                </a>
            ";
    }

    $html .= '</div>';

    echo $html;
} else {
    echo '<h1>Failed to connect to database and retrieve books<h1>';
}
 */

/* echo '
<div class="container">
<div id="overlay">
<div class="spinner"></div>
</div>
</div>'; */

echo '<div class="page">
<div class="container">

<div class="spinner">
</div>

</div>
</div>';
