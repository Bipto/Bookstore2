<?php

require_once '/var/www/shared/api.php';

$id = $_GET['id'];

echo API::delete("/cart?id={$id}");
