<?php
date_default_timezone_set('europe/moscow');
/**
 * Функция шаблонизатор проверяет наличие файла шаблона и возращает его.
 * Функция получает парметрами название файла шаблона и массив переменных.
 * @param string $file_name
 * @param array $data_array
 *
 * @return string возращает разметку шаблона
 */
function render ($file_name, $data_array) {
    $path = TEMPLATE_PATH . $file_name . PHP_EXTENSION;
    if(!file_exists($path)) {
        return '';
    }
    ob_start();
    extract($data_array);
    require_once ($path);
    return ob_get_clean();
};
/**
 * Функция форматирует цену товара и добавляет знак рубля.
 *@param integer $price
 *
 * @return string возращает отформатированную цену по тысячам со знаком рубля.
 */
function format_price ($price) {
    $correct_price = ceil($price);
    return $correct_price >= 1000 ? number_format($correct_price, 0, ',', ' ') . RUBLE_SYMBOL : $correct_price . RUBLE_SYMBOL;
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
