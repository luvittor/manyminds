-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 21-Maio-2023 às 17:18
-- Versão do servidor: 10.4.22-MariaDB
-- versão do PHP: 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `php_codeigniter3_manyminds`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `colaboradores`
--

CREATE TABLE `colaboradores` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(100) NOT NULL,
  `users_id` int(10) UNSIGNED DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `fornecedor` tinyint(1) NOT NULL DEFAULT 0,
  `documento` varchar(20) NOT NULL,
  `data_contratacao` date NOT NULL,
  `observacao` text NOT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `colaboradores`
--

INSERT INTO `colaboradores` (`id`, `nome`, `users_id`, `email`, `fornecedor`, `documento`, `data_contratacao`, `observacao`, `disable`) VALUES
(1, 'João da Silva', 3, 'joaodasilva@empresa.com', 0, '33222111A', '2021-01-21', 'Novo funcionário. Teste', 1),
(2, 'José Lima', 4, 'joselima@empresa.com', 0, '33222111A', '2021-02-02', 'Novo funcionário. Novo funcionário.Novo funcionário.Novo funcionário.Novo funcionário.Novo funcionário.Novo funcionário.Novo funcionário.Novo funcionário.Novo funcionário.Novo funcionário.Novo funcionário.Novo funcionário.Novo funcionário.Novo funcionário.Novo funcionário.Novo funcionário.Novo funcionário.Novo funcionário. \"Teste\" <teste></textarea>', 0),
(4, 'Antonio Oliveira', NULL, 'antoniooliveira@empresa.com', 1, '33222111A', '2021-02-02', 'Novo funcionário. Novo funcionário.Novo funcionário.Novo funcionário.Novo funcionário.Novo funcionário.Novo funcionário.Novo funcionário.Novo funcionário.Novo funcionário.Novo funcionário.Novo funcionário.Novo funcionário.Novo funcionário.Novo funcionário.Novo funcionário.Novo funcionário.Novo funcionário.Novo funcionário. teste', 0),
(6, 'Alberto da Costa', NULL, 'albertocosta@empresa.com', 0, '555444333AA', '2020-01-22', 'Teste', 0),
(7, 'Aline Matos', 5, 'alinematos@empresa.com', 0, '55444666V', '2019-10-05', 'Teste Teste', 0),
(8, 'Ricardo César', NULL, 'ricardocesar@empresa.com', 1, '22255566602', '2019-12-31', '', 0),
(10, 'Fernando Prado', NULL, 'fernadoprado@empresa.com', 0, '111155554444000101', '2021-01-01', '', 1),
(11, 'Alvaro Barbosa', NULL, 'alvaro@empresa.com', 1, '111333444', '2008-07-01', '', 0),
(12, 'Thiago Vieira', NULL, 'thiago@empresa.com', 1, '11222333', '2019-01-01', '', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `failed_login_attempts`
--

CREATE TABLE `failed_login_attempts` (
  `id` int(10) UNSIGNED NOT NULL,
  `ip` varchar(255) NOT NULL,
  `users_id` int(10) UNSIGNED DEFAULT NULL,
  `error_code` tinyint(4) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `failed_login_attempts`
--

INSERT INTO `failed_login_attempts` (`id`, `ip`, `users_id`, `error_code`, `created_at`) VALUES
(1, '::1', NULL, 1, '2023-05-20 22:05:19'),
(2, '::1', NULL, 2, '2023-05-20 22:05:36'),
(3, '::1', 1, 3, '2023-05-20 22:05:44'),
(4, '::1', 1, 3, '2023-05-21 15:03:15');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(10) UNSIGNED NOT NULL,
  `colaboradores_id` int(10) UNSIGNED NOT NULL,
  `observacao` text NOT NULL,
  `finalizado` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `pedidos`
--

INSERT INTO `pedidos` (`id`, `colaboradores_id`, `observacao`, `finalizado`) VALUES
(1000, 11, 'text text text text', 1),
(1001, 4, '', 1),
(1002, 11, 'text text', 1),
(1003, 12, '', 1),
(1004, 11, 'Teste', 1),
(1005, 8, 'text text', 1),
(1006, 11, 'text text text text', 1),
(1014, 11, 'text text text text', 1),
(1015, 11, 'text text text text text text text', 1),
(1016, 11, 'text text text text', 1),
(1017, 8, 'text text text text text text text', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedidos_produtos`
--

CREATE TABLE `pedidos_produtos` (
  `id` int(10) UNSIGNED NOT NULL,
  `pedidos_id` int(10) UNSIGNED NOT NULL,
  `produtos_id` int(10) UNSIGNED NOT NULL,
  `quantidade` int(10) UNSIGNED NOT NULL,
  `preco` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `pedidos_produtos`
--

INSERT INTO `pedidos_produtos` (`id`, `pedidos_id`, `produtos_id`, `quantidade`, `preco`) VALUES
(1, 1000, 3, 1, '1.25'),
(3, 1001, 8, 1, '10.50'),
(4, 1001, 8, 1, '10.50'),
(5, 1001, 8, 1, '10.50'),
(6, 1001, 8, 1, '10.50'),
(7, 1001, 4, 1, '10.50'),
(8, 1001, 4, 1, '10.50'),
(9, 1001, 4, 1, '10.50'),
(10, 1001, 6, 4, '10.99'),
(11, 1001, 6, 4, '10.99'),
(12, 1001, 6, 4, '10.99'),
(13, 1001, 5, 7, '2.66'),
(20, 1002, 3, 17, '9.98'),
(21, 1003, 4, 3, '7.84'),
(22, 1003, 8, 6, '3.81'),
(23, 1003, 2, 7, '6.54'),
(24, 1003, 2, 6, '3.23'),
(25, 1004, 4, 4, '3.31'),
(26, 1004, 5, 9, '5.32'),
(27, 1004, 6, 1, '111.00'),
(28, 1005, 6, 1, '149.99'),
(29, 1004, 2, 41, '0.99'),
(33, 1005, 4, 1, '99.99'),
(34, 1005, 2, 1, '0.99'),
(35, 1005, 5, 55, '11.99'),
(37, 1005, 5, 100, '1.89'),
(38, 1000, 1, 1, '100.99'),
(41, 1000, 2, 10, '56.99'),
(45, 1000, 8, 10, '56.99'),
(46, 1006, 8, 10, '56.99'),
(47, 1014, 8, 10, '56.99'),
(48, 1015, 6, 1, '179.99'),
(69, 1016, 6, 1, '9.99'),
(70, 1016, 6, 1, '10.11'),
(75, 1017, 7, 2, '9.99');

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(20) NOT NULL,
  `observacao` text NOT NULL,
  `disable` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `observacao`, `disable`) VALUES
(1, 'Rosca', 'Teste Teste', 1),
(2, 'Parafuso', '', 0),
(3, 'Martelo', '', 1),
(4, 'Parafusadeira', '', 0),
(5, 'Prego', '', 0),
(6, 'Furadeira', '', 0),
(7, 'Chave de Fenda', '', 0),
(8, 'Chave Philips', '', 0),
(9, 'Conjunto de Brocas', '', 0),
(10, 'Alicate', '', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `disable`) VALUES
(1, 'admin', '$2y$10$50IiYf6PtqtUVhChOyD6VuNVAW0x5a5TGmpCmjLwBLZ3ws6P5oBuG', 0),
(2, 'admin2', '$2y$10$thzVFYX0yPZCOyhedTViOOjsufbs.qHM5a9Ki13RhPY5VLTXW8Lvq', 1),
(3, 'joao_silva', '$2y$10$RIgkmAxJ/xZ7bMDgxvtHdeKN2V3udcNf0wTGkaQlZ9Adj7EpgO7pe', 0),
(4, 'jose_lima', '$2y$10$50IiYf6PtqtUVhChOyD6VuNVAW0x5a5TGmpCmjLwBLZ3ws6P5oBuG', 1),
(5, 'aline_matos', '$2y$10$U/oSk0WrQiFXq9g7slSbteSzXH.xD2fAGp41H3KNBuLLez1Wfjn0C', 0),
(6, 'thiago_lemos', '$2y$10$bpuA8vUXFu/PkNvB5gxeMeUvjk4/A4Ze8.6MzoExbD11Uz1E4Cv4K', 0),
(7, 'gustavo_oliveira', '$2y$10$ghAfldnNs7UW61cIUsmqe.cFAVse/0vN72T/QuOZXXhQZckSjfz/u', 0);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `colaboradores`
--
ALTER TABLE `colaboradores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_id` (`users_id`);

--
-- Índices para tabela `failed_login_attempts`
--
ALTER TABLE `failed_login_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_id` (`users_id`);

--
-- Índices para tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `colaboradores_id` (`colaboradores_id`);

--
-- Índices para tabela `pedidos_produtos`
--
ALTER TABLE `pedidos_produtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedidos_id` (`pedidos_id`),
  ADD KEY `produtos_id` (`produtos_id`);

--
-- Índices para tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `colaboradores`
--
ALTER TABLE `colaboradores`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `failed_login_attempts`
--
ALTER TABLE `failed_login_attempts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1018;

--
-- AUTO_INCREMENT de tabela `pedidos_produtos`
--
ALTER TABLE `pedidos_produtos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `colaboradores`
--
ALTER TABLE `colaboradores`
  ADD CONSTRAINT `colaboradores_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`);

--
-- Limitadores para a tabela `failed_login_attempts`
--
ALTER TABLE `failed_login_attempts`
  ADD CONSTRAINT `failed_login_attempts_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`);

--
-- Limitadores para a tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`colaboradores_id`) REFERENCES `colaboradores` (`id`);

--
-- Limitadores para a tabela `pedidos_produtos`
--
ALTER TABLE `pedidos_produtos`
  ADD CONSTRAINT `pedidos_produtos_ibfk_1` FOREIGN KEY (`pedidos_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `pedidos_produtos_ibfk_2` FOREIGN KEY (`produtos_id`) REFERENCES `produtos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
