<?php

namespace app\controllers;

use app\models\Addon;
use app\models\Expert;
use app\models\Pitch;
use app\extensions\helper\MoneyFormatter;
use app\models\User;

class AddonsController extends AppController
{

    /**
     * Метод добавления записи новой опции
     * @return array|bool|mixed
     */
    public function add()
    {
        if (isset($this->request->data) && ($pitch = Pitch::first($this->request->data['commonPitchData']['id']))) {
            $featuresData = $this->request->data['features'];
            $total = 0;
            if ((int) $pitch->category_id === 20) {
                $subscriber = true;
                $prolongCoeff = 1000;
                $pinnedPrice = 500;
            } else {
                $subscriber = false;
                $prolongCoeff = 1950;
                $pinnedPrice = 1450;
            }
            if (!isset($featuresData['experts'])) {
                $expert = 0;
                $expertId = serialize([]);
            } else {
                $expert = 1;
                $expertId = serialize($featuresData['experts']);
                $expertsAll = Expert::all();
                foreach ($expertsAll as $v) {
                    if (in_array($v->id, $featuresData['experts'])) {
                        $total += $v->price + 500;
                    }
                }
            }
            if (!isset($featuresData['prolong'])) {
                $prolong = 0;
            } else {
                $prolong = $featuresData['prolong'];
                $total += $featuresData['prolong'] * $prolongCoeff;
            }
            $brief = 0;
            $phonebrief = '';
            if ((int) $pitch->brief === 0 && ($featuresData['brief'] > 0)) {
                $brief = 1;
                $phonebrief = $this->request->data['commonPitchData']['phone-brief'];
                $total += 3200;
            }
            $guaranteed = 0;
            if ((int) $pitch->guaranteed === 0 && ($featuresData['guaranteed'] > 0)) {
                $guaranteed = 1;
                $total += 1400;
            }
            $pinned = 0;
            if ((int) $pitch->pinned === 0 && ($featuresData['pinned'] > 0)) {
                $pinned = 1;
                $total += $pinnedPrice;
            }
            $private = 0;
            if ((int) $pitch->private === 0 && ($featuresData['private'] > 0)) {
                $private = 1;
                $total += 3500;
            }
            $gatracking = new \Racecore\GATracking\GATracking('UA-9235854-5');
            $gaId = $gatracking->getClientId();
            $data = [
                'pitch_id' => $this->request->data['commonPitchData']['id'],
                'billed' => 0,
                'experts' => $expert,
                'expert-ids' => $expertId,
                'prolong' => $prolong,
                'prolong-days' => $prolong,
                'brief' => $brief,
                'phone-brief' => $phonebrief,
                'guaranteed' => $guaranteed,
                'pinned' => $pinned,
                'private' => $private,
                'created' => date('Y-m-d H:i:s'),
                'total' => $total,
                'ga_id' => $gaId
            ];
            if (isset($this->request->data['commonPitchData']['addonid']) && $this->request->data['commonPitchData']['addonid'] > 0) {
                $addon = Addon::first($this->request->data['commonPitchData']['addonid']);
            } else {
                $addon = Addon::create();
            }
            $addon->set($data);
            $addon->save();
            if ($subscriber && ((
                $total === 0 && (int) $addon->pinned !== 0 && $addon->brief !== 0
            ) || ($total !== 0))) {
                $paymentResult = User::reduceBalance($this->userHelper->getId(), (int) $total);
                if (!$paymentResult) {
                    $status = 'no_money';
                    $needToFillAmount = ($total - User::getBalance($addon->user_id));
                    $url = '/subscription_plans/subscriber?amount=' . $needToFillAmount;
                } else {
                    $status = 'success';
                    $url = '/pitches/view/' . $addon->pitch_id;
                    $newAddon = Addon::first($addon->id);
                    Addon::activate($newAddon);
                }
                return ['status' => $status, 'redirect' => $url];
            } else {
                return $addon->id;
            }
        }
        return false;
    }


    /**
     * Метод для скачивания счёта оплаты доп. опции
     * @todo Вынести шаблон в отдельный файл
     */
    public function getpdf()
    {
        error_reporting(E_ALL);
        if ($addon = Addon::first($this->request->id)) {
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
godesigner.ru, за проект № ' . $addon->id . '. НДС не предусмотрен.</td>
		<td style="border-left:1px solid;border-top:1px solid;border-bottom:1px solid; text-align:center;">шт.</td>
		<td style="border-left:1px solid;border-top:1px solid;border-bottom:1px solid; text-align:center;">1</td>
		<td style="border-left:1px solid;border-top:1px solid;border-bottom:1px solid; text-align:center;">' . $money->formatMoney($addon->total, ['suffix' => '.00р', 'dropspaces' => true]) . '</td>
		<td style="border-left:1px solid;border-top:1px solid;border-bottom:1px solid;border-right:1px solid; text-align:center;">' . $money->formatMoney($addon->total, ['suffix' => '.00р', 'dropspaces' => true]) . '</td>
	</tr>
	<tr height="25">
		<td height="25" colspan="5" style="text-align:right;"><b>Итого:&nbsp;&nbsp;</b></td>
		<td height="25" style="border-left:1px solid;border-bottom:1px solid;border-right:1px solid; text-align:center;">' . $money->formatMoney($addon->total, ['suffix' => '.00р', 'dropspaces' => true]) . '</td>
	</tr>
	<tr height="25">
		<td height="25" colspan="5" style="text-align:right;"><b>Без НДС:&nbsp;&nbsp;</b></td>
		<td height="25" style="border-left:1px solid;border-bottom:1px solid;border-right:1px solid; text-align:center;">---</td>
	</tr>
	<tr height="25">
		<td height="25" colspan="5" style="text-align:right;"><b>Всего к оплате:&nbsp;&nbsp;</b></td>
		<td height="25" style="border-left:1px solid;border-bottom:1px solid;border-right:1px solid; text-align:center;"><b>' . $money->formatMoney($addon->total, ['suffix' => '.00р', 'dropspaces' => true]) . '</b></td>
	</tr>
</table>
<p style="font-weight:bold; margin-top:20px; font-size: 20px; color:red">Внимание!<br/><span style="font-weight:bold;font-size:13px; color: black;">В назначении платежа указывайте точную фразу из столбца название услуги.</span> <p>
<p style="">Всего наименований 1, на сумму ' . $money->formatMoney($addon->total, ['suffix' => '.00р', 'dropspaces' => true]) . '.<p>
<p style="">' . $money->num2str($addon->total) . '<p>');

            $mpdf->Output('godesigner-pitch-' . $addon->id . '.pdf', 'd');
            exit;
        }
    }
}
