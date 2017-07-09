<?PHP
	include ('../../config/setup.php');
	session_start();
	if ($_SESSION["Auth"] == "" || !$_SESSION["Auth"])
		header('Location: ../../client/views/sign_in.html');
	else
	{
		?>
			<html>
				<head>
					<link rel="stylesheet" href="../css/galerie.css"> 
				</head>
				<body>
				<div class="title-page">
					<a href="../../index.php">CAMAGRU</a>
				</div>
					<div class="menu">
						<ul>
							<li style="float:right"><a class="active" href="../../server/logout.php">Déconnexion</a></li>
							<li style="float:right"><a href="galerie.php">Galerie</a></li>
						</ul>
					</div>
					<div class="countainer-box">
						<div class="image-box">
						<?PHP
							$like_prepare = $db->prepare("SELECT like_count as like_count FROM images");
							$like_prepare->execute();
							$like = $like_prepare->fetchAll(PDO::FETCH_ASSOC);
							$img_id = $db->prepare("SELECT * FROM images order by pub_date desc");
							$img_id->execute();
							$data2 = $img_id->fetchAll(PDO::FETCH_ASSOC);

							$max_images = 8;
							$current_page = (!isset($_GET['page']) || empty($_GET['page'])) ? 1 : $_GET['page'];
							$nb_pages = ceil(($img_id->rowcount()) / $max_images); 

							for($i = ($current_page - 1) * $max_images; $i < ($current_page - 1) * $max_images + $max_images; $i++) {
								if($i < $img_id->rowcount()) {
									$code = str_replace("'", '', $data2[$i]["name"]);
									?>
									<div class="img">
										<a href="information.php?id=<?php echo $data2[$i]["id"]?>&user_id=<?php echo $data2[$i]["user_id"]?>" id="square">
											<img src="<?php echo $code?>" class="image_rec"/>
										</a>
										<div class="like-comment">
										<form action="../../server/like_comment.php" method="post">
											<input hidden name="id" value="<?php echo $data2[$i]["id"]?>"/>
											<input hidden name="login" value="<?php echo $_SESSION['Auth'];?>"/>
											<input type="submit" name="like" value="Like" class="like"/>
											<input type="submit" name="comment" value="Commenter" class="comment"/>
										</form>
										</div>
									</div>
									<?PHP
								}
							}
							?>
						</div>
						<div class="pagination-box">
							<?PHP
								for ($i = 1 ; $i <= $nb_pages ; $i++) {
									echo '<div class="pagination"><a href="'.$_SERVER['PHP_SELF'].'?page=' . $i . '">' . $i . '</a></div>';
								}
							?>
						</div>
					</div>
					<div class="footer-box">
						<p style="text-align: right; font-style: italic; font-family: monospace; color:white; font-size:15">© ddevico 2016</p>
					</div>
				</body>
			</html>
		<?PHP
	}
?>