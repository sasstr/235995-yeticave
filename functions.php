<?php
require_once('mysql_helper.php');
/**
 * Проверяет наличие файла шаблона и возращает его.
 * @param string $file_name название файла шаблона
 * @param array $data_array массив переменных
 * @param integer $id ID лота для get параметра
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

 function format_price($price) {
    return number_format(ceil($price), 0, ',', ' ') . RUBLE_SYMBOL;
};

/**
 * Функция возращает верное написание слова из массива
 *
 * @param integer $time
 * @param array $words двумерный массив со списками корректных  слов
 * @param string $word ключ двумерного массива $words
 * @return string возращает правилный вариант склонения слова в русском языке
 */
function get_correct_word($time, $words, $word) {
    if (!isset($words[$word])) {
        return '';
    }
    if ($time % 100 > 4 && $time % 100 < 21) {
      return $words[$word][2];
    } elseif ($time % 10 === 1) {
      return $words[$word][0];
    } elseif ($time % 10 > 1 && $time % 10 < 5) {
      return $words[$word][1];
    }
    return $words[$word][2];
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
    return date_interval_format($diff,'%H:%I');
};

/**
 * Проверяет истекло ли вермя текущего лота
 *
 * @param string $end_time время окончания лота
 * @return bool истина если время вышло и ложь если нет.
 */
function check_finished_lot($end_time) {

    return time() >= strtotime($end_time);
};

/**
 * Возращает массив секунд, минут, часов и дней до окончания аукциона
 *
 * @param string $end_time  дата окончания аукциона лота
 * @return array массив секунд, минут, часов и дней до окончания аукциона
 */
function time_to_end_array ($end_time) {
    $seconds = strtotime($end_time) - time();
    $days = (int) floor($seconds / SECONDS_AMOUNT['DAY']);
    $hours = (int) floor(($seconds % SECONDS_AMOUNT['DAY']) / SECONDS_AMOUNT['HOUR']);
    $minutes = (int) floor(($seconds % SECONDS_AMOUNT['DAY']) / SECONDS_AMOUNT['MINUTE']);
    return ['seconds' => $seconds,
            'days' => $days,
            'hours' => $hours,
            'minutes' => $minutes
            ];
};

function time_array ($start_time) {
    $seconds = time() - strtotime($start_time);
    $days = (int) floor($seconds / SECONDS_AMOUNT['DAY']);
    $hours = (int) floor(($seconds % SECONDS_AMOUNT['DAY']) / SECONDS_AMOUNT['HOUR']);
    $minutes = (int) floor(($seconds % SECONDS_AMOUNT['DAY']) / SECONDS_AMOUNT['MINUTE']);
    return ['seconds' => $seconds,
            'days' => $days,
            'hours' => $hours,
            'minutes' => $minutes
            ];
};
/**
 * Возращает дату окончания аукциона по лоту
 *
 * @param string $end_time дата окончания аукциона лота
 * @return string время окончания торгов лота
 */
function get_end_of_time_lot ($end_time) {
    $to_end = time_to_end_array($end_time);
    if ($to_end['seconds'] <= 0) {
        return 'Время делать ставки вышло';
    } elseif ($to_end['days'] === 0) {
        return  sprintf('%02d:%02d', $to_end['hours'], $to_end['minutes']);
    } elseif ($to_end['days'] <= 3) {
        return sprintf('%d %s', $to_end['days'], get_correct_word($to_end['days'], WORDS['day'], 'day'));
    }
    return date('d.m.Y', strtotime($end_time));
};

/**
 * Добавляет контент переданной страницы к основному шаблону и отрисовывает его
 *
 * @param string $page_name имя файла страницы (без расширения php)
 * @param string $page_title Тайтл страницы
 * @param array $categories массив категорий
 * @param string $user_avatar ссылка на аватар пользователя
 * @param array $data массив данных для отрисовки шаблона локальной страницы
 * @param string $page_categories Шаблон категорий
 * @return void
 */
function include_template ($page_name, $page_title, $categories, $user_avatar, $data = [], $page_categories) {
    $page_content = render($page_name, $data);
        print render('layout', [
            'content' => $page_content,
            'title' => $page_title,
            'categories' => $categories,
            'page_categories' => $page_categories,
            'user_avatar' => $user_avatar
        ]);
        exit();
};
/**
 * Возращает правильное написание времени с момента размещения ставки на аукционе
 *
 * @param string $current_time время добавления ставки на торги
 * @return string Время добавления ставки в правильном формате.
 */
function format_time_rate($current_time) {
    $time = strtotime('now');
    $interval = $time - strtotime($current_time);
    if ($interval > SECONDS_AMOUNT['DAY']) {
        $add_time = date('d.m.Y в H:i', strtotime($current_time));
    }
    elseif ($interval > SECONDS_AMOUNT['HOUR'] && $interval < SECONDS_AMOUNT['DAY']) {
        $add_time = floor($interval / SECONDS_AMOUNT['HOUR']) . ' часов назад';
    }
    elseif ($interval > SECONDS_AMOUNT['MINUTE'] && $interval < SECONDS_AMOUNT['HOUR']) {
        $add_time = floor($interval / SECONDS_AMOUNT['MINUTE']) . ' минут назад';
    }
    else {
        $add_time = 'меньше минуты назад';
    }
    return $add_time;
}

/**
 * Функция рассчитывает разницу от текущего времни до конца суток
 * @param $end_date
 * @return string возращает время которое осталось до конца суток от текущего.
 */
function show_diff_time($end_date) {
    $now = date_create('now');
    $diff_date = date_create($end_date);
    $diff = date_diff($now, $diff_date);
    $time_diff = date_interval_format($diff,'%H:%I');
    return isset($time_diff) ? $time_diff : '';
};
/** Функция перемещает файл на сервере в указаную папку из временной и
 * добавляет префикс к названию файласоздавая уникальное название файла
 * @param string $pre_name
 * @param string $img_file_name
 * @param string $img_file_tmp_name
 * @param string $upload_dir
 * @param string $upload_local_dir
 * @param array $img_file_types
 */
function move_file_to_upload ($prefix, $img_file_name, $img_file_tmp_name, $upload_dir, $upload_local_dir, $img_file_types) {
    $errors = [];
    // Валидация на загрузку файла с картинкой лота
    // Проверяем есть ли каталог для загрузки картинок на сервере
    if(!file_exists($upload_local_dir)) {
        mkdir($upload_local_dir);
        if (!file_exists($upload_local_dir)) {
            return $errors['img-file'] = 'Не удалось найти или создать папку для загрузки файла';
        }
    }

    if (isset($img_file_name) && !empty($img_file_name)) {

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $img_file_tmp_name);

        if(!array_search($file_type, $img_file_types)) {
            $errors['img-file'] = 'Необходимо загрузить фото с расширением JPEG, JPG или PNG';
        } else {
            $file_tmp_name = $img_file_tmp_name;
            $file_name = $img_file_name;
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $file_type = finfo_file($finfo, $file_tmp_name);
            $file_name_uniq = uniqid($prefix) . '.' . pathinfo($file_name , PATHINFO_EXTENSION);
            $file_url = $upload_local_dir . trim($file_name_uniq);
            // Перемещение загруженного файла в папку сайта
            move_uploaded_file($file_tmp_name, $upload_dir . $file_name_uniq);
        }
    }
    return $file_url;
};

function is_show_rate_form($lots_user_id, $rates_user_id, $history_data, $session_user_id, $end_time) {
    if (isset($lots_user_id) && isset($rates_user_id)) {
        // если пользователь уже сделал ставку не показывать блок добавления ставки
        if ($rates_user_id !== $session_user_id) {
                $rate_limit = true;
        // если пользователь создал этот лот не показывать блок добавления ставки
            } elseif ($lots_user_id !== $session_user_id) {
                $rate_limit = true;
        // если время вышло делать ставки по этому лоту
            } elseif ($end_time <= time() ) {
                $rate_limit = true;
            }
    }
    // если пользователь уже сделал ставку не показывать блок добавления ставки
    foreach ($history_data as $value) {
        if ($value['user_id'] === (int) $session_user_id) {
            $rate_limit = false;
        }
    }
    return $rate_limit;
};
/**
 * Функция проверяет правильность введенного значения стоимости ставки лота
 * и в случае верного добавляет его в базу данных
 *
 * @param integer $post_cost
 * @param integer $min_rate
 * @param array $data
 * @param resource $link
 * @return array или ничего
 */
function validate_rate_cost ($post_cost, $min_rate, $data, $link) {
    if (empty($post_cost)) {
        return $_POST['post_cost_error'] = 'Это поле необходимо заполнить целочисленным значением';
    } else {
        /* if (!ctype_digit($post_cost)) {  // Куда его пихать не знаю он все перебивает или не правильно работает
         return $_POST['post_cost_error'] = 'Значение должно целым числом';
    } */
        if ($post_cost <= 0) {
            return $_POST['post_cost_error'] = 'Значение должно положительным числом';
        } elseif (($post_cost) < $min_rate && $post_cost > 0) {
            return $_POST['post_cost_error'] = 'Значение ставки должно быть не меньше минимальной';
        } elseif (!isset($_POST['post_cost_error'])) {
            add_new_rate_to_db($link, $data);
        }
    }
};
