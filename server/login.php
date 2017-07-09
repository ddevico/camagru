<?PHP
	include ('../config/setup.php');
	session_start();
	if (isset($_POST['login']) && isset($_POST['passwd']))
	{
		$username = $db->quote($_POST['login']);
		$password = $db->quote(sha1($_POST['passwd']));
		$login = $db->prepare("SELECT * FROM users WHERE username=:username AND password=:password");
		$login->bindParam(':username', $username);
		$login->bindParam(':password', $password);
		$login->execute();
		if ($login->rowCount() > 0){
			$_SESSION['Auth'] = $username;
			header('Location: ../index.php');
			die();
		}
		else
		{
			header('HTTP/1.1 404 Not Found');
			header('Location: ../index.php');
			$_SESSION['Auth'] = "";
		}
	}
	else
	{
		//echo "Les champs sont incomplets";
		header('HTTP/1.1 404 Not Found');
		header('Location: ../index.php');
		exit;
	}
?>