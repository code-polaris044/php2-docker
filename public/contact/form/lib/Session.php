<?php

/**
 * セッション管理用クラス
 */
class Session
{


    protected static $session_name = null;


    /**
     * セッションを開始
     *
     * @param  string $session_name セッション名
     * @return void
     */
    public static function start($session_name = null)
    {
        if (session_id() === '') {
            if (!is_null($session_name)) {
                self::$session_name = $session_name;
            }
            session_name($session_name);
            session_start();
        }
    }


    /**
     * セッション変数に値を設定
     *
     * @param  string $name 変数名
     * @param  mixed $value 設定する値
     * @return void
     */
    public static function set($name, $value)
    {
        self::start();
        $_SESSION[$name] = $value;
    }


    /**
     * セッション変数から値を取得
     *
     * @param  string $name 変数名
     * @return mixed 取得した値
     */
    public static function get($name)
    {
        self::start();
        return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
    }


    /**
     * セッション変数に値があるか調べる
     *
     * @param  string $name 変数名
     * @return bool 値の有無
     */
    public static function exists($name)
    {
        return isset($_SESSION[$name]);
    }


    /**
     * セッション変数から全ての値を連想配列で取得
     *
     * @return array 全ての値
     */
    public static function getAll()
    {
        self::start();
        return $_SESSION;
    }


    /**
     * セッション変数から値を削除
     *
     * @param  string $name 変数名
     * @return void
     */
    public static function delete($name)
    {
        self::start();
        unset($_SESSION[$name]);
    }


    /**
     * セッション変数から値を取得し、その場で削除
     *
     * @param  string $name 変数名
     * @return mixed 取得した値
     */
    public static function flash($name)
    {
        $value = self::get($name);
        self::delete($name);
        return $value;
    }


    /**
     * セッションIDを再生成する
     *
     * @return bool IDの再生成に成功したかどうか
     */
    public static function regenerateId()
    {
        return session_regenerate_id();
    }


}
