<?PHP
	session_start();
	$_SESSION["Auth"] = "";
	header('Location: ../client/views/sign_in.html');
?>