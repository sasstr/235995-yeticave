<?php
require_once('config.php');
require_once('functions.php');
require_once('data.php');

$title = 'Логин';

$login = render('login', $login_page);
print render('layout', [
    'content' => $login,
    'title' => $title,
    'categories' => $categories,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar
]);
