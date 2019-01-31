CREATE DATABASE yeticave
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;
USE yeticave;

CREATE TABLE categories (
  `id` INT NOT NULL PRIMARY KEY,
  `name` CHAR(25) NOT NULL UNIQUE
);

CREATE TABLE lots (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` CHAR(128) NOT NULL,
  `description` TEXT NOT NULL,
  `url-image` CHAR(256),
  `starting_price` INT UNSIGNED NOT NULL,
  `starting_date` DATE NOT NULL,
  `bet_step` INT UNSIGNED NOT NULL,
  `finishing_date` TIMESTAMP DEFAULT current_timestamp NOT NULL
);

 CREATE TABLE users (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `registration_date` DATETIME DEFAULT  current_timestamp NOT NULL,
  `email` CHAR(128) NOT NULL UNIQUE,
  `password` CHAR(64) NOT NULL,
  `name` CHAR(64) NOT NULL,
  `contacts` CHAR(64),
  `avatar` CHAR(256)
);

CREATE TABLE rate (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `date` DATETIME DEFAULT  current_timestamp NOT NULL,
  `amount`  INT UNSIGNED NOT NULL
);
