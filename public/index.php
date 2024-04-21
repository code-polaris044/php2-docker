<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>



    <form action="test.php" method="post">
        氏名: <input type="text" name="username" >
        <input type="submit" value="送信">
    </form>

    <?php echo date("Y-m-d H-i-s"); ?>

    <form action="test.php" method="post">
        数字1: <input type="text" name="val1"><br>
        数字2: <input type="text" name="val2">
        <input type="submit" value="結果">
    </form>

    <?php 
    $a = array("りんご","みかん","ぶどう","ばなな","こんにゃく");

    for($i = 0; $i < count($a); $i++) {
    echo $a[$i] . "<br>";
    }

    ?>

<form action="test.php" method="post">
    <input type="text" name="english"><br>
    <input type="submit" value="変換">
</form>


<form action="test.php" method="post">
    郵便番号を入力してください: 
    <input type="text" name="yubin" id=""><br>
    <input type="submit" value="チェック">
</form>


<form action="test.php" method="post">
    メールアドレスを入力してください: 
    <input type="text" name="email" id=""><br>
    <input type="submit" value="チェック">
</form>




</body>
</html>
