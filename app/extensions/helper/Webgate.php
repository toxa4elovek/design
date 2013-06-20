<?php
namespace app\extensions\helper;

class Webgate extends \app\extensions\paymentgateways\Webgate {

    public function renderHiddenInputs($pitchId) {
        //$pitchData = $this->getOrderData($pitchId);
        $pitchData = array('total' => 3500, 'id' => $pitchId);
        $timestamp = gmdate("YmdHis", time());
        $string = '<input type="HIDDEN" value="' . $pitchId .'" name="ORDER">
			<input type="HIDDEN" value="' . $pitchData['total'] . '" name="AMOUNT">
			<input type="HIDDEN" value="http://godesigner.ru/pitches/success/" name="MERCH_URL">
			<input type="HIDDEN" value="'.$this->_terminal.'" name="TERMINAL">
			<input type="HIDDEN" value="'.$timestamp.'" name="TIMESTAMP">
			<input type="HIDDEN" value="nyudmitriy@godesigner.ru" name="EMAIL">
			<input type="HIDDEN" value="'.$this->generateSign($pitchData).'" NAME="SIGN">';

        return $string;
    }







}
