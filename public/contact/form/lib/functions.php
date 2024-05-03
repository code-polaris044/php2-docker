<?php

/**
 * 汎用的に使いまわす関数
 */

/**
 * 再帰的に文字コードをShift_JISからUTF-8に変換する
 *
 * @param string|array $str 文字列または文字列配列
 * @return string|array 変換した文字列または文字列配列
 */
if (!function_exists('s2u')) {
    function s2u($str)
    {
        if (is_array($str)) {
            return array_map('s2u', $str);
        }
        //return iconv('SJIS', 'UTF-8//TRANSLIT', $str);
        return mb_convert_encoding($str, 'UTF-8', 'SJIS-win, UTF-8');
    }
}


/**
 * 再帰的に文字コードをUTF-8からShift_JISに変換する
 *
 * @param string|array $str 文字列または文字列配列
 * @return string|array 変換した文字列または文字列配列
 */
if (!function_exists('u2s')) {
    function u2s($str)
    {
        if (is_array($str)) {
            return array_map('u2s', $str);
        }
        //return iconv('UTF-8', 'SJIS//TRANSLIT', $str);
        return mb_convert_encoding($str, 'SJIS-win', 'UTF-8, SJIS-win');
    }
}


/**
 * 再帰的に全角スペースを含む空白文字のトリミングを行う
 *
 * @param string|array $str 文字列または文字列配列
 * @return string|array 変換した文字列または文字列配列
 */
if (!function_exists('mb_trim')) {
    function mb_trim($str)
    {
        if (is_array($str)) {
            return array_map('mb_trim', $str);
        }
        return preg_replace('/(^(\s|　)+|(\s|　)+$)/u', '', $str);
    }
}


/**
 * 再帰的にHTMLをエスケープする
 *
 * @param string|array $str 文字列または文字列配列
 * @param int $flags エスケープオプション
 * @param string $encoding 文字エンコーディング
 * @return string|array エスケープした文字列または文字列配列
 */
if (!function_exists('h')) {
    function h($str, $flags = ENT_QUOTES, $encoding = 'UTF-8')
    {
        if (is_array($str)) {
            return array_map('h', $str, $flags, $encoding);
        }
        return htmlspecialchars($str, $flags, $encoding);
    }
}


/**
 * 文字列をJavaScript文字列としてエスケープする
 *
 * @param string|array $str 文字列または文字列配列
 * @return string|array エスケープした文字列
 */
if (!function_exists('js')) {
    function js($str)
    {
        return str_replace('>', '&gt;', str_replace('<', '&lt;', str_replace('&', '&amp;', addslashes($str))));
    }
}


/**
 * 指定の桁数で0埋めする
 *
 * @param int|string $number 数値
 * @param int $length 長さ
 * @return string 0埋めされた文字列
 */
if (!function_exists('zero_pad')) {
    function zero_pad($number, $length)
    {
        return str_pad($number, $length, '0', STR_PAD_LEFT);
    }
}


/**
 * ファイルポインタから行を取得し、CSVフィールドを処理する
 *
 * @link http://yossy.iimp.jp/wp/?p=56
 * @param resource $handle ファイルハンドル
 * @param int $length 読み込むバイト数
 * @param string $delimiter 区切り文字
 * @param string $enclosure 括り文字
 * @return boolean ファイルの終端に達した場合を含み、エラー時にFALSEを返します。
 */
if (!function_exists('fgetcsv_reg')) {
    function fgetcsv_reg(&$handle, $length = null, $delimiter = ',', $enclosure = '"')
    {
        $d = preg_quote($delimiter);
        $e = preg_quote($enclosure);
        $line = '';
        $eof = false;
        while (!$eof && !feof($handle)) {
            $line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
            $itemcnt = preg_match_all('/' . $e . '/', $line, $dummy);
            if ($itemcnt % 2 == 0) {
                $eof = true;
            }
        }
        $csv_line = preg_replace('/(?:\\r\\n|[\\r\\n])?$/', $d, trim($line));
        $csv_pattern = '/(' . $e . '[^' . $e . ']*(?:' . $e . $e . '[^' . $e . ']*)*' . $e . '|[^' . $d . ']*)' . $d . '/';
        preg_match_all($csv_pattern, $csv_line, $csv_matches);
        $csv_data = $csv_matches[1];
        $csv_count = count($csv_data);
        for ($i = 0; $i < $csv_count; $i++) {
            $csv_data[$i] = preg_replace('/^' . $e . '(.*)' . $e . '$/s', '$1', $csv_data[$i]);
            $csv_data[$i] = str_replace($e . $e, $e, $csv_data[$i]);
        }
        return empty($line) ? false : $csv_data;
    }
}


/**
 * 1次元配列をExcelで読めるCSVの形に整形
 *
 * @param array $array 配列
 * @return string 整形したカンマ区切りの文字列
 */
if (!function_exists('get_csv_row')) {
    function get_csv_row($array)
    {
        $comma = 'TMP_COMMA_' . mt_rand(1000, 9999);
        foreach ($array as &$val) {
            $val = str_replace(',', $comma, $val);
        }
        $line = implode(',', $array);
        $line = str_replace('"', '""', $line);
        $line = str_replace(',', '","', $line);
        $line = preg_replace('/"(\d{9}\d+)"/', "\"\r$1\"", $line);
        $line = str_replace('","0', '",="0', $line);
        $line = str_replace($comma, ',', $line);
        $line = '"' . $line . '"';
        return $line;
    }
}


/**
 * 現在開いているページにリダイレクトする（リロード）
 *
 * HTML等を出力する前に実行する必要があります。
 * @return void
 */
if (!function_exists('page_reload')) {
    function page_reload()
    {
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }
}


/**
 * 指定のURLにリダイレクトする
 *
 * HTML等を出力する前に実行する必要があります。
 * @param string $url URL
 * @return void
 */
if (!function_exists('redirect')) {
    function redirect($url)
    {
        header('Location: ' . preg_replace('/[\r\n]/', '', $url));
        exit;
    }
}


/**
 * HTTPステータスコードを出力して終了
 *
 * HTML等を出力する前に実行する必要があります。
 * @param int|string $code ステータスコード
 * @param string $message 出力する文字列
 * @param string $file 同時に出力するファイルのパス
 * @return void
 */
if (!function_exists('http_status')) {
    function http_status($code, $message = null, $file = null)
    {

        $status = array(
            400 => array(
                'title' => 'Bad Request',
                'message' => 'Your browser (or proxy) sent a request that this server could not understand.',
            ),
            403 => array(
                'title' => 'Forbidden',
                'message' => 'You don\'t have permission to access %s on this server.',
            ),
            404 => array(
                'title' => 'Not Found',
                'message' => 'The requested URL %s was not found on this server.',
            ),
            500 => array(
                'title' => 'Internal Server Error',
                'message' => 'The server encountered an internal error and was unable to complete your request.',
            ),
            503 => array(
                'title' => 'Service Unavailable',
                'message' => 'The server is temporarily unable to service your request due to maintenance downtime or capacity problems. Please try again later.',
            ),
        );

        if (!isset($status[$code])) {
            return;
        }
        header('HTTP/1.1 ' . $code . ' ' . $status[$code]['title']);

        if ($file !== null && is_file($file)) {
            include $file;
        }else {
            if ($message === null) {
                $message = $status[$code]['message'];
            }
            $message = sprintf($message, $_SERVER['REQUEST_URI']);
            echo '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>' . h($code) . ' ' . h($status[$code]['title']) . '</title></head><body><h1>' . h($status[$code]['title']) . '</h1><p>' . h($message) . '</p></body></html>';
        }

        exit;

    }
}


/**
 * ウィンドウを閉じる
 *
 * ポップアップで開かれたウィンドウのみ有効です。
 * HTML等を出力する前に実行する必要があります。
 * @return void
 */
if (!function_exists('window_close')) {
    function window_close()
    {
        echo '<!DOCTYPE html><html lang="ja"><head><meta charset="UTF-8"><title></title><script>window.close();</script></head><body></body></html>';
        exit;
    }
}


/**
 * マルチバイト対応版 strtr
 *
 * @license free（Quita利用規約より）
 * @see http://qiita.com/mpyw/items/ceae0ed5285093c76087#2-7
 *
 * @return string
 */
if (!function_exists('strtr_utf8')) {
    function strtr_utf8()
    {
        if (func_num_args() < 3) {
            list($str, $replace_pairs) = func_get_args();
        }else {
            list($str, $from, $to) = func_get_args();
            $from = preg_split('//u', $from, -1, PREG_SPLIT_NO_EMPTY);
            $to = preg_split('//u', $to, -1, PREG_SPLIT_NO_EMPTY);
            $replace_pairs = array();
            foreach ($from as $i => $f) {
                if (!isset($to[$i])) {
                    break;
                }
                $replace_pairs[$f] = $to[$i];
            }
        }
        return strtr($str, $replace_pairs);
    }
}
