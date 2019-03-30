<?
date_default_timezone_set('europe/moscow');
/**
 * Функция устанавливает связь с базой данных
 *
 * @return resource возращает ресурс соединения или сообщение о ошибки если соединение не установлено
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
 * @param array $data массив данных для вставки в базу данных
 *
 * @return bool  $res или false
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
 * @param array $data массив полей для выборки из базы данных
 *
 * @return array двумерный массив c результатом выборки из базы данных по переданному запросу или пустой
 */
function  db_select ($link, $sql, $data = []) {
    if (!empty($data)) {
        $stmt = db_get_prepare_stmt($link, $sql, $data);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
    }
    return [];
};
/**
 * Возращеает список категорий для меню на сайте
 *
 * @param resource $link принимает ресурс соединения
 *
 * @return array Возращает список категорий
 */
function get_categories($link) {
    $sql = 'SELECT `name`, `id`, `class_name` FROM categories';
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
 *
 * @return array Возращает список лотов и данные для их отрисовки
 */
function get_lots($link) {
    $sql = 'SElECT `lots`.`title` AS `lots_title`,
                `lots`.`id`,
                `lots`.`starting_price`,
                lots.`img_path`,
                lots.`finishing_date`,
                lots.`starting_date`,
                categories.name AS categories_name,
                count(rates.lots_id) AS rates_count,
                MAX(rates.rate_amount) AS rate_amount
            FROM lots
                INNER JOIN categories
                ON lots.category_id = categories.id
                LEFT JOIN rates
                ON lots.id = rates.lots_id
            WHERE lots.finishing_date > NOW()
            AND lots.winner_id IS NULL
            GROUP BY
                lots.id,
                lots.title,
                lots.starting_price,
                lots.img_path,
                categories.name
            ORDER BY lots.`starting_date`
            LIMIT 9;';
    $result = mysqli_query($link, $sql);
    if ($result !== false) {
        return mysqli_fetch_all($result , MYSQLI_ASSOC);
    }
    return [];
};

/**
 * @param resource $link ресурс соединения
 * @param integer $lot_id номер id по которому надо получить лот
 *
 * @return array Возращает лот по id из базы данных или пустой массив
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
    return [];
};

/**
 * Функция добавляет новую ставку в базу данных
 *
 * @param resource $link  рескрс соединения
 * @param string $sql  подготовленное выражение
 * @param array $data массив данных
 *
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
 * @param resource $link рескрс соединения
 * @param string $sql подготовленное выражение
 * @param integer $lot_id номер id по которому надо получить
 *
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
 * @param resource $link рескрс соединения
 * @param string $sql подготовленное выражение
 * @param integer $lot_id номер id по которому надо получить
 *
 * @return Возращает результат запроса по выборке из базы данных
 */
function select_starting_price_data_by_id ($link, $lot_id) {
    $sql = 'SELECT `lots`.`starting_price`,
                        `lots`.`rate_step`,
                        `lots`.`user_id` AS lots_user_id,
                        `rates`.`user_id` AS rates_user_id,
                        `lots`.`finishing_date`
                        FROM `lots`
                        LEFT JOIN `rates` ON `lots`.`user_id` = `rates`.`user_id`
                        WHERE `lots`.`id` = ?;';
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $lot_id);
    mysqli_stmt_execute($stmt);
    return mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
};

/**
 * Функция возращает результат запроса по выборке из базы данных
 *
 * @param resource $link рескрс соединения
 * @param string $sql подготовленное выражение
 * @param integer $lot_id номер id по которому надо получить
 *
 * @return array Возращает результат запроса по выборке из базы данных
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
 * Функция возращает результат запроса по выборке из базы данных по ID лота
 *
 * @param resource $link рескрс соединения
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
 * @param resource $link рескрс соединения
 * @param string $email пользователя
 * @return array массив с ID пользователя
 */
function select_id_by_email ($link, $email) {
    $email_checked = mysqli_real_escape_string($link, $email);
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 's', $email_checked);
    mysqli_stmt_execute($stmt);
    return mysqli_fetch_assoc(mysqli_stmt_execute($stmt));
};
/**
 * Возращает массив лотов по полнотекстовому поиску или сообщение о ошибке
 *
 * @param resource $link рескрс соединения
 * @param string $search_text
 * @param array/string
 */
function search_ft_to_db ($link, $search_text) {
    $search_query = trim($search_text);
    if (!empty($search_query)) {
    $sql = 'SELECT  `lots`.`id`,
                        `lots`.`img_path`,
                        `lots`.`title`,
                        `lots`.`starting_price`,
                        `lots`.`description`,
                        `categories`.`name`
                FROM `lots`
                JOIN `categories` ON `categories`.`id` = `lots`.`category_id`
                JOIN users ON `lots`.`user_id` = `users`.`id`
                WHERE MATCH(`lots`.`title`, `lots`.`description` ) AGAINST(? IN BOOLEAN MODE)
                AND (`lots`.`winner_id` IS NULL)
                AND (`lots`.`finishing_date` > NOW())
                ORDER BY `lots`.`starting_date` DESC;';
                return db_select ($link, $sql, [$search_query]);
    }
    return 'Надо набрать поисковый запрос';
};

function select_email_from_db ($link, $email) {
    $sql = 'SELECT * FROM users WHERE email = ?';
    $res = db_select ($link, $sql, [$email]);
    return ($res) ? $res : null;
};

function check_email_in_db ($link, $email) {
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [$email]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_num_rows($res);
};

function insert_new_user_to_db ($link, $data_new_user) {
    $sql = 'INSERT INTO users (registration_date, email, name, password, contacts, avatar) VALUES (NOW(), ?, ?, ?, ?, ?)';
    $stmt = db_get_prepare_stmt($link, $sql, $data_new_user);
    return mysqli_stmt_execute($stmt);
};

function get_lots_by_category_id($link, $categ_id) {
    $sql = 'SElECT `lots`.`title` AS `lots_title`,
                        `lots`.`id`,
                        `lots`.`starting_price`,
                        `lots`.`img_path`,
                        `lots`.`finishing_date`,
                        `lots`.`starting_date`,
                        `categories`.`name` AS `categories_name`,
                        count(`rates`.`lots_id`) AS rates_count,
                        MAX(`rates`.`rate_amount`) AS rate_amount
                    FROM lots
                        INNER JOIN categories
                        ON `lots`.`category_id` = `categories`.`id`
                        LEFT JOIN `rates`
                        ON `lots`.`id` = `rates`.`lots_id`
                    WHERE lots.finishing_date > NOW() and categories.`id` = ?
                    AND lots.winner_id IS NULL
                    GROUP BY
                        lots.id,
                        lots.title,
                        lots.starting_price,
                        lots.img_path,
                        categories.name
                    ORDER BY lots.`starting_date`
                    LIMIT 9;';
    $res = db_select ($link, $sql, [(int) $categ_id]);
    return ($res) ? $res : [];
};

function add_new_lot($link, $lot_data) {
    $sql = 'INSERT INTO lots ( `title`,
    `description`,
    `img_path`,
    `starting_price`,
    `starting_date`,
    `rate_step`,
    `finishing_date`,
    `user_id`,
    `category_id`
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);';
    $res_add_new_lot = db_insert($link, $sql, $lot_data);
    if ($res_add_new_lot) {
        return mysqli_insert_id($link);
    };
}

function get_my_lots($link, $lots_user_id) {
    $sql = 'SElECT `lots`.`title` AS `lots_title`,
                `lots`.`starting_price`,
                `lots`.`description`,
                `lots`.`img_path`,
                `lots`.`finishing_date`,
                `lots`.`winner_id`,
                `lots`.`starting_date`,
                `categories`.`name` AS categories_name,
                MAX(`rates`.`rate_amount`) AS rate_amount
            FROM lots
                INNER JOIN categories
                ON `lots`.`category_id` = `categories`.`id`
                LEFT JOIN rates
                ON `lots`.`id` = `rates`.`lots_id`
                INNER JOIN users ON lots.user_id = ?
            GROUP BY
                `lots`.`id`,
                `lots`.`title`,
                `lots`.`starting_price`,
                `lots`.`img_path`,
                `categories`.`name`
            ORDER BY `lots`.`starting_date`;';
    $res = db_select ($link, $sql, [(int) $lots_user_id]);
    return isset($res) ? $res : null;
};

/**
 * Возращает лоты которые не имеют победителя и время их вышло
 *
 * @param resource $link ресурс соединения
 * @return array массив с лотами по запросу к базе данных
 */
function db_get_lots_not_winners($link) {
    $sql = 'SELECT id, title, user_id
        FROM lots
        WHERE finishing_date <= NOW() AND winner_id IS NULL;';
    $query = mysqli_query($link, $sql);
    if ($query) {
        return mysqli_fetch_all($query, MYSQLI_ASSOC);
    }
    return [];
};
/**
 * Заносит в лот победителем автора последней ставки
 *
 * @param resource $link ресурс соединения
 * @param integer $rate_amount_id ID ставки победителя
 * @return void
 */
function insert_winner_to_db ($link, $rate_amount_id) {
    $sql = 'INSERT INTO lots (winner_id) VALUES (?);';
    db_insert($link, $sql, [$rate_amount_id]);
};

function get_lots_pagination($link, $page_items, $offset) {
    $sql = 'SElECT `lots`.`title` AS `lots_title`,
                `lots`.`id`,
                `lots`.`starting_price`,
                lots.`img_path`,
                lots.`finishing_date`,
                lots.`starting_date`,
                categories.name AS categories_name,
                count(rates.lots_id) AS rates_count,
                MAX(rates.rate_amount) AS rate_amount
            FROM lots
                INNER JOIN categories
                ON lots.category_id = categories.id
                LEFT JOIN rates
                ON lots.id = rates.lots_id
            WHERE lots.finishing_date > NOW()
            AND lots.winner_id IS NULL
            GROUP BY
                lots.id,
                lots.title,
                lots.starting_price,
                lots.img_path,
                categories.name
            ORDER BY lots.`starting_date`
            LIMIT' . $page_items . ' OFFSET ' . $offset . ';';
    $result = mysqli_query($link, $sql);
    if ($result !== false) {
        return mysqli_fetch_all($result , MYSQLI_ASSOC);
    }
    return [];
};
