<?PHP
	include ('database.php');
	try {
		$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "CREATE DATABASE IF NOT EXISTS Camagru";
		$db->exec($sql);
		//`pub_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		$sql = "USE Camagru;
				CREATE TABLE `images` (`id` int(11) NOT NULL,`name` longtext NOT NULL,`user_id` int(11) NOT NULL,`pub_date` datetime NOT NULL,`like_count` int(11) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;
				CREATE TABLE `users` (`id` int(11) NOT NULL,`username` varchar(255) NOT NULL,`password` varchar(255) NOT NULL,`email` varchar(255) NOT NULL,`token` varchar(255),`id_token` varchar(255)) ENGINE=MyISAM DEFAULT CHARSET=utf8;
				CREATE TABLE `comments` (`id` int(11) NOT NULL,`content` longtext NOT NULL,`image_id` int(11) NOT NULL,`id_login` int(11) NOT NULL,`pub_date` datetime NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;
				ALTER TABLE `images` ADD PRIMARY KEY (`id`);
				ALTER TABLE `users` ADD PRIMARY KEY (`id`);
				ALTER TABLE `comments` ADD PRIMARY KEY (`id`);
				ALTER TABLE `images` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
				ALTER TABLE `users` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
				ALTER TABLE `comments` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";
		$db->exec($sql);
	}
	catch(PDOException $e)
	{
		echo $e->getMessage();
		die();
	}
?>