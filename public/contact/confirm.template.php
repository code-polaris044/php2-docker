<?php
	if( basename($_SERVER['PHP_SELF']) === basename(__FILE__) ){
		require_once(dirname(__FILE__) . '/form/lib/functions.php');
		http_status(404);
	}
?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<title>入力内容確認 | お問い合わせ</title>
<meta name="keywords" content="">
<meta name="description" content="">
<link rel="stylesheet" href="./css/style.css">
</head>
<body id="top" class="inquiry">

<div id="Main">

	<h1>お問い合わせ</h1>

	<p>入力内容をご確認いただき、よろしければ送信ボタンをクリックしてください。</p>

	<?php if(Session::exists('form_error')): ?>
		<div class="message"><?php echo h(Session::flash('form_error')); ?></div>
	<?php endif; ?>

	<table>
		<tr>
			<th>お名前<span>【必須】</span></th>
			<td><div><?php echo h($input['name']['value']); ?></div></td>
		</tr>
		<tr>
			<th>ふりがな<span>【必須】</span></th>
			<td><div><?php echo h($input['kana']['value']); ?></div></td>
		</tr>
		<tr>
			<th>性別<span>【必須】</span></th>
			<td><div><?php echo h($input['sex']['value']); ?></div></td>
		</tr>
		<tr>
			<th>会社名<span>【任意】</span></th>
			<td><div><?php echo h($input['company']['value']); ?></div></td>
		</tr>
		<tr>
			<th>メールアドレス<span>【必須】</span></th>
			<td><div><?php echo h($input['email']['value']); ?></div></td>
		</tr>
		<tr>
			<th>住所<span>【必須】</span></th>
			<td>
				<div>
					〒<?php echo h($input['zipcode']['value']); ?><br>
					<?php echo h($input['prefecture']['value']); ?><?php echo h($input['address']['value']); ?>
				</div>
			</td>
		</tr>
		<tr>
			<th>電話番号<span>【任意】</span></th>
			<td><div><?php echo h($input['tel']['value']); ?></div></td>
		</tr>
		<tr>
			<th>お問い合わせ内容<span>【必須】</span></th>
			<td>
				<div>
					<?php foreach($input['subject']['value'] as $contact_item): ?>
						<?php echo h($contact_item) . '　'; ?>
					<?php endforeach; ?>
				</div>
			</td>
		</tr>
		<tr>
			<th>お問い合わせ内容の詳細<span>【必須】</span></th>
			<td><div><?php echo h($input['body']['value']); ?></div></td>
		</tr>
	</table>

	<div class="buttons">

		<form method="post" action="">

			<?php foreach($input as $key => $i): ?>
				<?php if(is_array($i['value'])): ?>
					<?php foreach($i['value'] as $value_detail): ?>
						<input type="hidden" name="<?php echo h($key) . '[]'; ?>" value="<?php echo h($value_detail); ?>">
					<?php endforeach; ?>
				<?php else: ?>
					<input type="hidden" name="<?php echo h($key); ?>" value="<?php echo h($i['value']); ?>">
				<?php endif; ?>
			<?php endforeach; ?>

			<input type="hidden" name="mode" value="submit">
			<input type="hidden" name="token" value="<?php echo h($token); ?>">

			<button type="button" onclick="history.go(-1);return false;">戻る</button>
			<button type="submit">送信</button>

		</form>

	</div>

</div>

</body>
</html>
