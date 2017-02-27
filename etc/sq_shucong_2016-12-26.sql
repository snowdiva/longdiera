# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.16)
# Database: sq_shucong
# Generation Time: 2016-12-26 13:09:16 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table sc_authority
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sc_authority`;

CREATE TABLE `sc_authority` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '权限编号',
  `name` varchar(50) NOT NULL DEFAULT '''''' COMMENT '名称',
  `alias` varchar(50) DEFAULT NULL COMMENT '权限别名中文',
  `url` varchar(100) DEFAULT NULL COMMENT '菜单url地址',
  `description` varchar(200) DEFAULT '''''' COMMENT '描述',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '类型',
  `pid` int(11) DEFAULT '0' COMMENT '上级权限',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

LOCK TABLES `sc_authority` WRITE;
/*!40000 ALTER TABLE `sc_authority` DISABLE KEYS */;

INSERT INTO `sc_authority` (`id`, `name`, `alias`, `url`, `description`, `type`, `pid`, `create_time`)
VALUES
	(1,'system','系统设置',NULL,'\'\'',1,0,0),
	(2,'article','内容管理',NULL,'\'\'',1,0,0),
	(3,'user','用户管理',NULL,'\'\'',1,0,0),
	(4,'sort_manage','分类管理','article/sort','\'\'',1,2,0),
	(5,'article_manage','文章管理','article/index','\'\'',1,2,0),
	(7,'collect_manage','采集管理','article/collect','\'\'',1,2,0);

/*!40000 ALTER TABLE `sc_authority` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table sc_authority_to_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sc_authority_to_users`;

CREATE TABLE `sc_authority_to_users` (
  `authority_id` int(11) unsigned NOT NULL COMMENT '权限表编号',
  `user_id` int(11) unsigned NOT NULL COMMENT '用户表编号'
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

LOCK TABLES `sc_authority_to_users` WRITE;
/*!40000 ALTER TABLE `sc_authority_to_users` DISABLE KEYS */;

INSERT INTO `sc_authority_to_users` (`authority_id`, `user_id`)
VALUES
	(1,6),
	(2,6),
	(3,6),
	(4,6),
	(5,6),
	(7,6);

/*!40000 ALTER TABLE `sc_authority_to_users` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table sc_collect
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sc_collect`;

CREATE TABLE `sc_collect` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `name` varchar(100) NOT NULL DEFAULT '''''' COMMENT '名称',
  `description` varchar(200) DEFAULT '''''' COMMENT '详细描述',
  `rule` varchar(5000) NOT NULL DEFAULT '''''' COMMENT '规则序列化',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

LOCK TABLES `sc_collect` WRITE;
/*!40000 ALTER TABLE `sc_collect` DISABLE KEYS */;

INSERT INTO `sc_collect` (`id`, `name`, `description`, `rule`, `create_time`, `status`)
VALUES
	(1,'test','测试采集器','\'title\'=>[\'.head\', \'text\'],\r\n\'content\'=>[\'.content\',\'text\']',0,1),
	(2,'test2','阿斯顿发斯蒂芬','\'title\'=>[\'#aaa\',\'text\'],\r\n\'content\'=>[\'.aaa\',\'text\'],\r\n\'author\'=>[\'.tumblr\',\'alt\']',1482661964,1);

/*!40000 ALTER TABLE `sc_collect` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table sc_news
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sc_news`;

CREATE TABLE `sc_news` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` varchar(100) NOT NULL DEFAULT '''''' COMMENT '标题',
  `author` varchar(100) NOT NULL DEFAULT '''''' COMMENT '作者',
  `sort_id` int(11) NOT NULL DEFAULT '0' COMMENT '文章分类',
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '文章类型',
  `cover` varchar(300) DEFAULT '''''' COMMENT '封面',
  `source_url` varchar(300) DEFAULT '''''' COMMENT '原文地址',
  `release_time` int(11) NOT NULL DEFAULT '0' COMMENT '发布时间',
  `content` varchar(5000) NOT NULL DEFAULT '''''' COMMENT '文章内容',
  `read` int(11) NOT NULL DEFAULT '0' COMMENT '阅读量',
  `like` int(11) NOT NULL DEFAULT '0' COMMENT '点赞量',
  `unlike` int(11) NOT NULL DEFAULT '0' COMMENT '不喜欢',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `collect_id` int(11) NOT NULL DEFAULT '0' COMMENT '来源编号',
  `auditor` int(11) NOT NULL DEFAULT '0' COMMENT '审核人',
  `audit_time` int(11) NOT NULL DEFAULT '0' COMMENT '审核时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '显示状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

LOCK TABLES `sc_news` WRITE;
/*!40000 ALTER TABLE `sc_news` DISABLE KEYS */;

INSERT INTO `sc_news` (`id`, `title`, `author`, `sort_id`, `type`, `cover`, `source_url`, `release_time`, `content`, `read`, `like`, `unlike`, `create_time`, `collect_id`, `auditor`, `audit_time`, `status`)
VALUES
	(1,'1112','书丛2',9,2,'201612/2016122318450426.jpg,201612/2016122318450873.jpg,201612/2016122318451375.jpg','http://sdfasdf',0,'\'\'',0,0,0,1482489936,0,0,0,0),
	(2,'测试文章','书丛',8,2,'\"201612/2016122315192144.jpg\",\"201612/2016122315192859.jpg\",\"201612/2016122315193855.jpg\"','123123213123123',0,'\'\'',0,0,0,1482477581,0,0,0,0),
	(3,'test','书丛',17,2,'','http://sdfsdf',0,'\'\'',0,0,0,1482477684,0,0,0,0),
	(4,'asdfasdfasdf','书丛',12,2,'\"201612/2016122315230178.jpg\",\"201612/2016122315230638.jpg\",\"201612/2016122315231357.jpg\"','asdfasdf',0,'\'\'',0,0,0,1482477795,0,0,0,0),
	(5,'fsdfgsdfg','书丛',1,2,'\"201612/2016122317211819.jpg\",\"201612/2016122317213468.jpg\"','asdfasdf',0,'\'\'',0,0,0,1482484896,0,0,0,0),
	(6,'dddd','书丛',1,2,'\"201612/201612231723499.jpg\"','asd',0,'\'\'',0,0,0,1482485035,0,0,0,0),
	(7,'dfasdfasdf','书丛',15,2,'201612/2016122317252287.jpg','qwersdfasdf',0,'\'\'',0,0,0,1482485211,0,0,0,0),
	(8,'asdfasdf','书丛',9,2,'201612/2016122317523383.jpg,201612/2016122317523874.jpg,201612/2016122317524383.jpg','asdfasdf',0,'\'\'',0,0,0,1482486764,0,0,0,0),
	(9,'asdfasdf','书丛',1,2,'201612/2016122317582696.jpg,201612/201612231758303.jpg,201612/2016122317583433.jpg','sdfasdf',0,'\'\'',0,0,0,1482487115,0,0,0,0),
	(10,'asdfasdfasdf','书丛',1,2,'201612/2016122318033934.jpg,201612/2016122318112588.jpg,201612/2016122318115144.jpg','asdfasdfasdf',0,'\'\'',0,0,0,1482487941,0,0,0,0),
	(11,'是打发士大夫','书丛',1,2,'201612/2016122318125999.jpg','水电费',0,'\'\'',0,0,0,1482487996,0,0,0,0),
	(12,'阿斯顿发斯蒂芬ddd','书丛',1,2,'201612/2016122318155352.jpg,201612/201612231815585.jpg,201612/2016122318160372.jpg','阿斯蒂芬水电费',0,'\'\'',0,0,0,1482489267,0,0,0,0);

/*!40000 ALTER TABLE `sc_news` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table sc_reading_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sc_reading_log`;

CREATE TABLE `sc_reading_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(11) NOT NULL COMMENT '用户编号',
  `reading_log` varchar(1000) NOT NULL DEFAULT '' COMMENT '阅读记录',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;



# Dump of table sc_sort
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sc_sort`;

CREATE TABLE `sc_sort` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(50) NOT NULL DEFAULT '''''' COMMENT '名称',
  `short_name` varchar(10) NOT NULL DEFAULT '''''' COMMENT '短名称',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父级主键',
  `order` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `is_hot` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否热推',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

LOCK TABLES `sc_sort` WRITE;
/*!40000 ALTER TABLE `sc_sort` DISABLE KEYS */;

INSERT INTO `sc_sort` (`id`, `name`, `short_name`, `pid`, `order`, `is_hot`, `status`)
VALUES
	(1,'育儿','育儿',0,0,0,1),
	(2,'情感','情感',0,0,0,1),
	(7,'情感','情感',0,0,0,1),
	(8,'情感','情感',0,0,0,1),
	(9,'情感2','情感',2,0,0,1),
	(10,'情感','情感',1,0,0,1),
	(11,'情感','情感',0,0,1,1),
	(12,'情感','情感',0,100,0,1),
	(13,'情感','情感',0,0,0,1),
	(14,'情感','情感',0,0,0,1),
	(15,'情感','情感',0,0,0,1),
	(16,'情感','情感',0,0,0,1),
	(17,'情感','情感',0,0,0,1),
	(18,'情感','情感',0,0,0,1),
	(19,'情感','情感',0,0,0,1),
	(20,'情感','情感',0,0,0,1),
	(21,'情感','情感',0,0,0,1),
	(22,'情感','情感',0,0,0,1),
	(23,'情感','情感',0,0,0,1),
	(24,'情感','情感',0,0,0,1),
	(25,'情感','情感',0,0,0,1),
	(26,'情感','情感',2,0,0,1),
	(27,'情感','情感',2,0,0,1);

/*!40000 ALTER TABLE `sc_sort` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
