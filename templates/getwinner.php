<?php
require_once('vendor/autoload.php');
require_once('constants.php');
require_once('config.php');
require_once('functions.php');
require_once('init.php');
require_once('data.php');

/*
Остальные параметры перечислены ниже:
Имя параметра	Значение
Тема письма	Ваша ставка победила
Отправитель	keks@phpdemo.ru
Получатель	E-mail пользователя-победителя
Content-type тела письма	text/html

Алгоритм работы:

1/ Найти все лоты без победителей, дата истечения
которых меньше или равна текущей дате;  +++
2/ Для каждого такого лота найти последнюю ставку;
3/ Записать в лот победителем автора последней ставки;
4/ Отправить победителю на email письмо — поздравление с победой; */

$winners = db_get_lots_not_winners($link);

if(isset($winners) && count($winners) > 0) {

    foreach ($winners as $winner) {
        $rate_amount = select_rates_data_by_id($link, $winner['id']);
    }
    // Конфигурация траспорта
    $transport = new Swift_SmtpTransport('phpdemo.ru', 25);
    $transport->setUsername('keks@phpdemo.ru');
    $transport->setPassword('htmlacademy');

    // Формирование сообщения
    $message = new Swift_Message("Ваша ставка победила");
    // Content-type тела письма	text/html
    $content_type = $message->getHeaders()->get('Content-Type');
    $content_type->setValue('text/html');
    $content_type->setParameter('charset', 'utf-8');

    $message->setTo(["sasstr@gmail.com" => "sasstr"]);
    $message->setBody("Ваша ставка победила");
    $message->setFrom("keks@phpdemo.ru", "YetiCave Ваша ставка победила");
    // Отправка сообщения
    $mailer = new Swift_Mailer($transport);
    // Чтобы иметь максимально подробную информацию о процессе отправки сообщений
    // мы попросим SwiftMailer журналировать все происходящее внутри массива.
    $logger = new Swift_Plugins_Loggers_ArrayLogger();
    $mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));

    // $result = $mailer->send($message);

    try {
        $result = $mailer->send($message);
        if ($result) {
            print("Победителям письма успешно отправлены");
        }
        else {
            print("Не удалось отправить письма: " . $logger->dump());
        }
    } catch (Swift_TransportException $ex) {
        print($ex->getMessage() . '<br>');
    }
}
