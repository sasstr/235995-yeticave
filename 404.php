<?php
require_once('config.php');
require_once('functions.php');
require_once('data.php');

$p_404 = [
    'categories' => $categories,
];

$page_404 = render('404', $p_404);
print render('layout', [
    'content' => $page_404,
    'title' => '404 страница не найдена',
    'categories' => $categories,
    'user_avatar' => $user_avatar
]);
