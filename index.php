<?php
require_once('config.php');
require_once('data.php');
require_once('functions.php');

/* // Регистрация пользователя на сайте
$link = mysqli_connect(DB_SETUP['HOST'], DB_SETUP['LOGIN'], DB_SETUP['PASSWORD'], DB_SETUP['NAME']);
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
mysqli_stmt_execute($stmt); // Выполняет подготовленный запрос

! Все функции для работы с MySQL лучше выносить в отдельный модуль. Задача на подумать.
! Попробуй написать несколько универсальных функций:

`db_insert()` - выполняет запрос на добавление данных;
`db_update()` - выполняет запрос на обновление данных;
`db_delete()` - выполняет запрос на удаление данных;

! Спека функции может выглядеть так:

`db_insert($connect, $sql, $data);`.

! Таким образом, все задачи, связанные с взаимодействием
! с БД будут решаться с помощью трех функций.

! Далее. После этого, ты можешь в отдельном модуле сделать функции,
! которые будут формировать сами запросы.
! Например:

`getLots()` - возвращает все лоты;
`save_lot()` - сохраняет лот в базе и т.д.

! Функции будут возвращать текст запросов, которые дальше можно передавать
! в ранее описанные универсальные функции.
! Или могут вызывать эти универсальные функции самостоятельно.
! Подумай.
*/

$index_page = render('index', $major_indexes);
print render('layout', [
    'content' => $index_page,
    'title' => $title,
    'categories' => $categories,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar
]);
?>
