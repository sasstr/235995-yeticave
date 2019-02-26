<?php
require_once('config.php');
require_once('functions.php');
require_once('data.php');

$title = 'Страница поиска';

$search = render('search', $search_page);
print render('layout', [
    'content' => $search,
    'title' => $title,
    'categories' => $categories,
    'user_avatar' => $user_avatar
]);
