<?PHP
	include ('../config/setup.php');
	if (!$_POST['email'] || $_POST['submit'] !== "Submit")
	{
		header('HTTP/1.1 404 Not Found');
		header('Location: forget.html');
		return ;
	}

	$email = $db->quote($_POST['email']);
	$email_exist = $db->prepare("SELECT * FROM users WHERE email=:email");
	$email_exist->bindParam(':email', $email);
	$email_exist->execute();
	$token = sha1(uniqid(rand()));
	$token_quote = $db->quote($token);
	$id_token = $db->quote("0");
	$link = 'http://e3r3p15.42.fr:8080/camagru/client/views/test_reset.php?token='.$token;
	if ($email_exist->rowCount() != 0) {
		$stmt = $db->prepare("UPDATE users SET token=:token, id_token=:id_token WHERE email=:email");
		$stmt->bindParam(':token', $token_quote);
		$stmt->bindParam(':id_token', $id_token);
		$stmt->bindParam(':email', $email);
		$stmt->execute();
		mail( $_POST['email'] , "Modification du mot de passe" , "Bonjour ".$_POST['login'].", 
			cliqué sur ce lien pour modifier votre mot de passe : " . $link);
		header('Location: ../client/views/send_email.html');
	}
	else
	{
		header('HTTP/1.1 404 Not Found');
		header('Location: forget.php');
	}
?>