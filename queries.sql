INSERT INTO categories(`name`)
VALUES
  ('Доски и лыжи'),
  ('Крепления'),
  ('Ботинки'),
  ('Одежда'),
  ('Инструменты'),
  ('Разное');

INSERT INTO lots(`title`, `description`, `url_image`, `starting_price`, `starting_date`, `bet_step`)
VALUES
('2014 Rossignol District Snowboard', NULL, 'img/lot-1.jpg', 10999, 700),
('DC Ply Mens 2016/2017 Snowboard', NULL, 'img/lot-2.jpg', 159999, 2000),
('Крепления Union Contact Pro 2015 года размер L/XL', NULL, 'img/lot-3.jpg', 8000, 500),
('Ботинки для сноуборда DC Mutiny Charocal', NULL, 'img/lot-4.jpg', 10999, 800),
('Куртка для сноуборда DC Mutiny Charocal', NULL, 'img/lot-5.jpg', 7500, 300),
('Маска Oakley Canopy', NULL, 'img/lot-6.jpg', 5400, 200);

INSERT INTO users(`email`, `password`, `name`, `contacts`, `avatar`)
VALUES
('jena@deneg.net', 'denegnet0', 'Huanita', 'г. Мечта ул. Первая 11-12', NULL),
('muj@grush.eat', 'zanachka_est', 'John', 'пос. Дубки ул Центральная 34', NULL),
('good@rich.com', 'neskaju', 'Ben', 'London, Big-Ben 45', NULL);

INSERT INTO rates(`amount`)
VALUES
('8500'),
('7800');

-- получить все категории
SELECT name FROM categories;

-- получить самые новые, открыте лоты. Каждый лот должен включать название, стартовую цену,
-- ссылку на изображение, цену, кол-во ставок, название категории;
-- SELECT l.`title`, l.`starting_price`, l.`url_image` MAX(rates.`amount`)
-- FROM lots l

-- показать лот по его id. Получите также название категории, к которой принадлежит лот;
-- SELECT  FROM lots l WHERE id = 3;

-- обновить название лота по его идентификатору;
 UPDATE lots SET title = 'Маска Oakley Canopy 2019 XXL' WHERE id = 6;

-- получить список самых свежих ставок для лота по его идентификатору
SELECT r.`date`, r.`id` ,r.`amount`
FROM rates r
WHERE r.`id` = 1
ORDER BY r.`date` DESC;
