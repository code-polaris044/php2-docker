<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<?php
const HOST_NAME = 'mysql_test';
const HOST_PORT = '3306';
const DB_NAME   = 'testdb';
const USER_NAME = 'testuser';
const PASSWORD  = '1234567890';

$db_settings = [
	'host='.HOST_NAME,
	'port='.HOST_PORT,
	'dbname='.DB_NAME,
	'charset=utf8'
];

try {
	$db = new PDO('mysql:'.implode(';', $db_settings), USER_NAME, PASSWORD);
	echo '接続成功';
} catch (PDOException $e) {
	echo '接続失敗：'.$e->getMessage();
}
?>

<?php


$link =mysqli_connect("localhost:55011", "testuser", "1234567890", "testdb");


if (!$link) {
    die('接続失敗: ' . mysqli_error());
} else {
    echo '接続成功';
}


// mysqli_set_charset($link, "utf8");

// mysqli_query($link, "INSERT INTO address (username, zip, address1,
// address2, tel) " .
// "VALUES ('田中 次郎', '227-0062', '神奈川県', '横浜市青葉台1-2-3', '045-
// 678-9012');");

// mysqli_close($link);
// echo "レコードを追加しました";
?>
</body>
</html>
