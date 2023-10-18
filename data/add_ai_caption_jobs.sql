ALTER TABLE media_text_tracks MODIFY COLUMN source ENUM('amara','order','upload','ai transcriptionist') DEFAULT NULL;


CREATE TABLE IF NOT EXISTS `transcription_jobs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `media_id` int(10) unsigned NOT NULL,
  `uid` varchar(50) NULL,
  `datecreated` timestamp NOT NULL,
  `dateupdated` timestamp NULL,
  `job_id` varchar(256) NULL,
  `status` ENUM('SUBMITTED', 'WORKING', 'ERROR', 'FINISHED') NOT NULL DEFAULT 'SUBMITTED',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`uid`) REFERENCES users(uid) ON DELETE SET NULL,
  FOREIGN KEY (`media_id`) REFERENCES media(id) ON DELETE CASCADE,
  INDEX `transcription_datecreated` (`datecreated`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;