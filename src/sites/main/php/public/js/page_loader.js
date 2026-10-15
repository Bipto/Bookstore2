function loadContent(url, selector) {
  fetch(url, {method: 'GET'})
      .then(response => {
        if (!response.ok) {
          throw new Error('Request failed');
        }
        return response.text();
      })
      .then(html => {
        document.querySelector(`#${selector}`).innerHTML = html;
      })
      .catch(() => {
        document.querySelector(`#${selector}`).innerHTML =
            '<p>Something went wrong.</p>';
      });
}