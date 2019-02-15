<?php
require_once('config.php');
require_once('functions.php');
require_once('data.php');

$title = 'Добавить новый лот';

$page_404 = render('404', $p_404);
print render('layout', [
    'content' => $page_404,
    'title' => $title,
    'categories' => $categories,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar
]);
