<?PHP
	include ('../config/setup.php');
	session_start();
	$id_img = $_GET["id_img"];

	$id_login_prep = $db->prepare("DELETE FROM images WHERE id=:id_img ");
	$id_login_prep->bindParam(':id_img', $id_img);
	$id_login_prep->execute();
	$comment_prep = $db->prepare("DELETE FROM comments WHERE image_id=:image_id ");
	$comment_prep->bindParam(':image_id', $id_img);
	$comment_prep->execute();
	header('Location: ../client/views/galerie.php');
?>