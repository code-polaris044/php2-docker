お問い合わせありがとうございます
<?php echo $input['name']['value']; ?>様

この度はお問い合わせいただきありがとうございました。
以下、お問い合わせ内容の控えとなります。

▼お名前
<?php echo $input['name']['value']; ?>（<?php echo $input['kana']['value']; ?>）

▼性別
<?php echo $input['sex']['value']."\n"; ?>

▼会社名
<?php echo $input['company']['value']."\n"; ?>

▼メールアドレス
<?php echo $input['email']['value']."\n"; ?>

▼住所
〒<?php echo $input['zipcode']['value']."\n"; ?>
<?php echo $input['prefecture']['value']; ?><?php echo $input['address']['value']."\n"; ?>

▼電話番号
<?php echo $input['tel']['value']."\n"; ?>

▼お問い合わせ内容
<?php foreach($input['subject']['value'] as $subject){ echo h($subject)."\n"; } ?>

▼お問い合わせ内容の詳細
<?php echo $input['body']['value']."\n"; ?>

回答まで今しばらくお待ちいただきますよう、お願いいたします。
