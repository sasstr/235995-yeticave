<?php
require_once('vendor/autoload.php');

function db_get_lots_not_winners($link) {
    $sql = 'SELECT id, title, user_id
        FROM lots
        WHERE finishing_date <= NOW() AND winner_id IS NULL;';
    $query = mysqli_query($link, $sql);
    if ($query) {
        $res = mysqli_fetch_all($query, MYSQLI_ASSOC);
    }
    return [];
};


