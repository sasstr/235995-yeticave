<?
define('RUBLE_SYMBOL', ' &#x20BD');
define('TEMPLATE_PATH', 'templates/');
define('PHP_EXTENSION', '.php');
define('MOSCOW_TIME_ZONE', date_default_timezone_set('europe/moscow'));

// Массив с данными для подключения к базе данных yeticave
const DB_SETUP = [
    'HOST' => 'localhost',
    'LOGIN' => 'root',
    'PASSWORD' => '',
    'NAME' => 'yeticave'
];
