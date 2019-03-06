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
    }
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
    }
};
/**
 * Функция добавляет новый лот в БД и в случае успеха перенаправляет пользователя на страницу нового лота.
 *
 * @param resource $link рескрс соединения
 * @param string $sql подготовленное выражение
 * @param array $data
 * @return void
 */
function add_new_lot_to_db($link, $sql, $data = []) {
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    $res = mysqli_stmt_execute($stmt);
    if ($res) {
        $lot_id = mysqli_insert_id($link);
        header('Location: lot.php?id=' . $lot_id); // Вынести в сценарий !
        exit();
    }
};
/**
 * Функция добавляет новую ставку
 *
 * @param resource $link  рескрс соединения
 * @param string $sql  подготовленное выражение
 * @param array $data массив данных
 * @return void
 */
function add_new_rate_to_db($link, $sql, $data = []) {
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    $res = mysqli_stmt_execute($stmt);
    return $res;
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
    /* $stmt = db_get_prepare_stmt($link, $sql, ['lot_id' => $lot_id]);
    mysqli_stmt_execute($stmt); */
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $lot_id);
    mysqli_stmt_execute($stmt);
    return mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
};

function select_id_by_email ($link, $email) {
    $email = mysqli_real_escape_string($link, $sign_up['email']);
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    return mysqli_fetch_assoc(mysqli_stmt_execute($stmt));
};

/* function add_new_rate ($session_user, $link, $lot_id, $post_cost, $sql_select_data, $sql_add_data) {
    if (isset($_SESSION['user'])){
        $rates_data = select_data_by_lot_id ($link, $sql_select_data, $lot_id);
// user_id lots_id starting_price rate_step rate_amount rates.date finishing_date rates_lots_id
        $min_rate = $rates_data[0]['rate_step'] + $rates_data[0]['rate_amount'];
        $starting_price = $rates_data[0]['starting_price'];

         if (empty($post_cost)) {
            $errors['cost'] = 'Это поле необходимо заполнить';
        } elseif ($post_cost <= 0 || is_int($errors['cost'])) {
            $errors['cost'] = 'Значение должно положительным целым числом';
        } elseif (is_int(isset($_POST['cost'])) > 0
            && ($post_cost) >= $min_rate
            && empty($errors['cost'])) {
            $data = [$post_cost, $session_user['id'], $lot_id];
           // add_new_rate_to_db($link, $sql_add_data, $data, $lot_id);

            include_template ('lot', 'Лот', $categories, $user_avatar,  [
                'categories' => $categories,
                'lot' => $lot,
                'rates_data' => $rates_data,
                'min_rate' => $min_rate,
                'rates_history' => $rates_history
                ]);
                header('Location: lot.php?id=' . $lot_id);
        exit();
        } } else {
            include_template ('lot', 'Лот', $categories, $user_avatar,  [
                'categories' => $categories,
                'lot' => $lot
                ]);
            exit();
        }
}; */
