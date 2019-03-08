<?php

$link = db_connect();
$time_until_midnight = show_time();
$title = 'Главная';
$sql_avatar = 'SELECT `users`.`avatar` FROM `users` WHERE `users`.`id` = ?;';
$user_avatar = db_select($link, $sql_avatar, [$_SESSION['user']['id']]) ?? '';
$categories = get_categories($link);
$lots = get_lots($link);
