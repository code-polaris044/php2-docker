<?php

// エラー出力する場合
if($_SERVER['REMOTE_ADDR'] === '59.87.167.210') {
	ini_set( 'display_errors', 1 );
	error_reporting(-1);
}

setlocale(LC_TIME, 'ja_JP');
date_default_timezone_set('Asia/Tokyo');

require_once __DIR__ . '/form/etc/FormConfig.php';
require_once __DIR__ . '/form/lib/functions.php';
require_once __DIR__ . '/form/lib/Session.php';
require_once __DIR__ . '/form/lib/FormConstraint.php';

require_once __DIR__ . '/form/lib/PHPMailer/class.phpmailer.php';
require_once __DIR__ . '/form/lib/PHPMailer/class.jphpmailer.php';
require_once __DIR__ . '/form/lib/PHPMailer/class.smtp.php';


class SimpleFormController
{


    protected $script_encoding = 'UTF-8';

    protected $input_html = './index.template.php';
    protected $confirm_html = './confirm.template.php';
    protected $thanks_url = './thanks.html';

    protected $input = [];

    protected $email_for_admin = './form/etc/email_for_admin.template.php';
    protected $email_for_user = './form/etc/email_for_user.template.php';
    protected $email_return = 'email';

    protected $error = 0;

    /** @var FormConfig $config */
    private $config;


    public function __construct()
    {

        $this->config = new FormConfig();
        session_cache_limiter('');
        Session::start('form_session');

        $paths = ['input_html', 'confirm_html', 'email_for_admin', 'email_for_user'];
        foreach ($paths as $p) {
            $this->{$p} = realpath(__DIR__ . '/' . $this->{$p});
        }

        $columns = $this->config->getColumns();
        foreach ($columns as $key => $value) {
            $columns[$key]['error'] = [];
        }
        $this->input = $columns;

        if (!empty($_POST)) {
            foreach ($_POST as $key => $val) {
                if (!isset($this->input[$key])) {
                    continue;
                }
                $this->input[$key]['value'] = $val;
            }
        }

    }


    /**
     * フォーム入力画面
     */
    public function input()
    {
        $this->render($this->input_html);
    }


    /**
     * フォーム確認画面
     */
    public function confirm()
    {

        if (!$this->validate()) {
            Session::set('form_error', '入力内容に誤りがあります。ご確認ください。');
            $this->input();
            exit;
        }

        $tokens = Session::get('form_csrf_token');
        $new_token = $this->generateToken();
        $tokens[$new_token] = $new_token;
        Session::set('form_csrf_token', $tokens);

        $this->render($this->confirm_html, ['token' => $new_token]);

    }


    /**
     * バリデーション
     * @return bool
     */
    public function validate()
    {

        $error = 0;

        foreach ($this->input as $key => &$input) {

            // 値が配列になる場合(チェックボックスなど)
            if (is_array($input['value'])) {
                $input['value'] = array_filter($input['value'], 'strlen');
                if ($input['required'] && !count($input['value'])) {
                    $input['error'][] = $input['name'] . 'は必須項目です。';
                }

            }else if ($input['required'] && $input['value'] === '') {
                $input['error'][] = $input['name'] . 'は必須項目です。';

            }

            $preg_flags = false !== stripos($this->script_encoding, 'UTF')
                ? 'u'
                : '';

            switch ($input['type']) {
                case 'zenkaku':
                    $input['value'] = mb_convert_kana($input['value'], 'KVAS', $this->script_encoding);
                    break;
                case 'hankaku':
                    $input['value'] = mb_convert_kana($input['value'], 'khas', $this->script_encoding);
                    break;
                case 'hiragana':
                    $input['value'] = mb_convert_kana($input['value'], 'HVcS', $this->script_encoding);
                    if (!preg_match('/^[　ぁ-んーゝゞ]*$/' . $preg_flags, $input['value'])) {
                        $input['error'][] = $input['name'] . 'はひらがなで入力してください。';
                    }
                    break;
                case 'katakana':
                    $input['value'] = mb_convert_kana($input['value'], 'KVCS', $this->script_encoding);
                    if (!preg_match('/^[　ァ-ヶーヽヾ]*$/' . $preg_flags, $input['value'])) {
                        $input['error'][] = $input['name'] . 'はカタカナで入力してください。';
                    }
                    break;
                case 'number':
                    $input['value'] = mb_convert_kana($input['value'], 'a', $this->script_encoding);
                    if (!preg_match('/^\d*$/' . $preg_flags, $input['value'])) {
                        $input['error'][] = $input['name'] . 'は半角数字で入力してください。';
                    }
                    break;
                case 'numberh':
                    $input['value'] = preg_replace('/[−ー―‐]/u', '-', mb_convert_kana($input['value'], 'a', $this->script_encoding));
                    if (!preg_match('/^(\d|-)*$/' . $preg_flags, $input['value'])) {
                        $input['error'][] = $input['name'] . 'は半角数字とハイフンで入力してください。';
                    }
                    break;
                case 'zipcode':
                    $input['value'] = preg_replace('/[−ー―‐]/u', '-', mb_convert_kana($input['value'], 'a', $this->script_encoding));
                    if (preg_match('/^((\d{3})-?(\d{4}))?$/' . $preg_flags, $input['value'], $matches)) {
                        if ($input['value'] !== '') {
                            $input['value'] = $matches[2] . '-' . $matches[3];
                        }
                    }else {
                        $input['error'][] = $input['name'] . 'の書式が正しくありません。';
                    }
                    break;
                case 'phone':
                    $input['value'] = preg_replace('/[−ー―‐]/' . $preg_flags, '-', mb_convert_kana($input['value'], 'a', $this->script_encoding));
                    if (!preg_match('/^(\d{1,4}-?\d{1,4}-?\d{1,4})?$/' . $preg_flags, $input['value'])) {
                        $input['error'][] = $input['name'] . 'の書式が正しくありません。';
                    }
                    break;
                case 'email':
                    $input['value'] = mb_convert_kana($input['value'], 'a', $this->script_encoding);
                    if (!preg_match('/^([a-zA-Z0-9_.+-]+[@][a-zA-Z0-9-]+?(\.[a-zA-Z0-9-]+?)+)?$/' . $preg_flags, $input['value'])) {
                        $input['error'][] = $input['name'] . 'の書式が正しくありません。';
                    }
                    break;
                case 'alphabet':
                    $input['value'] = mb_convert_kana($input['value'], 'as', $this->script_encoding);
                    if (!preg_match('/^[a-zA-Z]*$/', $input['value'])) {
                        $input['error'][] = $input['name'] . 'は半角英字で入力してください。';
                    }
                    break;
                case 'alphanumeric':
                    $input['value'] = mb_convert_kana($input['value'], 'as', $this->script_encoding);
                    if (!preg_match('/^[a-zA-Z0-9]*$/', $input['value'])) {
                        $input['error'][] = $input['name'] . 'は半角英数で入力してください。';
                    }
                    break;
            }

            foreach ( (array) $input['constraints'] as $constraint ) {
                /** @var FormConstraint $constraint */
                $constraint->setInput($this->input);
                if (!$constraint->validate($key, $input['value'], $input['name'])) {
                    $input['error'][] = $constraint->getMessage();
                }
            }

            $error += count($input['error']);

            unset($input);

        }
        unset($input);

        $this->error = $error;

        return !$error;

    }


    /**
     * フォーム完了画面
     */
    public function submit()
    {

        $tokens = Session::get('form_csrf_token');

        if (!is_string($_POST['token']) || !isset($tokens[$_POST['token']])) {
            Session::set('form_error', '多重送信 または 不正な送信とみなされました。恐れ入りますがもう一度ご確認ください。');
            $this->confirm();
            exit;
        }

        if (!$this->validate()) {
            Session::set('form_error', '入力内容に誤りがあります。ご確認ください。');
            $this->input();
            exit;
        }

        unset($tokens[$_POST['token']]);
        Session::set('form_csrf_token', $tokens);

        $is_return = $this->config->getEmailReturn() && $this->input[$this->config->getEmailReturn()]['type'] === 'email' && $this->input[$this->config->getEmailReturn()]['value'] !== '';

        $send_to = $this->config->getSendTo();
        foreach ($send_to as $value) {
            if($is_return){
                $this->sendTo($value, $this->email_for_admin, $this->input[$this->config->getEmailReturn()]['value']);
            }else {
                $this->sendTo($value, $this->email_for_admin, null);
            }
        }

        if ($is_return) {
            $this->sendTo($this->input[$this->config->getEmailReturn()]['value'], $this->email_for_user, $this->config->getReplyTo());
        }

        $this->redirect($this->thanks_url);

    }


    /**
     * メール送信
     * @param $address
     * @param $template_path
     * @param null $reply_to
     * @return bool
     * @throws phpmailerException
     */
    protected function sendTo($address, $template_path, $reply_to = null)
    {

        $address = trim($address);

        ob_start();
        $input = $this->input;
        include $template_path;
        $message = explode("\n", trim(ob_get_clean()));

        mb_language('japanese');
        mb_internal_encoding('UTF-8');

        $mail = new JPHPMailer();

        $config = $this->config->getSendConfig();

        if ($config['mode'] === 'smtp') {

            $mail->isSMTP();

            $mail->Host = $config['server']['name'];
            $mail->Port = $config['server']['port'];

            if (isset($config['secure'])) {
                $mail->SMTPSecure = $config['secure'];
            }

            if (isset($config['account'])) {
                $mail->SMTPAuth = true;
                $mail->Username = $config['account']['name'];
                $mail->Password = $config['account']['password'];
            }

            if (isset($config['options'])) {
                $mail->SMTPOptions = $config['options'];
            }

        }

        //差出人
        $from = $this->config->getFromAddress();
        if (is_array($from)) {
            $mail->setFrom($from[1], $from[0]);
        }else {
            $mail->setFrom($from);
        }

        //返信先
        if ($reply_to) {
            $mail->addReplyTo(trim($reply_to));
        }

        //送信先
        $mail->addTo($address);

        //件名
        $mail->setSubject(trim(array_shift($message)));

        //本文
        $mail->setBody(trim(implode("\n", $message)));

        return $mail->send();

    }


    /**
     * テンプレート読み込み
     * @param $file
     * @param array $vars
     */
    public function render($file, $vars = array())
    {
        extract($vars, EXTR_OVERWRITE);
        $input = $this->input;
        include $file;
        exit;
    }


    /**
     * リダイレクト
     * @param $to
     */
    public function redirect($to)
    {
        header('Location: ' . $to);
        exit;
    }


    /**
     * トークン生成
     * @return false|string
     * @throws Exception
     */
    protected function generateToken()
    {
        if (function_exists('random_bytes')) {
            $bytes = random_bytes(32);
            return bin2hex( $bytes );
        }

        if (function_exists('openssl_random_pseudo_bytes')) {
            for( $i = 0, $max = 3; $i < $max; $i++) {
                $bytes = openssl_random_pseudo_bytes( 32, $crypto_strong );
                if ( ! ( false === $crypto_strong || false === $bytes ) ) {
                    return bin2hex( $bytes );
                }
            }

            if(isset($bytes) && false === $bytes){
                return bin2hex( $bytes );
            }
        }
        $hash = sha1(mt_rand()) . sha1(mt_rand());
        return substr($hash, 0, 64);
    }


}


$form = new SimpleFormController();
$mode = isset($_POST['mode'])
    ? $_POST['mode']
    : Session::get('mode');

switch ($mode) {
    case 'submit':
        $form->submit();
        break;
    case 'confirm':
        $form->confirm();
        break;
    default:
        $form->input();
        break;
}
