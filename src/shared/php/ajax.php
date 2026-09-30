<?php

class AJAX
{
    public static function call(string $url): string
    {
        return "
        <div class='page'>
            <div class='spinner-container'>

            <div class='spinner'>
            </div>

            </div>
        </div>

        <script>
        $.ajax({
            url: '{$url}',
            type: 'GET',
            success: function (html) {
                $('.page').html(html);
            },
            error: function () {
                $('.page').html('<p>Something went wrong.</p>');
            }
        });
        </script>
        ";
    }
}
