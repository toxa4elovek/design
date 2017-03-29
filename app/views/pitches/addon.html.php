<div class="wrapper">

<?=$this->view()->render(['element' => 'header'], ['header' => 'header2'])?>

<input type="hidden" value="<?=$pitch->id?>" id="pitch_id"/>
<input type="hidden" value="" id="addon_id"/>
<input type="hidden" value="<?=$pitch->billed?>" id="billed"/>
<input type="hidden" value="<?=$pitch->published?>" id="published"/>

<aside class="summary-price expanded">
    <h3>Итого:</h3>
    <p class="summary"><strong id="total-tag">0.-</strong></p><!-- .summary -->
    <ul id="check-tag">
    </ul>
    <a href="#" class="show" id="show-check"><span>Подробнее</span></a>
    <a href="#" class="hide" id="hide-check"><span>Скрыть</span></a>
</aside><!-- .summary-price -->

<div class="middle add-pitch" id="step2">

    <div class="main">

        <h1 style="background: url('/img/images/faq.png') no-repeat scroll 55% 0 transparent;	font-family: 'RodeoC', serif;
            font-size: 12px;
            font-style: normal;
            font-variant: normal;
            font-weight: 400;
            height: 38px;
            line-height: 41px;
            text-align: center;
            text-transform: uppercase;margin-bottom:20px;">Дополнительные опции</h1>
        <?php if ((int) $pitch->status === 0):?>
        <div class="ribbon">
            <p class="option"><label><input type="checkbox" name="" class="single-check" <?php if (isset($this->_request->query['click']) && $this->_request->query['click'] == 'prolong'): echo 'checked'; endif?> data-option-title="продлить срок" data-option-value="<?= $prolongCoeff?>" id="prolong-checkbox">Продлить срок</label></p>
            <?php if ((int) $pitch->category_id === 20):?>
            <p class="description">Укажите количество дней, на которое вы хотите продлить проект. Каждый день стоит 1 000 Р.-, которая будет добавлена на увеличение гонорара.
            <?php else:?>
            <p class="description">Укажите количество дней, на которое вы хотите продлить проект. Каждый день стоит 1 950 Р.-, из которых<br> 1 000Р.- добавляется в счет гонорара для дизайнера.
            <?php endif; ?>
                <a href="#" class="second tooltip" title="">(?)</a></p>
            <p class="label <?php if (isset($this->_request->query['click']) && $this->_request->query['click'] === 'prolong'): echo 'unfold'; endif?>" id="prolong-label" style="font:16px/68px "RodeoC",sans-serif">+<?= $prolongCoeff?>.-</p>
        </div>
        <?php endif ?>

        <div>
            <input type="prolong-num" data-option-title="продлить срок" id="sub-prolong" <?php if (isset($this->_request->query['click']) && $this->_request->query['click'] == 'prolong'):?>placeholder="1"<?php endif;?> class="placeholder initial-price" style="display:none;<?php if (isset($this->_request->query['click']) && $this->_request->query['click'] == 'prolong'): echo 'display:block;'; endif?>height: 78px; width: 268px; font-size: 48px; margin-bottom: 15px; margin-top: 2px; margin-left: 24px; border: medium none; box-shadow: 0px 3px 2px rgba(0, 0, 0, 0.2) inset; padding-left: 15px;"/>
        </div>
        <?php
        $briefValue = 3200;
        if ($this->user->isSubscriptionActive()):
            foreach ($this->user->getCurrentPlanData($pitch->user_id)['free'] as $index => $value):
                if ('phonebrief' === $value):
                    $briefValue = 0;
                endif;
            endforeach;
        endif;
        if (((int) $pitch->brief === '0') && ((int) $pitch->status === 0)): ?>
        <div class="ribbon complete-brief">
            <p class="option"><label><input  type="checkbox" name="" <?php if (isset($this->_request->query['click']) && $this->_request->query['click'] == 'phonebrief'): echo 'checked'; endif?> class="single-check" data-option-title="Заполнение брифа" data-option-value="<?=$briefValue?>" id="phonebrief">Заполнить бриф</label></p>
            <p class="description">Вы можете ознакомиться с примерами заполнения брифа <a href="/docs/<?=$briefExamples[$category->id]?>" target="_blank">тут</a>, или мы заполним его за вас. Оставьте свой № телефона, мы свяжемся с вами для интервью в течении рабочего дня с момента оплаты <a href="#" class="second tooltip" title="Мы работаем пн-пт с 10:00-19:00. Поставив галочку, вы сможете пропустить следующую страницу (или ответить на легкие вопросы) и перейти непосредственно к оплате.">(?)</a></p>
            <!--p class="description">Опция недоступна до 13.08.2013</p-->
            <p><input type="text"  id="phonenumber" name="phone-brief" placeholder="+7 XXX XXX XX XX" class="phone" value=""></p>
            <p class="label <?php if (isset($this->_request->query['click']) && $this->_request->query['click'] == 'phonebrief'): echo 'unfold'; endif?>"><?=$briefValue?>.-</p>
        </div>
        <?php endif ?>
        <div class="ribbon">
            <p class="option"><label><input type="checkbox" name="" class="multi-check" data-option-title="экспертное мнение" data-option-value="2000" <?php if (isset($this->_request->query['click']) && $this->_request->query['click'] == 'experts-checkbox'): echo 'checked'; endif?> id="experts-checkbox">Экспертное мнение</label></p>
            <p class="description"><a href="/experts" id="expert-trigger">Наши эксперты</a> с опытом работы в ведущих рекламных агентствах помогут вам с выбором варианта <a href="#" class="second tooltip" title="Эксперт укажет   и прокомментирует 3 лучших решения, которые максимально отвечают на вашу задачу. Вы можете выбрать несколько экспертов и заручиться надёжной поддержкой.">(?)</a></p>
            <?php
            foreach ($experts as $expert):
                if ($expert->enabled == 0) {
                    continue;
                }
                if (in_array($expert->id, unserialize($pitch->{'expert-ids'}))) {
                    continue;
                }
                if (((int) $pitch->category_id === 7) && ((int) $expert->id === 1)) {
                    continue;
                }
                $firstExpertPrice = ($expert->price + 500);
                break;
            endforeach;
            ?>
            <p class="label <?php if (isset($this->_request->query['click']) && $this->_request->query['click'] == 'experts-checkbox'): echo 'unfold'; endif?>" id="expert-label">+<?= $firstExpertPrice ?>.-</p>
        </div>

        <ul class="experts" <?php if (isset($this->_request->query['click']) && $this->_request->query['click'] == 'experts-checkbox'): echo 'style="display:block"'; endif?>>
            <?php
            $imageArray = [
                1 => '/img/temp/expert-1.jpg',
                2 => '/img/temp/expert-2.jpg',
                3 => '/img/jara_174.png',
                4 => '/img/temp/expert-4.jpg',
                5 => '/img/experts/nesterenko174.jpg',
                6 => '/img/experts/efremov174.jpg',
                7 => '/img/experts/percia_174.png',
                8 => '/img/experts/makarov_dmitry_174.png',
            ];
            $i = 0;
            foreach ($experts as $expert): if ($expert->enabled == 0) {
     continue;
 }
                if (in_array($expert->id, unserialize($pitch->{'expert-ids'}))) {
                    continue;
                }

                ?>
                <li>
                    <a href="/experts/view/<?=$expert->id?>" target="_blank" class="photo"><img src="<?=$imageArray[$expert->id]?>" alt="<?=$expert->name?>"></a><!-- .photo -->
                    <p class="select"><input type="checkbox" name="" <?php if ($i == 0 && isset($this->_request->query['click']) && $this->_request->query['click'] == 'experts-checkbox'): echo 'checked'; endif?> class="expert-check" data-id="<?=$expert->id?>" data-option-title="экспертное мнение" data-option-value="<?=($expert->price + 500)?>"></p><!-- .select -->
                    <dl>
                        <dt><strong><a href="/experts/view/<?=$expert->id?>" target="_blank"><?=$expert->name?></a></strong></dt>
                        <dd><a href="/experts/view/<?=$expert->id?>" target="_blank"><?=$expert->spec?> <?= $expert->price + 500 ?>&nbsp;р.-</a></dd>
                    </dl>
                </li>
                <?php
            $i ++;
            endforeach?>
        </ul><!-- .experts -->

        <?php if (((int) $pitch->guaranteed === '0') && ((int) $pitch->status === 0)): ?>
            <div class="ribbon" id="guaranteed-block">
                <p class="option"><label><input type="checkbox" id="guaranteed" name="" <?php if (isset($this->_request->query['click']) && $this->_request->query['click'] == 'guarantee'): echo 'checked'; endif?> class="single-check" data-option-title="Гарантировать проект" data-option-value="1400">Гарантировать проект</label></p>
                <p class="description">Гарантировать выбор победителя <a href="#" class="second tooltip" title="Вы гарантируете, что выберете победителя в любом случае, тем самым инициировав до 40% больше решений. Мы выделяем такой проект в списке. Дизайнеры увидят, что проект не останется без победителя, и вы получите больший выбор идей.">(?)</a></p>
                <p class="label <?php if (isset($this->_request->query['click']) && $this->_request->query['click'] == 'guarantee'): echo 'unfold'; endif?>">+1400.-</p>
            </div>
        <?php endif ?>

        <?php
        $pinnedValue = 1450;
        if ($this->user->isSubscriptionActive()):
            $pinnedValue = 500;
            foreach ($this->user->getCurrentPlanData($pitch->user_id)['free'] as $index => $value):
                if ('pinproject' === $value):
                    $pinnedValue = 0;
                endif;
            endforeach;
        endif;
        if (((int) $pitch->pinned === '0') && ((int) $pitch->status === 0)): ?>
            <div class="ribbon" id="pinned-block">
                <p class="option"><label><input type="checkbox" id="pinned" name="" <?php if (isset($this->_request->query['click']) && $this->_request->query['click'] == 'pinned'): echo 'checked'; endif?> class="single-check" data-option-title="“Прокачать” бриф" data-option-value="<?=$pinnedValue?>">“Прокачать” бриф</label></p>
                <p class="description">Увеличить количество решений <a href="#" class="second tooltip" title="Вы сможете увеличить количество предложенных вариантов на 15-40%. Для привлечения дизайнеров мы используем e-mail рассылку, facebook, vkontakte, twitter, выделение синим цветом в списке и на главной странице">(?)</a></p>
                <p class="label <?php if (isset($this->_request->query['click']) && $this->_request->query['click'] == 'pinned'): echo 'unfold'; endif?>">+<?=$pinnedValue?>-</p>
            </div>
        <?php endif ?>

        <?php if (($pitch->private == '0') && ($pitch->id == '105318')): ?>
            <div class="ribbon" id="private-block">
                <p class="option"><label><input type="checkbox" id="private" name="" <?php if (isset($this->_request->query['click']) && $this->_request->query['click'] == 'private'): echo 'checked'; endif?> class="single-check" data-option-title="Скрыть проект" data-option-value="3500">Скрыть проект</label></p>
                <p class="description">Проект станет не виден для поисковых систем, а идеи будут доступны для просмотра только вам и их авторам. Подробнее <a target="_blank" href="https://godesigner.ru/answers/view/64">тут</a></p>
                <p class="label <?php if (isset($this->_request->query['click']) && $this->_request->query['click'] == 'private'): echo 'unfold'; endif?>">+3500.-</p>
            </div>
        <?php endif ?>

        <p class="submit">
            <?php if ((int) $pitch->category_id === 20):?>
                <input type="submit" value="Оплатить со счёта" id="activate" class="button steps-link" data-step="3">
            <?php else: ?>
                <input type="submit" value="Продолжить" id="next" class="button steps-link" data-step="3">
            <?php endif?>
        </p><!-- .submit -->

    </div><!-- .main -->

</div><!-- .middle -->


    <div class="middle add-pitch" style="display:none;" id="step3">

        <div class="main">
            <div>
                <h1>выберите способ оплаты</h1>
                <div class="g_line"></div>
                <div id="P_card">
                    <table>
                        <tr class="paymaster-section">
                            <td>
                                <input type="radio" name="1" class="rb1" data-pay="paymaster" style="background: #a2b2bb;">
                            </td>
                            <td colspan="3" class="s3_text" style="padding-left: 20px;">
                                Оплата пластиковыми картами и эл. деньгами <br>через PayMaster<br><br>
                                <!--p style="font-size:11px; text-transform: ">Всвязи с временным ограничением платежной системы PayMaster,<br> максимально возможная сумма платежа может составлять от 15000-35000. <br>Подробнее <a href="/answers/view/91">тут</a>. В случае, если ваш платеж превышает лимит, пожалуйста, воспользуйтесь переводом на рассчетный счет (ниже).<br> Спасибо за понимание!</p-->
                            </td>
                        </tr>
                        <tr id="paymaster-images" class="paymaster-section">
                            <td colspan="4" class="s3_text" style="padding: 20px 0 0 40px; text-transform: uppercase;">
                                <img src="/img/s3_paymaster.png" alt="">
                                <span style="margin: 0 0 0 20px; line-height: 3em;">и другие...</span>
                            </td>
                        </tr>
                        <tr id="paymaster-select" class="paymaster-section" style="display: none;">
                            <td colspan="4">
                                <?php echo $this->html->script(['jquery-1.8.3.min.js']);?>
                                <script type='text/javascript' src='https://paymaster.ru/widget/BasicFP/1?LMI_MERCHANT_ID=d5d2e177-6ed1-4e5f-aac6-dd7ea1c16f60&LMI_PAYMENT_AMOUNT=1&LMI_PAYMENT_DESC=<?php echo urlencode('Оплата проекта')?>&LMI_CURRENCY=RUB'></script>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="g_line"></div>
                <p class="submit">
                    <input type="submit" value="Назад" id="prev" class="button steps-link" data-step="2">
                </p><!-- .submit -->
            </div>
        </div><!-- .main -->

    </div><!-- .middle -->

<?=$this->html->script(['jquery-ui-1.11.4.min.js', 'pitches/addon.js?' . mt_rand(100, 999), 'jquery.numeric', 'jquery.iframe-transport.js', 'jquery.fileupload.js', 'jquery.simplemodal-1.4.2.js', 'jquery.tooltip.js', 'popup.js', 'jquery.damnUploader.js'], ['inline' => false])?>
<?= $this->html->style(['/css/common/receipt.css', '/brief', '/step3'], ['inline' => false])?>