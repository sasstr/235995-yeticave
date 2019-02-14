CREATE DATABASE yeticave
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;
USE yeticave;

CREATE TABLE categories (
  `id` INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(25) NOT NULL
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;

CREATE TABLE users (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `registration_date` TIMESTAMP DEFAULT  current_timestamp NOT NULL,
  `email` VARCHAR(128) NOT NULL UNIQUE,
  `password` CHAR(64) NOT NULL,
  `name` VARCHAR(64) NOT NULL,
  `contacts` VARCHAR(64),
  `avatar` VARCHAR(256),
  INDEX user_email(email)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;


CREATE TABLE lots (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(128) NOT NULL,
  `description` TEXT NOT NULL,
  `img_path` VARCHAR(256),
  `starting_price` INT UNSIGNED NOT NULL,
  `starting_date` TIMESTAMP NOT NULL,
  `rate_step` INT UNSIGNED NOT NULL,
  `finishing_date` TIMESTAMP,
  `user_id` INT UNSIGNED NOT NULL,
  `winner_id` INT UNSIGNED DEFAULT NULL,
  `category_id` INT NOT NULL
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;


CREATE TABLE rates (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `date` TIMESTAMP DEFAULT  current_timestamp NOT NULL,
  `rate_amount`  INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `lots_id` INT UNSIGNED NOT NULL,
  INDEX rates_user_id(user_id) -- Индекса по полю user_id достаточно
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;
