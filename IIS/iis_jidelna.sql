-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Počítač: uvdb1.active24.cz
-- Vytvořeno: Ned 01. pro 2019, 21:12
-- Verze serveru: 5.5.62-38.14-log
-- Verze PHP: 7.2.25

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `elbigfilipka`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `alergeny`
--

CREATE TABLE `alergeny` (
  `id` int(11) NOT NULL,
  `nazev` varchar(150) COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `alergeny`
--

INSERT INTO `alergeny` (`id`, `nazev`) VALUES
(1, 'Lepek'),
(2, 'Korýši'),
(3, 'Vejce'),
(4, 'Ryby'),
(5, 'Arašídy'),
(6, 'Sója'),
(7, 'Mléko'),
(8, 'Ořechy'),
(9, 'Celer'),
(10, 'Hořčice'),
(11, 'Sezam'),
(12, 'Oxid siřičitý'),
(13, 'Vlčí bob'),
(14, 'Měkkýší');

-- --------------------------------------------------------

--
-- Struktura tabulky `alergeny_v_jidle`
--

CREATE TABLE `alergeny_v_jidle` (
  `id` int(11) NOT NULL,
  `alergen` int(11) NOT NULL,
  `jidlo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `alergeny_v_jidle`
--

INSERT INTO `alergeny_v_jidle` (`id`, `alergen`, `jidlo`) VALUES
(1, 1, 1),
(2, 3, 1),
(3, 7, 1),
(4, 9, 1),
(5, 10, 1),
(6, 11, 1),
(7, 3, 2),
(8, 7, 2),
(9, 9, 2),
(10, 10, 2),
(11, 11, 2),
(12, 1, 3),
(13, 3, 3),
(14, 7, 3),
(15, 9, 3),
(16, 12, 3),
(17, 13, 3),
(18, 1, 4),
(19, 3, 4),
(20, 6, 4),
(21, 7, 4),
(22, 9, 4),
(23, 10, 4),
(24, 13, 4),
(26, 1, 5),
(27, 3, 5),
(28, 6, 5),
(29, 7, 5),
(30, 9, 5),
(31, 13, 5),
(32, 1, 6),
(33, 9, 6),
(34, 1, 7),
(35, 3, 7),
(36, 6, 7),
(37, 7, 7),
(38, 9, 7),
(39, 10, 7),
(40, 13, 7),
(41, 1, 8),
(42, 3, 8),
(43, 6, 8),
(44, 7, 8),
(45, 9, 8),
(46, 13, 8),
(47, 7, 19),
(48, 9, 19),
(49, 10, 19),
(50, 11, 19),
(51, 1, 20),
(52, 3, 20),
(53, 7, 20),
(56, 9, 22),
(57, 10, 22),
(58, 12, 22),
(59, 13, 22),
(60, 14, 22);

-- --------------------------------------------------------

--
-- Struktura tabulky `jidelna`
--

CREATE TABLE `jidelna` (
  `id` int(11) NOT NULL,
  `nazev` varchar(200) COLLATE utf8_czech_ci NOT NULL,
  `mesto` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `adresa` varchar(200) COLLATE utf8_czech_ci NOT NULL,
  `operator` int(11) DEFAULT NULL,
  `stav` bit(1) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `jidelna`
--

INSERT INTO `jidelna` (`id`, `nazev`, `mesto`, `adresa`, `operator`, `stav`) VALUES
(1, 'Netu', 'Brno', 'Jelení 1', 3, b'1'),
(2, 'Jídelna Písečná', 'Písečná', '100', 3, b'1');

-- --------------------------------------------------------

--
-- Struktura tabulky `jidla_v_nabidce`
--

CREATE TABLE `jidla_v_nabidce` (
  `id` int(11) NOT NULL,
  `nabidka` int(11) NOT NULL,
  `jidlo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `jidla_v_nabidce`
--

INSERT INTO `jidla_v_nabidce` (`id`, `nabidka`, `jidlo`) VALUES
(1, 4, 20),
(2, 4, 5),
(3, 4, 6),
(4, 4, 8),
(5, 5, 1),
(6, 5, 2),
(7, 5, 20),
(8, 5, 4),
(9, 6, 5),
(10, 6, 3),
(11, 6, 2),
(12, 6, 8),
(13, 7, 5),
(14, 7, 19),
(15, 7, 3),
(16, 7, 7),
(17, 8, 5),
(18, 8, 20),
(19, 8, 19),
(20, 8, 7),
(21, 9, 2),
(22, 9, 3),
(23, 9, 1),
(24, 9, 7),
(25, 10, 3),
(26, 10, 2),
(27, 10, 1),
(28, 10, 7),
(29, 11, 20),
(30, 11, 5),
(31, 11, 6),
(32, 11, 4),
(33, 12, 6),
(34, 12, 20),
(35, 12, 3),
(36, 12, 4),
(37, 13, 19),
(38, 13, 2),
(39, 13, 1),
(40, 13, 8),
(41, 14, 5),
(42, 14, 2),
(43, 14, 1),
(44, 14, 7),
(45, 15, 19),
(46, 15, 1),
(47, 15, 20),
(48, 15, 4),
(49, 16, 5),
(50, 16, 20),
(51, 16, 19),
(52, 16, 8),
(53, 17, 19),
(54, 17, 20),
(55, 17, 1),
(56, 17, 8),
(57, 18, 5),
(58, 18, 6),
(59, 18, 19),
(60, 18, 4),
(61, 19, 6),
(62, 19, 19),
(63, 19, 3),
(64, 19, 7),
(65, 20, 20),
(66, 20, 5),
(67, 20, 3),
(68, 20, 4),
(69, 21, 5),
(70, 21, 1),
(71, 21, 3),
(72, 21, 7),
(73, 22, 3),
(74, 22, 19),
(75, 22, 1),
(76, 22, 8),
(77, 23, 2),
(78, 23, 3),
(79, 23, 6),
(80, 23, 7),
(81, 24, 5),
(82, 24, 3),
(83, 24, 6),
(84, 24, 4),
(85, 25, 3),
(86, 25, 19),
(87, 25, 2),
(88, 25, 7),
(89, 26, 20),
(90, 26, 19),
(91, 26, 1),
(92, 26, 8),
(93, 27, 3),
(94, 27, 2),
(95, 27, 5),
(96, 27, 8),
(97, 28, 20),
(98, 28, 6),
(99, 28, 3),
(100, 28, 7),
(101, 29, 2),
(102, 29, 20),
(103, 29, 3),
(104, 29, 4),
(105, 30, 3),
(106, 30, 6),
(107, 30, 20),
(108, 30, 7),
(109, 31, 20),
(110, 31, 2),
(111, 31, 3),
(112, 31, 4),
(113, 32, 3),
(114, 32, 5),
(115, 32, 1),
(116, 32, 8),
(117, 33, 19),
(118, 33, 2),
(119, 33, 3),
(120, 33, 8),
(121, 34, 3),
(122, 34, 6),
(123, 34, 5),
(124, 34, 8),
(125, 35, 1),
(126, 35, 2),
(127, 35, 5),
(128, 35, 7),
(129, 36, 1),
(130, 36, 6),
(131, 36, 3),
(132, 36, 7),
(133, 37, 3),
(134, 37, 2),
(135, 37, 1),
(136, 37, 8),
(137, 38, 1),
(138, 38, 5),
(139, 38, 19),
(140, 38, 4),
(141, 39, 3),
(142, 39, 5),
(143, 39, 6),
(144, 39, 8),
(145, 40, 5),
(146, 40, 20),
(147, 40, 1),
(148, 40, 8),
(149, 41, 1),
(150, 41, 3),
(151, 41, 19),
(152, 41, 7),
(153, 42, 5),
(154, 42, 2),
(155, 42, 20),
(156, 42, 7),
(157, 43, 6),
(158, 43, 5),
(159, 43, 1),
(160, 43, 7),
(161, 44, 2),
(162, 44, 6),
(163, 44, 5),
(164, 44, 4),
(165, 45, 3),
(166, 45, 5),
(167, 45, 19),
(168, 45, 8),
(169, 46, 1),
(170, 46, 19),
(171, 46, 2),
(172, 46, 8),
(173, 47, 3),
(174, 47, 6),
(175, 47, 19),
(176, 47, 7),
(177, 48, 1),
(178, 48, 20),
(179, 48, 5),
(180, 48, 7),
(181, 49, 20),
(182, 49, 6),
(183, 49, 2),
(184, 49, 7),
(185, 50, 6),
(186, 50, 3),
(187, 50, 19),
(188, 50, 7),
(189, 51, 19),
(190, 51, 20),
(191, 51, 1),
(192, 51, 4),
(193, 52, 2),
(194, 52, 3),
(195, 52, 1),
(196, 52, 8),
(197, 53, 20),
(198, 53, 1),
(199, 53, 3),
(200, 53, 7),
(205, 55, 1),
(206, 55, 3),
(207, 55, 19),
(208, 55, 8),
(209, 56, 2),
(210, 56, 19),
(211, 56, 20),
(212, 56, 4),
(213, 57, 1),
(214, 57, 2),
(215, 57, 3),
(216, 57, 7),
(217, 58, 20),
(218, 58, 19),
(219, 58, 5),
(220, 58, 8),
(221, 59, 5),
(222, 59, 3),
(223, 59, 6),
(224, 59, 7),
(225, 60, 19),
(226, 60, 5),
(227, 60, 20),
(228, 60, 4),
(229, 61, 19),
(230, 61, 6),
(231, 61, 1),
(232, 61, 4),
(233, 62, 5),
(234, 62, 1),
(235, 62, 19),
(236, 62, 4),
(237, 63, 5),
(238, 63, 19),
(239, 63, 1),
(240, 63, 7),
(241, 64, 20),
(242, 64, 6),
(243, 64, 5),
(244, 64, 8),
(245, 65, 5),
(246, 65, 6),
(247, 65, 20),
(248, 65, 7),
(249, 66, 2),
(250, 66, 20),
(251, 66, 19),
(252, 66, 4),
(253, 67, 1),
(254, 67, 2),
(255, 67, 5),
(256, 67, 7),
(257, 68, 6),
(258, 68, 20),
(259, 68, 3),
(260, 68, 4),
(261, 69, 19),
(262, 69, 20),
(263, 69, 1),
(264, 69, 8),
(265, 70, 1),
(266, 70, 3),
(267, 70, 20),
(268, 70, 8),
(269, 71, 6),
(270, 71, 5),
(271, 71, 3),
(272, 71, 8),
(273, 72, 19),
(274, 72, 1),
(275, 72, 3),
(276, 72, 4),
(277, 73, 5),
(278, 73, 2),
(279, 73, 20),
(280, 73, 8),
(281, 74, 6),
(282, 74, 1),
(283, 74, 5),
(284, 74, 8),
(285, 75, 2),
(286, 75, 20),
(287, 75, 5),
(288, 75, 4),
(289, 76, 3),
(290, 76, 6),
(291, 76, 19),
(292, 76, 7),
(293, 77, 2),
(294, 77, 1),
(295, 77, 6),
(296, 77, 4),
(297, 78, 19),
(298, 78, 3),
(299, 78, 2),
(300, 78, 4),
(301, 79, 1),
(302, 79, 20),
(303, 79, 5),
(304, 79, 8),
(305, 80, 1),
(306, 80, 6),
(307, 80, 2),
(308, 80, 4),
(309, 81, 5),
(310, 81, 19),
(311, 81, 1),
(312, 81, 7),
(313, 82, 5),
(314, 82, 20),
(315, 82, 3),
(316, 82, 4),
(317, 83, 2),
(318, 83, 1),
(319, 83, 3),
(320, 83, 8),
(321, 84, 20),
(322, 84, 2),
(323, 84, 3),
(324, 84, 7),
(325, 85, 6),
(326, 85, 20),
(327, 85, 2),
(328, 85, 8),
(329, 86, 1),
(330, 86, 19),
(331, 86, 2),
(332, 86, 8),
(333, 87, 20),
(334, 87, 6),
(335, 87, 19),
(336, 87, 4),
(337, 88, 2),
(338, 88, 19),
(339, 88, 20),
(340, 88, 4),
(341, 89, 6),
(342, 89, 1),
(343, 89, 5),
(344, 89, 4),
(345, 90, 5),
(346, 90, 6),
(347, 90, 3),
(348, 90, 7),
(349, 91, 19),
(350, 91, 6),
(351, 91, 5),
(352, 91, 4),
(353, 92, 3),
(354, 92, 2),
(355, 92, 5),
(356, 92, 4),
(357, 93, 1),
(358, 93, 6),
(359, 93, 19),
(360, 93, 4),
(361, 94, 1),
(362, 94, 20),
(363, 94, 2),
(364, 94, 4),
(365, 95, 20),
(366, 95, 6),
(367, 95, 19),
(368, 95, 4),
(369, 96, 2),
(370, 96, 20),
(371, 96, 1),
(372, 96, 4),
(373, 97, 6),
(374, 97, 5),
(375, 97, 20),
(376, 97, 4),
(377, 98, 1),
(378, 98, 20),
(379, 98, 3),
(380, 98, 4),
(381, 99, 6),
(382, 99, 5),
(383, 99, 20),
(384, 99, 8),
(418, 54, 2),
(419, 54, 5),
(420, 54, 3),
(421, 54, 4),
(422, 101, 1),
(423, 101, 2),
(424, 101, 3),
(425, 101, 4),
(426, 102, 5),
(427, 102, 3),
(428, 102, 19),
(429, 102, 7),
(430, 103, 3),
(431, 103, 19),
(432, 103, 20),
(433, 103, 8),
(434, 108, 3),
(435, 108, 1),
(436, 108, 5),
(437, 108, 8),
(438, 109, 1),
(439, 109, 2),
(440, 109, 3),
(441, 109, 4),
(442, 112, 2),
(443, 112, 3),
(444, 112, 1),
(445, 112, 8);

-- --------------------------------------------------------

--
-- Struktura tabulky `jidlo`
--

CREATE TABLE `jidlo` (
  `id` int(11) NOT NULL,
  `nazev` varchar(200) COLLATE utf8_czech_ci NOT NULL,
  `popis` text COLLATE utf8_czech_ci NOT NULL,
  `typ` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `ob` varchar(200) COLLATE utf8_czech_ci DEFAULT NULL,
  `cena` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `jidlo`
--

INSERT INTO `jidlo` (`id`, `nazev`, `popis`, `typ`, `ob`, `cena`) VALUES
(1, 'Kotleta steak v orientálním koření s jasmínovou rýží', 'Krkovice vepřová 150g, olej, sůl, steak 7 pepřů-koření, máslo, kari koření, Solamyl-bramborový škrob, cibule, rýže jasmínová', 'hlavni', 'temp.png', 215),
(2, 'Kuřecí steak s hranolkami', 'Kuřecí řízky, olej, grilovací koření, máslo, Solamyl-bramborový škrob, vejce, hranolky, sůl', 'hlavni', 'temp.png', 150),
(3, 'Těstoviny s krůtím masem a sýrem', 'Krůtí řízky, těstoviny, cibule, eidam sýr, kečup, rajčatový protlak, olej, mouka hladká, anglická slanina, sůl, vývar slepičí, sušený česnek-plátky, bazalka-koření', 'hlavni', 'temp.png', 145),
(4, 'Sýrová polévka', 'Mouka hladká, poesie sýrová, mr. žampiony, anglická slanina, sůl, vývar zeleninový, muškátový květ', 'polevka', 'temp.png', 85),
(5, 'Kuřecí chilli medové paličky 4ks', 'Kuřecí štylka, st. rajčata krájená, rajčatový protlak, olej, sojová omáčka, česneková pasta, med, paprika mletá, petržel nať, chilli papričky čerstvé', 'hlavni', 'temp.png', 120),
(6, 'Kuřecí nudličky na pivě', 'Kuřecí řízky, cibule, mr. francouzská směs zelenina, cuketa, slanina, olej, solamyl-bramborový škrob, paprika mletá, sůl, ocet krém balsamico/pyré/, pepř mletý, pilsner 0,33', 'hlavni', 'temp.png', 150),
(7, 'Fazolová polévka s uzeninou', 'Fazole, párky, mouka hladká, olej, mrkev, cibule, česneková pasta, celer, petržel, sůl, šunka, polévkové koření, vývar zeleninový, gothaj salám, majoránka, pepř mletý', 'polevka', 'temp.png', 90),
(8, 'Kapustová polévka', 'Mr. kapusta, brambory, mouka hladká, cibule tuk, česneková pasta, polévkové koření, sůl, vývar zeleninový', 'polevka', 'temp.png', 85),
(19, 'Hovězí steak s americkým kořením a hranolky ', 'Hovězí roštěná, olej, sůl, country koření, grilovací koření, hranolky', 'hlavni', 'temp.png', 200),
(20, 'Koláč mřížkový jablkový', 'Kompot jablečné řezy, mouka hladká, mléko, smetol tuk, cukr moučka, vejce, cukr vanilkový, prášek do pečiva', 'hlavni', 'temp.png', 100);

-- --------------------------------------------------------

--
-- Struktura tabulky `mesta`
--

CREATE TABLE `mesta` (
  `Nazev` varchar(100) COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `mesta`
--

INSERT INTO `mesta` (`Nazev`) VALUES
('Brno'),
('Černá hora'),
('Dolní Dobrouč'),
('Hnátnice'),
('Letohrad'),
('Lipůvka'),
('Písečná'),
('Sebranice');

-- --------------------------------------------------------

--
-- Struktura tabulky `mesta_dovozu`
--

CREATE TABLE `mesta_dovozu` (
  `id` int(11) NOT NULL,
  `mesto` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `jidelna` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `mesta_dovozu`
--

INSERT INTO `mesta_dovozu` (`id`, `mesto`, `jidelna`) VALUES
(1, 'Brno', 1),
(2, 'Lipůvka', 1),
(3, 'Sebranice', 1),
(4, 'Černá hora', 1),
(5, 'Hnátnice', 2),
(6, 'Písečná', 2),
(7, 'Dolní Dobrouč', 2),
(8, 'Letohrad', 2);

-- --------------------------------------------------------

--
-- Struktura tabulky `nabidka`
--

CREATE TABLE `nabidka` (
  `id` int(11) NOT NULL,
  `jidelna` int(11) NOT NULL,
  `den` date NOT NULL,
  `stav` varchar(100) COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `nabidka`
--

INSERT INTO `nabidka` (`id`, `jidelna`, `den`, `stav`) VALUES
(2, 1, '2019-11-07', 'Uzavřeno'),
(3, 1, '2019-10-10', 'Otevřeno'),
(4, 2, '2019-11-08', 'Uzavřeno'),
(5, 2, '2019-11-08', 'Uzavřeno'),
(6, 2, '2019-11-09', 'Otevřeno'),
(7, 2, '2019-11-10', 'Otevřeno'),
(8, 2, '2019-11-11', 'Otevřeno'),
(9, 2, '2019-11-12', 'Otevřeno'),
(10, 2, '2019-11-13', 'Otevřeno'),
(11, 2, '2019-11-14', 'Otevřeno'),
(12, 2, '2019-11-15', 'Otevřeno'),
(13, 2, '2019-11-16', 'Uzavřeno'),
(14, 2, '2019-11-17', 'Uzavřeno'),
(15, 2, '2019-11-18', 'Uzavřeno'),
(16, 2, '2019-11-19', 'Uzavřeno'),
(17, 2, '2019-11-20', 'Uzavřeno'),
(18, 2, '2019-11-21', 'Uzavřeno'),
(19, 2, '2019-11-22', 'Uzavřeno'),
(20, 2, '2019-11-23', 'Uzavřeno'),
(21, 2, '2019-11-24', 'Otevřeno'),
(22, 2, '2019-11-25', 'Uzavřeno'),
(23, 2, '2019-11-26', 'Uzavřeno'),
(24, 2, '2019-11-27', 'Otevřeno'),
(25, 2, '2019-11-28', 'Otevřeno'),
(26, 2, '2019-11-29', 'Otevřeno'),
(27, 2, '2019-11-30', 'Otevřeno'),
(28, 2, '2019-12-01', 'Uzavřeno'),
(29, 2, '2019-12-02', 'Uzavřeno'),
(30, 2, '2019-12-03', 'Otevřeno'),
(31, 2, '2019-12-04', 'Otevřeno'),
(32, 2, '2019-12-05', 'Otevřeno'),
(33, 2, '2019-12-06', 'Otevřeno'),
(34, 2, '2019-12-07', 'Otevřeno'),
(35, 2, '2019-12-08', 'Otevřeno'),
(36, 2, '2019-12-09', 'Otevřeno'),
(37, 2, '2019-12-10', 'Otevřeno'),
(38, 2, '2019-12-11', 'Otevřeno'),
(39, 2, '2019-12-12', 'Otevřeno'),
(40, 2, '2019-12-13', 'Otevřeno'),
(41, 2, '2019-12-14', 'Otevřeno'),
(42, 2, '2019-12-15', 'Otevřeno'),
(43, 2, '2019-12-16', 'Otevřeno'),
(44, 2, '2019-12-17', 'Otevřeno'),
(45, 2, '2019-12-18', 'Otevřeno'),
(46, 2, '2019-12-19', 'Otevřeno'),
(47, 2, '2019-12-20', 'Otevřeno'),
(48, 2, '2019-12-21', 'Otevřeno'),
(49, 2, '2019-12-22', 'Otevřeno'),
(50, 2, '2019-12-23', 'Otevřeno'),
(51, 2, '2019-12-24', 'Otevřeno'),
(52, 2, '2019-12-25', 'Otevřeno'),
(53, 2, '2019-12-26', 'Otevřeno'),
(54, 2, '2019-12-27', 'Otevřeno'),
(55, 2, '2019-12-28', 'Otevřeno'),
(56, 2, '2019-12-29', 'Otevřeno'),
(57, 2, '2019-12-30', 'Otevřeno'),
(58, 2, '2019-12-31', 'Otevřeno'),
(59, 2, '2020-01-01', 'Otevřeno'),
(60, 2, '2020-01-02', 'Otevřeno'),
(61, 2, '2020-01-03', 'Otevřeno'),
(62, 2, '2020-01-04', 'Otevřeno'),
(63, 2, '2020-01-05', 'Otevřeno'),
(64, 2, '2020-01-06', 'Otevřeno'),
(65, 2, '2020-01-07', 'Otevřeno'),
(66, 2, '2020-01-08', 'Otevřeno'),
(67, 2, '2020-01-09', 'Otevřeno'),
(68, 2, '2020-01-10', 'Otevřeno'),
(69, 2, '2020-01-11', 'Otevřeno'),
(70, 2, '2020-01-12', 'Otevřeno'),
(71, 2, '2020-01-13', 'Otevřeno'),
(72, 2, '2020-01-14', 'Otevřeno'),
(73, 2, '2020-01-15', 'Otevřeno'),
(74, 2, '2020-01-16', 'Otevřeno'),
(75, 2, '2020-01-17', 'Otevřeno'),
(76, 2, '2020-01-18', 'Otevřeno'),
(77, 2, '2020-01-19', 'Otevřeno'),
(78, 2, '2020-01-20', 'Otevřeno'),
(79, 2, '2020-01-21', 'Otevřeno'),
(80, 2, '2020-01-22', 'Otevřeno'),
(81, 2, '2020-01-23', 'Otevřeno'),
(82, 2, '2020-01-24', 'Otevřeno'),
(83, 2, '2020-01-25', 'Otevřeno'),
(84, 2, '2020-01-26', 'Otevřeno'),
(85, 2, '2020-01-27', 'Otevřeno'),
(86, 2, '2020-01-28', 'Otevřeno'),
(87, 2, '2020-01-29', 'Otevřeno'),
(88, 2, '2020-01-30', 'Otevřeno'),
(89, 2, '2020-01-31', 'Otevřeno'),
(90, 2, '2020-02-01', 'Otevřeno'),
(91, 2, '2020-02-02', 'Otevřeno'),
(92, 2, '2020-02-03', 'Otevřeno'),
(93, 2, '2020-02-04', 'Otevřeno'),
(94, 2, '2020-02-05', 'Otevřeno'),
(95, 2, '2020-02-06', 'Otevřeno'),
(96, 2, '2020-02-07', 'Otevřeno'),
(97, 2, '2020-02-08', 'Otevřeno'),
(98, 2, '2020-02-09', 'Otevřeno'),
(99, 2, '2020-02-10', 'Otevřeno'),
(100, 1, '2019-11-23', 'Uzavřeno'),
(101, 1, '2019-11-24', 'Otevřeno'),
(102, 1, '2019-11-25', 'Otevřeno'),
(103, 1, '2019-11-26', 'Otevřeno'),
(104, 1, '2019-11-27', 'Otevřeno'),
(105, 1, '2019-11-28', 'Otevřeno'),
(106, 1, '2019-11-29', 'Otevřeno'),
(107, 1, '2019-11-30', 'Otevřeno'),
(108, 1, '2019-12-03', 'Otevřeno'),
(109, 1, '2019-12-04', 'Otevřeno'),
(110, 1, '2019-12-25', 'Otevřeno'),
(111, 1, '2019-12-31', 'Otevřeno'),
(112, 1, '2019-12-05', 'Otevřeno');

-- --------------------------------------------------------

--
-- Struktura tabulky `objednana_jidla`
--

CREATE TABLE `objednana_jidla` (
  `id` int(11) NOT NULL,
  `objednavka` int(11) NOT NULL,
  `jidlo` int(11) NOT NULL,
  `pocet` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `objednana_jidla`
--

INSERT INTO `objednana_jidla` (`id`, `objednavka`, `jidlo`, `pocet`) VALUES
(1, 1, 5, 3),
(2, 1, 19, 2),
(3, 1, 8, 1),
(4, 2, 6, 4),
(5, 2, 19, 4),
(6, 2, 3, 4),
(7, 2, 7, 4),
(8, 3, 7, 1),
(9, 4, 1, 2),
(10, 5, 20, 1),
(11, 5, 4, 1),
(12, 6, 20, 3),
(13, 6, 3, 1),
(14, 7, 4, 1),
(15, 8, 20, 3),
(16, 8, 5, 3),
(17, 8, 3, 3),
(18, 8, 4, 3),
(19, 9, 8, 2),
(20, 10, 1, 3),
(21, 10, 3, 2),
(22, 11, 5, 3),
(23, 11, 3, 2),
(24, 11, 19, 4),
(25, 11, 7, 2),
(26, 12, 3, 4),
(27, 12, 19, 4),
(28, 12, 8, 4),
(29, 13, 6, 2),
(30, 13, 5, 2),
(31, 13, 20, 2),
(32, 13, 8, 2),
(33, 14, 1, 1),
(34, 15, 6, 1),
(35, 15, 4, 1),
(36, 16, 3, 2),
(37, 16, 20, 2),
(38, 17, 1, 2),
(39, 17, 20, 2),
(40, 17, 7, 2),
(41, 18, 20, 1),
(42, 19, 1, 2),
(43, 19, 2, 1),
(44, 20, 3, 1),
(45, 20, 8, 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `objednavka`
--

CREATE TABLE `objednavka` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `stav` varchar(150) COLLATE utf8_czech_ci NOT NULL DEFAULT 'Čekání',
  `ridic` int(11) DEFAULT NULL,
  `cena` int(11) NOT NULL,
  `cas_objednani` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `den_dodani` date NOT NULL,
  `mesto` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `adresa` varchar(200) COLLATE utf8_czech_ci NOT NULL,
  `jidelna` int(11) NOT NULL,
  `kod` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `objednavka`
--

INSERT INTO `objednavka` (`id`, `user`, `stav`, `ridic`, `cena`, `cas_objednani`, `den_dodani`, `mesto`, `adresa`, `jidelna`, `kod`) VALUES
(1, 3, 'Dodáno', 2, 845, '2019-11-22 09:34:17', '2019-11-19', 'Hnátnice', '1221', 2, 482768480),
(2, 2, 'Dodáno', 2, 2340, '2019-11-22 09:36:09', '2019-11-22', 'Hnátnice', '158', 2, 926682909),
(3, 2, 'Dodáno', 2, 90, '2019-11-25 21:17:55', '2019-11-24', 'Hnátnice', '158', 2, 148310062),
(4, 2, 'Dodáno', 2, 430, '2019-11-25 21:17:43', '2019-11-25', 'Hnátnice', '158', 2, 947290478),
(5, 2, 'Dodáno', 2, 185, '2019-11-25 21:17:41', '2019-11-23', 'Hnátnice', '158', 2, 874704004),
(6, 22, 'Dodáno', 2, 445, '2019-11-25 21:17:49', '2019-11-23', 'Dolní Dobrouč', '1', 2, 639432070),
(7, 24, 'Dodáno', 2, 85, '2019-11-25 21:17:51', '2019-11-23', 'Písečná', '123', 2, 157556244),
(8, 25, 'Dodáno', 2, 1350, '2019-11-25 22:40:15', '2019-11-23', 'Hnátnice', '123', 2, 382226546),
(9, 26, 'Dodáno', 2, 170, '2019-11-25 21:17:44', '2019-11-25', 'Hnátnice', '12', 2, 419216821),
(10, 24, 'Dodáno', 24, 935, '2019-11-25 21:19:49', '2019-11-24', 'Brno', '15', 1, 982823801),
(11, 24, 'Dodáno', 2, 1630, '2019-11-25 21:19:15', '2019-11-25', 'Brno', '12', 1, 583874956),
(12, 24, 'Dodáno', 2, 1720, '2019-11-25 21:19:15', '2019-11-26', 'Brno', 'Jelení 1985', 1, 483805143),
(13, 25, 'Čekání', NULL, 910, '2019-11-25 21:21:30', '2020-02-10', 'Hnátnice', '15', 2, 345485491),
(14, 25, 'Čekání', NULL, 215, '2019-11-25 21:22:38', '2020-02-09', 'Hnátnice', '15', 2, 755723353),
(15, 25, 'Dodáno', 2, 235, '2019-12-01 19:59:30', '2020-02-04', 'Hnátnice', '15', 2, 119535559),
(16, 27, 'Potvrzeno', 5, 490, '2019-12-01 19:42:35', '2019-12-03', 'Hnátnice', '159', 2, 375439395),
(17, 1, 'Čekání', NULL, 810, '2019-12-01 18:32:57', '2019-12-21', 'Hnátnice', '159', 2, 145117308),
(18, 25, 'Čekání', NULL, 100, '2019-12-01 19:01:45', '2019-12-04', 'Dolní Dobrouč', '15', 2, 242168868),
(19, 28, 'Potvrzeno', 24, 580, '2019-12-01 19:54:13', '2019-12-04', 'Černá hora', 'Kopírovaná 69', 1, 202605571),
(20, 29, 'Na cestě', 2, 230, '2019-12-01 19:55:10', '2019-12-05', 'Brno', 'Void 00', 1, 411827447);

-- --------------------------------------------------------

--
-- Struktura tabulky `prava`
--

CREATE TABLE `prava` (
  `id` int(11) NOT NULL,
  `nazev` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `popis` text COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `prava`
--

INSERT INTO `prava` (`id`, `nazev`, `popis`) VALUES
(1, 'Administrátor', 'Může vše'),
(2, 'Operátor', 'Spravuje jídelny a jejich nabídky, přiřazuje řidičům objednávky.'),
(3, 'Řidič', 'Dostává objednávky, vyzvedává objednaná jídla a rozváží je.'),
(4, 'Strávník', 'Spravovat svůj účet.'),
(5, 'Pleb', 'Objednávat jídlo, procházet jídelníčky.');

-- --------------------------------------------------------

--
-- Struktura tabulky `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `jmeno` varchar(150) COLLATE utf8_czech_ci DEFAULT NULL,
  `prijmeni` varchar(150) COLLATE utf8_czech_ci DEFAULT NULL,
  `email` varchar(200) COLLATE utf8_czech_ci NOT NULL,
  `password` varchar(200) COLLATE utf8_czech_ci DEFAULT NULL,
  `mesto` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  `adresa` varchar(200) COLLATE utf8_czech_ci DEFAULT NULL,
  `telefon` int(11) DEFAULT NULL,
  `prava` int(11) NOT NULL DEFAULT '5'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `user`
--

INSERT INTO `user` (`id`, `jmeno`, `prijmeni`, `email`, `password`, `mesto`, `adresa`, `telefon`, `prava`) VALUES
(1, 'Admin', 'Admin', 'admin@jidelna.cz', '$2y$10$Aklkl1KEk4tFEqX9apIWbuCq2SrPrCd5Qqe0yodi5.eX5WjBruuLy', NULL, NULL, 159753159, 1),
(2, 'Jan', 'Novák', 'novak@jidelna.cz', '$2y$10$1aVc/0Us2dJ0XgiOz8jdMewFvCaJpV0ywwpKEQFPqW8bErm3GnoKK', 'Hnátnice', '158', NULL, 3),
(3, 'Ladislav', 'Novák', 'LadNov@jidelna.cz', '$2y$10$WXIRJZz0kTHqwOEYkE4U9Ocy/Rd0cp3SYDMBiGxMTBif7.7L9czX2', 'Brno', 'Letohradská 5', 420420420, 2),
(4, 'Michal', 'Jansa', 'Mich@seznam.cz', '$2y$10$ozjtWuZcxX.liIsuMAW0xenUdcviSZWpmf.Y3g2bck6DM5OOuI2Pu', NULL, NULL, NULL, 3),
(5, 'Radek', 'Pospíšil', 'pos@seznam.cz', '$2y$10$.48n3E/4ydIUsKMbgULhqegl2UNLycsQ9Ltoal/toWmXVgcLfaeEi', NULL, NULL, NULL, 3),
(6, 'Jaroslav', 'Jareš', 'jares@gmail.cz', '$2y$10$UExpnYpmVQ/E8IkQyxFQ1OotJBHaaV3VeUfuDNmEfTavOcAsPOFci', NULL, NULL, NULL, 3),
(7, 'Antonína', 'Nováková', 'Annov@seznam.cz', '$2y$10$mbBbkos8uGWoAhcUW9AdSO1I9TznGeJul6Z99ax1Y9FxlX6532H1u', NULL, NULL, NULL, 4),
(8, 'Adam', 'Jíl', 'jil@gmail.com', '$2y$10$oEjtsPZBjOV.0q.DeecEJ.YAdKbnUrqEbFtuuuyMgtwaCo6UcqTbe', NULL, NULL, NULL, 4),
(9, 'Tereza', 'Mandlová', 'Tereza@email.cz', '$2y$10$dYI1uvX1iHVVjke8hK9uqug8v//aOhGy0rrXb8S.8O4hGGRMIDYKC', NULL, NULL, NULL, 4),
(10, 'Karina', 'Jedlá', 'kari@seznam.cz', '$2y$10$rHB3Cb5vEaq1/.QC4J7SHukDsifF9juL.wVg3j2dN705KlYeQSQxC', NULL, NULL, NULL, 4),
(11, 'Diana', 'Jelínková', 'Dijel@gmail.com', '$2y$10$MumZEVqIIaFpqgW9yVRq3.L7vgLJdaN3jG0YUpCP8ovv4DcZKYkKS', NULL, NULL, NULL, 4),
(12, 'Melichar', 'Pošeptný', 'Meli@centrum.cz', '$2y$10$aiwIlqJmhRQeiVG1MFsdu.6lTsj2pIQ.DB3rZrqApuVCwLirXQmx.', NULL, NULL, NULL, 4),
(13, 'Vilma', 'Špatná', 'Vili@centrum.cz', '$2y$10$zRx4vPZNIiNLqsHwYESvuu1s6WiVqqY3lUCbeVed2SFal.IA3QRYi', NULL, NULL, NULL, 4),
(14, 'Čestmír', 'Nový', 'cest@gmail.com', '$2y$10$dBTYfqZwgf.S.YVK5gVQgO3KmcZLqb.tzXrZuvySdE2BGfgb2JKmu', NULL, NULL, NULL, 4),
(15, 'Ctirad', 'Hruška', 'hrus@gmail.com', '$2y$10$Zm4qqBtnLSJ4uJkeu37DnesrTc392byqTelWb5ZyTNEzm0R9c2kUK', NULL, NULL, NULL, 4),
(16, 'Edita', 'Pražná', 'EditPra@gmail.com', '$2y$10$FXn.lQChgnU/jO8YtpRYje2GjVKzw2yLlfTCFLfBBPfaG.DUY/qfa', NULL, NULL, NULL, 4),
(17, 'Apolína', 'Poštolová', 'appos@gmail.com', '$2y$10$bsmAGcqXbZDDHDdoakgjKuVKolxm8RKqXpHMWiG3vktHsd3Vu.MnK', NULL, NULL, NULL, 4),
(18, 'Ladislav', 'Jmelí', 'ladi@gmail.com', '$2y$10$BPoUSaayw70jVso0YKt5sOY7t5uvf5LCk30UOQObFbzgq513U6cCy', NULL, NULL, NULL, 4),
(19, 'Jan', 'Perník', 'prna@seznam.cz', '$2y$10$PH4kIBTwbS9PTnv/QKRgU.jifZZ3l43Y8yyD59cZhP9RURYnt6Mxq', NULL, NULL, NULL, 4),
(20, 'Michal', 'Adam', 'mic@gmail.com', '$2y$10$ilmDfjHyd6qXVy0xgUcglec6ujaKfyDQORcGKsV8io9gyS9MAcG1C', NULL, NULL, NULL, 4),
(21, 'Melichar', 'Jansa', 'Milichar@jidelna.cz', '$2y$10$YqUY4xzCV5Is4JyKPhHssunbHnHrcqg.VDDmL.RKvDP.fa3/YZxla', NULL, NULL, NULL, 4),
(22, NULL, NULL, 'nevim@jidelna.cz', NULL, NULL, NULL, 123456789, 5),
(24, 'Ran', 'Dom', 'random@jidelna.cz', '$2y$10$3gFeEzhn9CeHn3ounC3DCO/rliEABhXaEXkBAegGFthR1/rWXxHCO', 'Brno', 'Jelení 1985', NULL, 3),
(25, 'Novy', 'acc', 'novy@jidelna.cz', '$2y$10$0jsA1afMP/Plz9Pq81go9eNEhWGwzw5jDgIav3Lk9jovJQcoZ2Wkq', 'Hnátnice', '15', 666555667, 4),
(26, NULL, NULL, 'asd@asd.asd', NULL, NULL, NULL, 159753268, 5),
(27, NULL, NULL, 'nejakynovy@jidelna.cz', NULL, NULL, NULL, 159753159, 5),
(28, 'Plagiát', 'Nulabodů', 'xplagiat0b@who.cz', '$2y$10$YB0jjcwrsGWFDuMOBYB3r.qv/ndQB53JsWa6rHuYfO2ISWOT7Lfxy', 'Černá hora', 'Kopírovaná 69', 420696969, 5),
(29, NULL, NULL, 'neexistuju@empty.vessel', NULL, NULL, NULL, 789456123, 5);

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `alergeny`
--
ALTER TABLE `alergeny`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `alergeny_v_jidle`
--
ALTER TABLE `alergeny_v_jidle`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alergen` (`alergen`),
  ADD KEY `jidlo` (`jidlo`);

--
-- Klíče pro tabulku `jidelna`
--
ALTER TABLE `jidelna`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mesto` (`mesto`),
  ADD KEY `operator` (`operator`);

--
-- Klíče pro tabulku `jidla_v_nabidce`
--
ALTER TABLE `jidla_v_nabidce`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jidlo` (`jidlo`),
  ADD KEY `nabidka` (`nabidka`);

--
-- Klíče pro tabulku `jidlo`
--
ALTER TABLE `jidlo`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `mesta`
--
ALTER TABLE `mesta`
  ADD PRIMARY KEY (`Nazev`);

--
-- Klíče pro tabulku `mesta_dovozu`
--
ALTER TABLE `mesta_dovozu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mesto` (`mesto`),
  ADD KEY `jidelna` (`jidelna`);

--
-- Klíče pro tabulku `nabidka`
--
ALTER TABLE `nabidka`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jidelna` (`jidelna`);

--
-- Klíče pro tabulku `objednana_jidla`
--
ALTER TABLE `objednana_jidla`
  ADD PRIMARY KEY (`id`),
  ADD KEY `objednavka` (`objednavka`),
  ADD KEY `jidlo` (`jidlo`);

--
-- Klíče pro tabulku `objednavka`
--
ALTER TABLE `objednavka`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user`),
  ADD KEY `ridic` (`ridic`),
  ADD KEY `mesto` (`mesto`),
  ADD KEY `jidelna` (`jidelna`);

--
-- Klíče pro tabulku `prava`
--
ALTER TABLE `prava`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prava` (`prava`),
  ADD KEY `mesto` (`mesto`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `alergeny`
--
ALTER TABLE `alergeny`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pro tabulku `alergeny_v_jidle`
--
ALTER TABLE `alergeny_v_jidle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT pro tabulku `jidelna`
--
ALTER TABLE `jidelna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pro tabulku `jidla_v_nabidce`
--
ALTER TABLE `jidla_v_nabidce`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=446;

--
-- AUTO_INCREMENT pro tabulku `jidlo`
--
ALTER TABLE `jidlo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pro tabulku `mesta_dovozu`
--
ALTER TABLE `mesta_dovozu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pro tabulku `nabidka`
--
ALTER TABLE `nabidka`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT pro tabulku `objednana_jidla`
--
ALTER TABLE `objednana_jidla`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT pro tabulku `objednavka`
--
ALTER TABLE `objednavka`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pro tabulku `prava`
--
ALTER TABLE `prava`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pro tabulku `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `alergeny_v_jidle`
--
ALTER TABLE `alergeny_v_jidle`
  ADD CONSTRAINT `alergeny_v_jidle_ibfk_3` FOREIGN KEY (`alergen`) REFERENCES `alergeny` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `alergeny_v_jidle_ibfk_4` FOREIGN KEY (`jidlo`) REFERENCES `jidlo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Omezení pro tabulku `jidelna`
--
ALTER TABLE `jidelna`
  ADD CONSTRAINT `jidelna_ibfk_3` FOREIGN KEY (`mesto`) REFERENCES `mesta` (`Nazev`),
  ADD CONSTRAINT `jidelna_ibfk_4` FOREIGN KEY (`operator`) REFERENCES `user` (`id`) ON UPDATE CASCADE;

--
-- Omezení pro tabulku `jidla_v_nabidce`
--
ALTER TABLE `jidla_v_nabidce`
  ADD CONSTRAINT `jidla_v_nabidce_ibfk_1` FOREIGN KEY (`jidlo`) REFERENCES `jidlo` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jidla_v_nabidce_ibfk_2` FOREIGN KEY (`nabidka`) REFERENCES `nabidka` (`id`) ON DELETE CASCADE;

--
-- Omezení pro tabulku `mesta_dovozu`
--
ALTER TABLE `mesta_dovozu`
  ADD CONSTRAINT `mesta_dovozu_ibfk_2` FOREIGN KEY (`mesto`) REFERENCES `mesta` (`Nazev`),
  ADD CONSTRAINT `mesta_dovozu_ibfk_3` FOREIGN KEY (`jidelna`) REFERENCES `jidelna` (`id`) ON DELETE CASCADE;

--
-- Omezení pro tabulku `nabidka`
--
ALTER TABLE `nabidka`
  ADD CONSTRAINT `nabidka_ibfk_2` FOREIGN KEY (`jidelna`) REFERENCES `jidelna` (`id`) ON DELETE CASCADE;

--
-- Omezení pro tabulku `objednana_jidla`
--
ALTER TABLE `objednana_jidla`
  ADD CONSTRAINT `objednana_jidla_ibfk_3` FOREIGN KEY (`objednavka`) REFERENCES `objednavka` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `objednana_jidla_ibfk_4` FOREIGN KEY (`jidlo`) REFERENCES `jidlo` (`id`) ON DELETE CASCADE;

--
-- Omezení pro tabulku `objednavka`
--
ALTER TABLE `objednavka`
  ADD CONSTRAINT `objednavka_ibfk_1` FOREIGN KEY (`user`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `objednavka_ibfk_2` FOREIGN KEY (`ridic`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `objednavka_ibfk_3` FOREIGN KEY (`mesto`) REFERENCES `mesta` (`Nazev`),
  ADD CONSTRAINT `objednavka_ibfk_4` FOREIGN KEY (`jidelna`) REFERENCES `jidelna` (`id`);

--
-- Omezení pro tabulku `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_4` FOREIGN KEY (`prava`) REFERENCES `prava` (`id`),
  ADD CONSTRAINT `user_ibfk_5` FOREIGN KEY (`mesto`) REFERENCES `mesta` (`Nazev`) ON DELETE SET NULL ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
