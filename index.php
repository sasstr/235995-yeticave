<?php
require_once('functions.php');
require_once('data.php');
$index_data = render('index', $index_main);
print render('layout', [
    'main_content' => $index_data,
    'title' => $title,
    'categories' => $categories,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar
]);
?>
