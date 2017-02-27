<!DOCTYPE  html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/><title>peredacha_prav</title><style type="text/css"> * {margin:0; padding:0; text-indent:0; }
        body {line-height: 1.4}
        .s1 { color: black; font-family:"Times New Roman", serif; font-style: normal; font-weight: normal; text-decoration: none; font-size: 10pt; }
        .s2 { color: #4D4F5F; font-family:"Book Antiqua", serif; font-style: normal; font-weight: normal; text-decoration: none; font-size: 14pt; }
        .p, p { color: #282A33; font-family:OfficinaSansBookCRegular; font-style: normal; font-weight: normal; text-decoration: none; font-size: 9pt; margin:0pt; }
        .s3 { color: #282A33; font-family:OfficinaSansBookCRegular; font-style: normal; font-weight: normal; text-decoration: underline; font-size: 11pt; }
        .s4 { color: #4D4F5F; font-family:"Book Antiqua", serif; font-style: normal; font-weight: normal; text-decoration: none; font-size: 11pt; }
        h1 { color: #282A33; font-family:"Times New Roman", serif; font-style: normal; font-weight: bold; text-decoration: none; font-size: 13pt; }
    </style></head><body>
    <div style="position: absolute; top:0; width: 100%; height: 108px; background: url(/var/godesigner/webroot/img/pdf/stripes.png) repeat-x; background-size: 6%;">

    </div>
<table style="width:100%">
    <tr>
        <td height="160" colspan="3"></td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: center;">
            <p class="s2">ПЕРЕДАЧА ИСКЛЮЧИТЕЛЬНОГО<br> ПРАВА НА ПРОИЗВЕДЕНИЕ </p>
        </td>
    </tr>
    <tr>
        <td height="40" colspan="3"></td>
    </tr>
    <tr>
        <td width="100"></td>
        <td style="line-height: 22px;">
            <p style="line-height: 22px;">Настоящее соглашение заключено в соответствии с Договором-офертой о передаче в полном объеме исключительного права на произведение между Исполнителем, действующим от своего имени: <?php if(isset($designerData['fio']) && $designerData['fio'] !== ''): echo $designerData['fio']; else: echo $designerData['wmr-fio']; endif;?>, <?=$designerData['passseries']?> <?=$designerData['passnum']?> <?=$designerData['issuedby']?>, <?=$designerData['addresss']?> (далее – Автор, Исполнитель), с одной стороны, и  Заказчиком: <?php
                switch($clientData['documentsfor']):
                    case 'company': ?><?=$clientData['documentsfor_companyname']?>, <?=$clientData['documentsfor_address']?>, ИНН <?=$clientData['documentsfor_inn']?>,<?php if($clientData['documentsfor_orgn'] !== ''):?>ОГРН <?=$clientData['documentsfor_orgn']?>,<?php endif?> в лице: <?=$clientData['documentsfor_fio']?><?php break;
                    case 'individual': ?>Индивидуальный предприниматель <?=$clientData['documentsfor_fio']?>, <?=$clientData['documentsfor_address']?>, ИНН <?=$clientData['documentsfor_inn']?>, <?php if($clientData['documentsfor_orgn'] !== ''):?>ОГРН <?=$clientData['documentsfor_orgn']?>,<?php endif?> действующим от своего имени<?php break;
                    case 'simpleclient': ?><?=$clientData['documentsfor_fio']?>, <?=$clientData['documentsfor_address']?>, действующим от своего имени<?php break;
                endswitch;
                ?> (далее – Заказчик), с другой стороны, совместно именуемые — Стороны, о нижеследующем:</p><br>
            <p>Исполнитель передает Заказчику в полном объеме все принадлежащие ему исключительные права на следующее произведение — Работу в проекте «<?= $project->title?>» (далее – Произведение).</p><br>
            <p>Настоящий Договор является договором об отчуждении исключительных прав
                на Произведение, в соответствии с которым исключительные права на Произведение переходят к Заказчику в полном объеме, в отношении любых видов использования
                на территории всего мира и в течение всего срока действия исключительных прав, с правом передачи полностью или частично.</p><br>
            <p>За передачу прав в соответствии с настоящим Договором Исполнитель получит единоразовое вознаграждение в размере <?= $project->price?> руб. Автор разрешает Заказчику осуществить обнародование Произведения любым способом по усмотрению Заказчика. Автор гарантирует, что заключение настоящего Договора не приведет к нарушению авторских прав или иных прав интеллектуальной собственности третьих лиц, а также, что им не заключались и не будут заключаться в дальнейшем какие-либо договоры, предусматривающие отчуждение прав на Произведения или предоставление каких-либо исключительных или неисключительных лицензий на использование Произведения.</p><br>
            <p>Во всем, что прямо не урегулировано настоящим Договором, Стороны руководствуются Договором-офертой о передаче в полном объеме исключительного права
                на произведение, доступным в сети Интернет по адресу https://godesigner.ru/pitches/viewsolution/<?=$project->awarded?>,
                и законодательством Российской Федерации. Настоящий Договор составлен в двух имеющих юридическую силу экземплярах по одному для каждой из Сторон.</p>
        </td>
        <td width="100"></td>
    </tr>

    <tr>
        <td height="35" colspan="3"></td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: center;">
            <p class="s2" style="line-height: 121%;">ПОДПИСИ СТОРОН</p>
        </td>
    </tr>
    <tr>
        <td height="35" colspan="3"></td>
    </tr>
    <tr>
        <td colspan="3">
            <table style="width:100%;">
                <tr>
                    <td width="100"></td>
                    <td style="text-align: left;"><h1 style="padding-top: 2pt;padding-left: 78pt;text-indent: 0pt;">Исполнитель</h1></td>
                    <td width="240"></td>
                    <td style="text-align: right;"><h1 style="padding-top: 2pt;padding-left: 78pt;text-indent: 0pt;">Заказчик</h1></td>
                    <td width="100"></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td height="85" colspan="3"></td>
    </tr>
</table>
    <div style="position: absolute; bottom:-45; width: 100%; height: 108px; background: url(/var/godesigner/webroot/img/pdf/stripes.png) repeat-x; background-size: 6%;">
    </div>
</body>
</html>
