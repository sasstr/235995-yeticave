<?
/**
 * Функция устанавливает связь с базой данных
 * @return resource возращает ресурс соединения
 */
function db_connect() {
    $link = mysqli_connect(DB_SETUP['HOST'], DB_SETUP['LOGIN'], DB_SETUP['PASSWORD'], DB_SETUP['NAME']);
        if ($link == false) {

            die("Ошибка подключения: " . mysqli_connect_error());
    };
    mysqli_set_charset($link, 'utf8');
    return $link;
};
/** Функция возращает результат вставки в базу данных
 * @param resource $link принимает ресурс соединения
 * @param string $sql подготовленное выражение
 * @param array $data массив данных для вставки в бд
 * @return bool $res или false
 */
function  db_insert($link, $sql, $data = []) {
    if (!empty($data)) {
        $stmt = db_get_prepare_stmt($link, $sql, $data);
        return  mysqli_stmt_execute($stmt);
    }
    return -1;
};
/** Функция возращает результат выборки из базы данных
 * @param resource $link принимает ресурс соединения
 * @param string $sql подготовленное выражение
 * @param array $data массив данных для вставки в бд
 * @return
 */
function  db_select ($link, $sql, $data = []) {
    if (!empty($data)) {
        $stmt = db_get_prepare_stmt($link, $sql, $data);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
    }
    return [];
    // return mysqli_fetch_assoc($result);
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
};
/**
 * Возращеает список лотов - карточек товара
 * лимит 9 шт.
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
        return mysqli_fetch_all($result , MYSQLI_ASSOC);
    } else { return []; }
};

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
    } else { return []; }
};

/**
 * Функция добавляет новую ставку
 *
 * @param resource $link  рескрс соединения
 * @param string $sql  подготовленное выражение
 * @param array $data массив данных
 * @return void
 */
function add_new_rate_to_db($link, $data = []) {
    $sql = 'INSERT INTO rates ( `rate_amount`,
                                `user_id`,
                                `lots_id`
                                )
                                VALUES (?, ?, ?);';
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    return mysqli_stmt_execute($stmt);

};
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
};

/**
 * Функция возращает результат запроса по выборке из базы данных
 *
 * @param resource $link
 * @param string $sql подготовленное выражение
 * @param integer $lot_id номер id по которому надо получить
 * @return Возращает результат запроса по выборке из базы данных
 */
function select_starting_price_data_by_id ($link, $lot_id) {
    $sql = 'SELECT `lots`.`starting_price`,
                        `lots`.`rate_step`,
                        `lots`.`user_id` AS lots_user_id,
                        `rates`.`user_id` AS rates_user_id,
                        `lots`.`finishing_date`
                        FROM `lots`
                        JOIN `rates` ON `lots`.`user_id` = `rates`.`user_id`
                        WHERE `lots`.`id` = ?;';
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $lot_id);
    mysqli_stmt_execute($stmt);
    return mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
};

/**
 * Функция возращает результат запроса по выборке из базы данных
 *
 * @param resource $link
 * @param string $sql подготовленное выражение
 * @param integer $lot_id номер id по которому надо получить
 * @return Возращает результат запроса по выборке из базы данных
 */
function select_history_data_by_id ($link, $lot_id) {
    $sql = 'SELECT `users`.`name`,
            `rates`.`rate_amount`,
            `rates`.`date`,
            `users`.`id`,
            `rates`.`user_id`
            FROM `rates`
            LEFT JOIN `users` ON `users`.`id` = `rates`.`user_id`
            WHERE `rates`.`lots_id` = ?
            ORDER BY `rates`.`date` DESC;';
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $lot_id);
    mysqli_stmt_execute($stmt);
    return mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
};

/**
 * Функция возращает результат запроса по выборке из базы данных
 *
 * @param resource $link
 * @param string $sql подготовленное выражение
 * @param integer $lot_id номер id по которому надо получить
 * @return Возращает результат запроса по выборке из базы данных
 */
function select_rates_data_by_id ($link, $lot_id) {
    $sql = 'SELECT  `users`.`id`,
                    `rates`.`rate_amount`,
                    `lots`.`rate_step`,
                    `lots`.`starting_price`,
                    `rates`.`date`,
                    `lots`.`finishing_date`,
                    `rates`.`lots_id`,
                    `lots`.`id`,
                    `lots`.`user_id` AS lots_user_id,
                    `rates`.`user_id` AS rates_user_id
                    FROM `rates`
                    LEFT JOIN `users` ON `users`.`id` = `rates`.`user_id`
                    JOIN `lots` ON `lots`.`id` = `rates`.`lots_id`
                    WHERE `rates`.`lots_id` = ?
                    ORDER BY `rates`.`rate_amount` DESC
                    LIMIT 1;';
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $lot_id);
    mysqli_stmt_execute($stmt);
    return mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
};
/**
 * Функция возращает ID пользователя по email
 *
 * @param resource $link
 * @param string $email
 * @return array
 */
function select_id_by_email ($link, $email) {
    $email = mysqli_real_escape_string($link, $sign_up['email']);
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    return mysqli_fetch_assoc(mysqli_stmt_execute($stmt));
};
