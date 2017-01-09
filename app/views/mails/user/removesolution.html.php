<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title></title>
</head>
<body style="margin: 0; padding: 0;">
<img src="https://godesigner.ru/img/logo_original-01.png" width="200">

<table>
    <tr><td colspan="3" height="40"></td></tr>
    <tr><td width="5"></td><td valign="top"></td>
        <td>
            <a style="color: #ff585d; line-height: 17px; font-size: 20px; font-weight: bold; font-family: Arial, sans-serif; text-decoration: none;" href="https://godesigner.ru/pitches/view/<?=$pitch->id?>"><?=$pitch->title?> <?=(int) $pitch->price?> Р.-</a><br/>
            <span style="color: #AEAEAE; line-height: 17px; font-size: 11px; font-family: Arial, sans-serif;"><?=$this->view()->render(['template' => 'pitch-info'], ['pitch' => $pitch]);?></span><br/><br/>
            <span style="color: #666666; line-height: 23px; font-size: 14px; font-family: Arial, sans-serif;">
                Здравствуйте, <?=$user['first_name']?>!<br>
                Пожалуйста, соблюдайте правила: <a href="http://godesigner.ru/answers/view/37" target="_blank">http://godesigner.ru/answers/view/37</a><br><br>

                Мы были вынуждены <?php echo (is_null($term)) ? '' : 'приостановить вашу возможность комментирования на ' .
                $term . ' ' . $this->numInflector->formatString($term, ['string' => ['first' => 'день', 'second' => 'дня', 'third' => 'дней']]) .
                ' и ';?>удалить ваше решение #<?=$solution_num?> всвязи с несоблюдением правил и <?php switch ($reason) {
                    case 'plagiat':
                        echo 'неоригинальной идеей.';
                    break;
                    case 'template':
                        echo 'использованием шаблонов.';
                    break;
                    default:
                        echo 'по следующей причине:<br>';
                    break;
                } ?>
                <br><br>
                <?php if (!empty($explanation)):?>
                    <?=$explanation;?>
                    <br><br>
                <?php endif;?>
                <?php if (!is_null($image)):?>
                    <img src="<?=$image;?>">
                    <br><br>
                <?php endif;?>
            </span><br/>
        </td></tr>
    <tr>
        <td colspan="3" height="100"></td>
    </tr>
</table>

</body></html>