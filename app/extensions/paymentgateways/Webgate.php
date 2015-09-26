<?php
namespace app\extensions\paymentgateways;

use \app\models\Pitch;
use \lithium\net\http\Service;

class Webgate extends \lithium\core\Object {

    protected $_model = '\app\models\Pitch';
    //protected $_terminal = '10000059';
    protected $_terminal = '71846655';
    //protected $_secretword = 'mO74WC9rnOJu';
    protected $_secretword = 'ge6biTwUghs78g73sY6';
    //protected $_rollbackUrl = 'https://pay.masterbank.ru/acquiring/rollback';
    protected $_rollbackUrl = 'http://godesigner.ru/callback';


    public function getOrderData($id) {
        $row = Pitch::first($id);
        $row->total = $row->price + $row->fee;
        return $row->data();
    }

    public function rollback($data) {
        $config = array(
            'persistent' => false,
            'scheme'     => 'https',
            'host'       => 'pay.masterbank.ru',
            'port'       => null,
            'timeout'    => 30,
            'auth'       => null,
            'username'   => null,
            'password'   => null,
            'encoding'   => 'UTF-8',
            'socket'     => 'Context');
        $service = new Service($config);
        $result = $service->post('acquiring/rollback', $data);
        return $result;
    }

    public function close($data) {
        $config = array(
            'persistent' => false,
            'scheme'     => 'https',
            'host'       => 'pay.masterbank.ru',
            'port'       => null,
            'timeout'    => 30,
            'auth'       => null,
            'username'   => null,
            'password'   => null,
            'encoding'   => 'UTF-8',
            'socket'     => 'Context');
        $service = new Service($config);
        $result = $service->post('acquiring/close', $data);
        return $result;
    }

    public function generateSign($data) {
        if(!isset($data['timestamp'])) {
            $data['timestamp'] = gmdate("YmdHis", time());
        }
        /*var_dump($this->_terminal);
        var_dump($data['timestamp']);
        var_dump($data['id']);
        var_dump($data['total']);
        var_dump($this->_secretword);
        die();    */
        $sign = md5($this->_terminal.$data['timestamp'].$data['id'].$data['total'].$this->_secretword);
        return $sign;
    }



}