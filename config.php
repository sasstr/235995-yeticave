<?php
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

const ADD_NEW_LOT = 'INSERT INTO lots ( `title`,
                                        `description`,
                                        `img_path`,
                                        `starting_price`,
                                        `starting_date`,
                                        `rate_step`,
                                        `finishing_date`,
                                        `user_id`,
                                        `category_id`
                                        )
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);';

session_start();
