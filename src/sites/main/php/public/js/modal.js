const modal = document.getElementById('book-modal');
const modalClose = document.getElementById('modal-close');
const modalOverlay = document.querySelector('.book-modal-overlay');
const modalBody = document.getElementById('book-modal-body');
const modalSpinner = document.getElementById('book-modal-spinner');
const modalHeader = document.getElementById('book-modal-title');
const modalFooter = document.getElementById('book-modal-footer');

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
        `/ajax/view_book_modal.php?id=${encodeURIComponent(bookId)}`);

    if (!response.ok) {
      throw new Error(`HTTP error: ${response.status}`);
    }

    const data = await response.json();
    const header = data['header'];
    const content = data['content'];
    const footer = data['footer'];

    // Hide spinner
    modalSpinner.style.display = 'none';

    modalHeader.innerHTML = header;

    // Insert returned HTML
    modalBody.innerHTML = content;

    modalFooter.innerHTML = footer;

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


modalClose.addEventListener('click', closeModal);


modalOverlay.addEventListener('click', closeModal);


document.addEventListener('keydown', function(event) {
  if (event.key === 'Escape' && !modal.hidden) {
    closeModal();
  }
});

function addToCart(id) {
  $.ajax({
    url: '/ajax/add_book_to_cart.php?book_id=' + id,
    type: 'GET',
    success: function() {
      closeModal();

      let count = parseInt($('#cart-count').text());
      $('#cart-count').text(count + 1);
      $('#cart-count').show();
    },
    error: function() {
      $('.page').html('<p>Something went wrong.</p>');
    }
  });
}