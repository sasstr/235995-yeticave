<?
define ('MOSCOW_TIME_ZONE', date_default_timezone_set('europe/moscow'));
const RUBLE_SYMBOL = '&#x20BD';
const TEMPLATE_PATH = 'templates/';
const PHP_EXTENSION = '.php';
const UPLOAD_DIR = __DIR__ . '/upload/';

// Массив с данными для подключения к базе данных yeticave
const DB_SETUP = [
    'HOST' => 'localhost',
    'LOGIN' => 'root',
    'PASSWORD' => '',
    'NAME' => 'yeticave'
];

const IMG_FILE_TYPES = ['jpg' =>'image/jpeg',
                          'jpeg' => 'image/pjpeg',
                          'png' =>'image/png'];

const ADD_NEW_LOT = 'INSERT INTO lots ( `title`,
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

const MOCK_IMG = 'http://placehold.it/150x100?text=Лот+на+фотосессии';
