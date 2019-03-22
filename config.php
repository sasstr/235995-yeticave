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
    $transport = new Swift_SmtpTransport('phpdemo.ru', 25),
    $transport->setUsername('keks@phpdemo.ru'),
    $transport->setPassword('htmlacademy')
];

session_start();
