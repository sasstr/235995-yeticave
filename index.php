<?php
require_once('constants.php');
require_once('config.php');
require_once('functions.php');
require_once('init.php');
require_once('data.php');

$major_indexes = [
  'categories' => $categories,
  'lots' => $lots,
  'time_until_midnight' => $time_until_midnight
];

include_template('index', 'Главная страница', $categories, $user_avatar, $major_indexes);
