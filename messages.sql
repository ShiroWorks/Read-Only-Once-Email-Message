CREATE TABLE `messages`
(
  `id` int
(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar
(255) DEFAULT NULL,
  `message` text,
  PRIMARY KEY
(`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;
