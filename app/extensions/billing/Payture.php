<?php

namespace app\extensions\billing;

use \lithium\net\http\Service;

class Payture {

    public static $url = 'sandbox.payture.com';
    public static $key = 'MerchantGoDesigner';
    public static $password = '123';
    public static $scheme = 'https';

    /**
     * Метод инициирует платеж и получает идентификатор сессии
     *
     * @param $data
     * @return mixed
     */
    public static function init($data) {
        $config = array(
            'scheme'     => self::$scheme,
            'host'       => self::$url
        );
        $service = new Service($config);
        $result = $service->get(self::__formUrl('Init'), self::__formRequestData($data));
        return self::__getArrayFromXml($result);
    }

    /**
     * Метод позволяет получить статус заказа
     *
     * @param $orderId
     * @return mixed
     */
    public static function payStatus($orderId){
        $config = array(
            'scheme'     => self::$scheme,
            'host'       => self::$url
        );
        $service = new Service($config);
        $result = $service->get(self::__formUrl('PayStatus'), self::__formOrderRequestData($orderId));
        return self::__getArrayFromXml($result);
    }

    public static function refund($orderId, $amount){
        $config = array(
            'scheme'     => self::$scheme,
            'host'       => self::$url
        );
        $service = new Service($config);
        $result = $service->get(self::__formUrl('Refund'), self::__formOrderRefundData($orderId, $amount));
        return self::__getArrayFromXml($result);
    }

    /**
     * Метод генерирует адрес для оплаты заказа
     *
     * @param $sessionId
     * @return string
     */
    public static function pay($sessionId) {
        return self::$scheme . '://' . self::$url . '/apim/Pay?SessionId=' . $sessionId;
    }

    /**
     * Метод формирует часть адресной строчки
     *
     * @param $methodName
     * @return string
     */
    private static function __formUrl($methodName) {
        return 'apim/' . $methodName;
    }

    /**
     * Метод конвертирует xml в читабельный массив
     *
     * @param $xml
     * @return mixed
     */
    private static function __getArrayFromXml($xml) {
        $xml = simplexml_load_string($xml);
        $json = json_encode($xml);
        $array = json_decode($json, true);
        return $array['@attributes'];
    }

    /**
     * Метод формирует массив для запроса Init
     * @param $data
     * @return array
     */
    private static function __formRequestData($data) {
        return array(
            'Key' => self::$key,
            'Data' => http_build_query($data, null, ';')
        );
    }

    /**
     * Метод формирует массив для запроса PayStatus
     *
     * @param $orderId
     * @return array
     */
    private static function __formOrderRequestData($orderId) {
        return array(
            'Key' => self::$key,
            'OrderId' => $orderId
        );
    }

    private static function __formOrderRefundData($orderId, $amount) {
        return array(
            'Key' => self::$key,
            'Password' => self::$password,
            'OrderId' => $orderId,
            'Amount' => $amount
        );
    }

}