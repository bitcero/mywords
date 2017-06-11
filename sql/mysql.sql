CREATE TABLE `mod_mywords_bookmarks` (
  `id_book` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(60) NOT NULL,
  `alt` varchar(150) NOT NULL,
  `url` varchar(255) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_book`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `mod_mywords_bookmarks` (`id_book`, `title`, `alt`, `url`, `icon`, `active`) VALUES
(1, 'BlinkList.com', 'Agregar a BlinkList.com!', 'http://blinklist.com/blink?u={URL}&t={TITLE}&d={DESC}', 'blinklist.png', 1),
(2, 'Delicious', 'Add to Del.icio.us!', 'http://delicious.com/save?jump=yes&url={URL}&title={TITLE}&notes={DESC}', 'delicious.png', 1),
(3, 'Digg', 'Digg It!', 'http://digg.com/submit?phase=2&url={URL}&title={TITLE}', 'digg.png', 1),
(5, 'FaceBook', 'Add to FaceBook!', 'http://www.facebook.com/share.php?u={URL}&t={TITLE}', 'facebook.png', 1),
(6, 'Furl', 'Add to Furl!', 'http://www.furl.net/items/new?t={TITLE}&u={URL}&r=&v=1&c=', 'furl.png', 1),
(8, 'Reddit.com', 'Agregar a Reddit!', 'http://www.reddit.com/submit?url={URL}&title={TITLE}', 'reddit.png', 1),
(10, 'StumbleUpon', 'Agregar a StumbleUpon!', 'http://www.stumbleupon.com/submit?url={URL}&title={TITLE}', 'stumbleupon.png', 1),
(11, 'Twitter', 'Tweet on Twitter', 'https://twitter.com/intent/tweet?text={URL}', 'twitter.png', 1),
(12, 'LinkedIn', 'Share on Linked In', 'http://www.linkedin.com/shareArticle?summary={DESC}&url={URL}', 'linkedin.png', 1),
(13, 'Google', 'Add to Google Bookmarks', 'https://www.google.com/bookmarks/mark?op=add&bkmk={URL}&title={TITLE}&annotation={DESC}', 'google.png', 1),
(14, 'Yahoo! Bookmarks', 'Add to Yahoo! Bookmarks', 'http://bookmarks.yahoo.com/toolbar/SaveBM/?opener=tb&u={URL}&d={DESC}&t={TITLE}', 'yahoo.png', 1),
(15, 'Tuenti', 'Share on Tuenti', 'http://www.tuenti.com/share?url={URL}', 'tuenti.png', 1);


CREATE TABLE `mod_mywords_categories` (
  `id_cat` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `shortname` varchar(150) NOT NULL,
  `parent` int(11) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `posts` int(11) NOT NULL,
  PRIMARY KEY (`id_cat`),
  KEY `shortname` (`shortname`),
  KEY `parent` (`parent`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `mod_mywords_catpost` (
  `post` int(11) NOT NULL,
  `cat` int(11) NOT NULL,
  KEY `post` (`post`),
  KEY `cat` (`cat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `mod_mywords_editors` (
  `id_editor` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `shortname` varchar(150) NOT NULL,
  `bio` text NOT NULL,
  `privileges` text NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_editor`),
  UNIQUE KEY `uid` (`uid`),
  KEY `shortname` (`shortname`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `mod_mywords_meta` (
  `id_meta` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` text NOT NULL,
  `post` int(11) NOT NULL,
  PRIMARY KEY (`id_meta`),
  KEY `name` (`name`,`post`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `mod_mywords_posts` (
  `id_post` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `shortname` varchar(200) NOT NULL,
  `content` longtext NOT NULL,
  `status` varchar(20) NOT NULL,
  `visibility` varchar(20) NOT NULL,
  `schedule` int(10) NOT NULL,
  `password` varchar(50) NOT NULL,
  `comments` int(11) NOT NULL,
  `author` int(11) NOT NULL,
  `comstatus` varchar(10) NOT NULL,
  `pingstatus` varchar(10) NOT NULL,
  `authorname` varchar(50) NOT NULL,
  `pubdate` int(10) NOT NULL,
  `created` int(10) NOT NULL,
  `reads` int(11) NOT NULL,
  `toping` text NOT NULL,
  `pinged` text NOT NULL,
  `image` text NOT NULL,
  `video` text NOT NULL,
  `description` text NOT NULL,
  `keywords` text NOT NULL,
  `customtitle` varchar(255) NOT NULL,
  `format` varchar(10) NOT NULL DEFAULT 'post',
  PRIMARY KEY (`id_post`),
  KEY `shortname` (`shortname`),
  KEY `format` (`format`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `mod_mywords_tags` (
  `id_tag` bigint(20) NOT NULL AUTO_INCREMENT,
  `tag` varchar(60) NOT NULL,
  `shortname` varchar(60) NOT NULL,
  `posts` int(11) NOT NULL,
  PRIMARY KEY (`id_tag`),
  KEY `shortname` (`shortname`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `mod_mywords_tagspost` (
  `post` int(11) NOT NULL,
  `tag` int(11) NOT NULL,
  KEY `post` (`post`,`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `mod_mywords_trackbacks` (
  `id_t` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `blog_name` varchar(150) NOT NULL,
  `excerpt` text NOT NULL,
  `url` varchar(255) NOT NULL,
  `post` int(11) NOT NULL,
  PRIMARY KEY (`id_t`),
  KEY `post` (`post`),
  KEY `url` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `mod_mywords_reports` (
  `id_report` int(11) NOT NULL,
  `post` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `when` datetime NOT NULL,
  `title` varchar(60) NOT NULL,
  `content` text NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'waiting'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `mod_mywords_reports`
  ADD UNIQUE KEY `id_report` (`id_report`),
  ADD KEY `post` (`post`),
  ADD KEY `user` (`user`);


ALTER TABLE `mod_mywords_reports`
  MODIFY `id_report` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `mod_mywords_reports`
  ADD CONSTRAINT `mod_mywords_reports_ibfk_1` FOREIGN KEY (`id_report`) REFERENCES `mod_mywords_posts` (`id_post`) ON DELETE CASCADE;