<?php
require_once('config.php');
require_once('functions.php');
require_once('data.php');

$title = 'Страница поиска';

$sign_up = render('sign-up', $sign_up_page);
print render('layout', [
    'content' => $sign_up,
    'title' => $title,
    'categories' => $categories,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar
]);
