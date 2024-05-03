<?php

//v20191125

abstract class FormConstraint
{
    protected $message;
    protected $input;

    abstract public function validate($key, $value, $name);

    public function getMessage()
    {
        return $this->message;
    }

    public function setInput($input)
    {
        $this->input = $input;
        return $this;
    }

    public function getInput()
    {
        return $this->input;
    }
}


/**
 * 文字幅制約
 *
 * 半角文字を1、全角文字を2として文字数制限する
 */
class StringWidthConstraint extends FormConstraint
{

    protected $min;
    protected $max;
    protected $encoding;

    public function __construct($min, $max, $encoding = 'UTF-8')
    {
        $this->min = $min;
        $this->max = $max;
        $this->encoding = $encoding;
    }

    public function validate($key, $value, $name)
    {
        $width = mb_strwidth($value, $this->encoding);
        $validate = ($this->min <= $width && $width <= $this->max);
        if (!$validate) {
            $this->message = $name . 'は全角';
            if ($this->min) {
                $this->message .= ($this->min / 2) . '文字以上';
            }
            if ($this->max) {
                $this->message .= ($this->max / 2) . '文字以下';
            }
            $this->message .= 'で入力してください。';

        }
        return $validate;
    }

}


/**
 * 範囲制約
 *
 * A以上B以下に制限
 */
class BetweenConstraint extends FormConstraint
{

    protected $min;
    protected $max;

    public function __construct($min, $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    public function validate($key, $value, $name)
    {
        $validate = ($this->min <= $value && $value <= $this->max);
        if (!$validate) {
            $this->message = "{$name}は{$this->min}から{$this->max}の間で指定してください";
        }
        return $validate;
    }


}


/**
 * 他フィールド一致制約
 *
 * フォーム上の別な項目と値が一致するよう制限
 */
class MatchUpAnotherFieldConstraint extends FormConstraint
{

    protected $field;

    public function __construct($field)
    {
        $this->field = $field;
    }

    public function validate($key, $value, $name)
    {
        $validate = ($value === $this->input[$this->field]['value']);
        if (!$validate) {
            $this->message = "{$name}が一致しません。";
        }
        return $validate;
    }

}


/**
 * 分割電話番号制約
 *
 * 日本の電話番号ルールに一致するよう制限
 */
class SplitJapanPhoneNumberConstraint extends FormConstraint
{

    protected $field2;
    protected $field3;

    public function __construct($field2, $field3)
    {
        $this->field2 = $field2;
        $this->field3 = $field3;
    }

    public function validate($key, $value, $name)
    {

        $names = array($key, $this->field2, $this->field3);
        $values = array();

        foreach ($names as $key_name) {
            $validate = isset($this->input[$key_name]['value']) && is_string($this->input[$key_name]['value']);
            if (!$validate) {
                $this->message = "{$name}を正しく入力してください。";
                return false;
            }
            $values[] = $this->input[$key_name]['value'];
        }

        $values = array_filter($values, 'strlen');

        if (empty($values)) {
            return true;
        }

        if (!preg_match('/^\d{10,11}$/', implode('', $values))) {
            $this->message = "{$name}を正しく入力してください。";
            return false;
        }

        return true;

    }

}


/**
 * 分割郵便番号制約
 *
 * 日本の郵便番号ルールに一致するよう制限
 */
class SplitJapanZipCodeConstraint extends FormConstraint
{
    protected $field2;

    public function __construct($field2)
    {
        $this->field2 = $field2;
    }

    public function validate($key, $value, $name)
    {

        $preg_flags = false !== stripos('UTF-8', 'UTF') ? 'u' : '';

        $names = array($key, $this->field2);
        $values = array();

        foreach ($names as $key_name) {
            $validate = is_string($this->input[$key_name]['value']);
            if (!$validate) {
                $this->message = "※{$name}を正しく入力してください。";
                return false;
            }
            $values[] = $this->input[$key_name]['value'];
        }

        $values = array_filter($values, 'strlen');

        if (empty($values)) {
            return true;
        }

        $values = preg_replace('/[−ー―‐]/u', '-', mb_convert_kana(implode('', $values), 'a', 'UTF-8'));

        if (!preg_match('/^((\d{3})-?(\d{4}))?$/' . $preg_flags, $values, $matches)) {
            $this->message = "※{$name}の書式が正しくありません。";
            return false;
        }

        return true;

    }
}


/**
 * 他フィールドによる必須制約
 *
 * (例) フィールドAの値が○○のとき、フィールドBは必須
 */
class RequiredByAnotherFieldConstraint extends FormConstraint
{

    protected $field;
    protected $condition;
    protected $is_callable;

    public function __construct($field, $condition, $is_callable = false)
    {
        $this->field = $field;
        $this->condition = $condition;
        $this->is_callable = $is_callable;
    }

    public function validate($key, $value, $name)
    {

        $another_field_value = $this->input[$this->field]['value'];
        $required = ($this->condition === $another_field_value);

        if ($this->is_callable) {
            $required = call_user_func($this->condition, $another_field_value);

        }elseif (is_array($this->condition)) {
            $required = in_array($another_field_value, $this->condition, false);

        }

        $validate = !$required || $value !== '';
        if (!$validate) {
            $this->message = "{$name}は必須項目です。";
        }

        return $validate;

    }

}


/**
 * 選択肢制約
 *
 * selectやradioでの利用を想定
 */
class ChooseOneConstraint extends FormConstraint
{

    protected $values;

    public function __construct($values = array())
    {
        $this->values = $values;
    }

    public function validate($key, $value, $name)
    {

        if (is_array($value)) {
            $result = true;
            foreach ($value as $v) {
                $result = $result && $this->validate($key, $v, $name);
            }
            return $result;
        }

        $validate = $value === '' || in_array($value, $this->values, false);
        if (!$validate) {
            $this->message = "{$name}は選択肢の中からお選びください";
        }

        return $validate;

    }

}


/**
 * 複数フィールドを入力/選択する際の個数制約
 *
 * checkboxでの利用を想定
 * 設定する際はいずれか1つのフィールドにこの制約を登録する
 *
 * (例) フィールドA, B, Cの中から最低1つ以上、最大2つまで選択しなければならない
 * A, B, Cのいずれか一つに new ChooseSomeFieldConstraint(['A', 'B', 'C'], 1, 2)
 */
class RequiredChooseSomeFieldConstraint extends FormConstraint
{

    protected $fields;
    protected $min;
    protected $max;

    public function __construct($fields, $min = 1, $max = null)
    {
        $this->fields = (is_array($fields)? $fields: array($fields));
        $this->min = $min;
        $this->max = $max;
    }

    public function validate($key, $value, $name)
    {

        $count = 0;

        if (count($this->fields) > 1) {
            foreach ($this->fields as $field) {
                if ($this->input[$field]['value'] !== '') {
                    $count++;
                }
            }
        } else {
            $count = count($value);
        }

        $validate = ($this->min <= $count) && ($this->max ? $count <= $this->max : true);
        if (!$validate) {

            if($this->min && $this->max){
                $this->message = "{$name}は{$this->min}つ以上{$this->max}つ以下で入力/選択してください";

            }elseif ($this->min) {
                $this->message = "{$name}は{$this->min}つ以上で入力/選択してください";

            }elseif ($this->max) {
                $this->message = "{$name}は{$this->max}つ以下で入力/選択してください";

            }

        }

        return $validate;

    }

}


/**
 * 正規表現制約
 */
class RegexConstraint extends FormConstraint
{

    protected $regex;
    protected $template;

    public function __construct($regex, $message = null)
    {
        $this->regex = $regex;
        $this->template = $message;
    }

    public function validate($key, $value, $name)
    {

        if (!$this->template) {
            $this->template = '%sは正しく入力してください';
        }

        $validate = preg_match($this->regex, $value);
        if (!$validate) {
            $this->message = sprintf($this->template, $name);
        }

        return $validate;

    }

}


/**
 * 実在している日付か確認する
 */
class IsDateExistingConstraint extends FormConstraint
{
    protected $month;
    protected $date;

    public function __construct($month, $date)
    {
        $this->month = $month;
        $this->date  = $date;
    }

    public function validate($key, $value, $name)
    {
        $year  = $this->input[$key]['value'];
        $month = $this->input[$this->month]['value'];
        $date  = $this->input[$this->date]['value'];

        $is_entered = (!empty($year) || !empty($month) || !empty($date));

        if($this->input[$key]['required'] || $is_entered){

            if(checkdate($month, $date, $year)){
                return true;
            }

            $this->message = "{$name}を正しく入力してください。";
            return false;

        }

        return true;
    }

}


/**
 * コールバック制約
 *
 * 自力でバリデーションを行うための制約
 * 主に案件独自の制約が必要になった時の利用を想定
 *
 * ▼コールバック実装仕様
 * @param  string $key バリデーション対象のフィールドのキー
 * @param  string $value バリデーション対象のフィールドの値
 * @param  string $name バリデーション対象のフィールドの表示名
 * @param  FormConstraint $constraint コールバック制約のインスタンス（$constraint->getInput()で送信内容全てを参照可能）
 * @return bool|string 問題なければ true を、不適切な値なら表示するエラーメッセージを返す
 */
class CallbackConstraint extends FormConstraint
{

    protected $callback;

    public function __construct($callback)
    {
        $this->callback = $callback;
    }

    public function validate($key, $value, $name)
    {

        $validate = call_user_func($this->callback, $key, $value, $name, $this);
        if (is_string($validate)) {
            $this->message = $validate;
            $validate = false;
        }else {
            $validate = (bool)$validate;
            if (!$validate && empty($this->message)) {
                $this->message = "{$name}は正しく入力してください。";
            }
        }

        return $validate;

    }

}
