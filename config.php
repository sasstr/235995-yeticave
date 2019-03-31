<?php
require_once('vendor/autoload.php');

const TEMPLATE_PATH = 'templates/';
const PHP_EXTENSION = '.php';
const UPLOAD_DIR = __DIR__ . '/upload/';
const UPLOAD_LOCAL_DIR = '/upload/';

// Массив с данными для подключения к базе данных yeticave
const DB_SETUP = [
    'HOST' => 'localhost',
    'LOGIN' => 'root',
    'PASSWORD' => '',
    'NAME' => 'yeticave'
];

$mail_config = [
    $transport = new Swift_SmtpTransport('smtp.yandex.ru', 465, 'SSL'),
    $transport->setUsername('a.stra21@yandex.ru'),
    $transport->setPassword('1043803str')
];

/* $mail_config = [
    $transport = new Swift_SmtpTransport('phpdemo.ru', 25),
    $transport->setUsername('keks@phpdemo.ru'),
    $transport->setPassword('htmlacademy')
];
setUsername($yandexEmail)
    ->setPassword($yandexPassword)
    ->setEncryption('SSL');
*/

session_start();
