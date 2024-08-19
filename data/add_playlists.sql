CREATE TABLE IF NOT EXISTS `playlists` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `feed_id` int(10) unsigned NOT NULL,
    `title` varchar(255) COLLATE utf8_unicode_ci NULL,
    `description` mediumtext COLLATE utf8_unicode_ci NULL,
    `uidcreated` varchar(50) NULL,
    `uidupdated` varchar(50) NULL,
    `datecreated` timestamp NOT NULL,
    `dateupdated` timestamp NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `playlist_has_media` (
    `playlist_id` int(10) unsigned NOT NULL,
    `media_id` int(10) unsigned NOT NULL,
    PRIMARY KEY (`playlist_id`,`media_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
