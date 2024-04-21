<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Document</title>
	</head>
	<body>



		<?php echo 'こんにちは' . htmlspecialchars($_POST["username"]) . "さん"; ?><br>

	
	<?php 
	if(isset($_POST["val1"]) && isset($_POST["val2"])) {

		$val1 = intval($_POST["val1"]);
		$val2 = intval($_POST["val2"]);
		$resule = $val1 + $val2;
		echo $val1 . "+" . $val2 . "=" . $resule;
		var_dump($_POST["val1"]);
		var_dump($val1);
	} else {
		echo "値を入力してください <br>";
		echo "<a href='index.php'>入力フォームへ</a>";
	}
	?>
 	<br>

	<?php 
	$h = array(
		"apple" => "りんご",
		"banana" => "ばなな",
		"orange" => "みかん",
		"tomato" => "とまと",
	);

	$key = $_POST["english"];
	if( array_key_exists($key, $h) ) {
		echo $h[$key];
	} else {
		echo $key . "は、登録されていません";
	}
	?>

	 <br>

	<?php 
	$yubin = $_POST["yubin"];
	if (preg_match("/^\\d{3}-?\\d{4}$/", $yubin)) {
		echo "正しい書式です";
	} else {
		echo "書式が正しくありません";
	}
	?>

		 <br>

	<?php 
	$email = $_POST["email"];
	if (preg_match("/^([a-zA-Z0-9\._\-]+)@([a-zA-Z0-9\._\-]+\.[a-zA-Z0-9\._\-]+)$/", $email, $matches)) {
		// if (preg_match("/^([a-zA-Z0-9\._\-]+)@([a-zA-Z0-9\._\-]+)\.[a-zA-Z0-9\._\-]+/", $email, $matches)) {
		$username = $matches[1];
		$domain = $matches[2];
		
		echo "ユーザー名:" . $username . "<br>";
		echo "ユーザー名:" . $domain . "<br>";
	} else {
		echo "書式が正しくありません";
	}
	?>



	</body>
</html>
