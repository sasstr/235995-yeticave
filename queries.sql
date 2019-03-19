INSERT INTO categories(`name`, `class_name`)
VALUES
  ('Доски и лыжи', 'promo__item--boards'),
  ('Крепления', 'promo__item--attachment'),
  ('Ботинки', 'promo__item--boots'),
  ('Одежда', 'promo__item--clothing'),
  ('Инструменты', 'promo__item--tools'),
  ('Разное', 'promo__item--other');

INSERT INTO lots(`title`, `description`, `img_path`, `starting_price`, `starting_date`, `rate_step`, `finishing_date`, `user_id`, `winner_id`, `category_id`)
VALUES
('2014 Rossignol District Snowboard', 'настоящий сноуборд', 'img/lot-1.jpg', 10999, '2019-01-01 21:15:10', 700, '2019-05-28 21:15:10', 1, 1, 1),
('DC Ply Mens 2016/2017 Snowboard', 'самая быстрая доска', 'img/lot-2.jpg', 159999, '2019-02-03 10:45:10', 2000, '2019-04-15 21:15:10', 2, NULL, 2),
('Крепления Union Contact Pro 2015 года размер L/XL', 'отличные крепления', 'img/lot-3.jpg', 8000, '2019-01-06 12:55:10',  500, '2019-06-05 21:15:10', 3, NULL, 3),
('Ботинки для сноуборда DC Mutiny Charocal', 'крутые ботинки для сноуборда', 'img/lot-4.jpg', 10999, '2019-01-12 11:35:10', 800, '2019-03-27 21:15:10', 4, NULL, 4),
('Куртка для сноуборда DC Mutiny Charocal', 'хайповый куртец для сноубордирования', 'img/lot-5.jpg', 7500, '2019-01-09 21:25:10', 300, '2019-03-31 21:15:10', 5, NULL, 5),
('Маска Oakley Canopy', 'маска на все лицо', 'img/lot-6.jpg', 5400, '2019-01-25 09:05:10', 200, '2019-04-15 21:15:10', 6, NULL, 6);

INSERT INTO users(`email`, `password`, `name`, `contacts`, `avatar`)
VALUES
('jena@deneg.net', 'denegnet0', 'Huanita', 'г. Мечта ул. Первая 11-12', NULL),
('muj@grush.eat', 'zanachka_est', 'John', 'пос. Дубки ул Центральная 34', NULL),
('good@rich.com', 'neskaju', 'Ben', 'London, Big-Ben 45', NULL);

INSERT INTO rates(`rate_amount`, `user_id`, `lots_id`)
VALUES
('8500', 3, 3),
('7800', 5, 5),
('5600', 6, 6);

-- получить все категории  +++
SELECT name FROM categories;

-- получить самые новые, открыте лоты. Каждый лот должен включать название, стартовую цену,
-- ссылку на изображение, цену, название категории;
SELECT lots.`title`, starting_price, img_path, MAX(rates.`rate_amount`), categ.`name`
FROM lots
JOIN categories categ
ON lots.`category_id` = categ.`id`
JOIN rates
ON rates.`lots_id` = lots.`id`
WHERE lots.`finishing_date` > CURRENT_TIMESTAMP and lots.`winner_id` IS NULL
GROUP BY rates.`lots_id`
ORDER BY lots.`starting_date` DESC;

-- показать лот по его id. Получите также название категории, к которой принадлежит лот; +++
SELECT lots.`title`, categ.`name`
FROM lots
JOIN categories categ
ON lots.`category_id` = categ.`id`
WHERE categ.`id` = 2;

-- обновить название лота по его идентификатору;
UPDATE lots
SET title = 'Маска Oakley Canopy 2019 XXL'
WHERE id = 6;

-- получить список самых свежих ставок для лота по его идентификатору
SELECT rates.`date`, rates.`id`, rates.`rate_amount`
FROM rates
WHERE rates.`id` = 1
ORDER BY rates.`date` DESC;
