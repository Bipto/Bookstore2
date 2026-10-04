<?php

require_once '/var/www/shared/api.php';
require_once '/var/www/shared/ajax.php';

?>

<div class="book-catalogue">

    <aside class="book-filters">
        <label for="book-search">Search books</label>

        <input
            type="search"
            id="book-search"
            placeholder="Search by title..."
            autocomplete="off">
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