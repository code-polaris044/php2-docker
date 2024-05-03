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


<?php phpinfo(); ?>
