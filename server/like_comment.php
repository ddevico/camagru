<?PHP
	include ('../config/setup.php');
	session_start();
	date_default_timezone_set('UTC');
	$today = date("Y-m-d H:i:s");
	$user = $_POST['login'];
	$id_img = $_POST["id"];
	$id_login_prep = $db->prepare("SELECT * FROM users WHERE username=:user");
	$id_login_prep->bindParam(':user', $user);
	$id_login_prep->execute();
	$id_login = $id_login_prep->fetchAll(PDO::FETCH_ASSOC);
	$like_count_prep = $db->prepare("SELECT * FROM images WHERE id=:id");
	$like_count_prep->bindParam(':id', $id_img);
	$like_count_prep->execute();
	$like_count = $like_count_prep->fetchAll(PDO::FETCH_ASSOC);
	if (isset($_POST["like"]))
	{
		$like_count[0]["like_count"]++;
		$stmt = $db->prepare("UPDATE images SET like_count=:like_count WHERE id=:id");
		$stmt->bindParam(':like_count', $like_count[0]["like_count"]);
		$stmt->bindParam(':id', $id_img);
		$stmt->execute();
		header('Location: ../client/views/information.php?id='.$id_img.'&user_id='.$id_login[0]['id'].'&like=1');
	}
	else if (isset($_POST["unlike"]))
	{
		$like_count[0]["like_count"]--;
		$stmt = $db->prepare("UPDATE images SET like_count=:like_count WHERE id=:id");
		$stmt->bindParam(':like_count', $like_count[0]["like_count"]);
		$stmt->bindParam(':id', $id_img);
		$stmt->execute();
		header('Location: ../client/views/information.php?id='.$id_img.'&user_id='.$id_login[0]['id']);
	}
	else
	{
		$comment = $_POST["comment_text"];
		$comment_quote = $db->quote($comment);
		$stmt = $db->prepare("INSERT INTO comments (image_id, content, id_login, pub_date) VALUES (?, ?, ?, ?)");
		$stmt->bindParam(1, $id_img);
		$stmt->bindParam(2, $comment_quote);
		$stmt->bindParam(3, $id_login[0]["id"]);
		$stmt->bindParam(4, $today);
		$stmt->execute();
		$id_login_new = $db->prepare("SELECT * FROM users WHERE id=:id");
		$id_login_new->bindParam(':id', $like_count[0]["user_id"]);
		$id_login_new->execute();
		$email_new = $id_login_new->fetchAll(PDO::FETCH_ASSOC);
		$email = str_replace("'", '', $email_new[0]["email"]);
		$link = "http://e3r3p15.42.fr:8080/camagru/client/views/information.php?id=".$id_img."&user_id=".$id_login[0]['id'];
		if ($id_login[0]['id'] !== $like_count[0]["user_id"])
			mail( $email , "Nouveau Commentaire !" , "Bonjour ".$email_new[0]["username"].", Vous avez recu un nouveau commentaire pour votre photo ! ".$link);
		header('Location: ../client/views/information.php?id='.$id_img.'&user_id='.$id_login[0]['id']);
	}
?>