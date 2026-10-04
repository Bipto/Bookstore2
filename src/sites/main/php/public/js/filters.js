const titleEntry = document.getElementById('book-search');

const searchDelay = 150;
let searchTimeout;

titleEntry.addEventListener('input', () => {
  clearTimeout(searchTimeout);

  searchTimeout = setTimeout(async () => {
    const params = new URLSearchParams();
    params.set('title', titleEntry.value);

    const url = '/ajax/view_book_grid.php?' + params.toString();

    const response = await fetch(url);
    const html = await response.text();

    document.getElementById('page-content').innerHTML = html;
  }, searchDelay);
});
