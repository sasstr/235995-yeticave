<?php
require_once('config.php');
require_once('functions.php');
require_once('data.php');

/* // Регистрация пользователя на сайте
$link = mysqli_connect(DB['HOST'], DB['LOGIN'], DB['PASSWORD'], DB['NAME']);
if ($link == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
};
mysqli_set_charset($link, 'utf8');

$email = mysqli_real_escape_string($link, $_POST['email']); // Так для всех полей надо делать?
$password = password_hash($_POST['password']);
$name = $_POST['name'];
$contacts = $_POST['message'];
$avatar = $_POST['']; // avatar - нет поля name в разметке

$sql = INSERT INTO users (email, password, name, contacts, avatar) VALUES (?, ?, ?, ?, ?); // Запрос к БД
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, 'ssssb', $email, $password, $name, $contacts, $avatar);
mysqli_stmt_execute($stmt);

*/

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
