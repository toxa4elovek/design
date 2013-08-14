<?php

namespace app\controllers;

use \app\models\Addon;
use \app\models\Pitch;
use \app\extensions\helper\MoneyFormatter;

class AddonsController extends \app\controllers\AppController {


    public function add() {
        if(isset($this->request->data)) {
            $pitch = Pitch::first($this->request->data['commonPitchData']['id']);
            $featuresData = $this->request->data['features'];
            $total = 0;
            if(!isset($featuresData['experts'])) {
                $expert = 0;
                $expertId = serialize(array());
            }else {
                $expert = 1;
                $expertId = serialize($featuresData['experts']);
                $total += count($featuresData['experts']) * 1000;
            }
            if(!isset($featuresData['prolong'])) {
                $prolong = 0;
            }else {
                $prolong = $featuresData['prolong'];
                $total += $featuresData['prolong'] * 1950;
            }
            $brief = 0;
            $phonebrief = '';
            if(($featuresData['brief'] > 0) && $pitch->brief == 0) {
                $brief = 1;
                $phonebrief = $this->request->data['commonPitchData']['phone-brief'];
                $total += 1750;
            }
            $data = array(
                'pitch_id' => $this->request->data['commonPitchData']['id'],
                'billed' => 0,
                'experts' => $expert,
                'expert-ids' => $expertId,
                'prolong' => $prolong,
                'prolong-days' => $prolong,
                'brief' => $brief,
                'phone-brief' => $phonebrief,
                'created' => date('Y-m-d H:i:s'),
                'total' => $total
            );
            if(isset($this->request->data['commonPitchData']['addonid']) && $this->request->data['commonPitchData']['addonid'] > 0) {
                $addon = Addon::first($this->request->data['commonPitchData']['addonid']);
            }else {
                $addon = Addon::create();
            }
            $addon->set($data);
            $addon->save();
            return $addon->id;
        }
        return false;




    }


    public function getpdf() {
        error_reporting(E_ALL);
        if($addon = Addon::first($this->request->id)) {
            require_once(LITHIUM_APP_PATH . '/' . 'libraries' . '/' . 'MPDF54/MPDF54/mpdf.php');
            $money = new MoneyFormatter();
            $mpdf = new \mPDF();

            $mpdf->WriteHTML('
<table style="" width="550" cellspacing="0" border="0" cellpadding="1">
	<tr ><td width="275"><img src="' . LITHIUM_APP_PATH . '/webroot/img/logo-01.png' . '" width="180"></td>
	<td>ООО "КРАУД МЕДИА"<br/>Юридический адрес: 199397, г. Санкт-Петербург<br>ул. Беринга, дом 27</td></tr>
	<tr ><td colspan="2" style="text-align:center"><br><br>Образец заполнения платежного поручения</td></tr>
</table>
<br/>
<br/>
<br/>
<table style="" width="550" cellspacing="0" cellpadding="1">
	<tr height="25">
		<td style="border-left:1px solid;border-top:1px solid;" width="180">ИНН 7801563047</td>
		<td style="border-left:1px solid;border-top:1px solid;" width="180">КПП 780101001</td>
		<td style="border-left:1px solid;border-top:1px solid;" width="40">&nbsp;</td>
		<td style="border-left:1px solid;border-top:1px solid;border-right:1px solid;" width="100">&nbsp;</td>
	</tr>

	<tr height="100">
		<td height="25" colspan="2" style="border-left:1px solid;border-top:1px solid;">Получатель:<br>ООО "КРАУД МЕДИА"</td>
		<td height="25" style="border-left:1px solid;">Сч. №</td>
		<td height="25" style="border-left:1px solid;border-right:1px solid;text-align:center;">40702810107375005023</td>
	</tr>

	<tr>
		<td height="25" rowspan="2"  height="50" colspan="2" style="border-left:1px solid;border-top:1px solid;border-bottom:1px solid;">Банк получателя:<br>ФКБ "САНКТ-ПЕТЕРБУРГ" "МАСТЕР-БАНК"(ОАО) г. САНКТ-ПЕТЕРБУРГ</td>
		<td height="25" style="border-left:1px solid;border-top:1px solid;">БИК</td>
		<td height="25" style="border-left:1px solid;border-top:1px solid;border-right:1px solid;text-align:center;">044030737</td>
	</tr>

	<tr>
		<td height="25" style="border-left:1px solid;border-top:1px solid;border-bottom:1px solid;">Сч. №</td>
		<td height="25" rowspan="2" style="border-left:1px solid;border-top:1px solid;border-bottom:1px solid;border-right:1px solid;text-align:center;">30101810400000000737</td>
	</tr>
</table>
<H2 style="margin-top:50px">СЧЕТ № ' . $addon->id . ' от ' . date('d.m.Y', strtotime($addon->created)) . '</H2>
<table style="" width="550" cellspacing="0" cellpadding="1">
	<tr height="25">
		<td style="border-left:1px solid;border-top:1px solid; text-align:center;" width="25">№</td>
		<td style="border-left:1px solid;border-top:1px solid; text-align:center;">Название товара, работ, услуг</td>
		<td style="border-left:1px solid;border-top:1px solid; text-align:center;" width="40">Ед. изм.</td>
		<td style="border-left:1px solid;border-top:1px solid; text-align:center;" width="40">Кол-во</td>
		<td style="border-left:1px solid;border-top:1px solid; text-align:center;" width="70">Цена</td>
		<td style="border-left:1px solid;border-top:1px solid;border-right:1px solid; text-align:center;" width="70">Сумма</td>
	</tr>
	<tr  valign="top">
		<td style="border-left:1px solid;border-top:1px solid;border-bottom:1px solid; text-align:center;">1</td>
		<td style="border-left:1px solid;border-top:1px solid;border-bottom:1px solid; text-align:center;">Оказание услуг на условиях агентского соглашения, размещённого на сайте
godesigner.ru, за питч № ' . $addon->id . '. НДС не предусмотрен.</td>
		<td style="border-left:1px solid;border-top:1px solid;border-bottom:1px solid; text-align:center;">шт.</td>
		<td style="border-left:1px solid;border-top:1px solid;border-bottom:1px solid; text-align:center;">1</td>
		<td style="border-left:1px solid;border-top:1px solid;border-bottom:1px solid; text-align:center;">' . $money->formatMoney($addon->total, array('suffix' => '.00р', 'dropspaces' => true)) . '</td>
		<td style="border-left:1px solid;border-top:1px solid;border-bottom:1px solid;border-right:1px solid; text-align:center;">' . $money->formatMoney($addon->total, array('suffix' => '.00р', 'dropspaces' => true)) . '</td>
	</tr>
	<tr height="25">
		<td height="25" colspan="5" style="text-align:right;"><b>Итого:&nbsp;&nbsp;</b></td>
		<td height="25" style="border-left:1px solid;border-bottom:1px solid;border-right:1px solid; text-align:center;">' . $money->formatMoney($addon->total, array('suffix' => '.00р', 'dropspaces' => true)) . '</td>
	</tr>
	<tr height="25">
		<td height="25" colspan="5" style="text-align:right;"><b>Без НДС:&nbsp;&nbsp;</b></td>
		<td height="25" style="border-left:1px solid;border-bottom:1px solid;border-right:1px solid; text-align:center;">---</td>
	</tr>
	<tr height="25">
		<td height="25" colspan="5" style="text-align:right;"><b>Всего к оплате:&nbsp;&nbsp;</b></td>
		<td height="25" style="border-left:1px solid;border-bottom:1px solid;border-right:1px solid; text-align:center;"><b>' . $money->formatMoney($addon->total, array('suffix' => '.00р', 'dropspaces' => true)) . '</b></td>
	</tr>
</table>
<p style="font-weight:bold; margin-top:20px; font-size: 20px; color:red">Внимание!<br/><span style="font-weight:bold;font-size:13px; color: black;">В назначении платежа указывайте точную фразу из столбца название услуги.</span> <p>
<p style="">Всего наименований 1, на сумму ' . $money->formatMoney($addon->total, array('suffix' => '.00р', 'dropspaces' => true)) . '.<p>
<p style="">' . $money->num2str($addon->total) . '<p>');

            $mpdf->Output('godesigner-pitch-' . $addon->id . '.pdf', 'd');
            exit;
        }
    }

}

?>