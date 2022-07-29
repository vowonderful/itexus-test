-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июл 29 2022 г., 10:23
-- Версия сервера: 8.0.29
-- Версия PHP: 8.1.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `itexus`
--

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `date_reg` datetime NOT NULL,
  `date_birth` date DEFAULT NULL,
  `country` varchar(30) NOT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `status` enum('active','blocked') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'active',
  `role` enum('admin','moder','user') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'user',
  `balance` bigint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `date_reg`, `date_birth`, `country`, `name`, `status`, `role`, `balance`) VALUES
(1, 'Admin', '$2y$10$zhJ6NoNl0qYfCQMGj3eGTOpI0.ueIGyJhSPHtT/vgQ./X9o2dcrO6', 'admin@gmail.com', '2022-07-25 12:19:19', '1996-07-30', 'Belarus', 'Mihalevich Vladimir Gennad\'evich', 'active', 'admin', 137055900),
(2, 'Moder', '$2y$10$9TYYHp.qc.jIQEKYMYaVqe98OgelpTzr9ma.4adKWqQwUbC6dFgmW', 'moder@gmail.com', '2022-07-25 15:28:32', '1969-07-31', 'Russia', 'Пупкин Иван Васильевич', 'active', 'moder', 125135000),
(3, 'User', '$2y$10$3qqNSopFixRjc2MyODpP1eK2jmm1sKlrxvluCmqLD4qoDMy4IE8MC', 'ukr@gmailik.com', '2022-07-25 16:36:51', '2000-08-02', 'Ukraine', 'Userok Polzovatel\'', 'blocked', 'user', 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
