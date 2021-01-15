-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : ven. 15 jan. 2021 à 15:52
-- Version du serveur :  8.0.22-0ubuntu0.20.04.3
-- Version de PHP : 7.3.22-1+ubuntu18.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `syges`
--

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `username` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `roles`, `password`) VALUES
(1, 'delrodie', '[\"ROLE_SUPER_ADMIN\"]', '$argon2id$v=19$m=65536,t=4,p=1$bTjw6EqVio30wArYbo8/hA$U5Ung1/VZJBkig2cm3jnDoxlpZhxQRNEQe68/y06yc4'),
(2, 'GHISLENE', '[\"ROLE_ADMIN\"]', '$argon2id$v=19$m=65536,t=4,p=1$5vAvvWm3u5s5LCJdqaKKcw$KPy6z+WVcdPZWaxz3WEkInlFuH8tpSG9fImd4xRiRtU'),
(3, 'ANGE', '[\"ROLE_USER\"]', '$argon2id$v=19$m=65536,t=4,p=1$N1gDRLIYvF7KFhOTOs+aoQ$ub0ZIX5kXUAmC+uyD/PRIzBWEsqIzB7Pf7ff6v000sc'),
(4, 'NGATTA', '[\"ROLE_USER\"]', '$argon2id$v=19$m=65536,t=4,p=1$AEOYQKV46cNoksl8c4Fmpw$3v5eWODZcMMxX31NI+1+zcLnls2mFcqtn0u0GgUYZvM');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
