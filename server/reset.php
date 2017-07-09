<?PHP
	include ('../config/setup.php');
	if (!$_POST['login'] || !$_POST['passwd'] || !$_POST['passwd2'] || $_POST['submit'] !== "Submit")
	{
		header('HTTP/1.1 404 Not Found');
		header('Location: ../client/views/test_reset.php?token='.$_GET['token']);
		exit;
	}
	if ($_POST['passwd'] != $_POST['passwd2'])
	{
		header('HTTP/1.1 404 Not Found');
		header('Location: ../client/views/test_reset.php?token='.$_GET['token']);
		exit;
	}

	$username = $db->quote($_POST['login']);
	$token = $db->quote($_GET['token']);
	$id_token = $db->quote("1");
	$password = $db->quote(sha1($_POST['passwd']));
	$name_exist = $db->prepare("SELECT * FROM users WHERE username=:username AND token=:token");
	$name_exist->bindParam(':username', $username);
	$name_exist->bindParam(':token', $token);
	$name_exist->execute();
	if ($name_exist->rowCount() != 0) {
		$stmt = $db->prepare("UPDATE users SET password=:password, id_token=:id_token WHERE username=:username");
		$stmt->bindParam(':password', $password);
		$stmt->bindParam(':id_token', $id_token);
		$stmt->bindParam(':username', $username);
		$stmt->execute();
		header('Location: ../client/views/sign_in.html');
	}
	else
	{
		header('HTTP/1.1 404 Not Found');
		header('Location: ../client/views/test_reset.php?token='.$_GET['token']);
		exit;
	}
?>