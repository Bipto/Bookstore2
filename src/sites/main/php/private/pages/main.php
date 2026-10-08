<?php

require_once '/var/www/shared/api.php';
require_once '/var/www/shared/ajax.php';

$data = json_decode(API::get('/genres?active=true'), TRUE);
$genres = json_decode($data['data'], TRUE);

?>

<div class="book-catalogue">

    <aside class="book-filters">

        <button
            type="button"
            class="book-filters-toggle"
            aria-expanded="false"
            aria-controls="book-filter-content">

            <span>Filters</span>

            <span class="filter-arrow" aria-hidden="true"></span>
        </button>

        <div class="book-filter-content" id="book-filter-content">

            <label for="book-search">Search books</label>

            <input
                type="search"
                id="book-search"
                placeholder="Search by title..."
                autocomplete="off">

            <label for="author-search">Search authors</label>

            <input
                type="search"
                id="author-search"
                placeholder="Search by author..."
                autocomplete="off">

            <fieldset>
                <legend>Genres</legend>

                <?php foreach ($genres as $genre): ?>

                    <label>
                        <input
                            type="checkbox"
                            name="genre"
                            value="<?= htmlspecialchars($genre['genre_id']) ?>">

                        <?= htmlspecialchars($genre['name']) ?>
                    </label>

                <?php endforeach; ?>

            </fieldset>

        </div>
    </aside>

    <?php

    echo AJAX::call('/ajax/view_book_grid.php');

    ?>

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

<script src='/js/modal.js'></script>
<script src='/js/filters.js'></script>