<?PHP
	include ('./config/setup.php');
	session_start();
	if ($_SESSION["Auth"] == "" || !$_SESSION["Auth"])
		header('Location: client/views/sign_in.html');
	else
	{
		?>
			<html>
				<head>
					<link rel="stylesheet" href="client/css/index.css">
				</head>
				<body onload="init();">
				<div class="title-page">
					<a href="index.php">CAMAGRU</a>
				</div>
					<div class="menu">
						<ul>
							<li style="float:right"><a class="active" href="server/logout.php">Déconnexion</a></li>
							<li style="float:right"><a href="client/views/galerie.php">Galerie</a></li>
						</ul>
					</div>
					<div class="countainer-box">
						<div class="image-box">
							<div class="tmp">
								<img src="" style="" class="tricky" id="img"/>
							</div>
							<video id="video" class="liveVideo" autoplay></video>
							<button id="startbutton" disabled>Take Photo</button>
							<div class="filtre">
								<div class="img">
									<a target="_blank">
									<img src="client/img/canard.png" alt="Fjords" width="300" height="200" onclick="add('canard');">
									</a>
								</div>
								<div class="img">
									<button target="_blank">
									<img src="client/img/birthday.png" onclick="add('birthday');">
									</button>
								</div>
								<div class="img">
									<a target="_blank">
									<img src="client/img/obama.png" alt="Northern Lights" width="300" height="200" onclick="add('obama');">
									</a>
								</div>
								<div class="img">
									<a target="_blank">
									<img src="client/img/chat.png" alt="Mountains" width="300" height="200" onclick="add('chat');">
									</a>
								</div>
								<div class="img">
									<a target="_blank">
									<img src="client/img/uni-hat.png" alt="Mountains" width="300" height="200" onclick="add('uni-hat');">
									</a>
								</div>
							</div>
						</div>
						<div class="right-box">
							<form action="server/recpicture.php" name="uploadphoto" method="post" style="display:inline-table;" enctype="multipart/form-data">
								<canvas hidden id="canvas"></canvas>
								<input name="img_sav" id="toto" hidden/>
								<input name="login" value="<?php echo $_SESSION['Auth']?>" hidden/>
								<input name="clip"  id="clipprep" hidden/>
								<input name="upload" type="file" accept="image/jpeg" style="display:inline;"/>
								<?PHP
									$token = $db->prepare("SELECT * FROM users WHERE username=:user");
									$token->bindParam(':user', $_SESSION['Auth']);
									$token->execute();
									$data1 = $token->fetch();
									$img_id = $db->prepare("SELECT * FROM images WHERE user_id=:user_id order by pub_date desc");
									$img_id->bindParam(':user_id', $data1["id"]);
									$img_id->execute();
									$data2 = $img_id->fetchAll(PDO::FETCH_ASSOC);
									for($i = 0; $i < 3; $i++)
									{
										$code = str_replace("'", '', $data2[$i]["name"]);
										?>
										<a href="client/views/information.php?id=<?php echo $data2[$i]["id"]?>&user_id=<?php echo $data2[$i]["user_id"]?>" id="square">
											<img src="<?php echo $code?>" class="image_rec" style="width: 60%; height: 150px;"/>
										</a>
										<?PHP
									}
									?>
							</form>
							<?PHP
								if ($img_id->rowcount() > 3)
								{
									?>
									<form action="client/views/galerie.php" method="POST">
										<input type="submit" value="See More" class="seemore">
									</form>
									<?PHP
								}
							?>
						</div>
					</div>
					<div class="footer-box">
						<p style="text-align: right; font-style: italic; font-family: monospace; color:white; font-size:15">© ddevico 2016</p>
					</div>
					<script type="text/javascript" src="js/webcam.js"></script>
					<script type="text/javascript" src="js/add_img.js"></script>
				</body>
			</html>
		<?PHP
	}
?>