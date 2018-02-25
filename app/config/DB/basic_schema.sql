/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  sofia
 * Created: 25-oct-2017
 */
-- Create syntax for TABLE 'centre'
CREATE TABLE `centre` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creator` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `editor` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `edited` datetime DEFAULT NULL,
  `deletor` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C6A0EA75BC06EA63` (`creator`),
  CONSTRAINT `FK_C6A0EA75BC06EA63` FOREIGN KEY (`creator`) REFERENCES `loginUser` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'department'
CREATE TABLE `department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creator` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `editor` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `edited` datetime DEFAULT NULL,
  `deletor` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_CD1DE18ABC06EA63` (`creator`),
  CONSTRAINT `FK_CD1DE18ABC06EA63` FOREIGN KEY (`creator`) REFERENCES `loginUser` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'grouppe'
CREATE TABLE `grouppe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creator` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `editor` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `edited` datetime DEFAULT NULL,
  `deletor` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `leader` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_9402359C77153098` (`code`),
  KEY `IDX_9402359CBC06EA63` (`creator`),
  KEY `IDX_9402359CF5E3EAD7` (`leader`),
  CONSTRAINT `FK_9402359CBC06EA63` FOREIGN KEY (`creator`) REFERENCES `loginUser` (`id`),
  CONSTRAINT `FK_9402359CF5E3EAD7` FOREIGN KEY (`leader`) REFERENCES `leader` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'leader'
CREATE TABLE `leader` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creator` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `editor` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `edited` datetime DEFAULT NULL,
  `deletor` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F5E3EAD7BC06EA63` (`creator`),
  CONSTRAINT `FK_F5E3EAD7BC06EA63` FOREIGN KEY (`creator`) REFERENCES `loginUser` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'loginUser'
CREATE TABLE `loginUser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `isactive` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'BdODmKWjGtaKeFyYsNCbOPRzquNIIRMiEFPjqTSgbfMvMeZgNKihEdUdUXUniHUh',
  `roleId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_username` (`username`),
  KEY `IDX_1E9897B7B8C2FD88` (`roleId`),
  CONSTRAINT `FK_1E9897B7B8C2FD88` FOREIGN KEY (`roleId`) REFERENCES `role` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'role'
CREATE TABLE `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_57698A6A57698A6A` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'user'
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `centre` int(11) DEFAULT NULL,
  `department` int(11) DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `centre_tmp` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `department_tmp` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `groupName_tmp` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `groupCode_tmp` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `groupWebsite_tmp` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `groupLeader_tmp` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `position` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `softwareSpecifications` tinytext COLLATE utf8_unicode_ci,
  `description` tinytext COLLATE utf8_unicode_ci,
  `isClusterUser` tinyint(1) NOT NULL DEFAULT '0',
  `registerdate` datetime DEFAULT NULL,
  `group` int(11) DEFAULT NULL,
  `usingTime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_email` (`email`),
  KEY `IDX_8D93D649C6A0EA75` (`centre`),
  KEY `IDX_8D93D649CD1DE18A` (`department`),
  KEY `IDX_8D93D6496DC044C5` (`group`),
  KEY `IDX_8D93D64997C22061` (`usingTime`),
  CONSTRAINT `FK_8D93D6496DC044C5` FOREIGN KEY (`group`) REFERENCES `grouppe` (`id`),
  CONSTRAINT `FK_8D93D64997C22061` FOREIGN KEY (`usingTime`) REFERENCES `usingTime` (`id`),
  CONSTRAINT `FK_8D93D649C6A0EA75` FOREIGN KEY (`centre`) REFERENCES `centre` (`id`),
  CONSTRAINT `FK_8D93D649CD1DE18A` FOREIGN KEY (`department`) REFERENCES `department` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'usingTime'
CREATE TABLE `usingTime` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `role` (`id`, `name`, `role`)
VALUES
	(1, 'Admin', 'ROLE_ADMIN');

INSERT INTO `loginUser` (`id`, `name`, `lastname`, `email`, `username`, `password`, `isactive`, `roleId`, `salt`)
VALUES
	(1, 'Admin', ' ', '', 'admin', '$2y$12$kda1j6og63kkg40s0g4kgu51.4gwstQMh75LGMtXErSB305sMDzv.', 1, 1, 'BdODmKWjGtaKeFyYsNCbOPRzquNIIRMiEFPjqTSgbfMvMeZgNKihEdUdUXUniHUh');

INSERT INTO `usingTime` (`id`, `code`)
VALUES
	(1, '1_MONTH'),
	(2, '6_MONTH'),
	(3, '1_YEAR'),
	(4, '4_YEAR');
	(4, 'UNLIMITED');
