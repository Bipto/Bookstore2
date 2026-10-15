<?php

class AJAX
{
    public static function call(string $url): string
    {
        return "
        <div class='page' id='page-content'>
            <div class='spinner-container'>

            <div class='spinner'>
            </div>

            </div>
        </div>

        <script>
            fetch('{$url}', {
                method: 'GET'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Request failed');
                }
                return response.text();
            })
            .then(html => {
                document.querySelector('.page').innerHTML = html;
            })
            .catch(() => {
                document.querySelector('.page').innerHTML =
                    '<p>Something went wrong.</p>';
            });
        </script>
        ";
    }
}
