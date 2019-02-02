<?php
// Функция шаблонизатор проверяет наличие файла шаблона и возращает его.
// Функция получает парметрами название файла шаблона и массив переменных.
function render ($file_name, $data_array) {
    $path = 'templates/' . $file_name . '.php';
    if(file_exists($path)) {
        ob_start();
        extract($data_array);
        require_once ($path);
        return ob_get_clean();
    } else
    return '';
};

$RUBLE_SYMBOL = ' &#x20BD;';
// Функция форматирует цену товара и добавляет знак рубля.
function format_price ($price) {
    $correct_price = ceil($price);
    return $correct_price >= 1000 ? number_format($correct_price, 0, ',', ' ') . $RUBLE_SYMBOL : $correct_price . $RUBLE_SYMBOL;
};

date_default_timezone_set('europe/moscow');

/* $end_of_lot = strtotime('midnight tomorrow');
$diff_time = $end_of_lot - time();
$hours = floor($diff_time / 3600);
$minutes = floor(($diff_time % 3600) / 60);
$time_until_midnight = $hours . ':' . $minutes; */

function show_time() {
    $now = date_create('now');
    $tomorrow = date_create('tomorrow');
    $diff = date_diff($now, $tomorrow);
    return date_interval_format($diff,"%H:%I");
}
?>
