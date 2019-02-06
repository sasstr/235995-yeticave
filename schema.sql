CREATE DATABASE yeticave
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;
USE yeticave;

CREATE TABLE categories (
  `id` INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(25) NOT NULL
);

CREATE TABLE lots (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(128) NOT NULL,
  `description` TEXT NOT NULL,
  `url_image` VARCHAR(256),
  `starting_price` INT UNSIGNED NOT NULL,
  `starting_date` TIMESTAMP NOT NULL,
  `bet_step` INT UNSIGNED NOT NULL,
  `finishing_date` TIMESTAMP
);

 CREATE TABLE users (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `registration_date` TIMESTAMP DEFAULT  current_timestamp NOT NULL,
  `email` VARCHAR(128) NOT NULL UNIQUE,
  `password` VARCHAR(64) NOT NULL,
  `name` VARCHAR(64) NOT NULL,
  `contacts` VARCHAR(64),
  `avatar` VARCHAR(256)
);

CREATE TABLE rates (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `date` TIMESTAMP DEFAULT  current_timestamp NOT NULL,
  `amount`  INT UNSIGNED NOT NULL
);
