INSERT INTO categories(`name`)
VALUES
  ('Доски и лыжи'),
  ('Крепления'),
  ('Ботинки'),
  ('Одежда'),
  ('Инструменты'),
  ('Разное');

INSERT INTO lots(`title`, `description`, `url_image`, `starting_price`, `starting_date`, `bet_step`, `user_id`, `winner_id`, `category_id`)
VALUES
('2014 Rossignol District Snowboard', 'настоящий сноуборд', 'img/lot-1.jpg', 10999, '2019-01-01 21:15:10', 700, 1, 1, 1),
('DC Ply Mens 2016/2017 Snowboard', 'самая быстрая доска', 'img/lot-2.jpg', 159999, '2019-02-03 10:45:10', 2000, 2, 2, 2),
('Крепления Union Contact Pro 2015 года размер L/XL', 'отличные крепления', 'img/lot-3.jpg', 8000, '2019-01-06 12:55:10',  500, 3, 3, 3),
('Ботинки для сноуборда DC Mutiny Charocal', 'крутые ботинки для сноуборда', 'img/lot-4.jpg', 10999, '2019-01-12 11:35:10', 800, 4, 4, 4),
('Куртка для сноуборда DC Mutiny Charocal', 'хайповый куртец для сноубордирования', 'img/lot-5.jpg', 7500, '2019-01-09 21:25:10', 300, 5, 5, 5),
('Маска Oakley Canopy', 'маска на все лицо', 'img/lot-6.jpg', 5400, '2019-01-25 09:05:10', 200, 6, 6, 6);

INSERT INTO users(`email`, `password`, `name`, `contacts`, `avatar`)
VALUES
('jena@deneg.net', 'denegnet0', 'Huanita', 'г. Мечта ул. Первая 11-12', NULL),
('muj@grush.eat', 'zanachka_est', 'John', 'пос. Дубки ул Центральная 34', NULL),
('good@rich.com', 'neskaju', 'Ben', 'London, Big-Ben 45', NULL);

INSERT INTO rates(`amount`)
VALUES
('8500'),
('7800');

-- получить все категории  +++
SELECT name FROM categories;

-- получить самые новые, открыте лоты. Каждый лот должен включать название, стартовую цену,
-- ссылку на изображение, цену, кол-во ставок, название категории;  ---
SELECT lots.`title`, lots.`starting_price`, lots.`url_image`, categ.`name`
FROM lots
JOIN categories categ
ON lots.`id` = categ.`id`
WHERE lots.`winner_id` IS NULL
JOIN rates
ON lots.`id` = rates.`id`
GROUP BY lots.`starting_date`;

-- показать лот по его id. Получите также название категории, к которой принадлежит лот; +++
SELECT lots.`title`, categ.`name`
FROM lots
JOIN categories categ
ON lots.`id` = categ.`id`
WHERE categ.`id` = 2;

-- обновить название лота по его идентификатору; +++
UPDATE lots
SET title = 'Маска Oakley Canopy 2019 XXL'
WHERE id = 6;

-- получить список самых свежих ставок для лота по его идентификатору +++
SELECT rates.`date`, rates.`id`, rates.`amount`
FROM rates
WHERE rates.`id` = 1
ORDER BY rates.`date` DESC;
