<?php
require_once('config.php');
require_once('functions.php');
require_once('data.php');

$title = 'Добавить новый лот';

$add_lot = render('add-lot', $add_lot_page);
print render('layout', [
    'content' => $add_lot,
    'title' => $title,
    'categories' => $categories,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar
]);
