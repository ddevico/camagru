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
					<link rel="stylesheet" href="../css/information.css"> 
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
								$id_img = $_GET["id"];
								$id_user_prep = $db->prepare("SELECT * FROM users WHERE username=:username");
								$id_user_prep->bindParam(':username', $_SESSION["Auth"]);
								$id_user_prep->execute();
								$id_user = $id_user_prep->fetchAll(PDO::FETCH_ASSOC);
								$img_prep = $db->prepare("SELECT * FROM images WHERE id=:id");
								$img_prep->bindParam(':id', $id_img);
								$img_prep->execute();
								$img = $img_prep->fetchAll(PDO::FETCH_ASSOC);
								$image_final = str_replace("'", '', $img[0]["name"]);
							?>
							<img src="<?php echo $image_final?>" class="image_final"/>
							<form action="../../server/like_comment.php" method="post" class="like_comment">
								<input hidden name="id" value="<?php echo $img[0]["id"]?>"/>
								<input hidden name="login" value="<?php echo $_SESSION['Auth'];?>"/>
								<?PHP
								if (isset($_GET["like"])) {
								?>
									<input type="submit" name="unlike" value="Unlike" class="like"/> <?PHP echo $img[0]["like_count"]; ?>
								<?php
								}else {?>
									<input type="submit" name="like" value="Like" class="like"/> <?PHP echo $img[0]["like_count"]; ?>
								<?PHP
								}
								if ($img[0]["user_id"] == $id_user[0]["id"]) {?>
									<a href="../../server/delete.php?id_img=<?php echo $img[0]["id"]?>" id="square">
										<img src="../img/corbeille.png" class="delete" width="5%" style="margin-left: 10%"/>
									</a>
								<?PHP } ?>
								<br><br>
								<input style="margin-left: -3%;height: 5%;width: 25%;"  type="text" name="comment_text" placeholder="Commenter" class="comment" autocomplete="off"/>
								<input type="submit" name="comment" value="Commenter" class="comment"/>
							</form>
						</div>
						<div class="information">
							<p>Commentaire :</p>
							<HR size=2 align=center width="100%">
							<?PHP
								$comment = $db->prepare("SELECT * FROM comments WHERE image_id=:image_id ORDER BY id ");
								$comment->bindParam(':image_id', $img[0]["id"]);
								$comment->execute();
								$data = $comment->fetchAll(PDO::FETCH_ASSOC);

								$max_images = 10;
								$current_page = (!isset($_GET['page']) || empty($_GET['page'])) ? 1 : $_GET['page'];
								$nb_pages = ceil(($comment->rowcount()) / $max_images); 
								$get_id = $_GET["id"];
								$get_userid = $_GET["user_id"];
								for($i = ($current_page - 1) * $max_images; $i < ($current_page - 1) * $max_images + $max_images; $i++) {
									if($i < $comment->rowcount()) {
										$code = str_replace("'", '', $data[$i]["content"]);
										$smtp = $db->prepare("SELECT * FROM users WHERE id=:id");
										$smtp->bindParam(':id', $data[$i]["id_login"]);
										$smtp->execute();
										$data_id = $smtp->fetchAll(PDO::FETCH_ASSOC);
										$username = str_replace("'", '', $data_id[0]["username"]);
										if (!empty($code)) {
										?>
										<p>
											<?PHP echo $username.": ".$code;?>
										</p>
										<?PHP }
									}
								}
								for ($i = 1 ; $i <= $nb_pages ; $i++) {
									echo '<div class="pagination" style="margin-left:50%;"><a href="'.$_SERVER['PHP_SELF'].'?page='.$i.'&id='.$get_id.'&user_id='.$get_userid.'">' . $i . '</a></div>';
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