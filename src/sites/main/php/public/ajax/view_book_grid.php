<?php

require_once '/var/www/shared/api.php';

$response = API::get('/books');
$results = json_decode($response, true);

if ($results['success']) {

    $decodedData = json_decode($results['data'], true);

?>

    <div class="book-grid">

        <?php foreach ($decodedData as $result): ?>

            <?php
            $bookId = htmlspecialchars(
                $result['book_id'],
                ENT_QUOTES,
                'UTF-8'
            );

            $title = htmlspecialchars(
                $result['title'],
                ENT_QUOTES,
                'UTF-8'
            );

            $imagePath = htmlspecialchars(
                "/img/{$result['image_path']}",
                ENT_QUOTES,
                'UTF-8'
            );
            ?>

            <button
                class="book-link"
                type="button"
                data-book-id="<?= htmlspecialchars($result['book_id'], ENT_QUOTES, 'UTF-8') ?>">
                <div class="book">
                    <img
                        src="<?= htmlspecialchars("/img/{$result['image_path']}", ENT_QUOTES, 'UTF-8') ?>"
                        class="book-image"
                        loading="lazy"
                        alt="<?= htmlspecialchars($result['title'], ENT_QUOTES, 'UTF-8') ?>">

                    <h3 class="book-title">
                        <?= htmlspecialchars($result['title'], ENT_QUOTES, 'UTF-8') ?>
                    </h3>

                    <span class="tooltiptext">
                        <?= htmlspecialchars($result['title'], ENT_QUOTES, 'UTF-8') ?>
                    </span>
                </div>
            </button>


        <?php endforeach; ?>

    </div>

    <div id="book-modal" class="book-modal" hidden>

        <div class="book-modal-overlay"></div>

        <div
            class="book-modal-content"
            role="dialog"
            aria-modal="true"
            aria-labelledby="book-modal-title">

            <header class="book-modal-header">
                <div id="book-modal-title"></div>

                <button
                    id="modal-close"
                    class="modal-close"
                    type="button"
                    aria-label="Close">
                    &times;
                </button>
            </header>

            <main id="book-modal-body" class="book-modal-body">
                <div id="book-modal-spinner" class="modal-spinner">
                    <div class="spinner"></div>
                </div>
            </main>

            <footer class="book-modal-footer" id="book-modal-footer">
            </footer>

        </div>

    </div>





    <style>

    </style>

    <script src='/js/modal.js'></script>


<?php

} else {

    echo '<h1>Failed to connect to database and retrieve books</h1>';
}

?>