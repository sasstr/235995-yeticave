<?php
require_once('config.php');
require_once('functions.php');
require_once('data.php');

$title = 'Лот';

$lot = render('lot', $lot_page);
print render('layout', [
    'content' => $lot,
    'title' => $title,
    'categories' => $categories,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar
]);
