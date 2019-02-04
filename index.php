<?php
require_once('config.php');
require_once('functions.php');
require_once('data.php');

$link = mysqli_connect(DB['HOST'], DB['LOGIN'], DB['PASSWORD'], DB['NAME']);
if ($link == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
}

$index_page = render('index', $major_indexes);
print render('layout', [
    'main_content' => $index_page,
    'title' => $title,
    'categories' => $categories,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar
]);
?>
