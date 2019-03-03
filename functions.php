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
 * Функция возращает верное написание слова из массива
 *
 * @param integer $time
 * @param array $words
 * @return string возращает правилный вариант склонения слова в русском языке
 */
function get_correct_word ($time, $words) {
    if ($time % 100 > 4 && $time % 100 < 21) {
      return $words[0];
    } elseif ($time % 10 === 1) {
      return $words[1];
    } elseif ($time % 10 > 1 && $time % 10 < 5) {
      return $words[2];
    }
    return $words[0];
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
}
// TODO Доделать функцию подсчета интервала времени.
function get_interval ($date_end) {
    $now = date_create('now');
    $n_time = time();
    var_dump($n);
    if($now < $date_end) {
        $diff = date_diff($now, $date_end);
        return date_interval_format($diff,'d.m.Y H:i');
    }
    return $errors = 'Ставку делать уже поздно - время вышло!';
};

/**
 * Добавляет контент переданной страницы к основному шаблону и отрисовывает его
 *
 * @param string $page_name Название страницы
 * @param string $page_title Тайтл страницы
 * @param array $categories массив категорий
 * @param string $user_avatar ссылка на аватар пользователя
 * @param array $data массив данных для отрисовки шаблона локальной страницы
 * @return void
 */
function include_template ($page_name, $page_title, $categories, $user_avatar, $data = []) {
    $page_content = render($page_name, $data);
        print render('layout', [
            'content' => $page_content,
            'title' => $page_title,
            'categories' => $categories,
            'user_avatar' => $user_avatar
        ]);
        exit();
};

session_start();
