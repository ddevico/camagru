<?PHP
	include ('../../config/setup.php');
	$token_recup = $db->quote($_GET['token']);
	$id_token = $db->quote("0");
	$email_exist = $db->prepare("SELECT * FROM users WHERE token=:token_recup AND id_token=:id_token");
	$email_exist->bindParam(':token_recup', $token_recup);
	$email_exist->bindParam(':id_token', $id_token);
	$email_exist->execute();
	if ($email_exist->rowCount() != 0)
	{
		?>
		<html>
			<head>
				<meta charset="utf-8">
				<link href="../css/index.css" rel="stylesheet" media="all" type="text/css">
				<title>CAMAGRU</title>
			</head>
			<body>
				<section>
					<div class="form-container">
						<div class="form-inner">
							<div class="info">
								<p class="sign-in">CHANGE PASSWORD</p>
							</div>
							<form action=<?PHP echo "../../server/reset.php?token=".$_GET['token'] ?> method="POST">
								<div class="sign-in-input">
									<div class="input-contain">
										<div class="warp">
											<input type="text" placeholder="login" name="login"/>
										</div>
										<div class="warp">
											<input type="password" placeholder="Password" class="tabIgnore" name="passwd"/>
										</div>
										<div class="warp">
											<input type="password" placeholder="Confirm Passsword" class="tabIgnore" name="passwd2"/>
										</div>
									</div>
								</div>
								<input style="margin-top: 2%" class="mention-info" type="submit" name="submit" value="Submit"></input>
							</form>
						</div>
					</div>
				</section>
			</body>
			</html>
			<?PHP
	}
	else
	{
		echo "Error, Page Not Found";
		return ;
	}
?>