<?php
require_once('config.php');
require_once('functions.php');
require_once('init.php');
require_once('data.php');

$index_page = render('index', $major_indexes);
print render('layout', [
    'content' => $index_page,
    'title' => $title,
    'categories' => $categories,
    'user_avatar' => $user_avatar
]);
?>
