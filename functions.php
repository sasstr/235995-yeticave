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
    $link = mysqli_connect(DB_SETUP['HOST'], DB_SETUP['LOGIN'], DB_SETUP['PASSWORD'], DB_SETUP['NAME']);
        if ($link == false) {
            print("Ошибка подключения: " . mysqli_connect_error());
            die();
    };
    mysqli_set_charset($link, 'utf8');
    return $link;
};

function get_categories($link)
{
    $sql = 'SELECT `name` FROM categories';
    $result = mysqli_query($link, $sql);
    if ($result !== false) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    return $categories;
}

function get_lots($link)
{
    $sql = 'SELECT lots.`title` AS `lots_title`, lots.`starting_price`, lots.`img_path`, categories.`name` AS `categories_name`
            FROM lots
            JOIN categories ON categories.`id` = lots.`category_id`
            WHERE lots.`winner_id` IS NULL and lots.`finishing_date` > CURRENT_TIMESTAMP
            ORDER BY lots.`starting_date` LIMIT 9;';
    $result = mysqli_query($link, $sql);
    if ($result !== false) {
        $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    return $lots;
}

/*

function get_lots($link)
    {
        $result = [];
        $sql = “your_sql”;
        $result_query = mysqli_query($link, $sql);
        if ($result_query !== false) {
            $result = mysqli_fetch_all($result_query, MYSQLI_ASSOC);
        }
        return $result;
    }

ну и далее в таком же стиле.
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
---------------------------------------------------------------------------------
На главной странице показываются карточки девяти новых лотов.+++

Это лоты, у которых не истек срок их публикации, +++

отсортированные от самых новых к старым.  +++

В прошлом задании вы написали SQL-запрос
для получения таких записей. Сейчас вам будет необходимо заменить
существующий массив с лотами на данные, полученные из MySQL по этому запросу.

Также в футере страницы находится список категорий.
Пока это статичный список, т.е. его отдельные пункты прописаны прямо в верстке.
В этом задании вы должны заменить его на данные из БД.

Необходимые действия

В сценарии главной страницы выполните подключение к MySQL
Отправьте SQL-запрос для получения списка новых лотов
Используйте эти данные для показа карточек лотов на главной странице
*/
