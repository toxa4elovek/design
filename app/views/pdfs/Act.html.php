<table style="" width="550" cellspacing="0" border="0" cellpadding="0">
<tr><td colspan="2" style="border-bottom:1px solid;"><h2 style="margin:0">Акт № ХХХХХХ от ХХ.ХХ.ХХХХ</td></tr>
<tr><td valign="top" style="padding-right:1em;">Исполнитель:</td><td valign="top">ООО "КРАУД МЕДИА", г. Санкт-Петербург, ул. Галерная, д. 55, оф. 63, ИНН: 7801563047</td></tr>
<tr><td height="30"></td></tr>
<tr><td valign="top" style="padding-right:1em;">Заказчик:</td><td valign="top">ООО "КРАУД МЕДИА", г. Санкт-Петербург, ул. Галерная, д. 55, оф. 63, ИНН: 7801563047</td></tr>
<tr><td height="30"></td></tr>
<tr><td valign="top" style="padding-right:1em;">По счету:</td><td valign="top">№ ХХХХХХ</td></tr>
</table>

<br />
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
godesigner.ru, за питч № ' . $pitch->id . '. НДС не предусмотрен.</td>
<td style="border-left:1px solid;border-top:1px solid;border-bottom:1px solid; text-align:center;">шт.</td>
<td style="border-left:1px solid;border-top:1px solid;border-bottom:1px solid; text-align:center;">1</td>
<td style="border-left:1px solid;border-top:1px solid;border-bottom:1px solid; text-align:center;">' . $money->formatMoney($pitch->total, array('suffix' => '.00р', 'dropspaces' => true)) . '</td>
<td style="border-left:1px solid;border-top:1px solid;border-bottom:1px solid;border-right:1px solid; text-align:center;">' . $money->formatMoney($pitch->total, array('suffix' => '.00р', 'dropspaces' => true)) . '</td>
</tr>
<tr height="25">
<td height="25" colspan="5" style="text-align:right;"><b>Итого:&nbsp;&nbsp;</b></td>
<td height="25" style="border-left:1px solid;border-bottom:1px solid;border-right:1px solid; text-align:center;">' . $money->formatMoney($pitch->total, array('suffix' => '.00р', 'dropspaces' => true)) . '</td>
</tr>
<tr height="25">
<td height="25" colspan="5" style="text-align:right;"><b>Без НДС:&nbsp;&nbsp;</b></td>
<td height="25" style="border-left:1px solid;border-bottom:1px solid;border-right:1px solid; text-align:center;">---</td>
</tr>
<tr height="25">
<td height="25" colspan="5" style="text-align:right;"><b>Всего к оплате:&nbsp;&nbsp;</b></td>
<td height="25" style="border-left:1px solid;border-bottom:1px solid;border-right:1px solid; text-align:center;"><b>' . $money->formatMoney($pitch->total, array('suffix' => '.00р', 'dropspaces' => true)) . '</b></td>
</tr>
</table>
<p style="">Всего оказано услуг на сумму ' . $money->num2str($pitch->total) . '.</p>
<p style="">Вышеперечисленные услуги выполнены полностью и в срок. Заказчик претензий по объему, качеству и срокам оказания услуг не имеет.</p>
<br /><br /><br />
<table>
<tr>
<td colspan="3"><h3>Исполнитель</h3></td>
<td style="padding:0 2em;"></td>
<td colspan="3"><h3>Заказчик</h3></td>
</tr>
<tr>
<td colspan="3">Генеральный директор</td>
<td></td>
<td colspan="3">хххххххххххххх</td>
</tr>
<tr>
<td style="border-bottom:1px solid;text-align:center;padding:0 6em;"></td>
<td style="padding:0 2em;"></td>
<td style="border-bottom:1px solid;text-align:center;padding:0 1em;">/Федченко М. Ю./</td>
<td></td>
<td style="border-bottom:1px solid;text-align:center;padding:0 6em;"></td>
<td style="padding:0 2em;"></td>
<td style="border-bottom:1px solid;text-align:center;padding:0 1em;">/Федченко М. Ю./</td>
</tr>
<tr>
<td style="text-align:center;"><sup>подпись</sup></td>
<td></td>
<td style="text-align:center;"><sup>расшифровка подписи</sup></td>
<td></td>
<td style="text-align:center;"><sup>подпись</sup></td>
<td></td>
<td style="text-align:center;"><sup>расшифровка подписи</sup></td>
</tr>
<tr>
<td width="180" height="180" style="background:url(' . LITHIUM_APP_PATH . '/webroot/img/logo-01.png' . ')">М.П.</td>
<td colspan="2"></td>
<td style="padding:0 2em;"></td>
<td colspan="3">М.П.</td>
</tr>

</table>
