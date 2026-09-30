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


    <!-- MODAL -->

    <div id="book-modal" class="book-modal" hidden>

        <div class="book-modal-overlay"></div>

        <div
            class="book-modal-content"
            role="dialog"
            aria-modal="true">

            <button
                id="modal-close"
                class="modal-close"
                type="button"
                aria-label="Close">
                &times;
            </button>

            <div id="book-modal-spinner" class="modal-spinner">
                <div class="spinner"></div>
            </div>

            <div id="book-modal-body"></div>

        </div>

    </div>




    <style>
        .book-modal {
            position: fixed;
            inset: 0;
            z-index: 1000;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 20px;
        }

        .book-modal[hidden] {
            display: none;
        }

        .book-modal-overlay {
            position: absolute;
            inset: 0;

            background: rgba(0, 0, 0, 0.7);
        }

        .book-modal-content {
            position: relative;
            z-index: 1;

            width: min(800px, 100%);
            max-height: 90vh;

            overflow-y: auto;

            background: white;
            border-radius: 12px;

            padding: 30px;

            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            background-color: #2c3e50;
        }

        .modal-close {
            position: absolute;

            top: 1rem;
            right: 1rem;

            width: 40px;
            height: 40px;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 0;
            margin: 0;

            border: none;
            border-radius: 50%;

            background: rgba(0, 0, 0, 0.08);
            color: #3498db;

            font-size: 1.5rem;

            cursor: pointer;

            transition:
                transform 0.2s ease,
                background-color 0.2s ease;

            &:hover {
                transform: rotate(90deg) scale(1.1);
                background-color: rgba(0, 0, 0, 0.15);
            }
        }


        .modal-book {
            display: flex;
            gap: 30px;
        }

        #modal-image {
            width: 220px;
            height: auto;

            object-fit: contain;
            align-self: flex-start;
        }

        .modal-book-info {
            flex: 1;
        }

        #modal-title {
            margin-top: 0;
        }

        @media (max-width: 600px) {

            .modal-book {
                flex-direction: column;
            }

            #modal-image {
                width: 160px;
                margin: 0 auto;
            }

        }
    </style>


    <script>
        const modal = document.getElementById('book-modal');
        const modalClose = document.getElementById('modal-close');
        const modalOverlay = document.querySelector('.book-modal-overlay');
        const modalBody = document.getElementById('book-modal-body');
        const modalSpinner =
            document.getElementById('book-modal-spinner');

        document.addEventListener('click', async function(event) {

            const book = event.target.closest('.book-link');

            if (!book) {
                return;
            }

            const bookId = book.dataset.bookId;

            // Clear previous book
            modalBody.innerHTML = '';

            // Show spinner
            modalSpinner.style.display = 'flex';

            // Open modal
            openModal();

            try {

                const response = await fetch(
                    `/ajax/view_book.php?id=${encodeURIComponent(bookId)}`
                );

                if (!response.ok) {
                    throw new Error(
                        `HTTP error: ${response.status}`
                    );
                }

                const html = await response.text();

                // Hide spinner
                modalSpinner.style.display = 'none';

                // Insert returned HTML
                modalBody.innerHTML = html;

            } catch (error) {

                console.error('Failed to load book:', error);

                modalSpinner.style.display = 'none';

                modalBody.innerHTML = `
        <div class="book-error">
            <h2>Unable to load book</h2>
            <p>
                There was a problem retrieving the book information.
            </p>
        </div>
    `;
            }

        });



        function openModal() {

            modal.hidden = false;

            document.body.style.overflow = 'hidden';

        }


        function closeModal() {

            modal.hidden = true;

            document.body.style.overflow = '';

        }


        modalClose.addEventListener(
            'click',
            closeModal
        );


        modalOverlay.addEventListener(
            'click',
            closeModal
        );


        document.addEventListener('keydown', function(event) {

            if (
                event.key === 'Escape' &&
                !modal.hidden
            ) {
                closeModal();
            }

        });
    </script>


<?php

} else {

    echo '<h1>Failed to connect to database and retrieve books</h1>';
}

?>