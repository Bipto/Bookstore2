const titleEntry = document.getElementById('book-search');
const authorEntry = document.getElementById('author-search');

const searchDelay = 150;
let searchTimeout;

function updateBookGrid() {
  clearTimeout(searchTimeout);

  searchTimeout = setTimeout(async () => {
    const params = new URLSearchParams();
    params.set('title', titleEntry.value);
    params.set('author', authorEntry.value);

    const url = '/ajax/view_book_grid.php?' + params.toString();

    const response = await fetch(url);
    const html = await response.text();

    document.getElementById('page-content').innerHTML = html;
  }, searchDelay);
}

titleEntry.addEventListener('input', () => {
  updateBookGrid();
});

authorEntry.addEventListener('input', () => {
  updateBookGrid();
});

const filterToggle = document.querySelector('.book-filters-toggle');
const filters = document.querySelector('.book-filters');

if (filterToggle && filters) {
  filterToggle.addEventListener('click', () => {
    const isOpen = filterToggle.getAttribute('aria-expanded') === 'true';

    filterToggle.setAttribute('aria-expanded', String(!isOpen));

    filters.classList.toggle('is-open', !isOpen);
  });
}