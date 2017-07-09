<?PHP
	include ('../config/setup.php');
	if (!$_POST['login'] || !$_POST['passwd'] || !$_POST['passwd2'] || !$_POST['email'] || $_POST['register'] !== "Register")
	{
		echo "ERROR\n";
		return ;
	}
	if ($_POST['passwd'] != $_POST['passwd2'])
	{
		echo "Erreur, les 2 mots de passes sont differents";
		return ;
	}
	$username = $db->quote($_POST['login']);
	$email = $db->quote($_POST['email']);
	$password = $db->quote(sha1($_POST['passwd']));
	$name_exist = $db->query("SELECT * FROM users WHERE username=$username");
	$email_exist = $db->query("SELECT * FROM users WHERE email=$email");
	$exist = $name_exist->rowCount() + $email_exist->rowCount();
	if ($exist == 0) {
		$stmt = $db->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
		$stmt->bindParam(1, $username);
		$stmt->bindParam(2, $password);
		$stmt->bindParam(3, $email);
		$stmt->execute();
		mail( $_POST['email'] , "Nouvel Utilisateur" , "Bienvenue ".$_POST['login'].", Vous vous etes bien enregistré sur Camagru !" );
		header('Location: ../client/views/sign_in.html');
	} 
	else
	{
		if ($email_exist->rowCount() != 0)
			echo "Erreur, l'email exite deja";
		if ($name_exist->rowCount() != 0)
			echo "Erreur, l'utilisateur exite deja";
	}
?>