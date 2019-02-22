<?php
require_once('config.php');
require_once('functions.php');
require_once('data.php');

$index_page = render('index', $major_indexes);
print render('layout', [
    'content' => $index_page,
    'title' => $title,
    'categories' => $categories,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar
]);
