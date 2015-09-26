<h1 style="background: url('/img/images/faq.png') no-repeat scroll 55% 0 transparent;	font-family: 'RodeoC', serif;
                font-size: 12px;
                font-style: normal;
                font-variant: normal;
                font-weight: 400;
                height: 38px;
                line-height: 41px;
                text-align: center;
                text-transform: uppercase; margin-bottom: 50px; margin-top: 20px;">Срок</h1>

<table style="padding-bottom: 0; margin-bottom: 50px;">
    <tr>
        <td style="width: 105px; font-size: 18px; font-family: OfficinaSansC Book, serif; color: #666666"><span style="position: relative; left: -8px; top: -3px;"><?= $category->shortestTimelimit ?> дня</span></td>
        <td style="width: 91px; font-size: 18px; font-family: OfficinaSansC Book, serif; color: #666666"><span style="position: relative; left: 20px; top: -3px;"><?= $category->shortTimelimit ?> дней</span></td>
        <td style="width: 141px; font-size: 18px; font-family: OfficinaSansC Book, serif; color: #666666"><span style="position: relative; left: 47px; top: -3px;"><?= $category->default_timelimit ?> дней</span></td>
        <td style="width: 105px; font-size: 18px; font-family: OfficinaSansC Book, serif; color: #666666"><span style="position: relative; left: 33px; top: -3px;"><?= $category->smallIncreseTimelimit ?> дней</span></td>
        <td style="width: 107px; font-size: 18px; font-family: OfficinaSansC Book, serif; color: #666666"><span style="position: relative; left: 55px; top: -3px;"><?= $category->largeIncreaseTimelimit ?> дней</span></td>
    </tr>
    <tr>
        <td><input style="position: relative; left: 8px;" type="radio" class="short-time-limit" name="short-time-limit" data-option-period="2" <?php if(($pitch) && ($pitch->timelimit == 2)): echo 'checked'; endif;?> data-option-title="Установлен срок" data-option-value="1450" ></td>
        <td><input style="position: relative; left: 40px;" type="radio" class="short-time-limit" name="short-time-limit" data-option-period="1" <?php if(($pitch) && ($pitch->timelimit == 1)): echo 'checked'; endif;?> data-option-title="Установлен срок" data-option-value="950" ></td>
        <td><input style="position: relative; left: 74px;" type="radio" class="short-time-limit" name="short-time-limit" data-option-period="0" <?php if(($pitch) && ($pitch->timelimit == 0)): echo 'checked'; endif;?> data-option-title="Установлен срок" data-option-value="0"></td>
        <td><input style="position: relative; left: 58px;" type="radio" class="short-time-limit" name="short-time-limit" data-option-period="3" <?php if(($pitch) && ($pitch->timelimit == 3)): echo 'checked'; endif;?> data-option-title="Установлен срок" data-option-value="950" ></td>
        <td><input style="position: relative; left: 81px;" type="radio" class="short-time-limit" name="short-time-limit" data-option-period="4" <?php if(($pitch) && ($pitch->timelimit == 4)): echo 'checked'; endif;?> data-option-title="Установлен срок" data-option-value="1450" ></td>
    </tr>
    <tr><td colspan="5"><img style="padding-top: 7px; padding-bottom: 7px;" src="/img/brief/timeline.png" alt="шкала сроков"/></td></tr>
    <tr>
        <td style="font-size: 18px; font-family: OfficinaSansC Book, serif; color: #666666"><span style="position: relative; left: -12px;">+1450р.</span></td>
        <td style="font-size: 18px; font-family: OfficinaSansC Book, serif; color: #666666"><span style="position: relative; left: 23px;">+950р.</span></td>
        <td style="font-size: 18px; font-family: OfficinaSansC Book, serif; color: #666666"><span style="position: relative; left: 45px;">бесплатно</span></td>
        <td style="font-size: 18px; font-family: OfficinaSansC Book, serif; color: #666666"><span style="position: relative; left: 42px;">+950р.</span></td>
        <td style="font-size: 18px; font-family: OfficinaSansC Book, serif; color: #666666"><span style="position: relative; left: 62px;">+1450р.</span></td>
    </tr>
</table>