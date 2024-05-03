<html>
<body>
決済ページ
<?php
// セッションを開始する
session_start();
if ((!isset($_SESSION["order"])) || ($_SESSION["order"] == "")) {
echo "商品が選択されていません";
} else {
$ordervalue = $_SESSION["order"];
echo $ordervalue . "の購入手続きを進めます";
}
?>
<br>
<a href="example7-2-1.html">商品を選択し直す</a>
</body>
</html>
