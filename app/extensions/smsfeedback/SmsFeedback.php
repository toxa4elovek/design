<?php
namespace app\extensions\smsfeedback;

class SmsFeedback
{

    protected static $defaults = [
        'host' => 'api.smsfeedback.ru',
        'port' => 80,
        'login' => 'godesigner',
        'password' => '6446969i',
        'sender' => 'GoDesigner',
        'wapurl' => '',
    ];

    public static function send($phone, $text, $options = [])
    {
        if (is_array($options)) {
            $options += static::$defaults;
        }
        $fp = fsockopen($options['host'], $options['port'], $errno, $errstr);
        if (!$fp) {
            return "errno: $errno \nerrstr: $errstr\n";
        }
        fwrite($fp, "GET /messages/v2/send/" .
            "?phone=" . rawurlencode($phone) .
            "&text=" . rawurlencode($text) .
            ($options['sender'] ? "&sender=" . rawurlencode($options['sender']) : "") .
            ($options['wapurl'] ? "&wapurl=" . rawurlencode($options['wapurl']) : "") .
            "  HTTP/1.0\n");
        fwrite($fp, "Host: " . $options['host'] . "\r\n");
        if ($options['login'] != "") {
            fwrite($fp, "Authorization: Basic " .
                base64_encode($options['login'] . ":" . $options['password']) . "\n");
        }
        fwrite($fp, "\n");
        $response = "";
        while (!feof($fp)) {
            $response .= fread($fp, 1);
        }
        fclose($fp);
        list($varNotUsed, $responseBody) = explode("\r\n\r\n", $response, 2);
        return $responseBody;
    }
}
