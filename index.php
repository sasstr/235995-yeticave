<?php
require_once('config.php');
require_once('functions.php');
require_once('init.php');
require_once('data.php');

include_template('index', 'Главная страница', $categories, $user_avatar, $major_indexes);
?>
