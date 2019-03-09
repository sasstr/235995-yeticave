<?php

$link = db_connect();
$time_until_midnight = show_time();
$categories = get_categories($link);
$lots = get_lots($link);

$user_avatar = MOCK_IMG;

if (isset($_SESSION['user'])){
    $user_avatar = $_SESSION['user']['avatar'];
    };
