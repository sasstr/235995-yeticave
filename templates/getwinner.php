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

/* $transport = new Swift_SmtpTransport('smtp.example.org', 25);
// Формирование сообщения
$message = new Swift_Message("Просмотры вашей гифки");
$message->setTo(["keks@htmlacademy.ru" => "Кекс"]);
$message->setBody("Вашу гифку «Кот и пылесос» посмотрело больше 1 млн!");
$message->setFrom("mail@giftube.academy", "GifTube");
// Отправка сообщения
$mailer = new Swift_Mailer($transport);
$mailer->send($message);

Остальные параметры перечислены ниже:
Имя параметра	Значение
Тема письма	Ваша ставка победила
Отправитель	keks@phpdemo.ru
Получатель	E-mail пользователя-победителя
Content-type тела письма	text/html

*/
