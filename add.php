<?php
require_once('config.php');
require_once('functions.php');
require_once('data.php');


$required_fields = ['email', 'password', 'login'];
$errors = [];
foreach ($required_fields as $field) {
if (empty($_POST[$field])) {
$errors[$field] = 'Поле не заполнено';
}
}
if (count($errors)) {

// показать ошибку валидации
}
$add_lot = render('add', $add_lot_page);
print render('layout', [
    'content' => $add_lot,
    'title' => 'Добавить новый лот',
    'categories' => $categories,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar
]);
