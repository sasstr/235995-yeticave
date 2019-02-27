<?php
require_once('config.php');
require_once('functions.php');
require_once('data.php');

$my_lots = render('my-lots', $my_lots_page);
print render('layout', [
    'content' => $my_lots,
    'title' => 'Мои лоты',
    'categories' => $categories,
    'user_avatar' => $user_avatar
]);
