<?php

$link = db_connect();
$time_until_midnight = show_time();
$categories = get_categories($link);
$lots = get_lots($link);

$sql_avatar = 'SELECT `users`.`avatar` FROM `users` WHERE `users`.`id` = ?;';
$id = [$_SESSION['user']['id']] ?? -1 ;
$user_avatar = db_select($link, $sql_avatar, [$id]) ?? '';
