<?PHP
	include ('../config/setup.php');
	session_start();
	date_default_timezone_set('UTC');
	$today = date("Y-m-d H:i:s");
	$image = $db->quote($_POST["img_sav"]);
	$user = $_POST['login'];
	$like_count = '0';

	if (!empty($_FILES["upload"]["name"]))
	{
		$target_dir = "uploads/";
		$target_file = $target_dir . basename($_FILES["upload"]["name"]);
		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		$check = getimagesize($_FILES["upload"]["tmp_name"]);
		if($check !== false) {
			if (move_uploaded_file($_FILES["upload"]["tmp_name"], $target_file)) {
				$dest = imagecreatefromjpeg($target_file);
			}
		}
		else {
			$img = $_POST['img_sav'];
			$img = str_replace('data:image/jpeg;base64,', '', $img);
			$img = str_replace(' ', '+', $img);
			$destim = base64_decode($img);
			$dest = imagecreatefromstring($destim);
		}
	}
	else {
		$img = $_POST['img_sav'];
		$img = str_replace('data:image/jpeg;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$destim = base64_decode($img);
		$dest = imagecreatefromstring($destim);
	}
	$clip = imagecreatefrompng("../client/img/".$_POST['clip'].".png");
	if ($_POST['clip'] == "birthday")
		imagecopyresized($dest, $clip, 0, 5, 0, 0, 700, 300, imagesx($clip), imagesy($clip));
	else if ($_POST['clip'] == "canard")
		imagecopyresized($dest, $clip, 230, 250, 0, 0, 400, 450, imagesx($clip), imagesy($clip));
	else if ($_POST['clip'] == "obama")
		imagecopyresized($dest, $clip, 150, 95, 0, 0, 350, 450, imagesx($clip), imagesy($clip));
	else if ($_POST['clip'] == "chat")
		imagecopyresized($dest, $clip, 5, 300, 0, 0, 300, 300, imagesx($clip), imagesy($clip));
	else if ($_POST['clip'] == "uni-hat")
		imagecopyresized($dest, $clip, 150, 10, 0, 0, 350, 350, imagesx($clip), imagesy($clip));
	ob_start();
	imagejpeg($dest);
	$image_data = ob_get_contents ();
	ob_end_clean ();
	$link = "data:image/jpeg;base64,".base64_encode($image_data);
	
	$token = $db->prepare("SELECT * FROM users WHERE username=:user");
	$token->bindParam(':user', $user);
	$token->execute();
	$donnee = $token->fetch();
	$stmt = $db->prepare("INSERT INTO images (name, user_id, pub_date, like_count) VALUES (?, ?, ?, ?)");
	$stmt->bindParam(1, $link);
	$stmt->bindParam(2, $donnee["id"]);
	$stmt->bindParam(3, $today);
	$stmt->bindParam(4, $like_count);
	$stmt->execute();
	header('Location: ../index.php');
?>