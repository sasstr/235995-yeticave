<?php
const SECONDS_AMOUNT = [
  'DAY' => 86400,
  'HOUR' => 3600,
  'MINUTE' => 60,
];
const WORDS = [
  'rate' => ['ставка', 'ставки', 'ставок'],
  'money' => ['рубль', 'рубля', 'рублей'],
  'minute' => ['минута', 'минуты', 'минут'],
  'hour' => ['час', 'часа', 'часов'],
  'day' => ['день', 'дня', 'дней']
];
define ('MOSCOW_TIME_ZONE', date_default_timezone_set('europe/moscow'));

const CATEGORY_SELECTOR = '-1';
const RUBLE_SYMBOL = '&#8381;';
const MOCK_IMG_LOT = 'http://placehold.it/150x100?text=Лот+на+фотосессии';
const MOCK_IMG = 'https://joeschmoe.io/api/v1//male/random';
const IMG_FILE_TYPES = ['jpg' =>'image/jpeg',
                          'jpeg' => 'image/pjpeg',
                          'png' =>'image/png'];
