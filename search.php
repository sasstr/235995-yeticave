<?php
require_once('config.php');
require_once('functions.php');
require_once('init.php');
require_once('data.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $search_query = trim($_POST['search']);
    if (!empty($search_query)) {
        $sql = 'SELECT `lots`.`title`, `lots`.`descripton`
                FROM `lots`
                WHERE MATCH(`lots`.`title`, `lots`.`description`) AGAINST(?);';
    }
}

$search = render('search', $search_page);
print render('layout', [
    'content' => $search,
    'title' => 'Страница поиска',
    'categories' => $categories,
    'user_avatar' => $user_avatar
]);
