-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 20, 2023 at 03:25 PM
-- Server version: 5.5.68-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `mediahub`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `media_id` int(10) UNSIGNED NOT NULL,
  `uid` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `comment` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `datecreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feeds`
--

DROP TABLE IF EXISTS `feeds`;
CREATE TABLE `feeds` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `image_data` longblob,
  `image_type` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image_size` int(11) DEFAULT NULL,
  `image_title` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image_description` longtext COLLATE utf8_unicode_ci,
  `itunes_uri` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'URI to this feed within iTunes or iTunes U',
  `boxee` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Flag indicating whether feed is included in Boxee',
  `uidcreated` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `datecreated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feed_has_media`
--

DROP TABLE IF EXISTS `feed_has_media`;
CREATE TABLE `feed_has_media` (
  `feed_id` int(10) UNSIGNED NOT NULL,
  `media_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feed_has_nselement`
--

DROP TABLE IF EXISTS `feed_has_nselement`;
CREATE TABLE `feed_has_nselement` (
  `feed_id` int(10) UNSIGNED NOT NULL,
  `xmlns` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `element` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `attributes` longtext COLLATE utf8_unicode_ci,
  `value` longtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feed_has_subscription`
--

DROP TABLE IF EXISTS `feed_has_subscription`;
CREATE TABLE `feed_has_subscription` (
  `feed_id` int(10) UNSIGNED NOT NULL,
  `subscription_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
CREATE TABLE `media` (
  `id` int(10) UNSIGNED NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `uidcreated` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uidupdated` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `poster` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `length` bigint(20) UNSIGNED DEFAULT '0',
  `duration` int(11) NOT NULL DEFAULT '0' COMMENT 'the duration of the media in milliseconds',
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `author` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `privacy` enum('PUBLIC','UNLISTED','PROTECTED','PRIVATE') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'PUBLIC',
  `play_count` int(11) NOT NULL DEFAULT '0',
  `popular_play_count` int(11) NOT NULL DEFAULT '0',
  `last_played` timestamp NULL DEFAULT NULL,
  `datecreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dateupdated` timestamp NULL DEFAULT NULL,
  `media_text_tracks_id` int(10) UNSIGNED DEFAULT NULL,
  `UUID` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media_has_nselement`
--

DROP TABLE IF EXISTS `media_has_nselement`;
CREATE TABLE `media_has_nselement` (
  `media_id` int(10) UNSIGNED NOT NULL,
  `xmlns` varchar(10) NOT NULL,
  `element` varchar(100) NOT NULL,
  `attributes` longtext NOT NULL,
  `value` mediumtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `media_text_tracks`
--

DROP TABLE IF EXISTS `media_text_tracks`;
CREATE TABLE `media_text_tracks` (
  `id` int(10) UNSIGNED NOT NULL,
  `media_id` int(10) UNSIGNED NOT NULL,
  `datecreated` datetime DEFAULT NULL,
  `source` enum('amara','order') COLLATE utf8_unicode_ci DEFAULT NULL,
  `revision_comment` mediumtext COLLATE utf8_unicode_ci,
  `media_text_tracks_source_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media_text_tracks_files`
--

DROP TABLE IF EXISTS `media_text_tracks_files`;
CREATE TABLE `media_text_tracks_files` (
  `id` int(10) UNSIGNED NOT NULL,
  `media_text_tracks_id` int(10) UNSIGNED NOT NULL,
  `datecreated` datetime DEFAULT NULL,
  `kind` enum('caption','subtitle','description') COLLATE utf8_unicode_ci NOT NULL,
  `format` enum('srt','vtt') COLLATE utf8_unicode_ci NOT NULL,
  `language` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `file_contents` mediumtext COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media_views`
--

DROP TABLE IF EXISTS `media_views`;
CREATE TABLE `media_views` (
  `id` int(10) UNSIGNED NOT NULL,
  `media_id` int(10) UNSIGNED NOT NULL,
  `datecreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip_address` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rev_orders`
--

DROP TABLE IF EXISTS `rev_orders`;
CREATE TABLE `rev_orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `media_text_tracks_id` int(10) UNSIGNED DEFAULT NULL,
  `media_id` int(10) UNSIGNED DEFAULT NULL,
  `uid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `datecreated` datetime DEFAULT NULL,
  `dateupdated` datetime DEFAULT NULL,
  `costobjectnumber` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `rev_order_number` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `media_duration` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estimate` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `error_text` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
CREATE TABLE `subscriptions` (
  `id` int(11) UNSIGNED NOT NULL,
  `filter_class` int(255) NOT NULL,
  `filter_option` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `datecreated` datetime NOT NULL,
  `uidcreated` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transcoding_jobs`
--

DROP TABLE IF EXISTS `transcoding_jobs`;
CREATE TABLE `transcoding_jobs` (
  `id` int(10) UNSIGNED NOT NULL,
  `media_id` int(10) UNSIGNED NOT NULL,
  `uid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `datecreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `dateupdated` timestamp NULL DEFAULT NULL,
  `job_type` enum('mp4','hls') COLLATE utf8_unicode_ci NOT NULL,
  `job_id` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('SUBMITTED','WORKING','ERROR','FINISHED') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'SUBMITTED',
  `error_text` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `uid` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `datecreated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_has_permission`
--

DROP TABLE IF EXISTS `user_has_permission`;
CREATE TABLE `user_has_permission` (
  `user_uid` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `permission_id` int(10) UNSIGNED NOT NULL,
  `feed_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feeds`
--
ALTER TABLE `feeds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feed_has_media`
--
ALTER TABLE `feed_has_media`
  ADD PRIMARY KEY (`feed_id`,`media_id`),
  ADD KEY `mh_fhm_priv_search` (`feed_id`);

--
-- Indexes for table `feed_has_nselement`
--
ALTER TABLE `feed_has_nselement`
  ADD PRIMARY KEY (`feed_id`,`xmlns`,`element`);

--
-- Indexes for table `feed_has_subscription`
--
ALTER TABLE `feed_has_subscription`
  ADD PRIMARY KEY (`feed_id`,`subscription_id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `media_ibfk_1` (`media_text_tracks_id`),
  ADD KEY `mh_search2` (`type`,`title`,`popular_play_count`,`privacy`,`datecreated`);

--
-- Indexes for table `media_has_nselement`
--
ALTER TABLE `media_has_nselement`
  ADD PRIMARY KEY (`media_id`,`xmlns`,`element`);

--
-- Indexes for table `media_text_tracks`
--
ALTER TABLE `media_text_tracks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `media_text_tracks_datecreated` (`datecreated`),
  ADD KEY `media_text_tracks_ibfk_1` (`media_id`);

--
-- Indexes for table `media_text_tracks_files`
--
ALTER TABLE `media_text_tracks_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `media_text_tracks_datecreated` (`datecreated`),
  ADD KEY `media_text_tracks_files_ibfk_1` (`media_text_tracks_id`);

--
-- Indexes for table `media_views`
--
ALTER TABLE `media_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `media_views_media_id` (`media_id`),
  ADD KEY `media_views_datecreated` (`datecreated`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rev_orders`
--
ALTER TABLE `rev_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rev_orders_datecreated` (`datecreated`),
  ADD KEY `rev_orders_cost_object` (`costobjectnumber`),
  ADD KEY `rev_orders_ibfk_1` (`media_text_tracks_id`),
  ADD KEY `rev_orders_ibfk_2` (`uid`),
  ADD KEY `rev_orders_ibfk_3` (`media_id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transcoding_jobs`
--
ALTER TABLE `transcoding_jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `media_id` (`media_id`),
  ADD KEY `transcoding_jobs_datecreated` (`datecreated`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `user_has_permission`
--
ALTER TABLE `user_has_permission`
  ADD PRIMARY KEY (`user_uid`,`permission_id`,`feed_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feeds`
--
ALTER TABLE `feeds`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media_text_tracks`
--
ALTER TABLE `media_text_tracks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media_text_tracks_files`
--
ALTER TABLE `media_text_tracks_files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media_views`
--
ALTER TABLE `media_views`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rev_orders`
--
ALTER TABLE `rev_orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transcoding_jobs`
--
ALTER TABLE `transcoding_jobs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `media_ibfk_1` FOREIGN KEY (`media_text_tracks_id`) REFERENCES `media_text_tracks` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `media_text_tracks`
--
ALTER TABLE `media_text_tracks`
  ADD CONSTRAINT `media_text_tracks_ibfk_1` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `media_text_tracks_files`
--
ALTER TABLE `media_text_tracks_files`
  ADD CONSTRAINT `media_text_tracks_files_ibfk_1` FOREIGN KEY (`media_text_tracks_id`) REFERENCES `media_text_tracks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rev_orders`
--
ALTER TABLE `rev_orders`
  ADD CONSTRAINT `rev_orders_ibfk_1` FOREIGN KEY (`media_text_tracks_id`) REFERENCES `media_text_tracks` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `rev_orders_ibfk_2` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE SET NULL,
  ADD CONSTRAINT `rev_orders_ibfk_3` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `transcoding_jobs`
--
ALTER TABLE `transcoding_jobs`
  ADD CONSTRAINT `transcoding_jobs_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE SET NULL,
  ADD CONSTRAINT `transcoding_jobs_ibfk_2` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE CASCADE;
COMMIT;
