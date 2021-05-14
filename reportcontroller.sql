-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2021. Máj 12. 20:28
-- Kiszolgáló verziója: 10.4.17-MariaDB
-- PHP verzió: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `reportcontroller`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `answers`
--

CREATE TABLE `answers` (
  `AID` int(11) NOT NULL,
  `TID` int(11) NOT NULL,
  `ANSWER` varchar(255) NOT NULL,
  `ANSWERTIME` datetime NOT NULL DEFAULT current_timestamp(),
  `ADMIN` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- TÁBLA KAPCSOLATAI `answers`:
--   `TID`
--       `errortickets` -> `TID`
--

--
-- A tábla adatainak kiíratása `answers`
--

INSERT INTO `answers` (`AID`, `TID`, `ANSWER`, `ANSWERTIME`, `ADMIN`) VALUES
(109, 22, 'Dobja ki', '2021-05-12 18:44:47', b'1'),
(110, 23, 'dobd ki', '2021-05-12 18:46:18', b'1'),
(111, 23, 'nincs valami megoldás rá?', '2021-05-12 18:47:55', b'0'),
(112, 23, 'sajnos nincs', '2021-05-12 18:48:13', b'1'),
(113, 23, 'rendben, köszönöm', '2021-05-12 18:48:32', b'0'),
(114, 23, 'sajnálom, zárom a hibajegyet', '2021-05-12 18:49:21', b'1'),
(115, 24, 'Ezzel lehet kezdeni valamit?', '2021-05-12 19:59:42', b'0'),
(116, 24, 'nem', '2021-05-12 20:00:18', b'1'),
(117, 24, 'egyáltalán nem?', '2021-05-12 20:00:54', b'0'),
(118, 24, 'egyáltalán nem', '2021-05-12 20:01:08', b'1');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `errortickets`
--

CREATE TABLE `errortickets` (
  `TID` int(11) NOT NULL,
  `ERRID` int(11) NOT NULL,
  `PID` int(11) NOT NULL,
  `SID` int(11) NOT NULL,
  `SUBJECT` varchar(55) NOT NULL,
  `ERRORDESC` varchar(255) NOT NULL,
  `IMAGE` varchar(255) NOT NULL,
  `CREATIONTIME` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- TÁBLA KAPCSOLATAI `errortickets`:
--   `ERRID`
--       `errortypes` -> `ERRID`
--   `PID`
--       `persons` -> `PID`
--   `SID`
--       `status` -> `SID`
--

--
-- A tábla adatainak kiíratása `errortickets`
--

INSERT INTO `errortickets` (`TID`, `ERRID`, `PID`, `SID`, `SUBJECT`, `ERRORDESC`, `IMAGE`, `CREATIONTIME`) VALUES
(21, 34, 89, 1, 'Valami elromlott', 'Valami elromlott', 'images/997909751_b_703checkengine-800x350.jpg', '2021-05-12 18:41:37'),
(22, 37, 89, 4, 'Valami más is elromlott', 'Valami más is elromlott', '', '2021-05-12 18:42:11'),
(23, 40, 89, 4, 'Ez is elromlott...', 'Ez is elromlott...', 'images/255728329maxresdefault.jpg', '2021-05-12 18:42:53'),
(24, 34, 77, 2, 'minden rossz', 'minden rossz', 'images/324440675marketing-hiba.jpg', '2021-05-12 19:59:24'),
(25, 34, 77, 1, 'valami', 'valami', '', '2021-05-12 20:02:47');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `errortypes`
--

CREATE TABLE `errortypes` (
  `ERRID` int(11) NOT NULL,
  `TYPENAME` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- TÁBLA KAPCSOLATAI `errortypes`:
--

--
-- A tábla adatainak kiíratása `errortypes`
--

INSERT INTO `errortypes` (`ERRID`, `TYPENAME`) VALUES
(34, 'valami'),
(37, 'valami3'),
(38, 'valami4'),
(39, 'valami5'),
(40, 'valami6'),
(41, 'valami7');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `persons`
--

CREATE TABLE `persons` (
  `PID` int(11) NOT NULL,
  `NAME` varchar(55) NOT NULL,
  `EMAIL` varchar(55) NOT NULL,
  `PW` char(32) NOT NULL,
  `COMPANY` varchar(55) DEFAULT NULL,
  `ADMIN` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- TÁBLA KAPCSOLATAI `persons`:
--

--
-- A tábla adatainak kiíratása `persons`
--

INSERT INTO `persons` (`PID`, `NAME`, `EMAIL`, `PW`, `COMPANY`, `ADMIN`) VALUES
(69, 'admin', 'admin@admin.hu', '21232f297a57a5a743894a0e4a801fc3', NULL, b'1'),
(74, 'teszt25', 'teszt@teszt.hu', '6c90aa3760658846a86a263a4e92630e', 'Samsung', b'0'),
(77, 'teszt', 'teszt2@teszt.hu', 'e970707c584b0c4574564ad239301c01', 'IBM', b'0'),
(78, 'teszt', 'teszt3@teszt.hu', '6c90aa3760658846a86a263a4e92630e', 'Samsung', b'0'),
(79, 'teszt', 'teszt4@teszt.hu', '6c90aa3760658846a86a263a4e92630e', 'Google', b'0'),
(80, 'teszt12', 'teszt5@teszt.hu', '6c90aa3760658846a86a263a4e92630e', 'IBM', b'0'),
(81, 'teszt', 'teszt6@teszt.hu', '6c90aa3760658846a86a263a4e92630e', 'Google', b'0'),
(82, 'teszt', 'teszt7@teszt.hu', '6c90aa3760658846a86a263a4e92630e', 'teszt', b'0'),
(83, 'admin', 'admin2@admin.hu', '21232f297a57a5a743894a0e4a801fc3', NULL, b'1'),
(84, 'admin', 'admin3@admin.hu', '21232f297a57a5a743894a0e4a801fc3', NULL, b'1'),
(85, 'admin', 'admin4@admin.hu', '21232f297a57a5a743894a0e4a801fc3', NULL, b'1'),
(86, 'admin', 'admin5@admin.hu', '21232f297a57a5a743894a0e4a801fc3', NULL, b'1'),
(87, 'admin', 'admin6@admin.hu', '21232f297a57a5a743894a0e4a801fc3', NULL, b'1'),
(88, 'admin', 'admin7@admin.hu', '21232f297a57a5a743894a0e4a801fc3', NULL, b'1'),
(89, 'teszt', 'teszt10@teszt.hu', '6c90aa3760658846a86a263a4e92630e', 'Google', b'0'),
(90, 'teszt', 'teszt12@teszt.hu', '6c90aa3760658846a86a263a4e92630e', 'teszt', b'0');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `status`
--

CREATE TABLE `status` (
  `SID` int(11) NOT NULL,
  `STATUS` varchar(55) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- TÁBLA KAPCSOLATAI `status`:
--

--
-- A tábla adatainak kiíratása `status`
--

INSERT INTO `status` (`SID`, `STATUS`) VALUES
(1, 'Függő'),
(2, 'Feldolgozás alatt'),
(3, 'Megoldott'),
(4, 'Lezárt');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`AID`),
  ADD KEY `TID` (`TID`);

--
-- A tábla indexei `errortickets`
--
ALTER TABLE `errortickets`
  ADD PRIMARY KEY (`TID`),
  ADD KEY `ERRID` (`ERRID`),
  ADD KEY `PID` (`PID`),
  ADD KEY `SID` (`SID`);

--
-- A tábla indexei `errortypes`
--
ALTER TABLE `errortypes`
  ADD PRIMARY KEY (`ERRID`);

--
-- A tábla indexei `persons`
--
ALTER TABLE `persons`
  ADD PRIMARY KEY (`PID`);

--
-- A tábla indexei `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`SID`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `answers`
--
ALTER TABLE `answers`
  MODIFY `AID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT a táblához `errortickets`
--
ALTER TABLE `errortickets`
  MODIFY `TID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT a táblához `errortypes`
--
ALTER TABLE `errortypes`
  MODIFY `ERRID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT a táblához `persons`
--
ALTER TABLE `persons`
  MODIFY `PID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT a táblához `status`
--
ALTER TABLE `status`
  MODIFY `SID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`TID`) REFERENCES `errortickets` (`TID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `errortickets`
--
ALTER TABLE `errortickets`
  ADD CONSTRAINT `errortickets_ibfk_1` FOREIGN KEY (`ERRID`) REFERENCES `errortypes` (`ERRID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `errortickets_ibfk_2` FOREIGN KEY (`PID`) REFERENCES `persons` (`PID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `errortickets_ibfk_3` FOREIGN KEY (`SID`) REFERENCES `status` (`SID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
