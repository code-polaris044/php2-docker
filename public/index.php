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
</body>
</html>
