<?php
namespace app\extensions\smsfeedback;

class SmsFeedback {

    protected $host = '';
    protected $port = '';
    protected $login = '';
    protected $password = '';
    protected $phone = '';
    protected $text = '';
    protected $sender = '';
    protected $wapurl = '';

    public static function send($host, $port, $login, $password, $phone, $text, $sender = false, $wapurl = false )
    {
        $fp = fsockopen($host, $port, $errno, $errstr);
        if (!$fp) {
            return "errno: $errno \nerrstr: $errstr\n";
        }
        fwrite($fp, "GET /messages/v2/send/" .
            "?phone=" . rawurlencode($phone) .
            "&text=" . rawurlencode($text) .
            ($sender ? "&sender=" . rawurlencode($sender) : "") .
            ($wapurl ? "&wapurl=" . rawurlencode($wapurl) : "") .
            "  HTTP/1.0\n");
        fwrite($fp, "Host: " . $host . "\r\n");
        if ($login != "") {
            fwrite($fp, "Authorization: Basic " .
                base64_encode($login. ":" . $password) . "\n");
        }
        fwrite($fp, "\n");
        $response = "";
        while(!feof($fp)) {
            $response .= fread($fp, 1);
        }
        fclose($fp);
        list($other, $responseBody) = explode("\r\n\r\n", $response, 2);
        return $responseBody;
    }

    public static function test() {
        return 'Ok';
    }
}
