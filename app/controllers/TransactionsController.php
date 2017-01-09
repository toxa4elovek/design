<?php

namespace app\controllers;

use \app\models\Pitch;
use \app\models\Addon;
use \app\models\Receipt;
use \app\models\Transaction;
use \app\extensions\paymentgateways\Webgate;
use \lithium\analysis\Logger;

class TransactionsController extends \app\controllers\AppController
{

    public function getsigndata()
    {
        if ($pitch = Pitch::first($this->request->id)) {
            $data = [
                'id' => $pitch->id,
                'total' => $pitch->total,
                'timestamp' => gmdate("YmdHis", time())

            ];
            $webgate = new Webgate;
            $sign = $webgate->generateSign($data);
            $data['sign'] = $sign;
            Logger::write('debug', serialize($data));
            return $data;
        }
    }

    public function getaddondata()
    {
        if ($addon = Addon::first($this->request->id)) {
            $data = [
                'id' => $addon->id,
                'total' => $addon->total,
                'timestamp' => gmdate("YmdHis", time())

            ];
            $webgate = new Webgate;
            $sign = $webgate->generateSign($data);
            $data['sign'] = $sign;
            Logger::write('debug', serialize($data));
            return $data;
        }
    }

    public function lost()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
        $string = 'a:23:{s:8:"FUNCTION";s:13:"TransResponse";s:2:"RC";s:2:"00";s:6:"AMOUNT";s:7:"6870.00";s:8:"CURRENCY";s:3:"RUB";s:5:"ORDER";s:6:"100216";s:3:"RRN";s:12:"215803476218";s:7:"INT_REF";s:16:"3A6133733E347668";s:9:"AUTH_CODE";s:6:"909025";s:3:"PAN";s:0:"";s:8:"TERMINAL";s:8:"71846655";s:6:"TRTYPE";s:2:"21";s:9:"MERCH_URL";s:36:"http://godesigner.ru/users/mypitches";s:14:"CARDHOLDERNAME";s:0:"";s:4:"DESC";s:0:"";s:10:"MERCH_NAME";s:13:"GODESIGNER.RU";s:11:"TEXTMESSAGE";s:8:"Approved";s:3:"ACS";s:0:"";s:4:"ACS1";s:0:"";s:6:"RESULT";s:1:"0";s:9:"TIMESTAMP";s:14:"20120606115858";s:7:"USER_IP";s:15:"192.168.201.107";s:4:"SIGN";s:32:"ae14e31ffaf8dea6dd45febc4f7a6004";s:13:"SIGN_CALLBACK";s:32:"94dca2a8462a983c5814a84ff3350cf9";}';
        $data = unserialize($string);
        $transaction = Transaction::create();
        $transaction->set($data);
        $transaction->save();
        die();
    }
}
