-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 30, 2026 at 09:00 PM
-- Server version: 12.0.2-MariaDB
-- PHP Version: 8.5.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `demo_p6`
--

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`id`, `title`, `author`, `image`, `description`, `user_id`, `status_id`, `created_at`) VALUES
(1, 'L\'Art de la guerre', 'Sun Tzu', 'upload_img/img_69cae1b3c80a45.36153633.jpg', 'Un classique de strategie militaire qui reste etonnamment moderne pour parler de negociation, de tactique et de prise de decision.', 3, 1, '2026-03-10 10:15:00'),
(2, 'Tao Te King', 'Lao Tseu', 'upload_img/img_69cae0e170aeb2.19420371.jpg', 'Petit livre philosophique, ideal pour une lecture lente. L\'edition est en bon etat avec quelques annotations au crayon.', 5, 1, '2026-03-11 19:05:00'),
(3, 'Le Petit Prince', 'Antoine de Saint-Exupery', 'upload_img/img_69cae27c6697c3.03331715.jpg', 'Edition illustree. Couverture legerement usee, interieur tres propre.', 1, 1, '2026-03-12 09:20:00'),
(4, 'Orgueil et Prejuges', 'Jane Austen', NULL, 'Roman en francais, parfait pour celles et ceux qui aiment les personnages subtils et les dialogues incisifs.', 2, 1, '2026-03-12 14:40:00'),
(5, 'Dune', 'Frank Herbert', NULL, 'Premier tome en excellent etat. Je cherche plutot des romans de science-fiction ou des essais recents en echange.', 4, 2, '2026-03-13 18:00:00'),
(6, 'Siddhartha', 'Hermann Hesse', NULL, 'Un roman court mais marquant sur la quete de sens. Quelques passages surlignes.', 6, 1, '2026-03-14 08:10:00'),
(7, '1984', 'George Orwell', NULL, 'Poche en bon etat. Les pages sont jaunies mais solides.', 7, 1, '2026-03-14 12:35:00'),
(8, 'Bel-Ami', 'Guy de Maupassant', NULL, 'Edition scolaire propre. Ideal pour lyceen ou amateur de classiques.', 8, 2, '2026-03-15 16:50:00'),
(9, 'La Peste', 'Albert Camus', NULL, 'Livre marquant, couverture souple, quelques marques d\'usage.', 1, 1, '2026-03-16 11:25:00'),
(10, 'Le Comte de Monte-Cristo', 'Alexandre Dumas', NULL, 'Edition integrale en deux volumes. Je prefere un echange contre un autre grand classique.', 2, 1, '2026-03-16 20:15:00'),
(11, 'Le Nom de la rose', 'Umberto Eco', 'upload_img/img_69cae21e058fe5.14251449.jpg', 'Roman historique et policier. Livre en tres bon etat.', 3, 2, '2026-03-17 09:45:00'),
(12, 'Les Miserables', 'Victor Hugo', NULL, 'Volume unique assez epais, mais encore tres agreable a lire.', 4, 1, '2026-03-17 13:20:00'),
(13, 'Kafka sur le rivage', 'Haruki Murakami', NULL, 'Roman envoutant, couverture souple, tranche un peu marquee.', 5, 1, '2026-03-18 17:30:00'),
(14, 'Le Hobbit', 'J. R. R. Tolkien', NULL, 'Une excellente porte d\'entree vers la fantasy, edition recente.', 6, 1, '2026-03-19 10:55:00'),
(15, 'La Promesse de l\'aube', 'Romain Gary', NULL, 'Tres beau texte autobiographique, livre comme neuf.', 7, 1, '2026-03-20 15:05:00'),
(16, 'L\'Etranger', 'Albert Camus', NULL, 'Petit format pratique, legerement corne sur un angle.', 8, 1, '2026-03-21 18:40:00'),
(17, 'Le Parfum', 'Patrick Suskind', NULL, 'Bon etat general. Quelques notes au stylo sur la premiere page.', 2, 2, '2026-03-22 12:10:00'),
(18, 'Jane Eyre', 'Charlotte Bronte', NULL, 'Edition brochee propre, disponible immediatement.', 1, 1, '2026-03-23 09:05:00'),
(19, 'Fondation', 'Isaac Asimov', NULL, 'Poche science-fiction, lecture fluide, ideal pour decouvrir Asimov.', 4, 1, '2026-03-24 19:25:00'),
(20, 'Le Rouge et le Noir', 'Stendhal', 'upload_img/img_69cae253d81235.80274754.jpg', 'Livre en tres bon etat, couverture semi-rigide.', 3, 1, '2026-03-25 14:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `book_status`
--

CREATE TABLE `book_status` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_status`
--

INSERT INTO `book_status` (`id`, `name`) VALUES
(1, 'disponible'),
(2, 'non dispo.');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `content`, `timestamp`, `sender_id`, `receiver_id`) VALUES
(1, 'Bonjour Alice, ton exemplaire du Petit Prince est-il toujours disponible ?', '2026-03-18 09:05:00', 5, 1),
(2, 'Oui, il est toujours disponible. Tu aurais quoi a proposer en echange ?', '2026-03-18 09:12:00', 1, 5),
(3, 'J\'ai Kafka sur le rivage en tres bon etat si ca t\'interesse.', '2026-03-18 09:18:00', 5, 1),
(4, 'Ca m\'interesse beaucoup. On peut se retrouver cette semaine.', '2026-03-18 09:24:00', 1, 5),
(5, 'Parfait, je te redis mes disponibilites ce soir.', '2026-03-18 09:30:00', 5, 1),
(6, 'Salut Benoit, le Monte-Cristo est bien l\'edition integrale ?', '2026-03-18 11:40:00', 3, 2),
(7, 'Oui, integrale en deux volumes. Je peux t\'envoyer une photo si tu veux.', '2026-03-18 11:44:00', 2, 3),
(8, 'Volontiers, je cherche justement une belle edition.', '2026-03-18 11:46:00', 3, 2),
(9, 'Bonjour David, Fondation est encore dispo ?', '2026-03-19 08:10:00', 8, 4),
(10, 'Oui, je ne l\'ai pas encore reserve. Tu aimerais l\'echanger contre quoi ?', '2026-03-19 08:14:00', 4, 8),
(11, 'J\'ai Bel-Ami, mais il est en statut non dispo pour le moment. Je peux te recontacter des qu\'il revient.', '2026-03-19 08:19:00', 8, 4),
(12, 'Pas de souci, tiens-moi au courant.', '2026-03-19 08:23:00', 4, 8),
(13, 'Bonjour Emma, je serais interessee par Tao Te King.', '2026-03-20 14:05:00', 7, 5),
(14, 'Avec plaisir. Tu aurais un classique anglais ou un recueil de poesie ?', '2026-03-20 14:09:00', 5, 7),
(15, 'J\'ai Jane Eyre en bon etat, ca pourrait te plaire.', '2026-03-20 14:12:00', 7, 5),
(16, 'Excellente idee, on fait comme ca.', '2026-03-20 14:16:00', 5, 7),
(17, 'Farid, ton Hobbit est-il reserve ?', '2026-03-22 10:01:00', 1, 6),
(18, 'Non, il est toujours dispo. Je cherche plutot des romans francais.', '2026-03-22 10:06:00', 6, 1),
(19, 'J\'ai La Peste si jamais, en tres bon etat.', '2026-03-22 10:09:00', 1, 6),
(20, 'Ca pourrait me convenir, merci.', '2026-03-22 10:15:00', 6, 1),
(21, 'Bonjour Chloe, Le Nom de la rose reviendra bientot en disponible ?', '2026-03-24 16:40:00', 2, 3),
(22, 'Oui, normalement d\'ici quelques jours. Je te fais signe.', '2026-03-24 16:44:00', 3, 2),
(23, 'Merci, je suis preneur.', '2026-03-24 16:46:00', 2, 3),
(24, 'Salut Hugo, tu cherches toujours de la SF ?', '2026-03-26 18:11:00', 4, 8),
(25, 'Oui, surtout des cycles un peu classiques.', '2026-03-26 18:14:00', 8, 4),
(26, 'Dans ce cas Fondation devrait vraiment te plaire.', '2026-03-26 18:18:00', 4, 8),
(27, 'Je confirme, je suis tres interesse.', '2026-03-26 18:21:00', 8, 4),
(28, 'Alice, je viens de voir Jane Eyre dans ta bibliotheque, est-ce encore dispo ?', '2026-03-28 09:50:00', 2, 1),
(29, 'Oui, il est encore disponible. Tu aurais quoi a proposer ?', '2026-03-28 09:55:00', 1, 2),
(30, 'Peut-etre Orgueil et Prejuges, pour rester dans le theme.', '2026-03-28 10:02:00', 2, 1),
(31, 'Parfait, c\'est exactement le genre d\'echange que j\'aime.', '2026-03-28 10:07:00', 1, 2),
(32, 'Emma, merci encore pour l\'echange de ce matin.', '2026-03-29 15:45:00', 1, 5),
(33, 'Avec plaisir, a refaire.', '2026-03-29 15:48:00', 5, 1),
(34, 'Giulia, ton exemplaire de 1984 est toujours visible sur le site, tu confirmes ?', '2026-03-30 08:05:00', 6, 7),
(35, 'Oui, toujours disponible. Je peux le mettre de cote si tu veux.', '2026-03-30 08:11:00', 7, 6),
(36, 'Oui, volontiers, je te recontacte ce soir.', '2026-03-30 08:19:00', 6, 7);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `avatar`, `created_at`) VALUES
(1, 'Alice Martin', 'alice@tomtroc.demo', '$2y$12$.vd9CgT7C58uJlOsT0rqF.6vwL9lzndv5dbgRd3/24dQmyY.QdAq.', NULL, '2025-11-10 20:20:09'),
(2, 'Benoit Leroy', 'benoit@tomtroc.demo', '$2y$12$.vd9CgT7C58uJlOsT0rqF.6vwL9lzndv5dbgRd3/24dQmyY.QdAq.', NULL, '2025-11-14 09:10:00'),
(3, 'Chloe Bernard', 'chloe@tomtroc.demo', '$2y$12$.vd9CgT7C58uJlOsT0rqF.6vwL9lzndv5dbgRd3/24dQmyY.QdAq.', NULL, '2025-12-02 18:45:00'),
(4, 'David Moreau', 'david@tomtroc.demo', '$2y$12$.vd9CgT7C58uJlOsT0rqF.6vwL9lzndv5dbgRd3/24dQmyY.QdAq.', NULL, '2026-01-08 11:30:00'),
(5, 'Emma Petit', 'emma@tomtroc.demo', '$2y$12$.vd9CgT7C58uJlOsT0rqF.6vwL9lzndv5dbgRd3/24dQmyY.QdAq.', NULL, '2026-01-15 15:10:00'),
(6, 'Farid Dubois', 'farid@tomtroc.demo', '$2y$12$.vd9CgT7C58uJlOsT0rqF.6vwL9lzndv5dbgRd3/24dQmyY.QdAq.', NULL, '2026-02-01 08:20:00'),
(7, 'Giulia Rossi', 'giulia@tomtroc.demo', '$2y$12$.vd9CgT7C58uJlOsT0rqF.6vwL9lzndv5dbgRd3/24dQmyY.QdAq.', NULL, '2026-02-12 13:55:00'),
(8, 'Hugo Lambert', 'hugo@tomtroc.demo', '$2y$12$.vd9CgT7C58uJlOsT0rqF.6vwL9lzndv5dbgRd3/24dQmyY.QdAq.', NULL, '2026-03-01 17:40:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_book_user` (`user_id`),
  ADD KEY `fk_book_status` (`status_id`);

--
-- Indexes for table `book_status`
--
ALTER TABLE `book_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_message_sender` (`sender_id`),
  ADD KEY `fk_message_receiver` (`receiver_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `book`
--
ALTER TABLE `book`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `book_status`
--
ALTER TABLE `book_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `book`
--
ALTER TABLE `book`
  ADD CONSTRAINT `fk_book_status` FOREIGN KEY (`status_id`) REFERENCES `book_status` (`id`),
  ADD CONSTRAINT `fk_book_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `fk_message_receiver` FOREIGN KEY (`receiver_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_message_sender` FOREIGN KEY (`sender_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;
COMMIT;

