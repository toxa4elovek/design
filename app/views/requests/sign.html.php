<div class="wrapper pitchpanel login">

<?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo', 'header' => 'header2'))?>

<div class="middle">
<div class="middle_inner_gallery" style="padding-top:25px">

    <div style="margin-left:280px;width: 560px; height:70px;margin-bottom:40px;">
        <table class="pitch-info-table" border="1">
            <tr><td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1"><span class="regular">Гонорар:</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pitch-info-text"><?=$this->moneyFormatter->formatMoney($pitch->price, array('suffix' => 'р.-'))?></span></td>
                <td width="15"></td>
                <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;"><span class="regular">Заказчик:</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$this->html->link($this->nameInflector->renderName($pitch->user->first_name, $pitch->user->last_name), array('users::view', 'id' => $pitch->user->id), array('class' => 'client-linknew'))?></td></tr>
            <tr><td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;"><span class="regular">Решений:</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pitch-info-text"><?=$pitch->ideas_count ?></span></td>
                <td width="15"></td>
                <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;"><span class="regular">Был online:</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pitch-info-text"><?=date('d.m.Y', strtotime($pitch->user->lastActionTime))?> в <?=date('H:i', strtotime($pitch->user->lastActionTime)) ?></span></td></tr>
            <tr>
                <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;"><span class="regular">Просмотры брифа:</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pitch-info-text"><?=$pitch->views?></span></td>
                <td width="15"></td>
                <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
                    <span class="regular">Срок:</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if($pitch->status == 0):?>
                    <span class="pitch-info-text"><?=preg_replace('@(м).*@', '$1. ', preg_replace('@(ч).*@', '$1. ', preg_replace('@(.*)(дн).*?\s@', '$1$2. ', $pitch->startedHuman)))?></span>
                    <?php elseif($pitch->status == 1):?>
                    <span class="pitch-info-text">Выбор победителя</span>
                    <?php elseif($pitch->status == 2):?>
                    <span class="pitch-info-text">Питч завершен</span>
                    <?php endif?>
                </td>
            </tr>
        </table>
    </div>

    <div id="pitch-title" style="height:36px;margin-bottom:5px;">
        <div class="breadcrumbs-view" style="width:770px;float:left;">
            <a href="/pitches">Все питчи /</a> <a href="/pitches/view/<?=$pitch->id?>"><?=$pitch->title?></a>

        </div>
        <?php if($pitch->status == 0):?>
            <?= $this->view()->render(array('element' => 'pitch-info/favourite_status'), array('pitch' => $pitch))?>
        <?php endif?>
    </div>

    <div style="float:left; width: 627px;padding-bottom: 40px;">
        <h2 class="largest-header" style="text-align: center; margin-bottom: 30px;">ЭТО <a target="_blank" href="http://www.godesigner.ru/answers/view/64">ЗАКРЫТЫЙ ПИТЧ</a><br/> И ВАМ НУЖНО ПОДПИСАТЬ<br/> СОГЛАШЕНИЕ О НЕРАЗГЛАШЕНИИ!<h2>

        <p class="regular">В закрытом питче вы сможете увидеть только свои работы, а также комментарии, оставленные только вам или всем дизайнерам. Вы не можете использовать свои решения для данного питча в портфолио без изменения имени бренда, заказчика и его данных.</p>
    </div>
    <div style="float:left; width: 180px;margin-right:20px; margin-bottom: 20px;margin-left:30px;">
        <p class="regular">Вы можете участвовать в закрытом питче только в случае, если подтвердите <a target="_blank" href="/pitches/agreement/<?=$pitch->id?>.txt">соглашение о неразглашении</a>.</p>
    </div>
    <div style="height:2px;clear:both;width:580px;background: url(/img/obnovleniya_line.jpg) repeat-x scroll 0 100% transparent; margin-bottom: 15px;margin-top:15px;"></div>
    <div style="float:left; width: 627px;">
        <h3 class="greyboldheader" style="display:inline;">Соглашение о неразглашении</h3>
        <a style="margin-left:270px;" href="#" id="expand">раскрыть</a>
        <div id="tos" style="overflow: scroll; height:300px;" class="regular">
            СОГЛАШЕНИЕ О НЕРАЗГЛАШЕНИИ<br><br>

            Исполнитель, в лице предварительно зарегистрированного пользователя интернет-сервиса Go Designer, акцептировавшего данное соглашение именуемый далее «Принимающая сторона», с одной стороны, и Заказчик <?=$this->NameInflector->renderName($pitch->user->first_name, $pitch->user->last_name)?> именуемый далее «Раскрывающая сторона», с другой стороны, далее именуемые совместно «Стороны», заключили настоящее Соглашение о нижеследующем:<br><br>

            1. ОПРЕДЕЛЕНИЯ<br>
            Для целей настоящего Соглашения:<br>
            «Раскрывающая сторона» - Сторона, которая на законных основаниях владеет Конфиденциальной информацией  и передает ее в пользование Принимающей стороне  на условиях настоящего Соглашения.<br>
            «Принимающая сторона» - Сторона, которая принимает в пользование Конфиденциальную информацию от Раскрывающей стороны на условиях настоящего Соглашения.<br>
            «Конфиденциальная информация»  -  любая информация или данные любого характера, опубликованные Раскрывающей стороной Принимающей стороне с даты заключения настоящего соглашения и в течение всего срока действия настоящего Соглашения, составляющие служебную или коммерческую тайны в понятии данном ст. 139 ГК РФ, и которые принадлежат, контролируются или находятся во владении Раскрывающей стороны, охраняются Раскрывающей стороной разумными средствами от разглашения третьими сторонами, представляют коммерческую ценность и обозначаются такой Стороной как «Конфиденциальная информация».<br>
            Информация не будет считаться Конфиденциальной и Принимающая сторона не будет иметь никаких обязательств в отношении данной информации, если на дату подписания настоящего Соглашения эта информация:<br>
            -	является  общедоступной (используется в печати и иных средствах массовой информации);<br>
            -    была известна на законном основании Принимающей стороне до ее раскрытия Раскрывающей стороной;<br>
            -    разрешена к распространению с письменного согласия Раскрывающей стороны;<br>
            -    не может считаться конфиденциальной в соответствии с действующим законодательством.<br><br>

            2. ПРЕДМЕТ СОГЛАШЕНИЯ<br>
            2.1 Раскрывающая сторона обязуется передать Принимающей стороне  во временное пользование Конфиденциальную информацию, а Принимающая сторона обязуется принять, обеспечить сохранность, неразглашение Конфиденциальной информации и использовать исключительно в целях реализации совместного проекта на условиях агентского договора сайта godesigner.ru.<br>

            3. ОБЯЗАННОСТИ СТОРОН<br>
            Принимающая сторона обязуется:<br>
            3.1 Использовать Конфиденциальную информацию исключительно в целях реализации совместного проекта на условиях агентского договора сайта godesigner.ru.<br>
            3.2 Обеспечить конфиденциальность информации, определенной Раскрывающей стороной как «Конфиденциальная», в течение срока действия данного Соглашения, а также в течение 3 (трех) лет с момента предоставления Раскрывающей стороной такой информации.<br>
            3.3 Не передавать Конфиденциальную информацию третьей стороне без предварительного письменного согласия Раскрывающей стороны.<br>
            3.4 Конфиденциальная информация может быть раскрыта в соответствии с законодательством Российской Федерации или по предъявлению законного требования государственных или иных компетентных органов Российской Федерации только в объеме поступившего запроса.<br>
            3.5 Принимающая сторона обязана соблюдать столь же высокую  степень конфиденциальности  во избежание разглашения  или использования Конфиденциальной информации, какую Принимающая сторона  соблюдала бы  в отношении своей собственной  Конфиденциальной информации.<br>
            3.6 Конфиденциальная информация, передаваемая Раскрывающей стороной Принимающей стороне в какой-либо форме согласно настоящему Соглашению, будет и останется исключительной собственностью Раскрывающей стороны, и переданная информация и  любые ее копии должны немедленно возвращаться по письменному требованию Раскрывающей стороне.<br><br>

            4. ОТВЕТСТВЕННОСТЬ<br>
            4.1 Принимающая сторона несет перед Раскрывающей стороной ответственность за разглашение и раскрытие Конфиденциальной информации;<br>
            4.2 В случае установления факта разглашения Принимающей стороной Конфиденциальной информации Принимающая сторона обязана:<br>
            уплатить Раскрывающей стороне штрафную неустойку в размере эквивалентном 1 000 000 руб. (один миллион рублей РФ) за каждый отдельный факт разглашения Конфиденциальной информации, но не более суммы эквивалентной 3 000 000 руб. (три миллиона рублей РФ) за все нарушения в части разглашения Конфиденциальной информации в совокупности.<br><br>

            5. СРОК  ДЕЙСТВИЯ СОГЛАШЕНИЯ<br>
            5.1 Соглашение вступает в силу с даты акцептирования его Принимающей стороной и действует 3 (три) года с момента предоставления Раскрывающей стороной Принимающей стороне Конфиденциальной информации. Срок действия Соглашения может быть пролонгирован.<br><br>

            6. РЕОРГАНИЗАЦИЯ И ЛИКВИДАЦИЯ<br>
            6.1. В случае реорганизации любой из Сторон настоящего Соглашения все права и обязанности Сторон по настоящему Соглашению переходят к правопреемникам реорганизованной Стороны и такие правопреемники будут нести все права и обязанности по настоящему Соглашению в отношении другой Стороны.<br><br>

            7. РАЗРЕШЕНИЕ СПОРОВ<br>
            7.1 Стороны примут все необходимые меры для урегулирования споров путем переговоров.<br>
            При невозможности решения разногласий путем переговоров,  все споры, разногласия или требования, возникающие  из настоящего Соглашения или в связи с ним, подлежат разрешению в Арбитражном суде г. Санкт-Петербург.<br><br>

            8. ПРОЧИЕ УСЛОВИЯ<br>
            8.1 Отношения Сторон по настоящему Соглашению регулируются правом Российской Федерации.<br>
            8.2. Все приложения, изменения и дополнения к настоящему Соглашению должны быть совершены в письменной форме и подписаны уполномоченными представителями Сторон.<br><br>

            9. УВЕДОМЛЕНИЯ<br>
            9.1 Вся Конфиденциальная Информация считается надлежащим образом представленной, если акцептированна Принимающей стороной с помощью акцепта средствами сайта godesigner.ru<br><br>
        <a style="display: none;" id="shrink">свернуть</a>
        </div>
        <div style="height:2px;clear:both;width:580px;background: url(/img/obnovleniya_line.jpg) repeat-x scroll 0 100% transparent; margin-bottom: 15px;margin-top:15px;"></div>
        <h3 class="greyboldheader" style="margin-bottom:10px;">Оставьте свои настоящие имя и фамилию</h3>
        <form action="/requests/create" method="post">
            <input type="hidden" name="pitch_id" value="<?=$pitch->id?>">
            <input style="width: 580px; margin-bottom: 15px;" type="text" name="first_name" placeholder="Имя"/>
            <input style="width: 580px; margin-bottom: 15px;" type="text" name="last_name" placeholder="Фамилия"/>
            <div class="tos-container supplement" style="margin-bottom: 20px;">
                <label><input type="checkbox" name="tos" style="vertical-align: middle; margin-right: 5px;"/>Подписывая соглашение о неразглашении, я соглашаюсь со всеми условиями, описанными в договоре о неразглашении.</label>
            </div>
            <input type="submit" id="submit" class="button" value="Отправить и ознакомиться с брифом" style="margin-left:120px;" />
        </form>
    </div>
    <div style="float:left; width: 180px;margin-right:20px; margin-bottom: 20px; margin-left:30px;padding-top:280px;">
        <p class="regular" style="margin-bottom: 20px;">Если вы пропишите ложные имя и фамилию, ваш аккаунт может быть блокирован, а денежные средства за победу не перечислены.</p>
        <p class="regular">Поставив галочку в поле, вы подтверждаете, что никому не расскажете об этом питче и о том, что прописано в брифе.</p>
    </div>
    <div style="clear:both"></div>




</div><!-- /solution -->
<div id="under_middle_inner"></div><!-- /under_middle_inner -->
</div>

</div><!-- /middle_inner -->


</div><!-- /middle -->

</div><!-- .wrapper -->

<div id="bridge" style="display:none;"></div>
<?=$this->html->script(array('jcarousellite_1.0.1.js', 'jquery.simplemodal-1.4.2.js', 'jquery.scrollto.min.js', 'requests/sign.js?' . mt_rand(100, 999)), array('inline' => false))?>
<?=$this->html->style(array('/view', '/messages12', '/pitches12', '/pitch_overview'), array('inline' => false))?>