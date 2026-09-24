<?php

echo '<div class="page">
<div class="spinner-container">

<div class="spinner">
</div>

</div>
</div>

<script>
$.ajax({
    url: "/ajax/view_book_grid.php",
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
