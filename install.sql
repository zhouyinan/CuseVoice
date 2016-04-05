SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `contestants` (
  `netid` char(28) COLLATE utf8_bin NOT NULL COMMENT 'NETID',
  `name` varchar(20) COLLATE utf8_bin DEFAULT NULL COMMENT '姓名',
  `firstname` varchar(50) COLLATE utf8_bin DEFAULT NULL COMMENT 'First Name',
  `lastname` varchar(50) COLLATE utf8_bin DEFAULT NULL COMMENT 'Last Name',
  `mobile` varchar(20) COLLATE utf8_bin DEFAULT NULL COMMENT 'Mobile',
  `song` varchar(50) COLLATE utf8_bin DEFAULT NULL COMMENT 'Song',
  `note` varchar(512) COLLATE utf8_bin NOT NULL,
  `accompany_uploaded` bit(1) NOT NULL DEFAULT b'0',
  `confirmed` bit(1) NOT NULL DEFAULT b'0',
  `register_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`netid`),
  KEY `name` (`name`),
  KEY `firstname` (`firstname`),
  KEY `lastname` (`lastname`),
  KEY `name_2` (`name`),
  KEY `name_3` (`name`),
  KEY `firstname_2` (`firstname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Information of contestants of Syracuse Voice';

CREATE TABLE IF NOT EXISTS `final_round_contestants` (
  `contestant_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_bin DEFAULT NULL COMMENT '姓名',
  `firstname` varchar(50) COLLATE utf8_bin DEFAULT NULL COMMENT 'First Name',
  `lastname` varchar(50) COLLATE utf8_bin DEFAULT NULL COMMENT 'Last Name',
  PRIMARY KEY (`contestant_id`),
  KEY `name` (`name`),
  KEY `firstname` (`firstname`),
  KEY `lastname` (`lastname`),
  KEY `name_2` (`name`),
  KEY `name_3` (`name`),
  KEY `firstname_2` (`firstname`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Information of contestants of Syracuse Voice' AUTO_INCREMENT=7 ;

CREATE TABLE IF NOT EXISTS `final_round_grades` (
  `grade_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `contestant_id` tinyint(3) unsigned DEFAULT NULL,
  `judge` tinyint(3) unsigned DEFAULT NULL,
  `score` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `round` tinyint(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`grade_id`),
  KEY `contestant_netid` (`judge`),
  KEY `grader` (`judge`),
  KEY `contestant_id` (`contestant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `final_round_judges` (
  `judge_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `judge_name` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`judge_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=6 ;

CREATE TABLE IF NOT EXISTS `grades` (
  `grade_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `contestant_netid` char(28) COLLATE utf8_bin DEFAULT NULL,
  `judge` tinyint(3) unsigned DEFAULT NULL,
  `score` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`grade_id`),
  KEY `contestant_netid` (`contestant_netid`,`judge`),
  KEY `grader` (`judge`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `judges` (
  `judge_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `judge_name` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`judge_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=6 ;


ALTER TABLE `final_round_grades`
  ADD CONSTRAINT `final_contestant_consistency` FOREIGN KEY (`contestant_id`) REFERENCES `final_round_contestants` (`contestant_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `judge_consistency` FOREIGN KEY (`judge`) REFERENCES `final_round_judges` (`judge_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `grades`
  ADD CONSTRAINT `grader_consistency` FOREIGN KEY (`judge`) REFERENCES `judges` (`judge_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contestant_consistency` FOREIGN KEY (`contestant_netid`) REFERENCES `contestants` (`netid`) ON DELETE CASCADE ON UPDATE CASCADE;
