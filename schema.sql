CREATE DATABASE yeticave
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;
USE yeticave;

CREATE TABLE categories (
  `id` INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(25) NOT NULL
) ENGINE = InnoDB;

CREATE TABLE lots (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(128) NOT NULL,
  `description` TEXT NOT NULL,
  `url_image` VARCHAR(256),
  `starting_price` INT UNSIGNED NOT NULL,
  `starting_date` TIMESTAMP NOT NULL,
  `rate_step` INT UNSIGNED NOT NULL,
  `finishing_date` TIMESTAMP,
  `user_id` INT(10) NOT NULL,
  `winner_id` INT(10),
  `category_id` INT unsigned NOT NULL
  -- FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE = InnoDB;

 CREATE TABLE users (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `registration_date` TIMESTAMP DEFAULT  current_timestamp NOT NULL,
  `email` VARCHAR(128) NOT NULL UNIQUE,
  `password` VARCHAR(64) NOT NULL,
  `name` VARCHAR(64) NOT NULL,
  `contacts` VARCHAR(64),
  `avatar` VARCHAR(256),
  INDEX user_email(email)
) ENGINE = InnoDB;

CREATE TABLE rates (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `date` TIMESTAMP DEFAULT  current_timestamp NOT NULL,
  `rate_amount`  INT UNSIGNED NOT NULL,
  `user_id` INT NOT NULL,
  `lots_id` INT NOT NULL,
  INDEX rates_user_id(user_id),
  INDEX rates_lots_id(lots_id)
) ENGINE = InnoDB;
