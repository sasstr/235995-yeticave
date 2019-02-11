<?php
require_once('mysql_helper.php');
MOSCOW_TIME_ZONE;
/**
 * Проверяет наличие файла шаблона и возращает его.
 * @param string $file_name название файла шаблона
 * @param array $data_array массив переменных
 *
 * @return string возращает разметку шаблона
 */
function render ($file_name, $data_array) {
    $path = TEMPLATE_PATH . $file_name . PHP_EXTENSION;
    if(!file_exists($path)) {
        return '';
    }
    ob_start();
    extract($data_array, EXTR_SKIP);
    require_once ($path);
    return ob_get_clean();
};
/**
 * Функция форматирует цену товара и добавляет знак рубля.
 * @param integer $price
 *
 * @return string возращает отформатированную цену по тысячам со знаком рубля.
 */

 function format_price ($price) {
    return number_format(ceil($price), 0, ',', ' ') . RUBLE_SYMBOL;
};
/**
 * Функция рассчитывает разницу от текущего времни до конца суток
 *
 * @return string возращает время которое осталось до конца суток от текущего.
 */
function show_time() {
    $now = date_create('now');
    $tomorrow = date_create('tomorrow');
    $diff = date_diff($now, $tomorrow);
    return date_interval_format($diff,"%H:%I");
}

/* // Регистрация пользователя на сайте
$email = mysqli_real_escape_string($link, $_POST['email']); // Так для всех полей надо делать?
$password = password_hash($_POST['password']);
$name = $_POST['name'];
$contacts = $_POST['message'];
$avatar = $_POST['']; // avatar - нет поля name в разметке

$sql = INSERT INTO users (email, password, name, contacts, avatar) VALUES (?, ?, ?, ?, ?); // Запрос к БД
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, 'ssssb', $email, $password, $name, $contacts, $avatar);
mysqli_stmt_execute($stmt); // Выполняет подготовленный запрос
mysqli_stmt_close($stmt);  // закрываем запрос

! Все функции для работы с MySQL лучше выносить в отдельный модуль. Задача на подумать.
! Попробуй написать несколько универсальных функций:

`db_insert()` - выполняет запрос на добавление данных;
`db_update()` - выполняет запрос на обновление данных;
`db_delete()` - выполняет запрос на удаление данных;

 Спека функции может выглядеть так:

`db_insert($link, $sql, $data);`. */

/* Функция для соединения с БД*/
function db_connect() {
    $link = mysqli_connect(...DB_SETUP);
        if ($link == false) {
            print("Ошибка подключения: " . mysqli_connect_error());
            exit();
    };
    mysqli_set_charset($link, 'utf8');
    return $link;
};
/* Функция формирует запрос к БД INSERT получает параметрами
   имя таблицы и массив полей и массив значений*/
function make_insert_query_sql ($table_name, $array_of_column , $array_of_values) {
    $sql = 'INSERT INTO' . $table_name . '( '. implode(', ', $array_of_column) .' )'
    . 'VALUES' . implode(', ', $array_of_values);
    return $sql;
  };

/* Функция для обработки INSERT запросов */
function db_insert($link, $sql, $data) {
    $link = db_connect(); // соеденились с базой
    // mysql_real_escape_string(); экранирует спец символы  в запросе
    $stmt = db_get_prepare_stmt($link, $sql, $data); // подготовили запрос
    mysqli_stmt_execute($stmt); // Выполняет подготовленный запрос
    $rows = mysqli_stmt_affected_rows($stmt); // кол-во строк, которые вставлены в БД
    mysqli_stmt_close($stmt); // закрываем запрос
    return $rows; // возращаем кол-во изменененных строк в БД
};
/* ! Чушь какая то получается !


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
*/
