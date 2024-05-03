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
<title>お問い合わせ</title>
<meta name="keywords" content="">
<meta name="description" content="">
<link rel="stylesheet" href="./css/style.css">
</head>
<body id="top" class="inquiry">

<div id="Main">

	<h1>お問い合わせ</h1>

	<p>以下の項目をご記入いただき、確認ボタンをクリックしてください。</p>

	<form method="post" action="" novalidate>

		<?php if(Session::exists('form_error')): ?>
			<div class="message"><?php echo h(Session::flash('form_error')); ?></div>
		<?php endif; ?>

		<table>
			<tr>
				<th>
					<?php $name = 'name'; ?>
					<label for="input-<?php echo h($name); ?>">お名前<span>【必須】</span></label>
				</th>
				<td>
					<?php if($input[$name]['error']): ?><div class="error"><?php echo nl2br(h(implode("\n", $input[$name]['error']))); ?></div><?php endif; ?>
					<input type="text" name="<?php echo h($name); ?>" value="<?php echo h($input[$name]['value']); ?>" id="input-<?php echo h($name); ?>">
				</td>
			</tr>
			<tr>
				<th>
					<?php $name = 'kana'; ?>
					<label for="input-<?php echo h($name); ?>">ふりがな<span>【必須】</span></label>
				</th>
				<td>
					<?php if($input[$name]['error']): ?><div class="error"><?php echo nl2br(h(implode("\n", $input[$name]['error']))); ?></div><?php endif; ?>
					<input type="text" name="<?php echo h($name); ?>" value="<?php echo h($input[$name]['value']); ?>" id="input-<?php echo h($name); ?>">
				</td>
			</tr>
			<tr>
				<th>
					<?php $name = 'sex'; ?>
					性別<span>【必須】</span>
				</th>
				<td>
					<?php if($input[$name]['error']): ?><div class="error"><?php echo nl2br(h(implode("\n", $input[$name]['error']))); ?></div><?php endif; ?>
					<?php foreach($input[$name]['options'] as $option): ?>
						<label>
							<input type="radio" name="<?php echo h($name); ?>" value="<?php echo h($option); ?>" <?php echo h(($input[$name]['value']===$option)? 'checked': ''); ?>>
							<span><?php echo h($option); ?></span>
						</label>
					<?php endforeach; ?>
				</td>
			</tr>
			<tr>
				<th>
					<?php $name = 'company'; ?>
					<label for="input-<?php echo h($name); ?>">会社名<span>【任意】</span></label>
				</th>
				<td>
					<?php if($input[$name]['error']): ?><div class="error"><?php echo nl2br(h(implode("\n", $input[$name]['error']))); ?></div><?php endif; ?>
					<input type="text" name="<?php echo h($name); ?>" value="<?php echo h($input[$name]['value']); ?>" id="input-<?php echo h($name); ?>">
				</td>
			</tr>
			<tr>
				<th>
					<?php $name = 'email'; ?>
					<label for="input-<?php echo h($name); ?>">メールアドレス<span>【必須】</span></label>
				</th>
				<td>
					<?php if($input[$name]['error']): ?><div class="error"><?php echo nl2br(h(implode("\n", $input[$name]['error']))); ?></div><?php endif; ?>
					<input type="email" name="<?php echo h($name); ?>" value="<?php echo h($input[$name]['value']); ?>" id="input-<?php echo h($name); ?>">
				</td>
			</tr>
			<tr>
				<th>
					<?php $name = 'email_confirm'; ?>
					<label for="input-<?php echo h($name); ?>">メールアドレス(確認)<span>【必須】</span></label>
				</th>
				<td>
					<?php if($input[$name]['error']): ?><div class="error"><?php echo nl2br(h(implode("\n", $input[$name]['error']))); ?></div><?php endif; ?>
					<input type="email" name="<?php echo h($name); ?>" value="<?php echo h($input[$name]['value']); ?>" id="input-<?php echo h($name); ?>">
				</td>
			</tr>
			<tr>
				<th>
					住所<span>【必須】</span>
				</th>
				<td>

					<div>
						<?php $name = 'zipcode'; ?>
						<?php if($input[$name]['error']): ?><div class="error"><?php echo nl2br(h(implode("\n", $input[$name]['error']))); ?></div><?php endif; ?>
						〒<input type="tel" name="<?php echo h($name); ?>" value="<?php echo h($input[$name]['value']); ?>" maxlength="8" size="9">
					</div>

					<div>
						<?php $name = 'prefecture'; ?>
						<?php if($input[$name]['error']): ?><div class="error"><?php echo nl2br(h(implode("\n", $input[$name]['error']))); ?></div><?php endif; ?>
						<select name="<?php echo h($name); ?>">
							<option value="">都道府県</option>
							<?php foreach($input[$name]['options'] as $value): ?>
								<option value="<?php echo $value?>"<?php echo $value===$input[$name]['value']? ' selected': '' ?>><?php echo $value?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div>
						<?php $name = 'address'; ?>
						<?php if($input[$name]['error']): ?><div class="error"><?php echo nl2br(h(implode("\n", $input[$name]['error']))); ?></div><?php endif; ?>
						<input type="text" name="<?php echo h($name); ?>" value="<?php echo h($input[$name]['value']); ?>">
					</div>

				</td>
			</tr>
			<tr>
				<th>
					<?php $name = 'tel'; ?>
					<label for="input-<?php echo h($name); ?>">電話番号<span>【任意】</span></label>
				</th>
				<td>
					<?php if($input[$name]['error']): ?><div class="error"><?php echo nl2br(h(implode("\n", $input[$name]['error']))); ?></div><?php endif; ?>
					<input type="tel" name="<?php echo h($name); ?>" value="<?php echo h($input[$name]['value']); ?>" id="input-<?php echo h($name); ?>">
				</td>
			</tr>
			<tr>
				<th>
					<?php $name = 'subject'; ?>
					<label>お問い合わせ内容<span>【必須】</span></label>
				</th>
				<td>
					<?php if($input[$name]['error']): ?><div class="error"><?php echo nl2br(h(implode("\n", $input[$name]['error']))); ?></div><?php endif; ?>
					<?php foreach($input[$name]['options'] as $option): ?>
						<label>
							<input type="checkbox" name="<?php echo h($name); ?>[]" value="<?php echo h($option); ?>" <?php echo h(in_array($option, $input[$name]['value'])? 'checked': ''); ?>>
							<span><?php echo h($option); ?></span>
						</label>
					<?php endforeach; ?>
				</td>
			</tr>
			<tr>
				<th>
					<?php $name = 'body'; ?>
					<label for="input-<?php echo h($name); ?>">お問い合わせ内容の詳細<span>【必須】</span></label>
				</th>
				<td>
					<?php if($input[$name]['error']): ?><div class="error"><?php echo nl2br(h(implode("\n", $input[$name]['error']))); ?></div><?php endif; ?>
					<textarea name="<?php echo h($name); ?>" rows="5" cols="50" id="input-<?php echo h($name); ?>"><?php echo h($input[$name]['value']); ?></textarea>
				</td>
			</tr>
		</table>

		<div class="buttons">

			<label>
				<?php $name = 'agreement'; ?>
				<?php if($input[$name]['error']): ?><div class="error">お問い合わせいただくには個人情報の取扱に同意いただく必要があります</div><?php endif; ?>
				<input type="checkbox" name="agreement" value="1" <?php echo h($input[$name]['value']? 'checked': ''); ?>>&nbsp;個人情報の取扱について同意する
			</label>

			<input type="hidden" name="mode" value="confirm" />
			<button type="submit">確認</button>

		</div>

	</form>

</div>

</body>
</html>
