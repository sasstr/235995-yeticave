<?php
require_once('config.php');
require_once('functions.php');
require_once('data.php');
session_start();
$index_page = render('index', $major_indexes);
print render('layout', [
    'content' => $index_page,
    'title' => $title,
    'categories' => $categories,
    'user_avatar' => $user_avatar
]);
?>
