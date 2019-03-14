<?php
require_once('constants.php');
require_once('config.php');
require_once('functions.php');
require_once('init.php');
require_once('data.php');

if (empty($_SESSION['user'])) {
    http_response_code(403);
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $lots_user_id = $_GET['id'];
    $my_lots = get_my_lots($link, $lots_user_id);

    $my_lots_data = [
        'page_categories' => $page_categories,
        'my_lots' => $my_lots,
        'categories' => $categories
    ];
    include_template ('my-lots', 'Мои лоты', $categories, $user_avatar, $my_lots_data, $page_categories);
}

$my_lots_data = [
    'page_categories' => $page_categories,
    'categories' => $categories
];
include_template ('my-lots', 'Мои лоты', $categories, $user_avatar, $my_lots_data, $page_categories);
/*
4.7. Мои ставки
Страница, где пользователь видит все свои ставки на различные лоты.

Каждая ставка имеет следующую информацию:

название лота (ссылка на страницу);
сумма ставки;
дата/время;

Выигравшие ставки должны выделяться отдельным цветом,
а также там должны быть размещены контакты владельца лота.
user:array(1)
0:array(7)
id:4
registration_date:"2019-03-12 01:48:14"
email:"sasstr@gmail.com"
password:"$2y$10$zg594wl1j/F9u4Q8PeWlcevDSB7FOZMi3GSQegiZEwiClYavr/4LK"
name:"Александр Страховенко"
contacts:"Раз два и готово"
avatar:"/upload/avatar-5c86e5ae4f835.j
-------------------------------------------
0:array
lots_title:"Ботинки для сноуборда DC Mutiny Charocal"
starting_price:10999
description:"крутые ботинки для сноуборда"
img_path:"img/lot-4.jpg"
finishing_date:"2019-03-27 21:15:10"
winner_id:null
starting_date:"2019-01-12 11:35:10"
categories_name:"Одежда"
rate_amount:null

*/
