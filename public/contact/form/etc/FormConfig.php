<?php

/**
 * 設定ファイル
 * フォームの設定をするファイルです。
 * 各項目の項目名や必須チェック設定はここでします。
 */
class FormConfig
{


    protected $sex = ['男性', '女性', '答えたくない'];

    protected $prefectures = [
        '北海道',
        '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県',
        '茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県',
        '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県',
        '岐阜県', '静岡県', '愛知県', '三重県',
        '滋賀県', '京都府', '大阪府', '兵庫県', '奈良県', '和歌山県',
        '鳥取県', '島根県', '岡山県', '広島県', '山口県',
        '徳島県', '香川県', '愛媛県', '高知県',
        '福岡県', '佐賀県', '長崎県', '熊本県', '大分県', '宮崎県', '鹿児島県',
        '沖縄県'
    ];

    protected $subjects = [
        '事業について',
        '採用について',
        'その他のお問い合わせ',
    ];


    public function getColumns()
    {

        return [
            'name' => [
                'name' => 'お名前',
                'type' => 'text',
                'value' => '',
                'required' => true,
                'constraints' => [
                    new StringWidthConstraint(0, 30),
                ],
            ],
            'kana' => [
                'name' => 'ふりがな',
                'type' => 'hiragana',
                'value' => '',
                'required' => true,
                'constraints' => [
                    new StringWidthConstraint(0, 60),
                ],
            ],
            'sex' => [
                'name' => '性別',
                'type' => 'text',
                'value' => '男性',
                'required' => true,
                'options' => $this->sex,
                'constraints' => [
                    new ChooseOneConstraint($this->sex),
                ],
            ],
            'company' => [
                'name' => '会社名',
                'type' => 'text',
                'value' => '',
                'required' => false,
                'constraints' => [
                    new StringWidthConstraint(0, 60),
                ],
            ],
            'email' => [
                'name' => 'メールアドレス',
                'type' => 'email',
                'value' => '',
                'required' => true,
                'constraints' => [
                    new StringWidthConstraint(0, 256),
                ],
            ],
            'email_confirm' => [
                'name' => 'メールアドレス（確認）',
                'type' => 'text',
                'value' => '',
                'required' => false,
                'constraints' => [
                    new MatchUpAnotherFieldConstraint('email'),
                ],
            ],
            'zipcode' => [
                'name' => '郵便番号',
                'type' => 'zipcode',
                'value' => '',
                'required' => true,
                'constraints' => [],
            ],
            'prefecture' => [
                'name' => '都道府県',
                'type' => 'text',
                'value' => '',
                'required' => true,
                'options' => $this->prefectures,
                'constraints' => [
                    new ChooseOneConstraint($this->prefectures),
                ],
            ],
            'address' => [
                'name' => '住所',
                'type' => 'text',
                'value' => '',
                'required' => true,
                'constraints' => [
                    new StringWidthConstraint(0, 200),
                ],
            ],
            'tel' => [
                'name' => '電話番号',
                'type' => 'phone',
                'value' => '',
                'required' => false,
                'constraints' => [],
            ],
            'subject' => [
                'name' => 'お問い合わせ内容',
                'type' => 'array',
                'value' => [],
                'required' => true,
                'options' => $this->subjects,
                'constraints' => [
                    new ChooseOneConstraint($this->subjects),
                    new RequiredChooseSomeFieldConstraint(null, 1, 2),
                ],
            ],
            'body' => [
                'name' => 'お問い合わせ内容の詳細',
                'type' => 'text',
                'value' => '',
                'required' => true,
                'constraints' => [
                    new StringWidthConstraint(0, 20000),
                ],
            ],
            'agreement' => [
                'name' => '個人情報の取扱について同意',
                'type' => 'text',
                'value' => '',
                'required' => true,
                'constraints' => [],
            ],
        ];

    }


    public function getEmailReturn()
    {
        return 'email';
    }


    public function getFromAddress()
    {
        return [
            'テスト株式会社',
            'test@testsv.biz'
        ];
    }


    public function getReplyTo()
    {
        return null;
    }


    public function getSendTo()
    {
        return [
            'test@testsv.biz',
        ];
    }


    public function getSendConfig()
    {

        $default = [
            'mode' => 'mail',
        ];

        $smtp = [

            'mode' => 'smtp',

            //SMTPサーバー
            'server' => [
                'name' => 'smtp.example.com',
                'port' => 587,
            ],

            //SMTP認証
            'account' => [
                'name'     => 'info%example.com',
                'password' => 'change_me',
            ],

            // 接続を暗号化するか
            // START TLS(25/587) → 'tls',
            // SMTPS(465)        → 'ssl',
            // 暗号化なし          → ''
            'secure' => 'tls',

            // その他の接続設定（通常指定不要）
            /*
            'options' => [
                'ssl' => [
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => true
                ],
            ],
            */

        ];

        // SMTPを利用して送信する場合は上記設定を変更の上
        // return $smtp; に変更
        return $default;

    }


}
