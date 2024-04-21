<html>
<body>
<?php
// セッションを開始する
session_start();
if (!isset($_POST["order"])) {
// 選択されていないとき。削除する
unset($_SESSION["order"]);
} else {
// 選択されているとき
$ordervalue = $_POST["order"];
$_SESSION["order"] = $ordervalue;
}
?>
商品を設定しました。<br>
<a href="example7-2-2.php">決済ページへ</a>
</body>
</html>
