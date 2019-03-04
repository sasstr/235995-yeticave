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
function get_correct_word ($time, $words, $word) {
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
function check_finished_lot($end_time)
{
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
    return $to_end = ['seconds' => $seconds,
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
    return $to_end = ['seconds' => $seconds,
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
        return sprintf('%d %s', $to_end['days'], get_correct_word($to_end['days'], 'day'));
    }
    return date('d.m.Y', strtotime($end_time));
};
/**
 * Возращает правильное написание времени с момента размещения ставки на аукционе
 *
 * @param string $current_time время добавления ставки на торги
 * @return string Время добавления ставки в правильном формате.
 */
function get_rate_add_time($current_time) {
    $add_time = strtotime($current_time);
    $passed_time = time_array($add_time);
    if ($passed_time['days'] === 0) {
        if ($passed_time['hours'] === 0 && $passed_time['minutes'] === 0) {
            return  $passed_time['seconds'] <= 30 ? 'Только что' : 'Минута назад';
        } elseif ($passed_time['hours'] === 0) {
            return  $passed_time['minutes'] === 1 ? 'Минута назад' : sprintf('%d %s назад', $passed_time['minutes'], get_correct_word($passed_time['minutes'], 'minute'));
        } elseif ($passed_time['hours'] > 0 && $passed_time['hours'] <= 10) {
            return  $passed_time['hours'] === 1 ? 'Час назад' : sprintf('%d %s назад', $passed_time['hours'], num_format($passed_time['hours'], 'hour'));
        }
    }
    if ($add_time >= strtotime('today')) {
        return  sprintf('Сегодня в %s', date('H:i', $add_time));
    }
    if ($add_time >= strtotime('yesterday')) {
        return  sprintf('Вчера в %s', date('H:i', $add_time));
    }
    return date('d.m.y в H:i', strtotime($current_time));
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
