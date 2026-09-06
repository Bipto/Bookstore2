<?php

/** @var array{id: string, title: string, author: string} $params */

$id = $params[0];
$title = $params[1];
$author = $params[2];

echo '<h1>ID: ' . $id . ' </h1>';
echo '<h1>Title: ' . $title . ' </h1>';
echo '<h1>Author: ' . $author . ' </h1>';
