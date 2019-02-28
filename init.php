<?
/**
 * Функция устанавливает связь с базой данных
 * @return resource возращает ресурс соединения
 */
function db_connect() {
    $link = mysqli_connect(DB_SETUP['HOST'], DB_SETUP['LOGIN'], DB_SETUP['PASSWORD'], DB_SETUP['NAME']);
        if ($link == false) {
            print("Ошибка подключения: " . mysqli_connect_error());
            die();
    };
    mysqli_set_charset($link, 'utf8');
    return $link;
};

/**
 * Возращеает список категорий для меню на сайте
 *
 * @param resource $link принимает ресурс соединения
 * @return array Возращает список категорий
 */
function get_categories($link) {
    $sql = 'SELECT `name`, `id` FROM categories';
    $result = mysqli_query($link, $sql);
    if ($result !== false) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    return $categories;
}
/**
 * Возращеает список лотов - карточек товара
 * лимит 9 шт
 * @param resource $link принимает ресурс соединения
 * @return array Возращает список лотов
 */
function get_lots($link) {
    $sql = 'SELECT lots.`title` AS `lots_title`, lots.`id`, lots.`starting_price`, lots.`img_path`, categories.`name` AS `categories_name`
            FROM lots
            JOIN categories ON categories.`id` = lots.`category_id`
            WHERE lots.`winner_id` IS NULL and lots.`finishing_date` > CURRENT_TIMESTAMP
            ORDER BY lots.`starting_date` LIMIT 9;';
    $result = mysqli_query($link, $sql);
    if ($result !== false) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}

/**
 * @param resource $link ресурс соединения
 * @param integer $lot_id номер id по которому надо получить лот
 *
 * @return string Возращает лот по id из БД
 */

 function get_lot_by_id ($link, $lot_id) {

    $sql = 'SELECT lots.`title`
            AS `lots_title`,
            lots.`description`,
            lots.`id`,
            lots.`img_path`,
            `categories`.`name` AS `categories_name`
            FROM lots
            JOIN categories ON categories.`id` = lots.`category_id`
            WHERE lots.`id`=' . intval($lot_id) . ';';
    $result = mysqli_query($link, $sql);
    if ($result !== false) {
        return mysqli_fetch_assoc($result);
    }
}
/**
 * Функция добавляет новый лот в БД и в случае успеха перенаправляет пользователя на страницу нового лота.
 *
 * @param resource $link
 * @param string $sql подготовленное выражение
 * @param array $data
 * @return void
 */
function add_new_lot_to_db($link, $sql, $data = []) {
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    $res = mysqli_stmt_execute($stmt);
    if ($res) {
        $lot_id = mysqli_insert_id($link);
        header('Location: lot.php?id=' . $lot_id);
        exit();
    }
}
/**
 * Функция возращает результат запроса по выборке из базы данных
 *
 * @param resource $link
 * @param string $sql подготовленное выражение
 * @param integer $lot_id номер id по которому надо получить
 * @return Возращает результат запроса по выборке из базы данных
 */
function select_data_by_lot_id ($link, $sql, $lot_id) {
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $lot_id);
    mysqli_stmt_execute($stmt);
    return mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
}

