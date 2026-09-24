<?php

/** @var array{id: string, title: string, author: string} $params */

$id = $params[0];

echo '<div class="page">
<div class="spinner-container">

<div class="spinner">
</div>

</div>
</div>

<script>
$.ajax({
    url: "/ajax/view_book.php?id=' . $id . '",
    type: "GET",
    success: function (html) {
        $(".page").html(html);
    },
    error: function () {
        $(".page").html("<p>Something went wrong.</p>");
    }
});
</script>
';
